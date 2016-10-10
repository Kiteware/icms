<?php
namespace Nixhatter\ICMS\controller;

/**
 * Register Controller
 * Called on /register
 *
 * @package ICMS
 * @author Dillon Aykac
 */

defined('_ICMS') or die;

use Nixhatter\ICMS\model;
use Respect\Validation\Validator as v;

class RegisterController extends Controller{

    public function __construct(model\UserModel $model) {
        if(isset($_SESSION['id'])) {
            header("Location: /");
            exit();
        } else {
            $this->model = $model;
            $this->page = 'register';
            $this->register();
        }
    }

    public function register() {
        if (!empty($_POST['submit'])) {

            $username = filter_input(INPUT_POST, 'username');
            $password = filter_input(INPUT_POST, 'password');
            $email    = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

            $username = $this->inputValidation($username, 'strict');
            $email = $this->inputValidation($email);

            if (!empty($username) && $this->model->user_exists($username)) {
                $this->errors[] = 'That username already exists';
            }

            if (!v::intVal()->min(6)->validate(strlen($password))) {
                $this->errors[] = 'Your password must be at least 6 characters';
            }
            if (!$email && !v::email()->validate($email)) {
                $this->errors[] = 'Please enter a valid email address';
            } elseif ($this->model->email_exists($email)) {
                $this->errors[] = 'That email already exists. Should we <a href="/user/register/resendemail/'.$email.'">resend the email</a>?';
            }

            if (empty($this->errors)) {
                if($this->model->register($username, $password, $email)) {
                    $_SESSION['message'] = ['info', 'Check your email to complete registration'];
                } else {
                    $_SESSION['message'] = ['error', 'Server error while registering'];
                }
            } elseif (!empty($this->errors)) {
                $_SESSION['message'] = ['error', implode("<br />", $this->errors)];
            }
        }
    }

    public function resendemail($email) {

        $clean_email = filter_var($email,FILTER_SANITIZE_EMAIL);

        if (filter_var($clean_email,FILTER_VALIDATE_EMAIL)){

            $username = $this->model->fetch_info("username", "email", $clean_email);

            if ($this->model->register_mail($clean_email, $username)) {
                $_SESSION['message'] = ['success', 'Resent email'];
            } else {
                $_SESSION['message'] = ['error', 'Server error while sending email'];
            }

        } else {
            $_SESSION['message'] = ['error', 'Invalid email given'];
        }

    }

    public function activate() {
        $code = filter_input(INPUT_GET, 'code');
        $email= filter_input(INPUT_GET, 'email', FILTER_VALIDATE_EMAIL);

        $code = $this->inputValidation($code);
        $email = $this->inputValidation($email);

        if (empty($this->errors) && $this->model->email_exists($email)) {
            if($this->model->activate($email, $code)) {
                $_SESSION['message'] = ['success', 'Registration Complete'];
            } else {
                $_SESSION['message'] = ['error', 'Incorrect email code'];
            }
        } else {
            $_SESSION['message'] = ['error', 'Invalid email address'];
        }
    }
}
