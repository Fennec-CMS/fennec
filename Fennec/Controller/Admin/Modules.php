<?php
/**
 ************************************************************************
 * @copyright 2015 David Lima
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0) 
 ************************************************************************
 */
namespace Fennec\Controller\Admin;

use Fennec\Controller\Admin\Index as AdminController;
use Fennec\Model\Modules as ModulesModel;
use Fennec\Services\ModuleManager;

/**
 * Module management class
 *
 * @author David Lima
 * @version b0.1
 */
class Modules extends AdminController
{
    
    /**
     * Modules model
     * 
     * @var \Fennec\Model\Modules
     */
    private $model;

    /**
     * Defines page info and $this->model
     *
     * @see Fennec\Controller\Admin\Index::__construct()
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->model = new ModulesModel();

        $this->moduleInfo = array(
            'title' => 'Modules',
            'subtitle' => 'Install, update and remove selected modules'
        );
    }
    
    /**
     * Return a list with all installed modules
     * 
     * @return array
     */
    public function listAction()
    {
        $this->moduleInfo = array(
            'title' => 'Installed modules',
            'subtitle' => 'View and manage modules that you\'ve installed'
        );
        $this->list = $this->model->getAll();
        return $this->list;
    }
    
    /**
     * Return a list with all modules available to download
     * 
     * @return array
     */
    public function availableAction()
    {
        $this->moduleInfo = array(
            'title' => 'Available modules',
            'subtitle' => 'View and install useful modules to improve your experience'
        );
        
        $this->list = ModuleManager::getAvailableModulesList();
        return $this->list;
    }
    
    /**
     * Install a module
     * 
     * @return array
     */
    public function installAction()
    {
        $this->moduleInfo = array(
            'title' => 'Module installation'
        );
        
        $moduleName = $this->getParam('name');
        $modulesList = ModuleManager::getAvailableModulesList();

        $result = array(
            'error' => false,
            'result' => ''
        );
        $fetchedModule = array();
        
        foreach ($modulesList as $module) {
            if ($module['name'] == $moduleName) {
               $fetchedModule = $module;
               break; 
            }
        }

        if (! $fetchedModule) {
            $result['result'] = "Failed to install <b>{$moduleName}</b>. Module not found.";
            $result['error'] = true;
        } else {
            try {
                ModuleManager::install($fetchedModule, function() use ($fetchedModule) {
                    $this->model->setName($fetchedModule['name']);
                    $this->model->setDescription($fetchedModule['description']);
                    $this->model->setVersion($fetchedModule['version']);
                    $this->model->setSource($fetchedModule['source']);
                    $this->model->setRelease($fetchedModule['release']);
                    $this->model->setInstalldate(date('Y-m-d H:i:s'));
                    $this->model->setUserinstalled($_SESSION['fennecAdmin']->getId());
                    $result = $this->model->register();
                });
                
                $result['result'] = "Module <b>{$moduleName}</b> successfully installed!";
            } catch(\Exception $e) {
                $result['result'] = "Failed to install <b>{$moduleName}</b>.<br>" . $e->getMessage();
                $result['error'] = true;
            }
        }
        
        $this->result = $result;
        return $this->result;
    }
    
    /**
     * Permanently uninstall a module
     *
     * @return array
     */
    public function uninstallAction()
    {
        $this->moduleInfo = array(
            'title' => 'Module installation'
        );
        
        $moduleName = $this->getParam('name');
        
        $result = array(
            'result' => null,
            'error' => false
        );
        
        try {
            ModuleManager::uninstall($moduleName, false);
            $this->model->name = $moduleName;
            $unregister = $this->model->unregister();
            $result['result'] = "Module <b>{$moduleName}</b> successfully uninstalled!";
        } catch (\Exception $e) {
            $result['result'] = "Failed to uninstall: " . $e->getMessage();
            $result['error'] = true;
        }
        
        $this->result = $result;

        return $result;
    }
}
