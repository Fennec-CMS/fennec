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

    /**
     * Runs the applicatoin
     */
    public function run()
    {
        require_once (__DIR__ . "/Config/Routes.php");
        require_once (__DIR__ . "/Config/Database.php");

        $this->loadModules();

        Router::dispatch();
    }

    /**
     * Load all modules
     */
    private function loadModules()
    {
        $modulesDir = new \DirectoryIterator(__DIR__ . '/Modules/');
        foreach ($modulesDir as $dir) {
            if ($dir->isDot())
                continue;

            $module = $dir->getFilename();

            require_once (__DIR__ . "/Modules/$module/Routes.php");
        }
    }
}
