<?php
/**
 * Class for managing alarms data.
 *
 * @author shayan-ma
 */

class Alarms extends Datachanger
{
    /**
     * Table name that contains relevant information.
     * @var type 
     */
    protected $table = 'alarms';
    
    /**
     * Data base columns list.
     * @var string
     */
    protected $columns = 'device_id, oid_id, message, occur_time, lvl, ack';
    
    public function acknowledge(int $id, $val = 1): void
    {
        $this->db
                ->table($this->table)
                ->update('ack', [$val])
                ->where('id', $id)
                ->fetch();
    }
    
    /**
     * Deletes all alarms of a certain device ID.
     * @param int $id Device ID.
     * @return void
     */
    public function deleteAlarmsByDeviceId(int $id): void
    {
        $this->db
                ->table($this->table)
                ->delete()
                ->where('device_id', $id)
                ->fetch();
    }
    
    /**
     * Checks if a device has particular oid alarms.
     * Returns the alarm data, or false if no alarms are found.
     * @param int $device_id
     * @param int $oid_id
     * @return mixed Can be array or false
     */
    public function existByDevOid(int $device_id, int $oid_id)
    {
        $exist = $this->db
                ->table($this->table)
                ->select('id, occur_time, recover, recover_time')
                ->where('device_id', $device_id)
                ->where('oid_id', $oid_id)
                ->fetch(1);
        return isset($exist['id']) ? $exist : false;
    }
    
    /**
     * Returns alarms information for a main page.
     * @param string $login
     * @param bool $admin
     * @param int $group_id
     * @return array
     */
    public function getAllPage(string $login, bool $admin, int $group_id): array
    {
        $query = 'SELECT `id`, `device_id`, '
                . '(SELECT `name` FROM `device_list` WHERE `id` = `device_id`) as `device`, '
                . '`message`, `occur_time`, `lvl`, `recover`, `ack` ';
        $this->db
                ->table($this->table)
                ->setQuery($query);
        if (!$admin) {
            $query = 'WHERE `device_id` IN (SELECT `id` FROM `device_list` '
                    . 'WHERE `group_id` = ' . $group_id . ') ';  
            $this->db
                    ->setQuery($query, false);
        }
        return $this->db
                        ->order('occur_time', 'DESC')
                        ->fetch();
    }
    
    /**
     * Switch an alarm to recover mode.
     * if third parameter is set to 0, turns off recover mode.
     * @param int $id
     * @param int $time
     * @param int $recover
     * @return void
     */
    public function recover(int $id, int $time, int $recover = 1): void
    {
        $this->db
                ->table($this->table)
                ->update('recover, recover_time', [$recover, $time])
                ->where('id', $id)
                ->fetch();
    }
    
    /**
     * Gets a list of devices with alarms.
     * @return array
     */
    public function getFailedDevices(): array
    {
        return $this->db
                        ->table($this->table)
                        ->selectDistinct('device_id')
                        ->fetch(0, true, PDO::FETCH_COLUMN);
    }
}
