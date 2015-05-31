<?php
/**
 ************************************************************************
 * @copyright 2015 David Lima
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0) 
 ************************************************************************
 */
namespace Fennec\Library\Db;

/**
 * Database class
 *
 * @author David Lima
 * @version b0.1
 * @todo implement error management
 */
class Db
{

    /**
     * Connection object
     *
     * @var \PDO
     */
    public static $connection;

    /**
     * Database management system
     *
     * @var string
     */
    public static $dbms;

    /**
     * Database host
     *
     * @var string
     */
    public static $host;

    /**
     * Database name
     *
     * @var string
     */
    public static $database;

    /**
     * Database user
     *
     * @var string
     */
    public static $user;

    /**
     * Database user password
     *
     * @var string
     */
    public static $password;

    /**
     * If self::$connection is not an \PDO instance, opens a new connection on it.
     *
     * @return PDO
     */
    public static function getConnection()
    {
        if (! self::$connection instanceof \PDO) {
            $dsn = self::$dbms . ":host=" . self::$host . ";dbname=" . self::$database;
            
            $options = array(
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            );
            
            try {
                self::$connection = new \PDO($dsn, self::$user, self::$password, $options);
            } catch (\Exception $e) {
                echo $e->getMessage();
                exit();
            }
        }
        
        return self::$connection;
    }

    /**
     * Starts a SQL transaction
     *
     * @return boolean true case success, false otherwise
     */
    public static function beginTransaction()
    {
        return self::getConnection()->beginTransaction();
    }

    /**
     * Commit a SQL transaction
     *
     * @return boolean true case success, false otherwise
     */
    public static function commit()
    {
        return self::getConnection()->commit();
    }

    /**
     * Undo the actual SQL transaction
     *
     * @return boolean true case success, false otherwise
     */
    public static function rollBack()
    {
        return self::getConnection()->rollBack();
    }

    /**
     * Runs a SQL query
     *
     * @param string $sql            
     * @return boolean
     */
    public static function query($sql)
    {
        return self::getConnection()->query($sql);
    }
}