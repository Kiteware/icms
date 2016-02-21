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
| Register Controller Class - Called on /register
|
*/
class RegisterController extends Controller{

    public function __construct(UserModel $model) {
        if(isset($_SESSION['id'])) {
            header ("Location: /");
        }
        $this->model = $model;
        $this->register();
    }

    public function register() {
        $username_validator = v::alnum()->noWhitespace();
        $password_length = v::intVal()->min(5);
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
                    $errors[] = 'Your password must be at least 6 characters';
                }
                if (v::email()->validate($email) === false) {
                    $errors[] = 'Please enter a valid email address';
                } elseif ($this->model->email_exists($email) === true) {
                    $errors[] = 'That email already exists. Should we <a href="/user/register/resendemail/'.$email.'">resend the email</a>?';
                }
            }
            if (empty($this->errors)) {
                if($this->model->register($username, $password, $email)) {
                    $this->alert("success", 'Registration Complete');
                }
            } else {
                $this->alert("error", implode($errors));
            }
        }
    }
    public function resendemail($email) {
        $username = $this->model->fetch_info("username", "email", $email);
        $this->model->register_mail($email, $username);
    }
    public function activate() {
        if (isset ($_GET['email'], $_GET['code'])) {
            $email = trim($_GET['email']);
            $emailCode = trim($_GET['code']);
            if($this->model->email_exists($email)) {
                if($this->model->activate($email, $emailCode)) {
                    $this->alert("success", 'Registration Complete');
                } else {
                    $this->alert("error", "Incorrect email code");
                }
            } else {
                $this->alert("error", "Email not found.");
            }
        }
    }
}
