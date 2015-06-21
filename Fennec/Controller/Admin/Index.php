<?php
/**
 ************************************************************************
 * @copyright 2015 David Lima
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0) 
 ************************************************************************
 */
namespace Fennec\Controller\Admin;

use Fennec\Controller\Base;
use Fennec\Model\Administrators;

/**
 * Administration base controller
 * 
 * @author David Lima
 * @version b0.1
 */
class Index extends Base
{

    /**
     * Fennec sidebar menu
     * @var array
     */
    public $menu = array();

    /**
     * Info to be shown on module internal pages
     *
     * @var array
     */
    public $moduleInfo = array();

    /**
     * Runs base authentication
     */
    public function __construct()
    {
        if (! $this->isAuthenticated()) {
            $this->layout('Admin/Unauthenticated');
            $this->module = false;
            $this->view = 'Admin/Index/login';
            $this->loginAction();
        }

        $this->loadMenu();
    }

    /**
     * Default action
     */
    public function indexAction()
    {}

    /**
     * Default logged page action
     */
    public function dashboardAction()
    {
        /**
         * @todo put some dashboard data here
         */
    }

    /**
     * Login page action
     */
    public function loginAction()
    {
        if ($this->isPost()) {
            $model = new Administrators();
            $model->setUsername($this->getPost('username'));
            $model->setPassword($this->getPost('password'));
            $model->authenticate();

            if (! $this->isAuthenticated()) {
                $this->errors = array(
                    'Cannot authenticate you :('
                );
            }
        }

        if ($this->isAuthenticated()) {
            $this->layout('Admin/Default');
            $this->view = 'Admin/Index/dashboard';
            $this->dashboardAction();
        }
    }

    /**
     * Logout action
     */
    public function logoutAction()
    {
        if ($this->isAuthenticated()) {
            unset($_SESSION['fennecAdmin']);
        }

        header("Location: /");
        exit();
    }

    /**
     * Check if there is an user authenticated
     * 
     * @return boolean
     */
    private function isAuthenticated()
    {
        return (isset($_SESSION['fennecAdmin']) && $_SESSION['fennecAdmin'] instanceof \Fennec\Model\Administrators);
    }

    /**
     * Hydrate $this->menu with Menu items
     */
    private function loadMenu()
    {
        $menuFile = __DIR__ . '/../../Config/Admin/Menu.php';

        if (file_exists($menuFile)) {
            $this->menu = require_once($menuFile);
        }
    }
}
