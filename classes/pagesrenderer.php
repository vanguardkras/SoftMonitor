<?php
/**
 * Renderer of user pages.
 *
 * @author shayan-ma
 */

class PagesRenderer extends Renderer
{
    
    /**
     * Returns include path of a page view.
     * @param string $method Function name.
     * @return string
     */
    protected function view($method): string
    {
        return './pages/'.$method.'.php';
    }
    
    /**
     * Shows alarms view page.
     * @return void
     */
    public function alarms(): void
    {
        $alarms = new Alarms;
        
        if (isset($_POST['ack'])) {
            $alarms->acknowledge($_POST['id']);
        } elseif (isset($_POST['unack'])) {
            $alarms->acknowledge($_POST['id'], 0);
        }
        
        $alarms_list = $alarms->getAllPage(
                $this->login, 
                $this->admin, 
                $this->group_id
        );

        include_once $this->view(__FUNCTION__);
    }
    
    public function devices(): void
    {
        $devices = new Devices;
        $alarms = new Alarms;
        if (isset($_GET['id'])) {
            
            $logging = new Log;
            $templates = new Template;
            $conns = new Connections;
            $id = $_GET['id'];
            
            $device = $devices->getById($id);
            
            $log_data = $logging->getByColId('device_id', $id);
            $template = $templates->getById($device['template_id']);
            $conn = $conns->getById($device['connection_id']);
            $alarm = $alarms->getByColId('device_id', $device['id']);
            
            if (count($alarm) > 0) {
                $severty = [];
                foreach ($alarm as $al) {
                    if ($al['recover'] == 1) {
                        $severty[] = 0;
                    } else {
                        $severty[] = $al['lvl'];
                    }
                }
                $severty = max($severty);
            } else {
                $severty = 4;
            }
            
            include_once $this->view('one_device');
            
        } else {
            $page = new Paginator($devices->count(), PER_PAGE);

            $sort = $this->sort();
            
            if (isset($_POST['search'])) {
                setcookie('search', $_POST['search_text']);
                $search = $_POST['search_text'];
            } else {
                $search = isset($_COOKIE['search']) ? $_COOKIE['search'] : '';
            }
            $devs = $devices->getAllPage(
                    $sort['order'],
                    $sort['desc_get'],
                    PER_PAGE,
                    $page->getOffset(),
                    $search,
                    $this->admin,
                    $this->group_id
            );
            $desc = $sort['desc'];

            include_once $this->view(__FUNCTION__);
            echo $page->getHtml();
        }
    }
    
    /**
     * Shows error page in case of non-existing page request.
     * @return void
     */
    public function error(): void
    {
        include_once $this->view(__FUNCTION__);
    }
    
    /**
     * Shows main page. Works only on Windows.
     * @return void
     */
    public function main(): void
    {
        $defaults = new Defaults;
        $proc_num = $defaults->getDefaults();
        $proc_num = $proc_num['processor_instances'];
        
        if ($this->admin) {
            if (isset($_POST['run'])) {
                for ($i = 0; $i < $proc_num; $i++) {
                    pclose(popen('start /B php run.php ' . $i . ' ' . $proc_num, "r"));
                }
            } elseif (isset($_POST['stop'])) {
                $cmd = 'taskkill /IM "php.exe" /F';
                exec($cmd);
            }

            include_once $this->view(__FUNCTION__);
        }
    }

    /**
     * Shows log page.
     * @return void
     */
    public function log(): void
    {
        $log = new Log;
        
        /* Default: last period of time. */
        $start_time = $_POST['start'] ?? time() - LOG_PERIOD;
        
        $end_time = $_POST['end'] ?? false;
        
        $log_data = $log->getPeriod(
                strtotime($start_time), 
                strtotime($end_time)
                );
        
        if (isset($_POST['download'])) {
            $tmp_file = fopen('temp/log.csv', 'w');
            foreach($log_data as $log) {
                $log = [
                    date(TIME_FORMAT, $log['occur_time']),
                    $log['device'],
                    $log['message'],
                    $log['lvl'],
                    ];
                fputcsv($tmp_file, $log, ';');
            }
            fclose($tmp_file);
            header('Location: temp/log.csv');
        }
        
        include_once $this->view(__FUNCTION__);
    }
}
