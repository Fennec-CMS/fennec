<?php
/**
 ************************************************************************
 * @copyright 2015 David Lima
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 ************************************************************************
 */
namespace Fennec\Modules\Sample\Controller;

use \Fennec\Controller\Base;

/**
 * Sample custom module
 *
 * @author David Lima
 * @version b0.1
 */
class Index extends Base
{

    /**
     * Default action
     */
    public function indexAction()
    {
        $this->text = "I'm a custom module";
    }
}
