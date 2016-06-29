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
| contactController
|--------------------------------------------------------------------------
|
| Contact Us Controller
|
*/
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

                $fullName = $this->postValidation($_POST['full_name']);
                $email = $this->postValidation($_POST['email']);
                $comment = $this->postValidation($_POST['comment']);
                $phone = $this->postValidation($_POST['phone']);
                $website = $this->postValidation($_POST['website']);

                if ($username_validator->validate($fullName) === false) {
                    $errors[] = 'A name may only contain alphanumeric characters';
                }
                if ($comment_length->validate(strlen($comment)) === false) {
                    $errors[] = 'A question must be at least 6 characters';
                }
                if (v::email()->validate($email) === false) {
                    $errors[] = 'Please enter a valid email address';
                }
            } else {
                $errors[] = 'a * represents a mandatory field';
            }
            if (empty($this->errors)) {
                $content = "From: ".$email.", phone #: ". $phone .", website: ". $website ." and question: " . $comment;
                if($this->model->mail($this->settings->production->site->email, $fullName, "Contact Form", $content)) {
                    $this->alert("success", "Email sent, we will get back to you shortly.");
                } else {
                    $this->alert("error", "Server error when sending email, please try again.");
                }
            } else {
                $this->alert("error", implode($errors));
            }
        }
    }
}