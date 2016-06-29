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
| Login Controller
|--------------------------------------------------------------------------
|
| Login Controller Class - Called on /user/login
|
*/
class LoginController extends Controller{

    public function getName() {
        return 'LoginController';
    }

    public function __construct(model\UserModel $model) {
        if(isset($_SESSION['id'])) {
            header('Location: /');
            exit();
        } else {
            $this->model = $model;
            $this->page = "login";
            // To login from /user/login and not /user/login/login
            $this->login($model);
        }
    }

    public function login($users) {
        if (isset($_POST['login'])) {

            $username = filter_input(INPUT_POST, 'username');
            $password = filter_input(INPUT_POST, 'password');

            echo($username . $password);
            $username = $this->inputValidation($username, 'strict');
            $password = $this->inputValidation($password);
            echo($username . $password);

            if ($users->user_exists($username) === false) {
                $this->errors[] = "Sorry that username doesn't exists.";
            } elseif ($users->email_confirmed($username) === false) {
                $this->errors[] = "Sorry, but you need to activate your account. <br /> Please check your email.";
            }

            if(empty($this->errors)) {
                $login = $users->login($username, $password);
                if ($login) {
                    // destroying the old session id and creating a new one
                    session_regenerate_id(true);
                    $_SESSION['id'] = $login;
                    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                    $_SESSION['remote_ip'] = $_SERVER['REMOTE_ADDR'];
                    header('Location: '.$_SERVER['HTTP_REFERER']);
                    exit();
                } else {
                    $errors[] = 'Sorry, the username or password is incorrect';
                    $this->alert("error", implode($errors));
                }
            }
        }
    }
}
