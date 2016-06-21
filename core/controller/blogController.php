<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 *
 * Blog Controller
 * Create/Edit/Update websites blogs
 */
namespace Nixhatter\ICMS\controller;
use Nixhatter\ICMS\model;
use Respect\Validation\Validator as v;

/*
|--------------------------------------------------------------------------
| Blog Controller
|--------------------------------------------------------------------------
|
| Blog Controller Class - Called on /blog
|
*/
class BlogController extends Controller{

    public $data;

    public function getName() {
        return 'BlogController';
    }

    public function __construct(model\BlogModel $model) {
        $this->model = $model;
        $this->model->posts = $this->model->get_posts();
        $this->page = "blog";
    }

    public function view($id) {
        if ($id) {
            if (v::intVal()->notEmpty()->validate($id)) {
            $this->model->posts = $this->model->get_post($id);
                if(empty($this->model->posts)) {
                    $this->alert("error", 'Post does not exist');
                } else {
                    $this->data = (object)[
                        "keywords" => $this->model->posts[0]['post_title'],
                        "description" => $this->model->posts[0]['post_description'],
                        ];
                }
            } else {
                $this->alert("error", 'Invalid post ID');
            }
        } else {
            $this->model->posts = $this->model->get_posts();
        }
    }
}
