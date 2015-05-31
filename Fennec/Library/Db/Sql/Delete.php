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
 * SQL Delete class
 *
 * @author David Lima
 * @version b0.1
 */
class Delete
{
    use Limit,
        Conditioning;

    /**
     * Table to delete data from
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
     * Defines table to delete data from
     * 
     * @param string $table            
     * @return \Fennec\Library\Db\Sql\Delete
     */
    public function from($table)
    {
        $this->from = $table;
        return $this;
    }

    /**
     * Return SQL String
     * 
     * @return \Fennec\Library\Db\Sql\string
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
        return Db::query($this->sql);
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
     * Generate SQL String
     * 
     * @return \Fennec\Library\Db\Sql\string
     */
    private function mountSql()
    {
        $this->sql = "DELETE FROM " . $this->from;
        
        $this->sql .= ($this->where ? ' WHERE' . $this->where : '');
        $this->sql .= ($this->limit ? ' LIMIT' . $this->limit : '');
        
        return $this->sql;
    }
}
