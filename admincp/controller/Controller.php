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
| Basic Controller Class Template
|
*/
class Controller {
    public $model;
    public $user_id;
    private $settings;

    public function __construct(UserModel $model) {
        $this->model = $model;
        $this->settings = $model->container['settings'];
    }

    public function success() {
        echo ("success");
    }
}