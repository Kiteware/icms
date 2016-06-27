<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */
namespace Nixhatter\ICMS\controller;

/*
|--------------------------------------------------------------------------
| Logout Controller
|--------------------------------------------------------------------------
|
| Logout Controller Class - Called on /user/logout
|
*/
class LogoutController extends Controller{

    public function __construct(\Nixhatter\ICMS\model\UserModel $model) {
        $this->logout();
    }

    public function logout() {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
        session_destroy();
        header('Location:/');
        exit();
    }

}