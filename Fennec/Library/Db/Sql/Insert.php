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
class Insert
{
    use Conditioning;

    /**
     * Columns to insert on
     *
     * @var array
     */
    private $columns = array();

    /**
     * Data to insert
     *
     * @var array
     */
    private $data = array();

    /**
     * Table to insert data
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
     * Shortcut to method insert()
     *
     * @param array $data            
     * @return \Fennec\Library\Db\Sql\Insert
     */
    public function __construct(array $data)
    {
        return $this->insert($data);
    }

    /**
     * Defines table to insert data on
     *
     * @param string $table            
     * @return \Fennec\Library\Db\Sql\Insert
     */
    public function into($table)
    {
        $this->table = $table;
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
        try {
            Db::beginTransaction();
            Db::query($this->sql);
            Db::commit();
            return $this->lastInsertId();
        } catch (\Exception $e) {
            Db::rollBack();
            throw $e;
        }
    }

    /**
     * Return SQL LAST_INSERT_ID
     *
     * @return integer
     */
    public function lastInsertId()
    {
        return Db::lastInsertId();
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
     * Defines columns and data to insert
     *
     * @param array $data            
     * @return \Fennec\Library\Db\Sql\Insert
     */
    private function insert(array $data)
    {
        $this->columns = array_keys($data);
        $this->data = $data;
        return $this;
    }

    /**
     * Generate SQL string
     *
     * @return \Fennec\Library\Db\Sql\string
     */
    private function mountSql()
    {
        $columns = implode(',', $this->columns);
        $data = "'" . implode("','", $this->data) . "'";
        
        $this->sql = "INSERT INTO " . $this->table . " ($columns) VALUES ($data)";
        
        return $this->sql;
    }
}