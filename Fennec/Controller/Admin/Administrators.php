<?php
/**
 ************************************************************************
 * @copyright 2015 David Lima
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0) 
 ************************************************************************
 */
namespace Fennec\Controller\Admin;

use Fennec\Controller\Admin\Index as AdminController;
use Fennec\Model\Administrators as AdministratorsModel;

/**
 * Administrators default controller
 *
 * @author David Lima
 * @version 0.3
 */
class Administrators extends AdminController
{
    /**
     * Administrators Model
     * @var \Fennec\Model\Administrators
     */
    private $model;

    /**
     * Defines $this->model
     *
     * @see Fennec\Controller\Admin\Index::__construct()
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new AdministratorsModel();

        $this->moduleInfo = array(
            'title' => $this->translate('Administrators'),
            'subtitle' => $this->translate('Manage website administrators')
        );
    }

    /**
     * Generate a list with all administrators
     * 
     * @return \Fennec\Model\PDOStatement
     */
    public function listAction()
    {
        $this->list = $this->model->getAll();
        return $this->list;
    }

    /**
     * If is a POST, try to save a new administrator. Show form otherwise
     */
    public function createAction()
    {
        if ($this->isPost()) {
            try {
                foreach ($this->getPost() as $postKey => $postValue) {
                    $this->$postKey = $postValue;
                }
                
                $this->model->setName($this->getPost('name'));
                $this->model->setEmail($this->getPost('email'));
                $this->model->setUsername($this->getPost('username'));
                $this->model->setPassword($this->getPost('password'));
                $this->result = $this->model->create();
                if (isset($this->result['errors'])) {
                    $this->result['result'] = implode('<br>', $this->result['errors']);
                }
            } catch (\Exception $e) {
                $this->exception = $e;
                $this->throwHttpError(500);
            }
        }
    }

    /**
     * Tries to permanently delete an administrator
     *
     * @see Fennec\Model\Administrators::remove()
     * @todo Enable abstract deletion (status-based)
     */
    public function deleteAction()
    {
        header("Content-Type: Application/JSON");
        $this->model->setId($this->getParam('id'));
        $this->result = $this->model->remove();
        print_r(json_encode($this->result));
        exit();
    }

    /**
     * Generate basic data from an administrator
     */
    public function profileAction()
    {
        $this->profile = $this->model->getByColumn("id", $this->getParam('id'), 1);
    }
}
