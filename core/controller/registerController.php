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
| Register Controller
|--------------------------------------------------------------------------
|
| Register Controller Class - Called on /register
|
*/
class RegisterController extends Controller{

    public function __construct(model\UserModel $model) {
        if(isset($_SESSION['id'])) {
            header("Location: /");
            exit();
        } else {
            $this->model = $model;
            $this->page = "register";
            $this->register();
        }
    }

    public function register() {
        $username_validator = v::alnum()->noWhitespace();
        $password_length = v::intVal()->min(6);
        if (!empty($_POST['submit'])) {
            if (!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['email'])) {

                $username = $this->strictValidation($_POST['username']);
                $password = $this->postValidation($_POST['password']);
                $email = $this->postValidation($_POST['email']);

                if ($this->model->user_exists($username)) {
                    $errors[] = 'That username already exists';
                }
                if (empty($username)) {
                    $errors[] = 'A username may only contain alphanumeric characters';
                }
                if (!$password_length->validate(strlen($password))) {
                    $errors[] = 'Your password must be at least 6 characters';
                }
                if (!v::email()->validate($email)) {
                    $errors[] = 'Please enter a valid email address';
                } elseif ($this->model->email_exists($email)) {
                    $errors[] = 'That email already exists. Should we <a href="/user/register/resendemail/'.$email.'">resend the email</a>?';
                }

                if (empty($this->errors)) {
                    if($this->model->register($username, $password, $email)) {
                        $this->alert("info", "Check your email to complete registration.");
                    } else {
                        $this->alert("error", "Server error while registering.");
                    }
                }
            } else {
                $errors[] = 'All fields are required.';
            }
            if (!empty($this->errors)) {
                $this->alert("error", implode($errors));
            }
        }
    }
    public function resendemail($email) {
        $email = $this->postValidation($email);

            $username = $this->model->fetch_info("username", "email", $email);
            if ($this->model->register_mail($email, $username)) {
                $this->alert("success", "Resent email");
            } else {
                $this->alert("error", "Server error while sending email");
            }
    }
    public function activate() {
        if (!empty ($_GET['email']) && !empty($_GET['code'])) {
            $email = $this->postValidation($_GET['email']);
            $emailCode = $this->postValidation($_GET['code']);
            if(v::email()->validate($email) && $this->model->email_exists($email)) {
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
