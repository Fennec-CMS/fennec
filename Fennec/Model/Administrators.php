<?php
/**
 ************************************************************************
 * @copyright 2015 David Lima
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0) 
 ************************************************************************
 */
namespace Fennec\Model;

/**
 * Administrators model
 *
 * @author David Lima
 * @version b0.1
 * @todo implement Profile manager
 */
class Administrators extends Base
{
    use \Fennec\Library\Security;

    /**
     * Table to save data
     *
     * @var string
     */
    public static $table = "administrators";

    /**
     * Administrator name
     *
     * @var string
     */
    public $name;

    /**
     * Administrator email
     *
     * @var string
     */
    public $email;

    /**
     * Administrator password
     *
     * @var string
     */
    public $password;

    /**
     * Administrator profile
     *
     * @var integer
     */
    public $profile;

    /**
     * Administrator ID
     *
     * @var integer
     */
    public $id;
    /**

     * Creates a new administrator
     *
     * @return PDOStatement
     */
    public function create()
    {
        $data = $this->prepare();
        return $this->insert($data)
            ->into(self::$table)
            ->execute();
    }

    /**
     * Authenticate an user
     *
     * @return boolean
     */
    public function authenticate()
    {
        $this->username = filter_var($this->username, \FILTER_SANITIZE_STRING);
        $userExists = $this->select('*')
                            ->from(self::$table)
                            ->where("username = '{$this->username}'")
                            ->limit(1)
                            ->execute();
        $userData = $userExists->fetch();

        if ($userData) {
            if ($this->verify($this->password, $userData->getPassword())) {
                $_SESSION['fennecAdmin'] = $userData;
                return true;
            }
        }

        return false;
    }

    /**
     * Prepare data to create administrator
     *
     * @return multitype:string |multitype:\Fennec\Model\string \Fennec\Model\integer
     */
    private function prepare()
    {
        $errors = $this->validate();
        if ($errors) {
            return $errors;
        }
        
        $this->name = filter_var($this->name, \FILTER_SANITIZE_STRING);
        $this->username = filter_var($this->username, \FILTER_SANITIZE_STRING);
        $this->email = filter_var($this->email, \FILTER_SANITIZE_EMAIL);
        $this->password = self::hash($this->password);
        $this->profile = 1; // needs profile manager
        
        return array(
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'password' => $this->password,
            'profile' => $this->profile
        );
    }

    /**
     * Validate administrator data
     *
     * @return multitype:string
     */
    private function validate()
    {
        $errors = array();
        
        if (! filter_var($this->email, \FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Email inválido";
        }
        
        return $errors;
    }
}
