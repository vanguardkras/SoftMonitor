<?php
/**
 * Class for managing user groups.
 *
 * @author shayan-ma
 */

class UserGroups extends Datachanger
{
    /**
     * Table name that contains relevant information.
     * @var type 
     */
    protected $table = 'user_groups';
    
    /**
     * Data base columns list.
     * @var string
     */
    protected $columns = 'name, rights';
    
    /**
     * Deletes a user group.
     * @param int $id
     * @return string
     */
    public function delete(int $id): string
    {
        $users = new Users;
        $num_users = $users->countByColumn('group_id', $id);
        
        if ($num_users > 0) {
            return USER_GROUP_DELETE_FAIL;
        } else {
            parent::delete($id);
            return USER_GROUP_DELETE_SUCCESS;
        }
    }
    
    /**
     * Gets all groups which do not have admin rights.
     * @return array
     */
    public function getAllNoAdmin(): array
    {
        return $this->db
                        ->table($this->table)
                        ->select('id, name')
                        ->where('rights', 1, '!=')
                        ->fetch();
    }
}
