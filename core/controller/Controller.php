<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */
namespace Nixhatter\ICMS\controller;
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
    protected $model;
    public $user_id;
    public $page;
    protected $settings;
    protected $error = [];

    public function __construct(\Nixhatter\ICMS\model\UserModel $model) {
        $this->model = $model;
        $this->settings = $model->container['settings'];
        $this->page = "";
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
    public function logged_in()
    {
        if (isset($_SESSION['id']) === true) {
            $this->user_id    = $_SESSION['id'];
            return true;
        }
        else {
            return false;
        }
    }

    public function logged_in_protect()
    {
        if ($this->logged_in() === true) {
            //header('Location: /');
            //die();
        }
    }

    public function logged_out_protect()
    {
        if ($this->logged_in() === false) {
            //header('Location: /');
            //die();
        }
    }

    protected function postValidation($variable) {
        // Checks if the post was sent and not blank
        if (isset($variable) && !empty($variable)) {
            //Strip tags
            $variable = strip_tags($variable);
            //Escape basic strings
            $variable = addslashes($variable);
            return $variable;
        } else {
            return 0;
        }
    }
}