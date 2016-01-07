<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */

/*
|--------------------------------------------------------------------------
| Profile Controller
|--------------------------------------------------------------------------
|
| Profile Controller Class - Called on /Profile
|
*/
class ProfileController extends Controller{
    private $model;

    public function getName() {
        return 'ProfileController';
    }

    public function __construct(UserModel $model) {
        $this->model = $model;
        $this->model->user_id = $_SESSION['id'];
        //$this->model->content = $model->content;
        // ID is an int

        $this->profile();
    }

    public function profile() {
      $this->model->user   = $this->model->userdata($this->model->user_id);
      $username = $this->model->user["username"];
      $user_exists = $this->model->user_exists($username);
    }

    public function view($user_id) {
      $this->model->user  = $this->model->userdata($user_id);
      $username = $this->model->user["username"];
      $user_exists = $this->model->user_exists($username);

      // If the user doesn't exist
      if ($user_exists === false) {
          // redirect to index page. Alternatively you can show a message or 404 error
          header('Location: /');
          die();
        }
    }
}
