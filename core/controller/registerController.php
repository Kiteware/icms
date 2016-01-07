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
| Register Controller
|--------------------------------------------------------------------------
|
| Register Controller Class - Called on /Register
|
*/
class RegisterController extends Controller{
    private $model;

    public function __construct(UserModel $model) {
        $this->model = $model;
        if(isset($_SESSION['id'])) {
            header ("Location: /");
        }
        $this->register();
    }

    public function register() {
    $username_validator = v::alnum()->noWhitespace();
    $password_length = v::alnum()->noWhitespace()->between(6, 18);
    if (isset($_POST['submit'])) {
        if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
            $errors[] = 'All fields are required.';
        } else {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $email = $_POST['email'];
            if ($this->model->user_exists($username) === true) {
                $errors[] = 'That username already exists';
            }
            if ($username_validator->validate($username) === false) {
                $errors[] = 'A username may only contain alphanumeric characters';
            }
            if ($password_length->validate(strlen($password)) === false) {
                $errors[] = 'Your password must be at least 6 characters and at most 18 characters';
            }
            if (v::email()->validate($email) === false) {
                $errors[] = 'Please enter a valid email address';
            } elseif ($this->model->email_exists($email) === true) {
                $errors[] = 'That email already exists.';
            }
        }
        if (empty($errors) === true) {
            $this->model->register($username, $password, $email, $settings->production->site->url, $settings->production->site->name, $settings->production->site->email);
            header('Location: /user/register/success');
            exit();
        }
    }
  }
}
