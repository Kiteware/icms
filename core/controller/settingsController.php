<?php
use Respect\Validation\Validator as v;
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */

/*
|--------------------------------------------------------------------------
| Settings Controller
|--------------------------------------------------------------------------
|
| Settings Controller Class - Called on /Settings
|
*/
class SettingsController extends Controller{
    private $model;

    public function getName() {
        return 'SettingsController';
    }

    public function __construct(UserModel $model) {
        $this->model = $model;
        //$this->model->content = $model->content;
        $this->model->user_id = $_SESSION['id'];
        $this->model->user   = $this->model->userdata($this->model->user_id);
        $this->settings();
    }
    public function settings() {
    if (empty($_POST) === false) {
    $fullname_validator = v::alpha()->notEmpty()->noWhitespace()->length(3,25);
    $username_validator = v::alnum()->notEmpty()->noWhitespace()->length(3,25);
    if (isset($_POST['username'])) {
        if ($username_validator->validate($_POST['username']) === false) {
            $errors[] = 'Username can only contain letters and must be under 25 characters! ';
        }
    }
    if (isset($_POST['full_name'])) {
        if ($fullname_validator->validate($_POST['full_name']) === false) {
            $errors[] = 'Please enter your Full Name with only letters!';
        }
    }
    if (isset($_POST['gender']) && !empty($_POST['gender'])) {
        $allowed_gender = array('undisclosed', 'Male', 'Female');
        if (in_array($_POST['gender'], $allowed_gender) === false) {
            $errors[] = 'Please choose a Gender from the list';
        }
    }
    if (isset($_FILES['myfile']) && !empty($_FILES['myfile']['name'])) {
        $name           = $_FILES['myfile']['name'];
        $tmp_name       = $_FILES['myfile']['tmp_name'];
        $allowed_ext    = array('jpg', 'jpeg', 'png', 'gif' );
        $a              = explode('.', $name);
        $file_ext       = strtolower(end($a)); unset($a);
        $file_size      = $_FILES['myfile']['size'];
        $path           = "avatars";

        if (in_array($file_ext, $allowed_ext) === false) {
            $errors[] = 'Image file type not allowed';
        }
        if ($file_size > 2097152) {
            $errors[] = 'File size must be under 2mb';
        }
    } else {
        $newpath = $user['image_location'];
    }
    if (empty($errors) === true) {
        if (isset($_FILES['myfile']) && !empty($_FILES['myfile']['name']) && $_POST['use_default'] != 'on') {
            $newpath = $general->file_newpath($path, $name);
            move_uploaded_file($tmp_name, $newpath);
        } elseif (isset($_POST['use_default']) && $_POST['use_default'] === 'on') {
            $newpath = 'images/avatars/default_avatar.png';
        }
        $username    = htmlentities(trim($_POST['username']));
        $full_name        = htmlentities(trim($_POST['full_name']));
        $gender        = htmlentities(trim($_POST['gender']));
        $bio            = htmlentities(trim($_POST['bio']));
        $image_location    = htmlentities(trim($newpath));
        $this->model->update_user($username, $full_name, $gender, $bio, $image_location, $this->model->user_id);
        header('Location: /user/settings/success');
        exit();
    } elseif (empty($errors) === false) {
        echo '<p>' . implode('</p><p>', $errors) . '</p>';
    }
  }
  }
}
