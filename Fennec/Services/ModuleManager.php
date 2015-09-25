<?php
/**
 ************************************************************************
* @copyright 2015 David Lima
* @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
************************************************************************
*/
namespace Fennec\Services;

#use Fennec\Library\Db\Sql\Select;

/**
 * Module manager service
 * This service is useful to check, install and update Fennec's modules
 *
 * @author David Lima
 * @version b0.1
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
}
