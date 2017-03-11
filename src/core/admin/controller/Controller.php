<?php
namespace Nixhatter\ICMS\admin\controller;

/**
 * Controller
 *
 * @package ICMS
 * @author Dillon Aykac
 */

defined('_ICMS') or die;

use Respect\Validation\Validator as v;


class Controller {
    public $user_id;
    public $purifier;
    private $settings;
    private $model;
    protected $errors = array();

    public function __construct(\Nixhatter\ICMS\model\UserModel $model) {
        $this->model = $model;
        $this->settings = $model->container['settings'];
    }

    public function success() {
        echo ("<script>window.onload = function() {
                    successAlert('');
               };</script>");
    }
    public function alert($type, $message) {
        echo("<script>window.onload = function() {
               ".$type."Alert('".$message."');
              };</script>");
    }
    public function logged_in() {
        if (!empty($_SESSION['id'])) {
            $this->user_id   = $_SESSION['id'];
            return true;
        }
        return false;
    }

    protected function postValidation($variable) {
        $variable = trim($variable);
        $variable = strip_tags($variable);
        $variable = htmlspecialchars($variable, ENT_QUOTES, "UTF-8");
        if (get_magic_quotes_gpc()) {
            //Escape basic strings
            $variable = addslashes($variable);
        }
        return $variable;

    }

    protected function strictValidation($variable) {
        $variable = htmlspecialchars($variable, ENT_QUOTES, "UTF-8");
        if (v::alnum('-')->noWhitespace()->validate($variable)) {
            return $variable;
        } else {
            $this->errors = "Strict validation failed on ". $variable;
            return "";
        }
    }

    protected function dotslashValidation($variable) {
        $variable = strip_tags($variable);
        if (v::alnum('./')->noWhitespace()->validate($variable)) {
            return $variable;
        } else {
            return "";
        }
    }

    protected function slashValidation($variable) {
        $variable = strip_tags($variable);
        if (v::alnum('/')->noWhitespace()->validate($variable)) {
            return $variable;
        } else {
            return "";
        }
    }

    /**
     * Numbers, Letters, Forward slashes and one . in the middle
     * True: hello.php, /dir/index2.html
     * False: ../test.php, /dir/../../etc/passwd
     * @param $variable
     */
    protected function fileValidation($variable) {
        $variable = strip_tags($variable);
        if(v::regex('@^[a-zA-Z/]+(\.{1}[a-zA-Z]+)?$@')->validate($variable)) {
            return $variable;
        } else {
            return "";
        }
    }

    protected function filterIP($ip) {
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return $ip;
        } else {
            return "";
        }
    }

    public function inputValidation($variable, $option = null) {
        $variable = trim($variable);
        if (!empty($variable) && $variable != false) {
            $safe = htmlspecialchars($variable, ENT_QUOTES, "UTF-8");
            switch ($option) {
                case 'strict':
                    if (!v::alnum('-')->noWhitespace()->validate($variable)) {
                        $this->errors[] = $safe . " is not valid.";
                        $safe = "";
                    }
                    break;
                case 'int':
                    if(!v::intVal()->validate($variable)) {
                        $this->errors[] = $safe . " is not valid number.";
                        $safe = "";
                    }
                    break;
                case 'alpha':
                    if(!v::alpha()->validate($variable)) {
                        $this->errors[] = $safe . " can only include letters";
                        $safe = "";
                    }
                    break;
                /**
                 * Numbers, Letters, Forward slashes and one . in the middle
                 * True: hello.php, /dir/index2.html
                 * False: ../test.php, /dir/../../etc/passwd
                 * @param $variable
                 */
                case 'file':
                    if(!v::regex('@^[a-zA-Z/]+(\.{1}[a-zA-Z]+)?$@')->validate($variable)) {
                        $this->errors[] = $safe . " is not a valid file.";
                        $safe = "";
                    }
                    break;
            }
        } else {
            $safe = "";
            $this->errors[] = "Missing input";
        }

        return $safe;

    }

    // Takes in unlimited arguments and checks if any is empty
    function emptyCheck() {
        foreach(func_get_args() as $arg)
            if(!empty($arg))
                continue;
            else
                return false;
        return true;
    }

}