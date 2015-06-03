<?php
/**
 ************************************************************************
 * @copyright 2015 David Lima
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 ************************************************************************
 */
namespace Fennec\Controller;

/**
 * Index controller
 *
 * @author David Lima
 * @version b0.1
 */
class Index extends Base
{

    /**
     * Action to Index/action view
     */
    public function indexAction()
    {
        $this->test = "I'm a view";
    }
}
