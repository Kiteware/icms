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
class BlogController {
    private $model;

    public function getName() {
        return 'BlogController'; //In the real world this may well be get_class($this), and this method defined in a parent class.
    }

    public function __construct(BlogModel $model) {
        $this->model = $model;
        $this->model->posts = $model->get_posts();
    }

    public function post($id) {
        $this->model->posts = $this->model->get_post($id);
    }
}