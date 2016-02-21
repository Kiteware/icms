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
| ChangePassword Controller
|--------------------------------------------------------------------------
|
| ChangePassword Controller Class - Called on /ChangePassword
|
*/
class ChangePasswordController extends Controller
{

    public function getName()
    {
        return 'ChangePasswordController';
    }

    public function __construct(UserModel $model)
    {
        $this->model = $model;
        $this->model->user_id = $_SESSION['id'];
        $this->model->user = $this->model->userdata($this->model->user_id);
    }

    public function changePassword()
    {
        $password_length = v::intVal()->min(5);

        if (!empty($_POST)) {
            if (isset($_POST['current_password'])) $current_password = $_POST['current_password'];
            if (isset($_POST['password'])) $password = trim($_POST['password']);
            if (isset($_POST['password_again'])) $password_again = trim($_POST['password_again']);

            if (!isset($current_password) || !isset($password) || !isset($password_again)) {
                $errors[] = 'All fields are required';
            } elseif ($this->model->compare($this->model->user['username'], $current_password) === true) {
                if ($password != $password_again) {
                    $errors[] = 'Your passwords do not match';
                } elseif ($password_length->validate(strlen($password)) === false) {
                    $errors[] = 'Your new password must be at least 6 characters';
                }
            } else {
                $errors[] = 'Your current password is incorrect.';
            }
        }
            if (empty($_POST) === false && empty($errors) === true) {
                $this->model->change_password($this->model->user['id'], $password);
                $this->alert("success", "Password has been changed");
            } elseif (empty ($errors) === false) {
                $this->alert("error", implode($this->errors));
            }

    }
}