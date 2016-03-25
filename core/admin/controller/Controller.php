<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */
namespace Nixhatter\ICMS\admin\controller;
/*
|--------------------------------------------------------------------------
| Controller
|--------------------------------------------------------------------------
|
| Basic Controller Class Template
|
*/
class Controller {
    private $model;
    public $user_id;
    private $settings;

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
}