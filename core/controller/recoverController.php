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

    public function __construct(model\UserModel $model) {
        $this->model = $model;
        $this->page = "recover";
        $this->startRecover();
    }

    public function startRecover() {
        if (!empty($_POST['email'])) {
            $email = $this->postValidation($_POST['email']);
            if (empty($email)) {
                $errors[] = 'Sorry, but we need your email.';
            }  elseif (v::email()->validate($email) === false) {
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
        if(!empty($_GET['email']) && !empty($_GET['recover_code'])) {
            $email = $this->postValidation($_GET['email']);
            $recoverCode = $this->postValidation($_GET['recover_code']);
            if(v::email()->validate($email) && v::alnum('.')->validate($recoverCode)) {
                if ($this->model->endRecover($email, $recoverCode)) {
                    $this->alert("success", "Password has been reset");
                } else {
                    $this->alert("error", "Incorrect email or code");
                }
            }
        } else {
            header("Location: /");
            exit();
        }
    }
}