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
        header('Location: /admin/blog/edit');
        die();
    }
    public function create() {
        $post_name_validator = v::alnum();

        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        // check for a submitted form
        if (isset($_POST['add_post'])) {
            $postName = $_POST['postName'];
            $postContent = $purifier->purify($_POST['postContent']);

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
    public function update($id) {

        $post_name_validator = v::alnum();

        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);

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
            if (v::int()->validate($id) == false) {
                $this->errors[] = 'Post ID must be a valid int.';
            }
        } else {
            $this->errors[] = 'Post ID is Required';
        }

        if (empty($errors) === true) {
            if ($this->model->update_post($post_name, $post_content, $id)) {
                header('Location: /admin/blog/edit');
                echo("<script> successAlert();</script>");
                die();
            }
        } elseif (empty($errors) === false) {
            echo '<p>' . implode('</p><p>', $this->errors) . '</p>';
        }
    }
}