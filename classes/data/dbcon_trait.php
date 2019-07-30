<?php
/**
 * Trait for receiving an instance of database connection.
 *
 * @author shayan-ma
 */

trait Dbcon
{
    /**
     * An instance for database connection.
     * @var type 
     */
    protected $db;
    
    public function __construct()
    {
        $this->db = Database::db();
    }
}
