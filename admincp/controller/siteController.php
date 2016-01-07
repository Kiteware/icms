<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */

/*
|--------------------------------------------------------------------------
| Controller
|--------------------------------------------------------------------------
|
| Basic Controller Class - Called on /index.php
|
*/
class siteController {
    private $model;
    public $user_id;

    public function getName() {
        return 'SiteController';
    }

    public function __construct(SiteModel $model) {
        $this->model = $model;
        $this->model->posts = $model->posts;
       // $this->user_id    = $_SESSION['id'];      //put in general

    }

    public function success() {
        echo ("success");
    }

    public function settings() {

    }

    public function template() {

    }
}
