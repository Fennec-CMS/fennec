<?php
/**
 ************************************************************************
 * @copyright 2015 David Lima
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 ************************************************************************
 */
namespace Fennec\Library\Db;

use Fennec\Library\Db\Sql\Select;
use Fennec\Library\Db\Sql\Insert;
use Fennec\Library\Db\Sql\Update;
use Fennec\Library\Db\Sql\Delete;

/**
 * SQL management trait
 *
 * @author David Lima
 * @version b0.1
 */
trait Sql
{

    /**
     * Init a new SQL SELECT query
     *
     * @param string $column            
     * @return \Fennec\Library\Db\Sql\Select
     */
    public function select($column = '*')
    {
        return new Select($column, get_class($this));
    }

    /**
     * Init a new SQL INSERT query
     *
     * @param array $data            
     * @return \Fennec\Library\Db\Sql\Insert
     */
    public function insert(array $data)
    {
        return new Insert($data);
    }

    /**
     * Init a new SQL UPDATE query
     *
     * @param array $data            
     * @return \Fennec\Library\Db\Sql\Update
     */
    public function update($table)
    {
        return new Update($table);
    }

    /**
     * Init a new SQL DELETE query
     *
     * @param array $data            
     * @return \Fennec\Library\Db\Sql\Delete
     */
    public function delete()
    {
        return new Delete();
    }
}