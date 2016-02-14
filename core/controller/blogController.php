<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */

/*
|--------------------------------------------------------------------------
| Blog Controller
|--------------------------------------------------------------------------
|
| Blog Controller Class - Called on /blog
|
*/
class BlogController extends Controller{
    private $model;

    public function getName() {
        return 'BlogController';
    }

    public function __construct(BlogModel $model) {
        $this->model = $model;
        $this->model->posts = $this->model->get_posts();
    }

    public function post($id) {
        $this->model->posts = $this->model->get_post($id);
    }
    public function view($id) {
    if ($id) {
        $this->model->posts = $this->model->get_post($id);
    } else {
        $this->model->posts = $this->model->get_posts();
    }
  }
}
