<?php
/**
 ************************************************************************
* @copyright 2015 David Lima
* @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
************************************************************************
*/
namespace Fennec\Services;

#use Fennec\Library\Db\Sql\Select;
use Fennec\Library\Db\Db;

/**
 * Module manager service
 * This service is useful to check, install and update Fennec's modules
 *
 * @author David Lima
 * @version b0.2
 * @todo Implement methods to install and update modules
 */
class ModuleManager
{
    /**
     * Relative path to file containing all repositories URLs
     * 
     * @var string
     */
    const MODULE_REPOS = __DIR__ . '/../Config/ModuleRepos.php';
    
    /**
     * Temporary directory to save downloads
     * 
     * @var string
     */
    const TMP_DIR = __DIR__ . '/../.tmp';

    /**
     * List of repositories fetched from self::MODULE_REPOS file
     * 
     * @var array
     */
    public static $repoList = array();
    
    /**
     * List of all modules fetched from self::$repoList
     * 
     * @var array
     */
    public static $modulesList = array();
    
    /**
     * Fetch and returns all available modules
     * 
     * @throws \Exception
     * @return \Fennec\Services\array
     */
    public static function getAvailableModulesList()
    {
        if (! self::$repoList && file_exists(self::MODULE_REPOS)) {
            self::$repoList = require_once(self::MODULE_REPOS);
        }
        
        $modulesList = array();
        
        foreach (self::$repoList as $repoName => $repo) {
            if (! filter_var($repo, \FILTER_VALIDATE_URL)) {
                throw new \Exception("Invalid module repository URL: $repo");
            }
            
            $ch = curl_init($repo);
            curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, \CURLOPT_SSL_VERIFYPEER, 0); # HTTPS workaround
            $data = json_decode(curl_exec($ch), true);
            foreach ($data['modules'] as &$module) {
                $module['repoName'] = $repoName;
                $module['repo'] = $repo;
            }
            $modulesList = array_merge($modulesList, $data['modules']);
            
        }
        
        self::$modulesList = $modulesList;
        unset($modulesList);
        
        return self::$modulesList;
    }
    
    /**
     * Downloads and install a module
     * 
     * @param string $module
     */
    public static function install($module, callable $callback)
    {
        if (self::isInstalled($module['name'])) {
            throw new \Exception("Module {$module['name']} already exists.");
        }
        
        if (! is_dir(self::TMP_DIR) || ! is_writable(self::TMP_DIR)) {
            throw new \Exception(self::TMP_DIR . ' must exists and be writable to continue');
        }
        
        $tempFilename = self::TMP_DIR . '/' . uniqid('module-') . '.tar.gz';
        $release = $module['release'];
        $ch = curl_init($release);
        curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, \CURLOPT_SSL_VERIFYPEER, 0); # HTTPS workaround
        curl_setopt($ch, \CURLOPT_FOLLOWLOCATION, 1);
        $data = curl_exec($ch);
        $tar = fopen($tempFilename, 'w');
        fwrite($tar, $data);
        fclose($tar);
        `tar -xf $tempFilename -C ../Fennec/Modules`;
        $moduleDir = "../Fennec/Modules/{$module['name']}"; 
        $sqlFile = $moduleDir . "/Sql/base.sql";
        
        if (file_exists($sqlFile)) {
            Db::exec(file_get_contents($sqlFile));
        }
        unlink($tempFilename);
        
        if (is_callable($callback)) {
            $callback();
        }
    }
    
    /**
     * Remove module folder and run the Sql/uninstall.sql file if it exists
     * 
     * @param string $moduleName
     *                  Module to uninstall (must be the exact name of the module folder)
     * @param boolean $keepSettings
     *                  If false, delete all module settings of the 'settings' table. Otherwise, keep it. 
     * @throws \Exception
     * @return boolean
     */
    public static function uninstall($moduleName, $keepSettings = true)
    {
        $moduleDir = "../Fennec/Modules/{$moduleName}";
        if (! self::isInstalled($moduleName)) {
            throw new \Exception("Module {$moduleName} not exists");
        }
        
        if (file_exists($moduleDir . '/Sql/uninstall.sql')) {
            Db::exec(file_get_contents($moduleDir . '/Sql/uninstall.sql'));
        }
        
        if (! $keepSettings) {
            Db::query("DELETE FROM settings WHERE module = '{$moduleName}'");
        }
        
        `rm -rf {$moduleDir}`;
        
        return true;
    }
    
    /**
     * Checks if a module is already installed
     * 
     * @param string $moduleName must be the exact name of installed module (case-sensitive)
     * @return bool
     */
    public static function isInstalled($moduleName)
    {
        $moduleDir = "../Fennec/Modules/{$moduleName}";
        return is_dir($moduleDir);
    }
}
