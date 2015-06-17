<?php
/**
 ************************************************************************
 * @copyright 2015 David Lima
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0) 
 ************************************************************************
 */
namespace Fennec\Library;

/**
 * Trait used to generate Bootstrap's alerts
 * @author David Lima
 * @version b1.0
 */
trait Alerts
{
    /**
     * The structure of the alert
     *
     * @var string
     */
    public static $structure = "<div class=\"alert alert-%s\" id=\"result\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button> %s</div>";

    /**
     * Return alert markup
     *
     * @param string $type
     *            warning|danger|success|info|default
     * @param string $message
     *            the message to display
     * @return string
     */
    public static function Create($type, $message)
    {
        return sprintf(self::$structure, $type, $message);
    }
}
