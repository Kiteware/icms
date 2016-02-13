<?php

/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */

/*
|--------------------------------------------------------------------------
| Admin Pages Controller
|--------------------------------------------------------------------------
|
| Admin Pages Controller Class - Called on /admin
|
*/
use Respect\Validation\Validator as v;


class userController {
    public $model;
    public $user_id;

    public function __construct(UserModel $model) {
        $this->model = $model;
    }
    public function getName() {
        return 'user';
    }
    public function setGlobals(\Pimple\Container $globals) {
        $this->settings = $globals['settings'];
        //$this->users = $globals['users'];
    }
    public function permissions($action) {
        if (isset($_POST['userID']) && isset($_POST['pageName'])) {
            $userID = htmlentities($_POST['userID']);
            $pageName = htmlentities($_POST['pageName']);
            if ($action == "create") {
                $this->model->add_permission($userID, $pageName);
            } elseif ($action == "delete") {
                $this->model->delete_permission($userID, $pageName);
            } else {

            }
            header('Location: /admin/user/permissions');
            die();
        }
    }
    public function usergroup($action) {
        $usergroupID = htmlentities($_POST['usergroupID']);
        $pageName = htmlentities($_POST['pageName']);

        if (empty($_POST['usergroupID']) && empty($_POST['pageName'])) {
            $errors[] = 'All fields are required.';
        } else {
            if ($action == "create") {
                $this->model->add_usergroup($usergroupID, $pageName);
            } elseif ($action == "delete") {
                $this->model->delete_usergroup($usergroupID, $pageName);
            }
            header('Location: /admin/user/permissions');
            die();
        }
    }

    public function edit($id) {
        $this->model->action    = "edit";
        $this->model->id        = $id;
        $members                =$this->model->get_users();
        $this->model->memberCount   = count($members);
    }
    public function delete($id) {
        if (isset($id)) {

            //echo confirmation if successful
            if ($this->model->delete_user($id)) {
                $this->model->delete_all_user_permissions($id);
                echo("<script> successAlert();</script>");
            } else {
                echo 'Delete Failed.';
            }
        }
        header('Location: /admin/user/edit');
        die();
    }
    public function update() {
        $username = $_POST['username'];
        $full_name = $_POST['fullName'];
        $gender = $_POST['gender'];
        $bio = $_POST['bio'];
        $image_location = $_POST['imageLocation'];
        $id = $_POST['userID'];

        if ($this->model->update_user($username, $full_name, $gender, $bio, $image_location, $id)) {
            echo("<script> successAlert();</script>");
        }
        header('Location: /admin/user/edit');
        die();
    }

    public function create() {

        $username_validator = v::alnum()->noWhitespace();
        $password_length = v::alnum()->noWhitespace()->between(6, 18);
        if (isset($_POST['submit'])) {

            if (!isset($_POST['username']) || !isset($_POST['email']) || !isset($_POST['password'])) {

                $errors[] = 'All fields are required.';

            } else {

                $username = $_POST['username'];
                $password = $_POST['password'];
                $email = $_POST['email'];

                if ($this->model->user_exists($username) === true) {
                    $errors[] = 'That username already exists';
                }
                if ($username_validator->validate($username) === false) {
                    $errors[] = 'A username may only contain alphanumeric characters';
                }
                if ($password_length->validate(strlen($password)) === false) {
                    $errors[] = 'Your password must be at least 6 characters and at most 18 characters';
                }
            }
            if (empty($errors) === true) {

                $this->model->register($username, $password, $email, $this->settings->production->site->url,
                    $this->settings->production->site->name, $this->settings->production->site->email);

                echo("<script> successAlert();</script>");

            }
            if (empty($errors) === false) {
                echo '<p>' . implode('</p><p>', $errors) . '</p>';
            }
        }



    }

}
