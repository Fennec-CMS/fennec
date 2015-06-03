<?php
/**
 ************************************************************************
 * @copyright 2015 David Lima
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 ************************************************************************
 */
namespace Fennec;

use Fennec\Library\Router;

/**
 * Fennec CMS base file
 *
 * @author David Lima
 * @version b0.1
 */
class Application
{

    public function run()
    {
        require_once (__DIR__ . "/Config/Routes.php");
        require_once (__DIR__ . "/Config/Database.php");

        Router::dispatch();
    }
}
