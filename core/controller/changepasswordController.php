<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */

/*
|--------------------------------------------------------------------------
| ChangePassword Controller
|--------------------------------------------------------------------------
|
| ChangePassword Controller Class - Called on /ChangePassword
|
*/
class ChangePasswordController extends Controller{
    private $model;

    public function getName() {
        return 'ChangePasswordController'; //In the real world this may well be get_class($this), and this method defined in a parent class.
    }

    public function __construct(UserModel $model) {
        $this->model = $model;
        //$this->model->content = $model->content;
        $this->model->user_id = $_SESSION['id'];
        $this->model->user   = $this->model->userdata($this->model->user_id);
    }
    //put in general

}