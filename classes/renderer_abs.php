<?php
/**
 * Abstract class for rendering pages.
 *
 * @author shayan-ma
 */

abstract class Renderer 
{   
    /**
     * Determines if a current user has admin rights.
     * @var bool 
     */
    protected $admin;
    
    /**
     * Current user login.
     * @var string 
     */
    protected $login; 
    
    /**
     * Curent user group id.
     * @var int 
     */
    protected $group_id;
    
    /**
     * Returns include path of a page view.
     */
    abstract protected function view($method): string;
    
    /**
     * @param string $login Curent user login
     */
    public function __construct(string $login, bool $admin, int $group_id)
    {
        $this->admin = $admin;
        $this->login = $login;
        $this->group_id = $group_id;
    }
    
    /**
     * Displays sorting arrows.
     * @param string $name Sorting name.
     * @param int $desc 1 - Descending sorting, 0 - Ascending sorting.
     * @return void
     */
    protected function getSortArrows(string $name, int $desc): void
    {
        if (isset($_GET['order'])) {
            if ($_GET['order'] === $name) {
                echo $desc === 1 ? '▲' : '▼';
            } else {
                echo '';
            }
        }
    }
    
    /**
     * Returns information about order and order direction.
     * @return array
     */
    protected function sort(): array
    {
        $desc = 1;
        $desc_get = '';
        if (isset($_GET['desc']) && isset($_GET['order'])) {
            if ($_GET['desc'] == 1) {
                $desc = 0;
                $desc_get = 'DESC';
            }
            $order = $_GET['order'];
        } else {
            $order = 'name';
        }
        
        return ['order' => $order, 'desc_get' => $desc_get, 'desc' => $desc];
    }
}
