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
 * SQL Select class
 *
 * @author David Lima
 * @version b0.1
 */
class Select
{
    
    use Limit,
        Conditioning;

    /**
     * Columns to retrieve
     *
     * @var array|string
     */
    private $columns;

    /**
     * Table to select data from
     *
     * @var string
     */
    private $from;

    /**
     * Join info
     *
     * @var array
     */
    private $join = array();

    /**
     * SQL ORDER clause
     *
     * @var string
     */
    private $order;

    /**
     * SQL GROUP clause
     *
     * @var string
     */
    private $group;

    /**
     * SQL String
     *
     * @var sring
     */
    private $sql;

    /**
     * Classname to hydrate with Fetch
     *
     * @var string
     */
    private $class;

    /**
     * Shortcut to select() method
     *
     * @param array|string $column
     * @param string $class
     */
    public function __construct($column, $class = null)
    {
        $this->select($column);
        $this->class = $class;
    }

    /**
     * Defines table to select data from
     *
     * @param string $table            
     * @return \Fennec\Library\Db\Sql\Select
     */
    public function from($table)
    {
        $this->from = $table;
        return $this;
    }

    /**
     * Define SQL Join
     *
     * @param string $table            
     * @param string|array $columns            
     * @param string $condition            
     * @param string $type            
     * @return \Fennec\Library\Db\Sql\Select
     */
    public function join($table, $columns, $condition, $type = null)
    {
        if (is_array($columns)) {
            $columns = $table . '.' . implode(",$table.", $columns);
        } else {
            $columns = "$table.$columns";
        }
        
        $this->join[] = array(
            'table' => $table,
            'columns' => $columns,
            'condition' => $condition,
            'type' => $type
        );
        return $this;
    }

    /**
     * Define SQL ORDER clause
     *
     * @param string $column            
     * @param string $type            
     * @return \Fennec\Library\Db\Sql\Select
     */
    public function order($column, $type = 'ASC')
    {
        $this->order = $column . ' ' . $type;
        return $this;
    }

    /**
     * Define SQL GROUP clause
     *
     * @param string $column            
     * @param string $type            
     * @return \Fennec\Library\Db\Sql\Select
     */
    public function group($column, $type = 'ASC')
    {
        $this->group = $column . ' ' . $type;
        return $this;
    }

    /**
     * Generate SQL String
     *
     * @return string
     */
    private function mountSql()
    {
        $select = 'SELECT ' . $this->columns;
        
        $join = '';
        
        $from = ' FROM ' . $this->from;
        
        if ($this->join) {
            foreach ($this->join as $joinPart) {
                $select .= ',' . $joinPart['columns'];
                
                $join .= ($joinPart['type'] ? ' ' . $joinPart['type'] : '') . ' JOIN ' . $joinPart['table'] . ' ON ' . $joinPart['condition'];
            }
        }
        
        $where = ($this->where ? ' WHERE ' . $this->where : '');
        
        $order = ($this->order ? ' ORDER BY ' . $this->order : '');
        
        $group = ($this->group ? ' GROUP BY ' . $this->group : '');
        
        $limit = ($this->limit ? ' LIMIT ' . $this->limit : '');
        
        $sql = $select . $from . $join . $where . $order . $group . $limit;
        
        $this->sql = $sql;
        return $sql;
    }

    /**
     * Return SQL STring
     *
     * @return \Fennec\Library\Db\Sql\sring
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
        $query = Db::query($this->sql);
        if ($this->class) {
            $query->setFetchMode(\PDO::FETCH_CLASS, $this->class);
        }

        return $query;
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
     * Define columns to retrieve
     * 
     * @param string $column            
     * @return \Fennec\Library\Db\Sql\Select
     */
    private function select($column = '*')
    {
        if (is_array($column)) {
            $this->columns = implode(',', $column);
        } else {
            $this->columns = $column;
        }
        
        return $this;
    }
}