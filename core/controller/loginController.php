<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */
namespace Nixhatter\ICMS\controller;
use Nixhatter\ICMS\model;
use Respect\Validation\Validator as v;

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
        $username_validator = v::alnum()->noWhitespace();
        if (!empty($_POST)) {
            $username = $this->postValidation($_POST['username']);
            $password = $this->postValidation($_POST['password']);
            if (empty($username)|| empty($password)) {
                $errors[] = 'Sorry, but we need your username and password.';
            }  elseif ($username_validator->validate($username) === false) {
                $errors[] = 'Invalid username';
            } elseif ($users->user_exists($username) === false) {
                $errors[] = 'Sorry that username doesn\'t exists.';
            } elseif ($users->email_confirmed($username) === false) {
                $errors[] = 'Sorry, but you need to activate your account.
      					 Please check your email.';
            } else {
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
                    $errors[] = 'Sorry, that username/password is incorrect';
                    $this->alert("error", implode($errors));
                }
            }
        }
    }
}
