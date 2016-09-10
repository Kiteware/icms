<?php
namespace Nixhatter\ICMS\controller;

/**
 * Recover Controller
 * Called on /Recover
 *
 * @package ICMS
 * @author Dillon Aykac
 */

defined('_ICMS') or die;

use Nixhatter\ICMS\model;
use Respect\Validation\Validator as v;

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
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        if (!empty($email)) {
            if ($this->model->email_exists($email) === false) {
                $errors[] = 'Sorry that email doesn\'t exists.';
            } elseif ($this->model->email_confirmed($email) === false) {
                $errors[] = 'Sorry, but you need to activate your account.
      					 Please check your email.';
            }

            if (empty($errors)) {
                $_SESSION['message'] = ['error', 'Recovery email could not be sent'];

                if ($this->model->start_recover($email)) {
                    $_SESSION['message'] = ['success', 'Recovery email sent'];
                }
            } else {
                $_SESSION['message'] = ['error', 'Invalid email'];
            }
        }
    }

    public function endRecover() {
        $email = filter_input(INPUT_GET, 'email', FILTER_VALIDATE_EMAIL);
        $recoverCode = filter_input(INPUT_GET, 'recover_code', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $_SESSION['message'] = ['error', 'Incorrect email or code'];

        if(!empty($email) && !empty($recoverCode)) {
            if(v::alnum('.')->validate($recoverCode)) {
                if ($this->model->endRecover($email, $recoverCode)) {
                    $_SESSION['message'] = ['success', 'Password has been reset'];
                }
            }
        }

        header("Location: /");
        exit();

    }
}