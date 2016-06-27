<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */
namespace Nixhatter\ICMS\admin\controller;
use Respect\Validation\Validator as v;

/*
|--------------------------------------------------------------------------
| Controller
|--------------------------------------------------------------------------
|
| Basic Controller Class Template
|
*/
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
        $variable = htmlspecialchars($variable);
        if (get_magic_quotes_gpc()) {
            //Escape basic strings
            $variable = addslashes($variable);
        }
        return $variable;

    }

    protected function strictValidation($variable) {
        $variable = strip_tags($variable);
        if (v::alnum()->noWhitespace()->validate($variable)) {
            return $variable;
        } else {
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
}