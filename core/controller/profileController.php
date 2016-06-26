<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */
namespace Nixhatter\ICMS\controller;
use Nixhatter\ICMS\model;
/*
|--------------------------------------------------------------------------
| Profile Controller
|--------------------------------------------------------------------------
|
| Profile Controller Class - Called on /Profile
|
*/
class ProfileController extends Controller{

    public $user;

    public function getName() {
        return 'ProfileController';
    }

    public function __construct(model\UserModel $model) {
        $this->model = $model;
        $this->page = "profile";
        $this->profile();
    }

    public function profile() {
        if(isset($this->model->user_id)) {
            $this->user = $this->model->userdata($this->model->user_id);
        }
    }

    /*
    public function view($user_id) {
        if (!empty($user_id)) {
            if($this->model->user_exists($user_id)) {
                $this->model->user  = $this->model->userdata($user_id);
                $username = $this->model->user["username"];

            } else {
                echo "user not found";
                header('Location: /');
                exit();
            }
        } else {
            // No userid is given
        }
    }
    */
}
