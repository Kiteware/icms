<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */
namespace Nixhatter\ICMS\admin\controller;
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
    public $id;
    public $posts;
    public $settings;
    private $errors;
    private $user;


    public function getName() {
        return 'blog';
    }
    public function __construct(ICMS\model\BlogModel $model) {
        $this->model = $model;
        $this->posts = $model->get_posts();
        $this->settings = $model->container['settings'];
        $this->user     = $model->container['user'];

        $config = \HTMLPurifier_Config::createDefault();
        $this->purifier = new \HTMLPurifier($config);
    }

    /**
     * Retrieve a specific blog post
     * @param $bid - blog id
     */
    public function edit($bid = NULL) {
        if(!empty($bid) && v::intVal()->validate($bid)) {
            $this->id = $bid;
            $this->posts = $this->model->get_post($bid);
        }
    }
    public function delete($bid) {
        if(!empty($bid) && v::intVal()->validate($bid)) {
            if($this->model->delete_posts($bid)) {
                $response = array('result' => "success", 'message' => 'Post Deleted');
            } else {
                $response = array('result' => "fail", 'message' => 'Could not delete post');
            }
        } else {
            $response = array('result' => "fail", 'message' => 'Invalid post ID');
        }
        echo(json_encode($response));
        exit();
    }
    public function create() {
        $post_name_validator = v::alnum();


        // check for a submitted form
        if (isset($_POST['submit'])) {
            //Check to make sure fields are filled in
            if (empty($_POST['postName']) or empty($_POST['postContent'])) {
                $response = array('result' => "fail", 'message' => 'Make sure you filled out all the fields!');
            } else {
                $postTitle = $this->postValidation($_POST['postName']);
                $postContent = $this->purifier->purify($_POST['postContent']);

                $ip = $this->filterIP($_SERVER['REMOTE_ADDR']);
                if($_POST['submit'] == "publish") $published = 1; else $published = 0;

                if($post_name_validator->validate($postTitle)) {
                    $post_desc = !empty($_POST['postDesc']) ? $this->postValidation($_POST['postDesc']) : "";
                    if($this->model->newBlogPost($postTitle, $postContent, $ip, $post_desc, $published, $this->user['full_name'])) {
                        $response = array('result' => "success", 'message' => 'Blog Created!');
                    } else {
                        $response = array('result' => "fail", 'message' => 'Blog post could not be created');
                    }
                } else {
                    $response = array('result' => "fail", 'message' => 'Only alphanumeric values in the post name');
                }
            }
            echo(json_encode($response));
            exit();
        }
    }
    public function update($bid) {
        $post_name_validator = v::alnum()->notEmpty();

        if (!empty($_POST['postName']) && !empty($_POST['postContent']) && !empty($bid)) {
            $post_name = $this->postValidation($_POST['postName']);
            if ($post_name_validator->validate($post_name) == false) {
                $this->errors[] = 'Only Alphanumeric Values allowed in the post name ';
            }
            $post_content = $this->purifier->purify($_POST['postContent']);

            if (v::intVal()->validate($bid) == false) {
                $this->errors[] = 'Post ID must be a valid integer.';
            }
            if (isset($_POST['postDesc']) && $post_name_validator->validate($_POST['postDesc'])) $post_desc = $_POST['postDesc'];

            if (empty($errors) === true) {
                if($_POST['submit'] == "publish") $published = 1; else $published = 0;
                if ($this->model->update_post($post_name, $post_content, $bid, $post_desc, $_SERVER['REMOTE_ADDR'], $published, $this->user['full_name'])) {
                    $response = array('result' => "success", 'message' => 'Blog Updated');
                } else {
                    $response = array('result' => "fail", 'message' => 'Database error while updating blog');
                }
            } elseif (empty($errors) === false) {
                $response = array('result' => "fail", 'message' => implode($this->errors));
            }
        }

        echo(json_encode($response));
        exit();
    }
}