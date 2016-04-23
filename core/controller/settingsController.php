<?php
namespace Nixhatter\ICMS\controller;
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

    public function getName() {
        return 'SettingsController';
    }

    public function __construct(\Nixhatter\ICMS\model\UserModel $model) {
        $this->model = $model;
        $this->model->user_id = $_SESSION['id'];
        $this->model->user   = $this->model->userdata($this->model->user_id);
        $this->settings();
    }
    public function settings() {
        if (empty($_POST) === false) {
            $fullname_validator = v::alpha()->notEmpty();
            $username_validator = v::alnum()->notEmpty()->noWhitespace();
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
                    $errors[] = 'Undefined Gender';
                }
            }
            if (isset($_FILES['myfile']) && !empty($_FILES['myfile']['name'])) {
                $name           = $_FILES['myfile']['name'];
                $tmp_name       = $_FILES['myfile']['tmp_name'];
                $a              = explode('.', $name);
                $path           = "avatars";
                if(v::image()->validate($name)) {
                    $errors[] = 'Image file type not allowed';
                }
                if (v::size(null, '5MB')->validate($name)) {
                    $errors[] = 'File size must be under 2mb';
                }

            } else {
                $newpath = $this->user['image_location'];
            }
            if (empty($errors)) {
                if (isset($_FILES['myfile']) && !empty($_FILES['myfile']['name']) && $_POST['use_default'] != 'on') {
                    $newpath = $this->file_newpath($path, $name);
                    move_uploaded_file($tmp_name, $newpath);
                } elseif (isset($_POST['use_default']) && $_POST['use_default'] === 'on') {
                    $newpath = 'images/avatars/default_avatar.png';
                }
                $username       = htmlentities(trim($_POST['username']));
                $full_name      = htmlentities(trim($_POST['full_name']));
                $gender         = htmlentities(trim($_POST['gender']));
                $bio            = htmlentities(trim($_POST['bio']));
                $image_location = htmlentities(trim($newpath));
                $this->model->update_user($username, $full_name, $gender, $bio, $image_location, $this->model->user_id);
                $this->alert("success", "Settings have been saved");
            } elseif (empty($errors) === false) {
                $this->alert("error", implode($errors));
            }
        }
    }
    private function file_newpath($path, $filename)
    {
        if ($pos = strrpos($filename, '.')) {
            $name = substr($filename, 0, $pos);
            $ext = substr($filename, $pos);
        } else {
            $name = $filename;
        }

        $newpath = $path.'/'.$filename;
        $counter = 0;

        while (file_exists($newpath)) {
            $newname = $name .'_'. $counter . $ext;
            $newpath = $path.'/'.$newname;
            $counter++;
        }

        return $newpath;
    }
}
