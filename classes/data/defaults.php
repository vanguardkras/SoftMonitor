<?php
/**
 * Process default connection parameters.
 *
 * @author shayan-ma
 */

class Defaults extends Datachanger
{
    /**
     * Table name that contains relevant information.
     * @var type 
     */
    protected $table = 'config';
    
    /**
     * Data base columns list.
     * @var string
     */
    protected $columns = 'name, param';
    
    /**
     * Get default connection parameters as an array with elements:
     * 'parameter_name' => 'value'
     * @return array
     */
    public function getDefaults(): array
    {
        $param_list = $this->getAll();
        
        $result = [];
        foreach ($param_list as $par_l) {
            $result[$par_l['name']] = $par_l['param'];
        }
        
        return $result;
    }
    
    /**
     * Sets new default connection parameters.
     * @param int $processor_instances
     * @param int $default_recovery_time
     * @param int $defaulst_ping_attempts
     * @param int $default_ping_timeout
     */
    public function setDefaults(
            int $processor_instances = 10,
            int $default_recovery_time = 500,
            int $defaulst_ping_attempts = 2,
            int $default_ping_timeout = 200
            ): void
    {
        $this->db
                ->table($this->table)
                ->update('param', [$processor_instances])
                ->where('name', 'processor_instances')
                ->fetch();
        $this->db
                ->table($this->table)
                ->update('param', [$default_recovery_time])
                ->where('name', 'default_recovery_time')
                ->fetch();
        $this->db
                ->table($this->table)
                ->update('param', [$defaulst_ping_attempts])
                ->where('name', 'default_ping_attempts')
                ->fetch();
        $this->db
                ->table($this->table)
                ->update('param', [$default_ping_timeout])
                ->where('name', 'default_ping_timeout')
                ->fetch();
    }
}
