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
use Respect\Validation\Validator as v;

class BlogController {
    private $model;
    private $errors;

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
    public function edit($id) {
        $this->model->action = "edit";
        $this->model->id = $id;
        $this->model->posts = $this->model->get_post($id);

    }
    public function delete($id) {
        $this->model->delete_posts($id);
    }
    public function create() {
        $post_name_validator = v::alnum();

        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        // check for a submitted form
        if (isset($_POST['add_post'])) {
            $postName = $_POST['postName'];
            $postContent = $purifier->purify($_POST['html']);

            //Check to make sure fields are filled in
            if (empty($postName) or empty ($postContent)) {
                echo ('Make sure you filled out all the fields!');
            } else {
                if($post_name_validator->validate($postName) == true) {
                    $this->model->newBlogPost($postName, $postContent);
                    echo("<script> successAlert();window.location.href = \"/admin/blog/create\";</script>");
                } else {
                    echo ('Only alphanumeric values in the post name');
                }
            }
        }
    }
    public function update() {

        $post_name_validator = v::alnum();

        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);

        if (isset($_POST['post_name'])) {
            $post_name = $_POST['post_name'];
            if ($post_name_validator->validate($post_name) == false) {
                $this->errors[] = 'Only Alphanumeric Values allowed in the post name ';
            }
        } else {
            $this->errors[] = 'Post Name is Required';
        }
        if (isset($_POST['post_content'])) {
            $post_content = $purifier->purify($_POST['post_content']);
        } else {
            $this->errors[] = 'Post Content is Required';
        }
        if (isset($_POST['post_id'])) {
            $post_id = $_POST['post_id'];
            if (v::int()->validate($post_id) == false) {
                $this->errors[] = 'Post ID must be a valid int.';
            }
        } else {
            $this->errors[] = 'Post ID is Required';
        }

        if (empty($errors) === true) {
            if ($this->model->update_post($post_name, $post_content, $post_id)) {
                echo("<script> successAlert();</script>");
            }
        } elseif (empty($errors) === false) {
            echo '<p>' . implode('</p><p>', $this->errors) . '</p>';
        }
    }
}