<?php
/**
 * Class for managing connection templates.
 *
 * @author shayan-ma
 */

class Connections extends Datachanger
{
    /**
     * Table name that contains relevant information.
     * @var type 
     */
    protected $table = 'connections';
    
    /**
     * Data base columns list.
     * @var string
     */
    protected $columns = 'name, snmp_version, login, pass';
    
    /**
     * Deletes a connection template.
     * Prohibits deletion if a device using this connection template exists.
     * @param type $id
     * @return string Informational deletion status message.
     */
    public function delete(int $id)
    {
        $devices = new Devices();
        
        if ($devices->isDevice('connection_id', $id)) {
            return CONNECTION_DELETE_FAIL;
        } else {
            parent::delete($id);
            
            return CONNECTION_DELETE_SUCCESS;
        }
    }
}
