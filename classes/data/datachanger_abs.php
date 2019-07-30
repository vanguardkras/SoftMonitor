<?php
/**
 * Main class for working with database and processing the information
 * from it.
 *
 * @author shayan-ma
 */

abstract class Datachanger 
{
    protected $search_column = 'name';
    /**
     * Creating an instance for database connection.
     */
    use Dbcon;
    
    /**
     * Inserts new data.
     * @param array $data
     * @return void
     */
    public function add(array $data): void
    {
        $this->db
                ->table($this->table)
                ->insert($this->columns, $data)
                ->fetch();
    }
    
    /**
     * Changes existing data.
     * @param int $id
     * @param array $data
     * @return void
     */
    public function change(int $id, array $data): void
    {
        $this->db
                ->table($this->table)
                ->update($this->columns, $data)
                ->where('id', $id)
                ->fetch();
    }
    
    /**
     * Counts number of lines in a corresponding table.
     * @return int
     */
    public function count(): int
    {
        $res = $this->db
                ->table($this->table)
                ->select('COUNT')
                ->fetch(1);
        return $res['COUNT(*)'];
    }
    
    /**
     * Counts number of lines with selected column value.
     * @param string $column
     * @param mixed $value
     * @return int
     */
    public function countByColumn(string $column, $value): int
    {
        $res = $this->db
                ->table($this->table)
                ->select('COUNT')
                ->where($column, $value)
                ->fetch(1);
        return $res['COUNT(*)'];
    }
    
    /**
     * Deletes a record from the table.
     * @param int $id
     * @return no specific type
     */
    public function delete(int $id)
    {
            $this->db
                ->table($this->table)
                ->delete()
                ->where('id', $id)
                ->fetch();
    }
    
    /**
     * Returns all results.
     * @param string $columns Possible to select specific columns.
     * @param mixed $order Column name.
     * @param mixed $desc If set, sorts in descending way/
     * @param int $limit Maximum results number.
     * @param int $offset Offset.
     * @param mixed $search Searching word.
     * @return array
     */
    public function getAll(
            string $columns = '', 
            $order = 0,
            $desc = '',
            int $limit = 0, 
            int $offset = 0,
            $search = ''
            ): array 
    {
        $this->db
                ->table($this->table)
                ->select($columns);
        if ($search !== '') {
            $this->db->where($this->search_column, '%' . $search . '%', 'LIKE');
        }
        if ($limit !== 0) {
            $this->db->limit($limit, $offset);
        }
        if ($order !== 0) {
            $this->db->order($order, $desc);
        }
        return $this->db->fetch();
    }
    
    /**
     * Returns records with a certain column parameter.
     * @param string $column_name
     * @param mixed $param
     * @return array
     */
    public function getByColId(string $column_name, $param): array
    {
        return $this->db
                ->table($this->table)
                ->select()
                ->where($column_name, $param)
                ->fetch();
    }
    
    /**
     * Returns a record with a certain ID.
     * @param int $id
     * @return array
     */
    public function getById(int $id): array
    {
        return $this->db
                ->table($this->table)
                ->select()
                ->where('id', $id)
                ->fetch(1);
    }

    public function clear(): void
    {
        $this->db
                ->table($this->table)
                ->truncate()
                ->fetch();
    }
}
