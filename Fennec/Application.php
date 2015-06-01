<?php
namespace Fennec;

use Fennec\Library\Router;

class Application
{

    public function run()
    {
        require_once (__DIR__ . "/Config/Routes.php");
        require_once (__DIR__ . "/Config/Database.php");

        Router::dispatch();
    }
}
