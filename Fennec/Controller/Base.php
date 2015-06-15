<?php
/**
 ************************************************************************
 * @copyright 2015 David Lima
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 ************************************************************************
 */
namespace Fennec\Controller;

use Fennec\Library\Http;

/**
 * Base controller
 *
 * @author David Lima
 * @version b0.1
 */
class Base
{

    /**
     * Layout to load.
     * Must be a valid .phtml file in Fennec/Layout/
     *
     * @var string
     */
    public $layout;

    /**
     * Determines if it is a custom Fennec module
     * 
     * @var string
     */
    public $module;

    /**
     * View to load.
     * Must be a valid .phtml file in Fennec/View
     *
     * @var string
     */
    public $view;

    /**
     * GET params
     *
     * @var string
     */
    private $params = array();

    /**
     * Sets layout if file $layout exists.
     * Do nothing otherwise
     *
     * @param string $layout
     */
    public function layout($layout)
    {
        $layout = __DIR__ . "/../Layout/$layout.phtml";
        if (file_exists($layout)) {
            $this->layout = $layout;
        }
    }

    /**
     * Load a view
     *
     * @param string $viewFile
     */
    public function loadView($viewFile)
    {
        if ($this->module) {
            $viewFile = __DIR__ . "/../$viewFile.phtml";
        } else {
            $viewFile = __DIR__ . "/../View/$viewFile.phtml";
        }

        if (file_exists($viewFile)) {
            require_once ($viewFile);
        } else {
            $this->throwHttpError(404);
        }
    }

    /**
     * Sets a GET param
     *
     * @param string $key
     * @param multitype $value
     */
    public function setParam($key, $value)
    {
        $this->params[$key] = $value;
    }

    /**
     * Return a GET param
     *
     * @param string $key
     * @param string $default
     * @return string
     */
    public function getParam($key, $default = null)
    {
        if (isset($this->params[$key])) {
            return $this->params[$key];
        }

        return $default;
    }

    /**
     * Throws an HTTP error
     *
     * @param integer $errorCode
     */
    public function throwHttpError($errorCode)
    {
        ob_clean();
        Http::changeHeader($errorCode);
        $this->view = "Error/$errorCode";
        require ($this->layout);
    }

    /**
     * Return all GET params
     *
     * @return \Fennec\Controller\string
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Try to load $this->layout
     */
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

    /**
     * Determine if request is a post
     *
     * @return boolean
     */
    protected function isPost()
    {
        return (isset($_POST) && $_POST);
    }

    /**
     * If request is a post, return $_POST or a $_POST key
     *
     * @param string $key
     * @return multitype|boolean
     */
    protected function getPost($key = null)
    {
        if ($this->isPost()) {
            return ($key ? $_POST[$key] : $_POST);
        }
        return false;
    }
}
