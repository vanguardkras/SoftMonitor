<?php
/**
 * Class for logging.
 *
 * @author shayan-ma
 */

class Log extends Datachanger
{
    /**
     * Table name that contains relevant information.
     * @var type 
     */
    protected $table = 'logging';
    
    /**
     * Data base columns list.
     * @var string
     */
    protected $columns = 'occur_time, lvl, message, device_id';
    
    /**
     * Returns an array of log for a certain period.
     * @param string $start
     * @param mixed $end
     * @return array
     */
    public function getPeriod(string $start, $end = false): array
    {   
        $end = $end ?: time();
        $query = 'SELECT `id`, `occur_time`, `lvl`, `message`, (SELECT `name` '
                . 'FROM `device_list` WHERE `id` = `device_id`) as `device`';
        return $this->db
                ->table($this->table)
                ->setQuery($query)
                ->where('occur_time', $start, '>')
                ->where('occur_time', $end, '<')
                ->fetch();
    }
}
