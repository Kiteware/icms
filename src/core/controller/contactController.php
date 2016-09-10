<?php
namespace Nixhatter\ICMS\controller;

/**
 * Contact Us Controller
 *
 * @package ICMS
 * @author Dillon Aykac
 */

defined('_ICMS') or die;

use Nixhatter\ICMS\model;
use Respect\Validation\Validator as v;

class contactController extends Controller{
    public $posts;

    public function getName() {
        return 'contactController';
    }

    public function __construct(model\userModel $model) {
        $this->model = $model;
        $this->settings = $model->container['settings'];
        $this->page = "contact";
        $this->contact();
    }

    public function contact() {
        $username_validator = v::alnum()->noWhitespace();
        $comment_length = v::intVal()->min(6);
        if (isset($_POST['submit'])) {
            if (!empty($_POST['full_name']) && !empty($_POST['email']) && !empty($_POST['comment'])
                && !empty($_POST['phone']) && !empty($_POST['website'])) {

                $fullName = $this->inputValidation($_POST['full_name']);
                $email    = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
                $comment = $this->inputValidation($_POST['comment']);
                $phone = $this->inputValidation($_POST['phone']);
                $website = $this->inputValidation($_POST['website']);

                if ($username_validator->validate($fullName) === false) {
                    $errors[] = 'A name may only contain alphanumeric characters';
                }
                if ($comment_length->validate(strlen($comment)) === false) {
                    $errors[] = 'A question must be at least 6 characters';
                }
                if (!$email) {
                    $errors[] = 'Please enter a valid email address';
                }
            } else {
                $errors[] = 'a * represents a mandatory field';
            }
            if (empty($this->errors)) {
                $content = "From: ".$email.", phone #: ". $phone .", website: ". $website ." and question: " . $comment;
                if($this->model->mail($this->settings->production->site->email, $fullName, "Contact Form", $content)) {
                    $_SESSION['message'] = ['success', 'Email sent, we will get back to you shortly.'];
                } else {
                    $_SESSION['message'] = ['error', 'Server error when sending email, please try again.'];
                }
            } else {
                $_SESSION['message'] = ['error',  implode($errors)];
            }
        }
    }
}