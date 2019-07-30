<?php
/**
 * Class for managing templates OID information.
 *
 * @author shayan-ma
 */

class Oid extends Datachanger
{
    /**
     * Table name that contains relevant information.
     * @var type 
     */
    protected $table = 'oids';
    
    /**
     * Data base columns list.
     * @var string
     */
    protected $columns = 'oid, low, high, message, level, template_id';
    
    /**
     * Returns oids of a particular device template.
     * @param int $id
     * @return array
     */
    public function getByTemplateId(int $id): array
    {
        return $this->db
                ->table($this->table)
                ->select()
                ->where('template_id', $id)
                ->fetch();
    }
}
