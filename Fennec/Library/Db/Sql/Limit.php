<?php
/**
 ************************************************************************
 * @copyright 2015 David Lima
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 ************************************************************************
 */
namespace Fennec\Library\Db\Sql;

/**
 * SQL Limitation class
 *
 * @author David Lima
 * @version b0.1
 */
trait Limit
{

    /**
     * SQL LIMIT
     * 
     * @var integer
     */
    private $limit;

    /**
     * Defines SQL LIMIT
     * 
     * @param integer $limit            
     * @return \Fennec\Library\Db\Sql\Select|\Fennec\Library\Db\Sql\Update\Fennec\Library\Sql\Delete
     */
    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Defines SQL OFFSET
     *
     * @param integer $offset
     * @return \Fennec\Library\Db\Sql\Limit
     */
    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }
}
