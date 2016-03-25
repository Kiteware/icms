<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */
namespace Nixhatter\ICMS\Controller;
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

    public function getName() {
        return 'ChangePasswordController';
    }

    public function __construct(model\UserModel $model) {
        $this->model = $model;
        $this->model->user_id = $_SESSION['id'];
        $this->model->user = $this->model->userdata($this->model->user_id);
        $this->changePassword();
    }

    public function changePassword() {
        $password_length = v::intVal()->min(5);

        if (!empty($_POST)) {
            if (isset($_POST['current_password'])) $current_password = $_POST['current_password'];
            if (isset($_POST['password'])) $password = trim($_POST['password']);
            if (isset($_POST['password_again'])) $password_again = trim($_POST['password_again']);

            if (!isset($current_password) || !isset($password) || !isset($password_again)) {
                $errors[] = 'All fields are required';
            } else {
                if ($this->model->compare($this->model->user['username'], $current_password)) {
                    $errors[] = 'Your current password is incorrect.';
                }
                if ($password != $password_again) {
                    $errors[] = 'Your new passwords do not match';
                }
                if ($password_length->validate(strlen($password)) === false) {
                    $errors[] = 'Your new password must be at least 6 characters';
                }
            }

            if (empty($errors)) {
                if ($this->model->change_password($this->model->user['id'], $password)) {
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