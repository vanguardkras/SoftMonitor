<?php
/** (WORK IN PROGRESS)
 * Class launching devices monitoring process.
 * 
 * Usage:
 * $a = new AlarmsProcessor;
 * $a->launch();
 *
 * @author shayan-ma
 */

class AlarmsProcessor
{
    /**
     * An instance of Alarms class.
     * @var Alarms 
     */
    private $alarms;
    
    /**
     * An instance of Connections class.
     * @var Connections 
     */
    private $connections;
    
    /**
     * Current device data.
     * @var array 
     */
    private $device;
    
    /**
     * An instance of Devices class.
     * @var Devices 
     */
    private $devices;
    
    /**
     * A list of failed devices ID.
     * @var array 
     */
    private $failed_devices;
    
    /**
     * An instance of Log class.
     * @var Log 
     */
    private $log;
    
    /**
     * An instance of Oid class.
     * @var Oid 
     */
    private $oid;
    
    /**
     * an instance of SnmpReq class.
     * @var SnmpReq 
     */
    private $snmp;
    
    /**
     * An instance of Template class.
     * @var Template 
     */
    private $template;
    
    /**
     * Temporary information about current request timestamp.
     * @var string 
     */
    private $time;
    
    /**
     * A list of normally working devices ID.
     * @var array 
     */
    private $working_devices;
    
    /**
     * Temporary devices number counter.
     * @var int 
     */
    private $counter = 0;
    
    /**
     * Creates instances of all dependent classes.
     */
    public function __construct() 
    {
        $this->alarms = new Alarms;
        $this->failed_devices = $this->alarms->getFailedDevices();
        $this->connections = new Connections;
        $this->devices = new Devices;
        $all_devs = $this->devices->getAllDevicesId();
        $this->working_devices = array_diff($all_devs, $this->failed_devices);
        $this->log = new Log;
        $this->oid = new Oid;
        $this->snmp = new SnmpReq;
        $this->template = new Template;
    }
    
    /**
     * Checks if current parameter can be determined as an alarm.
     * @param array $oid An oid data.
     * @return mixed Can return Error in case of wrong data.
     */
    private function isAlarm(array $oid)
    {
        if ($result = $this->snmp->get(
                $oid['oid'],
                $this->device['ping_timeout'],
                $this->device['ping_attempts']
                )) {
            if (is_numeric($oid['low']) && is_numeric($oid['high'])) {
                return ($result < $oid['low']) || ($result > $oid['high']);
            } elseif (is_numeric($oid['high'])) {
                return $result > $oid['high'];
            } elseif (is_numeric($oid['low'])) {
                return $result < $oid['low'];
            } else {
                /* Log notification about unset high or low values */
                $this->log->add([
                    $this->time,
                    0,
                    NO_OID_VALUE_SET.' ' . $oid['message'],
                    $this->device['id']
                ]);
                return 'Error';
            }
        } else {
            /* Log notification about wrong snmp settings */
            $this->log->add([
                $this->time,
                0,
                WRONG_SNMP_PARAMETERS,
                $this->device['id']
            ]);
            return 'Error';
        }
    }
    
    /**
     * Logs alarm information.
     * @param array $oid An oid data.
     * @param string $message Alarm message.
     * @param mixed $level Use your own alarm level, determined in Log class.
     * @return void
     */
    private function logAlarm(array $oid, string $message, $level = true): void
    {
        $level = $level === true ? $oid['level'] : $level;
        $this->log->add([
            $this->time,
            $level,
            $message . $oid['message'],
            $this->device['id']
        ]);
    }
    
    /**
     * Checks ping to a device.
     * 
     * Checks ping, write this information into Alarms tables and logs it.
     * @param int $recover_time A device recover time.
     * @return bool
     */
    private function pingCheck(int $recover_time): bool
    {
        for ($i = 0; $i < $this->device['ping_attempts']; $i++) {
            $ping_result = $this->snmp::ping(
                    $this->device['ip'], 
                    $this->device['ping_timeout']
                    );
            
            $existing_alarm = $this->alarms->existByDevOid($this->device['id'], 0);
            $exist = is_array($existing_alarm) ? true : false;
            
            if ($ping_result !== false) {//Ping is successful
                $this->devices->changePing($this->device['id'], $ping_result);
                if ($exist) { //Ping is ok and alarm exists
                    if ($existing_alarm['recover'] == 1) { //Ping, alarm and was recovering
                        if (($this->time - $existing_alarm['occur_time']) > $recover_time) {
                            $this->alarms->delete($existing_alarm['id']);
                            $mess = PING_RESTORED;
                            $this->log->add([$this->time, 4, $mess, $this->device['id']]);
                        }
                    } else { //Ping, alarm and was not recovering
                        $this->alarms->recover($existing_alarm['id'], $this->time);
                        $mess = PING_RECOVER;
                        $this->log->add([$this->time, 0, $mess, $this->device['id']]);
                    }
                }
                $ping = true;
            } else { //Ping is failed
                $this->devices->changePing($this->device['id'], NO_PING);
                if ($exist) { //Ping is failed and alarm exists
                    if ($existing_alarm['recover'] == 1) { //No ping, existed and it was recovering
                        $this->alarms->recover($existing_alarm['id'], 0, 0);
                        $mess = PING_FAIL_REPEAT;
                        $this->log->add([$this->time, 3, $mess, $this->device['id']]);
                    }
                } else { //Ping is failed and alarm does not exists
                    $mess = PING_FAIL;
                    $this->alarms->add([$this->device['id'], 0, $mess, $this->time, 3, 0]);
                    $this->log->add([$this->time, 3, $mess, $this->device['id']]);
                }
                $ping = false;
            }
        }
        return $ping;
    }
    
