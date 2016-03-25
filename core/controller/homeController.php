<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */
namespace Nixhatter\ICMS\Controller;
use Nixhatter\ICMS\model;

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

    public function __construct(model\homeModel $model) {
        $this->model = $model;
        $this->model->posts = $model->posts;
    }
}