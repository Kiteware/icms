<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */
namespace Nixhatter\ICMS\controller;
use Respect\Validation\Validator as v;

/*
|--------------------------------------------------------------------------
| Controller
|--------------------------------------------------------------------------
|
| Basic Controller Class Template
|
*/
class Controller {
    protected $model;
    public $user_id;
    public $page;
    public $data;
    protected $settings;
    protected $error = [];

    public function __construct(\Nixhatter\ICMS\model\UserModel $model) {
        $this->model = $model;
        $this->settings = $model->container['settings'];
        $this->page = "";
    }

    public function success() {
        echo ("<script>window.onload = function() {
                    successAlert('');
               };</script>");
    }
    public function alert($type, $message) {
        echo("<script>window.onload = function() {
               ".$type."Alert('".$message."');
              };</script>");
    }
    public function logged_in()
    {
        if (isset($_SESSION['id']) === true) {
            $this->user_id    = $_SESSION['id'];
            return true;
        }
        else {
            return false;
        }
    }

    public function logged_in_protect()
    {
        if ($this->logged_in() === true) {
            //header('Location: /');
            //die();
        }
    }

    public function logged_out_protect()
    {
        if ($this->logged_in() === false) {
            //header('Location: /');
            //die();
        }
    }

    protected function postValidation($variable) {
        // Checks if the post was sent and not blank
        if (isset($variable) && !empty($variable)) {
            //Strip tags
            $variable = strip_tags($variable);
            //Escape basic strings
            $variable = addslashes($variable);
            return $variable;
        } else {
            return 0;
        }
    }

    /**
     * @param $posts - An array of blog posts
     * @return string - Fully formatted html of the blog posts
     */
    protected function compilePosts($posts) {
        $Parsedown = new \Parsedown();
        $blogPage = "";
        if (count($posts) === 1 && $posts[0]['post_published'] == 1) {
            // View one blog post
            $content = $Parsedown->text($posts[0]['post_content']);
            $blogPage .= '<h1 class="title"> '.$posts[0]["post_title"].'</h1>
                    <p class="text-muted">'.date('F j, Y', strtotime($posts[0]['post_date'])) .'</p>
                    <p>'.$content.'</p>';
        } elseif (empty($posts)) {
            // No Blog Posts
            $blogPage .= '<p> There are currently no blog posts.</p>';
        } else {
            // Multiple Blog Posts
            foreach ($posts as $post) {
                if($post['post_published'] == 1) {
                    $content = $Parsedown->text($post['post_content']);
                    $blogPage .= '<h1><a href="/blog/view/' . $post['post_id'] . '">' . $post['post_title'] . '</a></h1>
                        <p class="text-muted">' . date('F j, Y', strtotime($post['post_date'])) . '</p>
                        <p>' . $this->model->truncate($content, "<a href=\"/blog/view/" . $post['post_id'] . "\">Read more</a>") . '</p>
<hr />';
                }
            }
        }
        return $blogPage;
    }
}