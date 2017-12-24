<?php
namespace Nixhatter\ICMS\controller;

/**
 * ChangePassword Controller
 *
 * @package ICMS
 * @author Dillon Aykac
 */

defined('_ICMS') or die;

use Nixhatter\ICMS\model;
use Respect\Validation\Validator as v;

class ChangePasswordController extends Controller
{
    private $user;

    public function getName()
    {
        return 'ChangePasswordController';
    }

    public function __construct(model\UserModel $model)
    {
        $this->model = $model;
        $this->user = $this->model->userdata($_SESSION['id']);
        $this->page = "changepassword";
        $this->changePassword();
    }

    public function changePassword()
    {
        $password_length = v::intVal()->min(6);
        if (isset($_POST['submit'])) {
            $current_password = filter_input(INPUT_POST, 'current_password');
            $password = filter_input(INPUT_POST, 'password');
            $password_again = filter_input(INPUT_POST, 'password_again');

            if (!empty($current_password) || !empty($password) || !empty($password_again)) {
                if ($this->model->compare($this->user['username'], $current_password)) {
                    $errors[] = 'Your current password is incorrect.';
                }
                if ($password != $password_again) {
                    $errors[] = 'Your new passwords do not match';
                }
                if ($password_length->validate(strlen($password)) === false) {
                    $errors[] = 'Your new password must be at least 6 characters';
                }

                $_SESSION['message'] = ['error', implode($errors)];

                if (empty($errors)) {
                    $_SESSION['message'] = ['error', 'Server error while changing passwords'];

                    if ($this->model->change_password($this->user['id'], $password)) {
                        $_SESSION['message'] = ['success', 'Password has been changed'];
                    }
                }
            }
        }
    }
}
