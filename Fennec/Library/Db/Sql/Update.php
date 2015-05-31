<?php
/**
 ************************************************************************
 * @copyright 2015 David Lima
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 ************************************************************************
 */
namespace Fennec\Library\Db\Sql;

use Fennec\Library\Db\Db;

/**
 * SQL Update class
 *
 * @author David Lima
 * @version b0.1
 */
class Update
{
    use Limit,
        Conditioning;

    /**
     * Data to update
     *
     * @var array
     */
    private $data = array();

    /**
     * Table to update
     *
     * @var string
     */
    private $table;

    /**
     * SQL String
     *
     * @var string
     */
    private $sql;

    /**
     * Shortcut method update()
     *
     * @param string $table            
     */
    public function __construct($table)
    {
        $this->update($table);
    }

    /**
     * Create SQL SET
     *
     * @param array $data            
     * @return \Fennec\Library\Db\Sql\Update
     */
    public function set(array $data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Return SQL string
     *
     * @return string
     */
    public function __toString()
    {
        $this->mountSql();
        return $this->sql;
    }

    /**
     * Execute query in queue
     *
     * @throws \PDOException
     * @return \PDOStatement
     */
    public function execute()
    {
        $this->mountSql();
        try {
            Db::beginTransaction();
            Db::query($this->sql);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            throw $e;
        }
    }

    /**
     * Reset current query
     *
     * @return void
     */
    public function reset()
    {
        $this->sql = "";
    }

    /**
     * Defines table to update
     *
     * @param string $table            
     * @return \Fennec\Library\Db\Sql\Update
     */
    private function update($table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Generate SQL string based on class params
     *
     * @return string
     */
    private function mountSql()
    {
        $update = array();
        
        foreach ($this->data as $column => $value) {
            $update[] = $column . "='" . $value . "'";
        }
        
        $update = implode("','", $update);
        
        $this->sql = "UPDATE " . $this->table . " SET $update";
        
        $this->sql .= ($this->where ? " WHERE {$this->limit}" : '');
        
        $this->sql .= ($this->limit ? " LIMIT {$this->limit}" : '');
        
        return $this->sql;
    }
}