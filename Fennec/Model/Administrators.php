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

        if (isset($data['valid']) && ! $data['valid']){
            return $data;
        } else {
            try {
                $this->insert($data)
                    ->into(self::$table)
                    ->execute();
                return array(
                    'result' => 'Administrator created!'
                );
            } catch (\Exception $e) {
                return array(
                    'result' => 'Failed to create administrator!',
                    'errors' => $e->getMessage()
                );
            }
        }
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
                $userData->setSince(new \DateTime($userData->getSince()));
                $_SESSION['fennecAdmin'] = $userData;
                return true;
            }
        }

        return false;
    }

    /**
     * Perform a SQL delete
     *
     * @return multitype:string |multitype:string NULL
     */
    public function remove()
    {
        $this->id = intval($this->id);
        try {
            $this->delete()
            ->from(self::$table)
            ->where("id = '$this->id'")
            ->execute();
            return array(
                'result' => 'Administrator removed!'
            );
        } catch (\Exception $e) {
            return array(
                'result' => 'Failed to remove administrator!',
                'errors' => $e->getMessage()
            );
        }
    }

    /**
     * Prepare data to create administrator
     *
     * @return multitype:string |multitype:\Fennec\Model\string \Fennec\Model\integer
     */
    private function prepare()
    {
        $errors = $this->validate();
        if (! $errors['valid']) {
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
        $validation = array(
            'valid' => true,
            'errors' => array()
        );

        if (! $this->name) {
            $validation['valid'] = false;
            $validation['errors']['name'] = "Name is a required field";
        }

        if (! $this->username) {
            $validation['valid'] = false;
            $validation['errors']['username'] = "Username is a required field";
        }

        if (! filter_var($this->email, \FILTER_VALIDATE_EMAIL)) {
            $validation['valid'] = false;
            $validation['errors']['email'] = "Invalid email";
        }

        return $validation;
    }
}
