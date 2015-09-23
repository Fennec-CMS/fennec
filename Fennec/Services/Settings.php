<?php
/**
 ************************************************************************
* @copyright 2015 David Lima
* @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
************************************************************************
*/
namespace Fennec\Services;

use Fennec\Library\Db\Sql\Select;

/**
 * Settings service
 * This class provides basic implementation of a "Settings" zone persisted on database
 * Note: the setting keys must exists on database in order to user this class.
 *
 * @author David Lima
 * @version b0.1
 */
class Settings
{
    
    use \Fennec\Library\Db\Sql;
    
    /**
     * Table to save data. Do not change unless it is necessary
     */
    const SETTINGS_TABLE = 'settings';
    
    /**
     * Full-qualified module name
     * 
     * @var string
     */
    protected $module;
    
    /**
     * Module name
     * 
     * @var string
     */
    protected $moduleName;
    
    /**
     * Fetched module settings
     * 
     * @var array
     */
    public $settings = array();
    
    /**
     * Fetch module settings and fill $this->settings array with these settings
     * 
     * @param string $module Module name to fetch settings (case-sensitive)
     * @throws \Exception
     */
    public function __construct($module) {
        
        $className = "\\Fennec\\Modules\\$module\\Controller\\Index";

        if (! class_exists($className, true)) {
            throw new \Exception("Unable to load settings for $module module");
        }
        
        $this->module = $className;
        $this->moduleName = $module;
        
        $this->fetchSettings();
    }
    
    /**
     * Return a single setting value or full $this->settings array
     * 
     * @param string $key If provided, return only the value to this setting key
     * @throws \Exception
     * @return mixed
     */
    public function getSetting($key = null)
    {
        if ($key) {
            if (! isset($this->settings[$key])) {
                throw new \Exception("Configuration key $key for module {$this->module} do not exists");
            }
    
            return $this->settings[$key];
        }
    
        return $this->settings;
    }
    
    /**
     * Update a setting on database
     * 
     * @param string $key
     * @param string $value
     * @return PDOStatement
     */
    public function saveSetting($key, $value = null)
    {
        $this->getSetting($key);
        
        $data = array(
            'value' => $value,
            'lastchange' => date('Y-m-d H:i:s')
        );
        
        $save = $this->update(self::SETTINGS_TABLE)
            ->set($data)
            ->where("key = '$key' AND module = '{$this->moduleName}'")
            ->execute();
            
        $this->settings[$key] = $value;
        
        return $save;
    }
    
    /**
     * Fetch $this->module settings and put it on $this->settings array
     */
    private function fetchSettings()
    {
        $select = new Select('*', '\ArrayObject');
        $settings = $select->from(self::SETTINGS_TABLE)
                    ->where("module = '{$this->moduleName}'")
                    ->execute()
                    ->fetchAll();
                    
        if ($settings) {
            foreach ($settings as $setting) {
                $this->settings[$setting->key] = $setting->value;
            }
        }
                    
        return $this->settings;
    }
    
    public function __get($key)
    {
        return $this->getSetting($key);
    }

}
