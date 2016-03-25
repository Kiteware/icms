<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */
namespace Nixhatter\ICMS\Admin\Controller;
use Nixhatter\ICMS as ICMS;

if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
}
/*
|--------------------------------------------------------------------------
| Blog Controller
|--------------------------------------------------------------------------
|
| Blog Controller Class - Called on /blog
|
*/
use Respect\Validation\Validator as v;

class BlogController extends Controller{
    public $model;
    public $settings;
    private $errors;

    public function getName() {
        return 'blog';
    }
    public function __construct(ICMS\model\BlogModel $model) {
        $this->model = $model;
        $this->model->posts = $model->get_posts();
        $this->settings = $model->container['settings'];
    }

    public function post($id) {
        if(v::intVal()->notEmpty()->validate($id)) {
            $this->model->posts = $this->model->get_post($id);
        } else {
            $response = array('result' => "fail", 'message' => 'Invalid post ID');
            echo(json_encode($response));
            die();
        }
    }
    public function edit($id) {
        if(isset($id)) {
            if (v::intVal()->notEmpty()->validate($id)) {
                $this->model->action = "edit";
                $this->model->id = $id;
                $this->model->posts = $this->model->get_post($id);
            } else {
                $response = array('result' => "fail", 'message' => 'Invalid post ID');
                echo(json_encode($response));
                die();
            }
        }
    }
    public function delete($id) {
        if(v::intVal()->notEmpty()->validate($id)) {
            if($this->model->delete_posts($id)) {
                $response = array('result' => "success", 'message' => 'Post Deleted');
            } else {
                $response = array('result' => "fail", 'message' => 'Could not delete post');
            }
        } else {
            $response = array('result' => "fail", 'message' => 'Invalid post ID');
        }
        echo(json_encode($response));
        die();
    }
    public function create() {
        $post_name_validator = v::alnum();

        $config = \HTMLPurifier_Config::createDefault();
        $purifier = new \HTMLPurifier($config);
        // check for a submitted form
        if (isset($_POST['submit'])) {
            $postName = $_POST['postName'];
            $postContent = $purifier->purify($_POST['postContent']);

            //Check to make sure fields are filled in
            if (empty($postName) or empty ($postContent)) {
                $response = array('result' => "fail", 'message' => 'Make sure you filled out all the fields!');
            } else {
                if($post_name_validator->validate($postName)) {
                    if($this->model->newBlogPost($postName, $postContent)) {
                        $response = array('result' => "success", 'message' => 'Blog Created!');
                    } else {
                        $response = array('result' => "fail", 'message' => 'Blog post could not be created');
                    }
                } else {
                    $response = array('result' => "fail", 'message' => 'Only alphanumeric values in the post name');
                }
            }
            echo(json_encode($response));
            die();
        }
    }
    public function update($id) {
        $post_name_validator = v::alnum()->notEmpty();

        $config = \HTMLPurifier_Config::createDefault();
        $purifier = new \HTMLPurifier($config);

        if (isset($_POST['postName'])) {
            $post_name = $_POST['postName'];
            if ($post_name_validator->validate($post_name) == false) {
                $this->errors[] = 'Only Alphanumeric Values allowed in the post name ';
            }
        } else {
            $this->errors[] = 'Post Name is Required';
        }
        if (isset($_POST['postContent'])) {
            $post_content = $purifier->purify($_POST['postContent']);
        } else {
            $this->errors[] = 'Post Content is Required';
        }
        if (isset($id)) {
            if (v::intVal()->validate($id) == false) {
                $this->errors[] = 'Post ID must be a valid int.';
            }
        } else {
            $this->errors[] = 'Post ID is Required';
        }

        if (empty($errors) === true) {
            if ($this->model->update_post($post_name, $post_content, $id)) {
                $response = array('result' => "success", 'message' => 'Blog Updated');
            }
        } elseif (empty($errors) === false) {
            $response = array('result' => "fail", 'message' => implode($this->errors));
        }
        echo(json_encode($response));
        die();
    }
}