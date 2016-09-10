<?php
namespace Nixhatter\ICMS\controller;

/**
 * Logout Controller
 * Called on /user/logout
 *
 * @package ICMS
 * @author Dillon Aykac
 */

defined('_ICMS') or die;

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