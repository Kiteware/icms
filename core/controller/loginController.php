<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */
 use Respect\Validation\Validator as v;

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
        $this->login($model);
    }

    public function login($users) {
$username_validator = v::alnum()->noWhitespace();
if (empty($_POST) === false) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    if (empty($username) === true || empty($password) === true) {
        $errors[] = 'Sorry, but we need your username and password.';
    } elseif ($users->user_exists($username) === false) {
        $errors[] = 'Sorry that username doesn\'t exists.';
    } elseif ($username_validator->validate($username) === false) {
        $errors[] = 'Invalid username';
    } elseif ($users->email_confirmed($username) === false) {
        $errors[] = 'Sorry, but you need to activate your account.
					 Please check your email.';
    } else {
        if (strlen($password) > 18) {
            $errors[] = 'The password should be less than 18 characters, without spacing.';
        }
        $login = $users->login($username, $password);
        if ($login === false) {
            $errors[] = 'Sorry, that username/password is incorrect';
        } else {
            session_regenerate_id(true);// destroying the old session id and creating a new one
            $_SESSION['id'] =  $login;
            if (isset($_GET['from'])) {
                header('Location: index.php?page='.$_GET['from']);
            } else {
                header('Location: /');
            }
            exit();
        }
    }
}
    }

}
