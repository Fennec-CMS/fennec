<?php
namespace Fennec\Controller;

class Index extends Base
{

    public function indexAction()
    {
        $this->test = "I'm a view";
    }
}