    /**
     * Launches the scan process of one device from a list.
     * 
     * Includes ping check and SNMP alarms check.
     * @param int $device_id Device ID.
     * @return void
     */
    private function scan(int $device_id): void
    {
        $this->counter++;
        $this->device = $this->devices->getById($device_id);
        $template = $this->template->getById($this->device['template_id']);
        $conn = $this->connections->getById($this->device['connection_id']);
        $oids = $this->oid->getByTemplateId($this->device['template_id']);
        
        $this->snmp
                ->setSnmpVersion($conn['snmp_version'])
                ->setSecName($conn['login'])
                ->setAuthPass($conn['pass'])
                ->setIp($this->device['ip']);
        
        $this->time = time();
        if ($this->pingCheck($template['recover_time'])) {
            $this->snmpCheck($oids, $template['recover_time']);
        }
    }
    
    /**
     * Checks SNMP-data of one device.
     * 
     * Checks, write this information into Alarms tables and logs it.
     * @param array $oids
     * @param int $recover_time
     * @return void
     */
    private function snmpCheck(array $oids, int $recover_time): void
    {
        foreach ($oids as $oid) {
            $alarm = $this->isAlarm($oid);
            if ($alarm === 'Error') {
                continue;
            }
            $existing_alarm = $this->alarms->existByDevOid(
                    $this->device['id'],
                    $oid['id']
            );
            $exist = is_array($existing_alarm) ? true : false;
            if ($alarm) { //It is an alarm
                if ($exist) { //An alarm and it existed
                    if ($existing_alarm['recover'] == 1) { //Alarm, existed and it was recovering
                        $this->alarms->recover($existing_alarm['id'], 0, 0);
                        $this->logAlarm($oid, SNMP_REPEAT_AFTER_REC.': ');
                    }
                } else { //An alarm and it is new.
                    $this->alarms->add([
                            $this->device['id'],
                            $oid['id'],
                            $oid['message'],
                            $this->time,
                            $oid['level'],
                            0
                    ]);
                    $this->logAlarm($oid, '');
                }
            } else { // It is not an alarm
                if ($exist) { //No Alarm, but it existed
                    if ($existing_alarm['recover'] == 1) { //No Alarm, existed and it was recovering
                        if (($this->time - $existing_alarm['occur_time']) > $recover_time) {
                            $this->alarms->delete($existing_alarm['id']);
                            $this->logAlarm($oid, CLEARED_ALARM.': ', 4);
                        }
                    } else { //No Alarm, existed and was no recovering
                        $this->alarms->recover($existing_alarm['id'], $this->time);
                        $this->logAlarm($oid, RECOVER_ALARM.': ', 0);
                    }
                }
            }
        }
    }
    
    /**
     * Launches monitoring process. 
     * Uses algorithm for sharing load among different processes.
     * @param int $process_number
     * @param int $total_number
     * @return void
     */
    public function launch(int $process_number, int $total_number): void
    {   
        $num_failued = count($this->failed_devices);
        $num_working = count($this->working_devices);

        $length_failued = ceil($num_failued / ($total_number / 5));
        $length_working = ceil($num_working / $total_number);

        $offset_failued = floor($length_failued * $process_number / 5);
        $offset_working = $length_working * $process_number;

        $failed = array_slice(
                $this->failed_devices,
                $offset_failued,
                $length_failued
        );
        $working = array_slice(
                $this->working_devices,
                $offset_working,
                $length_working
        );
        foreach ($failed as $failed_device_id) {
            $this->scan($failed_device_id);
        }
        foreach ($working as $working_device_id) {
            $this->scan($working_device_id);
        }
    }

}
