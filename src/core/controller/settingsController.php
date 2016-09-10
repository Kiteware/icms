<?php
namespace Nixhatter\ICMS\controller;

/**
 * Settings Controller
 * Called on /Settings
 * @package ICMS
 * @author Dillon Aykac
 */

defined('_ICMS') or die;

use Respect\Validation\Validator as v;

class SettingsController extends Controller{

    public $user;

    public function getName() {
        return 'SettingsController';
    }

    public function __construct(\Nixhatter\ICMS\model\UserModel $model) {
        $this->model = $model;
        $this->user  = $this->model->userdata($this->model->user_id);
        $this->page = "settings";
        $this->settings();
    }
    /*
     * TODO: Refactor this
     */
    public function settings() {
        if (!empty($_POST['submit'])) {

            $username = filter_input(INPUT_POST, 'username');
            $full_name = filter_input(INPUT_POST, 'full_name');
            $useDefault = filter_input(INPUT_POST, 'use_default');

            $username = $this->inputValidation($username, 'strict');
            $full_name = $this->inputValidation($full_name, 'alpha');


            if (!empty($_POST['gender'])) {
                $allowed_gender = array('undisclosed', 'Male', 'Female');
                if (in_array($_POST['gender'], $allowed_gender) === false) {
                    $errors[] = 'Undefined Gender';
                } else {
                    $gender = $_POST['gender'];
                }
            }
            if (isset($_FILES['myfile']) && !empty($_FILES['myfile']['name'])) {
                $name           = $_FILES['myfile']['name'];
                $tmp_name       = $_FILES['myfile']['tmp_name'];

                if(v::extension('png','jpg','jpeg')->validate($name)) {
                    $errors[] = 'Image file type not allowed';
                }
                if (!v::size(null, '5MB')->validate($tmp_name)) {
                    $errors[] = 'File size must be under 5mb';
                }
            } else {
                $newpath = $this->user['image_location'];
            }
            //TODO - Refactor
            if (empty($errors)) {
                if (isset($_FILES['myfile']) && !empty($name) && $useDefault != 'on') {
                    $newpath = $this->file_newpath('images/avatars', $name);
                    move_uploaded_file($tmp_name, $newpath);
                } elseif ($useDefault === 'on') {
                    $newpath = 'images/avatars/default_avatar.png';
                }
                $bio            = htmlspecialchars(filter_input(INPUT_POST, 'bio'), ENT_QUOTES, "UTF-8");
                $image_location = $newpath;

                $this->model->update_user($username, $full_name, $gender, $bio, $image_location, $this->user['id'], $this->user['usergroup']);
                $_SESSION['message'] = ['success', 'Settings have been saved'];

            } elseif (!empty($errors)) {
                $_SESSION['message'] = ['error', implode($errors)];
            }
        }
    }

    private function file_newpath($path, $filename) {
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
