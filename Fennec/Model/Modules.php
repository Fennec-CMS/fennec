<?php
/**
 ************************************************************************
 * @copyright 2015 David Lima
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0) 
 ************************************************************************
 */
namespace Fennec\Model;

/**
 * Modules model
 *
 * @author David Lima
 * @version 1.0
 * @todo implement Profile manager
 */
class Modules extends Base
{
    use \Fennec\Library\Security;

    /**
     * Table to save data
     *
     * @var string
     */
    public static $table = "modules";

    /**
     * Module name
     * 
     * @var string
     */
    public $name;
    
    /**
     * Module description
     * 
     * @var string
     */
    public $description;
    
    /**
     * Module version
     * 
     * @var string
     */
    public $version;
    
    /**
     * Module source URL
     * 
     * @example https://github.com/Fennec-CMS/module-name.git
     * @var string
     */
    public $source;
    
    /**
     * URL to download a module tar-gzipped file
     * 
     * @example https://anywebsite.com/anymodule-v1.0.tar.gz
     * @var string
     */
    public $release;
    
    /**
     * Date and time that module was registered
     * 
     * @var string (Y-m-d H:i:s)
     */
    public $installdate;
    
    /**
     * Date and time for the last module update
     * 
     * @var string (Y-m-d H:i:s)
     */
    public $updatedate;
    
    /**
     * Administrator that installed the module
     * 
     * @var int
     */
    public $userinstalled;

    /**

     * Register a fresh-installed module
     *
     * @return PDOStatement
     */
    public function register()
    {
        $data = $this->prepare();

        if (isset($data['valid']) && ! $data['valid']){
            return $data;
        } else {
            try {
                $this->insert($data)
                    ->into(self::$table)
                    ->execute();
                return array(
                    'result' => 'Module registered!'
                );
            } catch (\Exception $e) {
                return array(
                    'result' => 'Failed to register module!',
                    'errors' => $e->getMessage()
                );
            }
        }
    }


    /**
     * Perform a SQL delete
     *
     * @return multitype:string |multitype:string NULL
     */
    public function unregister()
    {
        $this->name = filter_var($this->name, \FILTER_SANITIZE_STRING);
        try {
            $this->delete()
            ->from(self::$table)
            ->where("name = '$this->name'")
            ->execute();
            return array(
                'result' => 'Module unregistered!'
            );
        } catch (\Exception $e) {
            return array(
                'result' => 'Failed to unregister module!',
                'errors' => $e->getMessage()
            );
        }
    }

    /**
     * Prepare data to register module
     *
     * @return string|integer|array
     */
    private function prepare()
    {
        $errors = $this->validate();
        if (! $errors['valid']) {
            return $errors;
        }
        
        $this->name = filter_var($this->name, \FILTER_SANITIZE_STRING);
        $this->description = filter_var($this->description, \FILTER_SANITIZE_STRING);
        $this->version = filter_var($this->version, \FILTER_SANITIZE_STRING);
        $this->source = filter_var($this->source, \FILTER_SANITIZE_STRING);
        $this->release = filter_Var($this->release, \FILTER_SANITIZE_STRING);
        
        return array(
            'name' => $this->name,
            'description' => $this->description,
            'version' => $this->version,
            'source' => $this->source,
            'release' => $this->release
        );
    }

    /**
     * Validate module data
     *
     * @return multitype:string
     */
    private function validate()
    {
        $validation = array(
            'valid' => true,
            'errors' => array()
        );

        if (! $this->name) {
            $validation['valid'] = false;
            $validation['errors']['name'] = "Module name must be informed";
        }

        if (! $this->version) {
            $validation['valid'] = false;
            $validation['errors']['version'] = "Module version must be informed";
        }
        
        if (! $this->source) {
            $validation['valid'] = false;
            $validation['errors']['source'] = "Module source URL must be informed";
        }
        
        if (! $this->release) {
            $validation['valid'] = false;
            $validation['errors']['release'] = "Module release URL must be informed";
        }

        return $validation;
    }
}
