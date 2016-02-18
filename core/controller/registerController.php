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
    public $errors;

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
                $this->errors[] = 'All fields are required.';
            } else {
                $username = $_POST['username'];
                $password = $_POST['password'];
                $email = $_POST['email'];
                if ($this->model->user_exists($username) === true) {
                    $this->errors[] = 'That username already exists';
                }
                if ($username_validator->validate($username) === false) {
                    $this->errors[] = 'A username may only contain alphanumeric characters';
                }
                if ($password_length->validate(strlen($password)) === false) {
                    $this->errors[] = 'Your password must be at least 6 characters';
                }
                if (v::email()->validate($email) === false) {
                    $this->errors[] = 'Please enter a valid email address';
                } elseif ($this->model->email_exists($email) === true) {
                    $this->errors[] = 'That email already exists. Should we <a href="/user/register/resendemail/'.$email.'">resend the email</a>?';
                }
            }
            if (empty($this->errors)) {
                if($this->model->register($username, $password, $email)) {
                    $this->alert("success", 'Registration Complete');
                }
            } else {
                $this->alert("error", implode($this->errors));
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
