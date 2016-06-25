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
| ChangePassword Controller
|--------------------------------------------------------------------------
|
| ChangePassword Controller Class - Called on /ChangePassword
|
*/
class ChangePasswordController extends Controller {

    private $user;

    public function getName() {
        return 'ChangePasswordController';
    }

    public function __construct(model\UserModel $model) {
        $this->model = $model;
        $this->user = $this->model->userdata($_SESSION['id']);
        $this->page = "changepassword";
        $this->changePassword();
    }

    public function changePassword() {
        $password_length = v::intVal()->min(6);
        if (isset($_POST['submit'])) {
            if (!empty($_POST['current_password']) || !empty($_POST['password']) || !empty($_POST['password_again'])) {
                $current_password = $this->postValidation($_POST['current_password']);
                $password = $this->postValidation($_POST['password']);
                $password_again = $this->postValidation($_POST['password_again']);

                if ($this->model->compare($this->user['username'], $current_password)) {
                    $errors[] = 'Your current password is incorrect.';
                }
                if ($password != $password_again) {
                    $errors[] = 'Your new passwords do not match';
                }
                if ($password_length->validate(strlen($password)) === false) {
                    $errors[] = 'Your new password must be at least 6 characters';
                }
                if (empty($errors)) {
                    if ($this->model->change_password($this->user['id'], $password)) {
                        $this->alert("success", "Password has been changed");
                    } else {
                        $this->alert("error", "Server error while changing passwords");
                    }
                } else {
                    $this->alert("error", implode($errors));
                }
            }
        }
    }
}