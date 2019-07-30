<?php
/**
 * Class for paginating pages.
 * 
 * Usage:
 * 
 * $a = new Paginator(100, 10); //Sets 100 as total number of elements
 * and 10 as number of elements per page.
 * 
 * $a->getOffset(); //To get current offset for using in data requests.
 * 
 * echo $a->getHtml(); //Draws pagination on a page.
 * 
 * @author shayan-ma
 */

class Paginator
{
    /**
     * Total number of elements.
     * @var int 
     */
    private $limit;
    
    /**
     * Number of elements per page.
     * @var int 
     */
    private $max;
    
    /**
     * Calculated number of pages.
     * @var type 
     */
    private $pages_num;
    
    /**
     * Creates an instance of Paginator.
     * @param int $max Number of elements per page.
     * @param int $limit Total number of elements.
     */
    public function __construct(int $max, int $limit = 30) 
    {
        $this->max = $max;
        $this->limit = $limit;
        $this->pages_num = ceil ($max / $limit);
    }
    
    /**
     * Gets HTML-code for paginator.
     * 
     * Insert this paginator anywhere in your code:
     * 
     * echo $a->getHtml();
     * 
     * Use css class 'paginator' for it and 'selected_page' for a chosen page.
     * @return string
     */
    public function getHtml(): string
    {
        $res = '<p class="paginator">';
        if ($this->max > $this->limit) {
            
            if (isset($_GET['p']) && filter_var($_GET['p'], FILTER_VALIDATE_INT)) {
                $current = $_GET['p'];
            } else {
                $current = 1;
            }

            for ($i = 1; $i <= $this->pages_num; $i++) {
                if ($i == $current) {
                    $class = ' class="selected_page"';
                } else {
                    $class = '';
                }
                $res .= '<a' . $class . ' href="?p=' . $i . '">' . $i . '</a> ';
            }
            
        }
        $res = trim($res);
        $res .= '</p>';
        return $res;
    }
    
    /**
     * Gets query offset for different pages.
     * 
     * Uses get parameter 'p' as page number.
     * @return int
     */
    public function getOffset(): int
    {
        if (isset($_GET['p']) && filter_var($_GET['p'], FILTER_VALIDATE_INT)) {
            $page = $_GET['p'];
            return $this->limit * ($page - 1);
        } else {
            return 0;
        }
    }
}
