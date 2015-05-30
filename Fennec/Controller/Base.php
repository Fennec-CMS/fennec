<?php
namespace Fennec\Controller;

use Fennec\Library\Router;

class Base
{

    public $layout;

    public $view;

    private $params = array();

    public function layout($layout)
    {
        $layout = __DIR__ . "/../Layout/$layout.phtml";
        if (file_exists($layout)) {
            $this->layout = $layout;
        }
    }

    public function loadView($viewFile)
    {
        $viewFile = __DIR__ . "/../View/$viewFile.phtml";
        
        if (file_exists($viewFile)) {
            require_once ($viewFile);
        } else {
            echo "404";
        }
    }

    public function setParam($key, $value)
    {
        $this->params[$key] = $value;
    }

    public function getParam($key, $default = null)
    {
        if (isset($this->params[$key])) {
            return $this->params[$key];
        }

        return $default;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function run()
    {
        require_once(__DIR__ . "/../Config/Routes.php");

        Router::dispatch();

        if (isset(Router::$params)) {
            $this->params = array_merge($this->params, Router::$params);
        }

        $this->view = Router::$view;

        if ($this->layout) {
            require_once ($this->layout);
        }
    }
}
