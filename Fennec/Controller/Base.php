<?php
namespace Fennec\Controller;

class Base
{

    public $layout;

    public $view;

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

    public function run()
    {
        $this->view = 'Index/index';
        if ($this->layout) {
            require_once ($this->layout);
        }
    }
}