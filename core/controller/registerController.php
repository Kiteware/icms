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
    }

    public function signup() {
        if(isset($_SESSION['id'])) {
            header ("Location: /");
        }
        $username_validator = v::alnum()->noWhitespace();
        $password_length = v::alnum()->noWhitespace()->between(6, 18);
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
                    $this->errors[] = 'Your password must be at least 6 characters and at most 18 characters';
                }
                if (v::email()->validate($email) === false) {
                    $this->errors[] = 'Please enter a valid email address';
                } elseif ($this->model->email_exists($email) === true) {
                    $this->errors[] = 'That email already exists.';
                }
            }
            if (empty($this->errors) === true) {
                if($this->model->register($username, $password, $email)) {
                    header('Location: /user/register/success');
                    die();
                }
            } else {
            echo '<p>' . implode('</p><p>', $this->errors) . '</p>';
            }
        }
    }
    public function activate() {
        if (isset ($_GET['email'], $_GET['code'])) {
            $email = trim($_GET['email']);
            $email_code = trim($_GET['code']);
            if($this->model->email_exists($email)) {
                if($this->model->activate($email, $email_code)) {
                    header('Location: /success');
                    die();
                } else {
                    echo("error");
                }
            } else {
                echo("error");
            }
        }
    }
}
