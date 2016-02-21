<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */
if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
}
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
    protected $settings;

    public function __construct(UserModel $model) {
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
}