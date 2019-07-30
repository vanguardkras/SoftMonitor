<?php
/**
 * Class for managing users.
 *
 * @author shayan-ma
 */

class Users extends Datachanger
{
    /**
     * Table name that contains relevant information.
     * @var type 
     */
    protected $table = 'users';
    
    /**
     * Data base columns list.
     * @var string
     */
    protected $columns = 'login, pass, group_id';
       
    public function change(int $id, array $data): void
    {
        $this->columns = 'login, group_id';
        parent::change($id, $data);
        $this->columns = 'login, pass, group_id';
    }
    
    /**
     * Changes a user's password.
     * @param int $id
     * @param string $pass
     * @return void
     */
    public function changeUserPass(int $id, string $pass): void
    {
        $this->db
                ->table($this->table)
                ->update('pass', [md5($pass)])
                ->where('id', $id)
                ->fetch();
    }
    
    /**
     * Gets a user by its login.
     * @param string $login
     * @return mixed
     */
    public function getByName(string $login)
    {
        return $this->db
                ->table($this->table)
                ->select()
                ->where('login', $login)
                ->fetch(1);
    }
}
