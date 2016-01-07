<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */

/*
|--------------------------------------------------------------------------
| Logout Controller
|--------------------------------------------------------------------------
|
| Logout Controller Class - Called on /user/logout
|
*/
class LogoutController {
    private $model;

    public function logout() {
      session_destroy();
      header('Location:/');
      exit();
    }

    public function __construct(UserModel $model) {
        $this->model = $model;
        //$this->model->content = $model->content;
        $this->logout();
    }


}
