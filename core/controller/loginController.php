<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */

/*
|--------------------------------------------------------------------------
| Login Controller
|--------------------------------------------------------------------------
|
| Login Controller Class - Called on /user/login
|
*/
class LoginController {
    private $model;

    public function getName() {
        return 'LoginController'; //In the real world this may well be get_class($this), and this method defined in a parent class.
    }

    public function __construct(UserModel $model) {
        $this->model = $model;
        //$this->model->content = $model->content;
    }


}