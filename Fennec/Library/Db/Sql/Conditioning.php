<?php
/**
 ************************************************************************
 * @copyright 2015 David Lima
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 ************************************************************************
 */
namespace Fennec\Library\Db\Sql;

/**
 * SQL Conditioning class
 *
 * @author David Lima
 * @version b0.1
 *         
 */
trait Conditioning
{

    /**
     * SQL WHERE
     *
     * @var string
     */
    public $where;

    /**
     * Defines SQL WHERE condition
     *
     * @param string $condition
     * @return \Fennec\Library\Db\Sql\Select|\Fennec\Library\Db\Sql\Update\Fennec\Library\Sql\Delete
     */
    public function where($condition)
    {
        $this->where = $condition;
        return $this;
    }
}