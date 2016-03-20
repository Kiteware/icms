<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */
namespace Nixhatter\ICMS\controller;
use Nixhatter\ICMS\Model;
use Respect\Validation\Validator as v;

/*
|--------------------------------------------------------------------------
| Recover Controller
|--------------------------------------------------------------------------
|
| Recover Controller Class - Called on /Recover
|
*/
class RecoverController extends Controller{

    public function getName() {
        return 'RecoverController';
    }

    public function __construct(Model\UserModel $model) {
        $this->model = $model;
        $this->startRecover();
    }

    public function startRecover() {
        $email_validator = v::alnum()->noWhitespace();
        if (!empty($_POST)) {
            $email = trim($_POST['email']);
            if (empty($email)) {
                $errors[] = 'Sorry, but we need your email.';
            }  elseif ($email_validator->validate($email) === false) {
                $errors[] = 'Invalid email';
            } elseif ($this->model->email_exists($email) === false) {
                $errors[] = 'Sorry that email doesn\'t exists.';
            } elseif ($this->model->email_confirmed($email) === false) {
                $errors[] = 'Sorry, but you need to activate your account.
      					 Please check your email.';
            }
            if (empty($errors)) {
                if($this->model->start_recover($email)) {
                    $this->alert("success", "Recovery email sent.");
                } else {
                    $this->alert("error", "Recovery email could not be sent.");
                }
            } else {
                $this->alert("error", implode($this->errors));
            }
        }
    }

    public function endRecover() {
        if(v::email()->validate($_GET['email']) && v::alnum('.')->validate($_GET['recover_code'])) {
            $email = $_GET['email'];
            $recoverCode = $_GET['recover_code'];
            if ($this->model->endRecover($email, $recoverCode)) {
                $this->alert("success", "Password has been reset");
            } else {
                $this->alert("error", "Incorrect email or code");
            }
        } else {
            header("Location: /");
            die();
        }
    }
}