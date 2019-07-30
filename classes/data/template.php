<?php
/**
 * Class for managing device templates.
 *
 * @author shayan-ma
 */

class Template extends Datachanger
{
    /**
     * Table name that contains relevant information.
     * @var type 
     */
    protected $table = 'templates';
    
    /**
     * Data base columns list.
     * @var string
     */
    protected $columns = 'name, recover_time';
    
    /**
     * Inserts new template.
     * @param string $name
     * @return void
     */
    public function add($name): void
    {
        $defaults = new Defaults;
        $rec_time = $defaults->getDefaults();
        $rec_time = $rec_time['default_recovery_time'];
        parent::add([$name, $rec_time]);
    }
    
    /**
     * Deletes a template from the table.
     * Returns a string with information about status of deletion.
     * If any device uses this template, it will not be deleted.
     * @param int $id
     * @return string
     */
    public function delete(int $id): string
    {
        $devices = new Devices();

        if ($devices->isDevice('template_id', $id)) {
            return TEMPLATE_DELETE_FAIL;
        } else {
            parent::delete($id);
            return TEMPLATE_DELETE_SUCCESS;
        }
    }
    
    /**
     * Gets a template by its ID.
     * @param int $id
     * @return array
     */
    public function getById(int $id): array
    {
        $oids = new Oid;
        $oid_list = $oids->getByTemplateId($id);
        $templates = parent::getById($id);
        $templates['oids'] = $oid_list;
        
        return $templates;
    }
    
    /**
     * Gets the last added template id.
     * @return int
     */
    public function getMaxId(): int
    {
        $res =  $this->db
                    ->table($this->table)
                    ->setQuery('SELECT MAX(`id`) as `id` ')
                    ->fetch(1);
        return intval($res['id']);
    }
}
