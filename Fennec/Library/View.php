<?php
/**
 ************************************************************************
 * @copyright 2015 David Lima
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 ************************************************************************
 */
namespace Fennec\Library;

/**
 * View manager
 *
 * @author David Lima
 * @version b0.3
 */
class View
{
    /**
     * Path to View file
     *
     * @var string
     */
    public $viewFile;
    
    /**
     * Load a view
     *
     * @param string $viewFile
     */
    public function __construct($viewFile, $module = null)
    {
        if ($module) {
            $this->viewFile = __DIR__ . "/../$viewFile.phtml";
        } else {
            $this->viewFile = __DIR__ . "/../View/$viewFile.phtml";
        }
    
        if (file_exists($this->viewFile)) {
            return $this->viewFile;
        } else {
            die("404");
        }
    }
    
    /**
     * Return full View filename
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->viewFile;
    }
}
