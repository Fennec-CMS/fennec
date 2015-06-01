<?php
namespace Fennec\Controller;

use Fennec\Library\Http;

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
            $this->throwHttpError(404);
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

    public function throwHttpError($errorCode)
    {
        ob_clean();
        Http::changeHeader($errorCode);
        $this->view = "Error/$errorCode";
        require ($this->layout);
    }

    public function getParams()
    {
        return $this->params;
    }

    public function loadLayout()
    {
        if ($this->layout) {
            try {
                require_once ($this->layout);
            } catch (\Exception $e) {
                $this->exception = $e;
                $this->throwHttpError(500);
            }
        }
    }
}
