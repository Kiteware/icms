<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */
namespace Nixhatter\ICMS\admin\controller;
use Nixhatter\ICMS\model;

if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
}
/*
|--------------------------------------------------------------------------
| Admin Pages Controller
|--------------------------------------------------------------------------
|
| Admin Pages Controller Class - Called on /admin
|
*/
use Respect\Validation\Validator as v;


class userController extends Controller{
    public $model;
    public $user;
    public $membercount;
    public $members;
    private $settings;
    public $id;

    public function __construct(model\UserModel $model) {
        $this->model = $model;
        $this->settings = $model->container['settings'];
    }
    public function getName() {
        return 'user';
    }

    public function permissions($action) {
        if (!empty($_POST['userID']) && !empty($_POST['pageName'])) {

            $userID = $this->strictValidation($_POST['userID']);
            $pageName = $this->strictValidation($_POST['pageName']);

            if ($action == "create") {
                $this->model->add_permission($userID, $pageName);
            } elseif ($action == "delete") {
                $this->model->delete_permission($userID, $pageName);
            } else {
                $errors[] = 'Invalid Action';
            }
            if (empty($errors)) {
                $response = array('result' => 'success', 'message' => '');
            } else {
                $response = array('result' => 'fail', 'message' => implode($errors));
            }
            echo(json_encode($response));
            die();
        }
    }
    public function usergroup($action) {

        if (!empty($_POST['usergroupID']) && !empty($_POST['pageName'])) {

            $usergroupID = $this->strictValidation($_POST['usergroupID']);
            $pageName = $this->strictValidation($_POST['pageName']);

            if ($action == "create") {
                $this->model->add_usergroup($usergroupID, $pageName);
            } elseif ($action == "delete") {
                $this->model->delete_usergroup($usergroupID, $pageName);
            } else {
                $errors[] = 'Invalid Action';
            }
        } else {
            $errors[] = 'All fields are required.';
        }

        if (empty($errors)) {
            $response = array('result' => 'success', 'message' => '');
        } else {
            $response = array('result' => 'fail', 'message' => implode($errors));
        }
        echo(json_encode($response));
        die();
    }

    public function edit($id) {
        if (!empty($id) && v::intVal()->validate($id)) {
            $this->id = $id;
            $this->user = $this->model->userdata($id);
        } else {
            $this->members = $this->model->get_users();
            $this->memberCount   = count($this->members);
        }
    }
    public function delete($id) {
        if (!empty($id) && v::intVal()->validate($id)) {
            //echo confirmation if successful
            if ($this->model->delete_user($id)) {
                $this->model->delete_all_user_permissions($id);
                $response = array('result' => 'success', 'message' => 'User Deleted');
            } else {
                $response = array('result' => 'fail', 'message' => 'Failed to Delete User');
            }
        }
        echo(json_encode($response));
        die();
    }
    public function update() {
        if (!empty($_POST['username']) && !empty($_POST['fullName']) && !empty($_POST['gender'])
            && !empty($_POST['bio']) && !empty($_POST['imageLocation']) && !empty($_POST['userID'])
            && !empty($_POST['usergroup'])) {
            $username = $this->strictValidation($_POST['username']);
            $full_name = $this->postValidation($_POST['fullName']);
            $gender = $this->strictValidation($_POST['gender']);
            $bio = $this->postValidation($_POST['bio']);
            $image_location = $this->fileValidation($_POST['imageLocation']);
            $id = $this->strictValidation($_POST['userID']);
            $usergroup = $this->strictValidation($_POST['usergroup']);

            if ($this->model->update_user($username, $full_name, $gender, $bio, $image_location, $id, $usergroup)) {
                $response = array('result' => 'success', 'message' => 'Updated User');
            } else {
                $response = array('result' => 'fail', 'message' => 'Failed to Update User');
            }
        } else {
            $response = array('result' => 'fail', 'message' => 'Missing inputs');
        }
        echo(json_encode($response));
        die();
    }

    public function create() {

        $username_validator = v::alnum()->noWhitespace();
        $password_length = v::intVal()->min(6);
        if (isset($_POST['submit'])) {

            if (!empty($_POST['username']) || !empty($_POST['email']) || !empty($_POST['password'])) {

                $username = $this->strictValidation($_POST['username']);
                $password = $this->postValidation($_POST['password']);
                $email = $this->postValidation($_POST['email']);

                if ($this->model->user_exists($username) === true) {
                    $errors[] = 'That username already exists';
                }
                if ($username_validator->validate($username) === false) {
                    $errors[] = 'A username may only contain alphanumeric characters';
                }
                if ($password_length->validate(strlen($password)) === false) {
                    $errors[] = 'Your password must be at least 6 characters and at most 18 characters';
                }
                if (empty($errors)) {
                    $this->model->register($username, $password, $email, $this->settings->production->site->url,
                        $this->settings->production->site->name, $this->settings->production->site->email);
                    $response = array('result' => 'success', 'message' => 'User Created!');
                } else {
                    $response = array('result' => 'fail', 'message' => implode($errors));
                }
            } else {
                $response = array('result' => 'fail', 'message' => 'Please enter in all the fields');
            }

            echo(json_encode($response));
            die();
        }

    }

}
