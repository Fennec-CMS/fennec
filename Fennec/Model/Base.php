<?php
/**
 ************************************************************************
 * @copyright 2015 David Lima
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0) 
 ************************************************************************
 */
namespace Fennec\Model;


/**
 * Basic model class
 *
 * @author David Lima
 * @version b0.1
 */
class Base
{
    use \Fennec\Library\Db\Sql;

    /**
     * Return a param
     *
     * @param string $param            
     */
    public function __get($param)
    {
        return $this->$param;
    }

    /**
     * Sets a param value
     *
     * @param string $param            
     * @param string $value            
     * @return multitype
     */
    public function __set($param, $value)
    {
        return $this->$param = $value;
    }

    /**
     * Dynamic setter and getter
     *
     * @param string $name            
     * @param array $arguments            
     * @throws \BadMethodCallException if method is not "set" nor "get"
     */
    public function __call($name, $arguments)
    {
        $type = strtolower(substr($name, 0, 3)); // set, get
        
        $param = strtolower(substr($name, 3));
        
        if ($type == "set") {
            $this->$param = $arguments[0];
            return $this;
        } elseif ($type == "get") {
            return $this->$param;
        } else {
            throw new \BadMethodCallException("Method $name don't exists");
        }
    }

    /**
     * Returns all data from specified model
     * 
     * @throws \Exception
     * @return PDOStatement
     */
    public function getAll()
    {
        if (! isset(static::$table)) {
            throw new \Exception("Table for " . static::class . " not defined");
        }
        
        return $this->select("*")
            ->from(static::$table)
            ->execute();
    }
}
