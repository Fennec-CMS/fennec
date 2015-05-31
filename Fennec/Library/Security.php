<?php
/**
 ************************************************************************
 * @copyright 2015 David Lima
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0) 
 ************************************************************************
 */
namespace Fennec\Library;

/**
 * Security functions
 *
 * @author David Lima
 * @version b0.1
 */
trait Security
{

    /**
     * Generates a new hash
     *
     * @param string $string            
     * @return string
     */
    public static function hash($string)
    {
        return password_hash($string, \PASSWORD_DEFAULT);
    }

    /**
     * Validate a string against a hash
     * 
     * @param string $password            
     * @param string $hash            
     * @return boolean
     */
    public static function verify($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * Verify if is necessary rehash a hash
     * 
     * @param string $hash            
     * @return boolean
     */
    public static function needsRehash($hash)
    {
        return password_needs_rehash($hash, \PASSWORD_DEFAULT);
    }
}
