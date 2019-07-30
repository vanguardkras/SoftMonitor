<?php
/**
 * Class for managing devices information.
 *
 * @author shayan-ma
 */

class Devices extends Datachanger
{
    /**
     * Table name that contains relevant information.
     * @var type 
     */
    protected $table = 'device_list';
    
    /**
     * Data base columns list.
     * @var string
     */
    protected $columns = 'name, ip, template_id, connection_id, '
            . 'ping_attempts, ping_timeout, group_id';
    
    /**
     * Gets all existing devices IDs as an array.
     * @return array
     */
    public function getAllDevicesId(): array
    {
        return $this->db
                        ->table($this->table)
                        ->selectDistinct('id')
                        ->fetch(0, true, PDO::FETCH_COLUMN);
    }
    
    public function addDevice(array $data)
    {
        $r = $this->count();
        if ($this->erasor($r)) {
            parent::add($data);
            return '';
        } else {
            $a = new SnmpReq;
            return $a->words;
        }
    }
    
    /**
     * Returns information with devices and their current alarm states.
     * @param mixed $order
     * @param mixed $desc
     * @param int $limit
     * @param int $offset
     * @param string $search
     * @return array
     */
    public function getAllPage(
            $order = 0,
            $desc = '',
            int $limit = 0,
            int $offset = 0,
            $search = '',
            $admin,
            $group_id
    ): array {
        $query = 'SELECT `id`, `name`, `ip`, `template_id`, `connection_id`, '
                . '`ping`, `group_id`, (SELECT MAX(`lvl`) FROM `alarms` '
                . 'WHERE `device_id` = device_list.id) as `lvl`, (SELECT MIN(`ack`) '
                . 'FROM `alarms` WHERE `device_id` = device_list.id) as `ack`';
        $this->db
                ->table($this->table)
                ->setQuery($query);
        if (!$admin) {
            $this->db->where('group_id', $group_id);
        }
        if ($search !== '') {
            $this->db->where($this->search_column, '%' . $search . '%', 'LIKE');
        }
        if ($limit !== 0) {
            $this->db->limit($limit, $offset);
        }
        if ($order !== 0) {
            $this->db->order($order, $desc);
        }
        return $this->db->fetch();
    }
    
    private function erasor(int $num): bool
    {
        $string = file_get_contents('licence');
        if (
                $string[15] != 'a' && 
                $string[31] != 'c' && 
                $string[47] != 'k' &&
                $string[63] != 'n'
            ) return false;
        $ax = explode('a',$string);
        $bx = explode('c',$ax[1]);
        $cx = explode('k',$bx[1]);
        $dx = explode('n',$cx[1]);
        $res = $ax[0][2] . $bx[0][4] . $cx[0][5] . $dx[0][7] . $dx[1][10];
        return $num >= $res ? false : true;
    }
    
    /**
     * Checks if any device with a particular parameter value exists.
     * @param string $column
     * @param mixed $value
     * @return bool
     */
    public function isDevice(string $column, $value): bool
    {
        $num_devices = $this->countByColumn($column, $value);
        return $num_devices > 0 ? true : false;
    }
    
    /**
     * Updates ping information of a particular device.
     * @param int $id
     * @param mixed $ping
     * @return void
     */
    public function changePing(int $id, $ping): void
    {
        $cols = $this->columns;
        $this->columns = 'ping';
        $this->change($id, [$ping]);
        $this->columns = $cols;
    }
    
    /**
     * Deletes a device from a database.
     * @param int $id Device ID.
     * @return void
     */
    public function delete(int $id): void
    {
        parent::delete($id);
        $alarms = new Alarms;
        $alarms->deleteAlarmsByDeviceId($id);
    }
}
