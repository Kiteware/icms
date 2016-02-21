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
| Index Controller
|
*/
class homeController extends Controller{
    public $posts;

    public function getName() {
        return 'homeController';
    }

    public function __construct(homeModel $model) {
        $this->model = $model;
        $this->model->posts = $model->posts;
    }
}