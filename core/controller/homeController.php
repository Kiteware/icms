<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */
namespace Nixhatter\ICMS\controller;
use Nixhatter\ICMS\Model;

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

    public function __construct(Model\homeModel $model) {
        $this->model = $model;
        $this->model->posts = $model->posts;
    }
}