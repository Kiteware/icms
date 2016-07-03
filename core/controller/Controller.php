<?php
namespace Nixhatter\ICMS\controller;
/**
 * Controller
 *
 * @package ICMS
 * @author Dillon Aykac
 */

defined('_ICMS') or die;

use Respect\Validation\Validator as v;

class Controller {
    protected $model;
    public $user_id;
    public $page;
    public $data;
    protected $settings;
    protected $errors = [];

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
            $this->user_id = $_SESSION['id'];
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * @param $posts - An array of blog posts
     * @return string - Fully formatted html of the blog posts
     */
    protected function compilePosts($posts) {
        $Parsedown = new \Parsedown();
        $blogArray = "";
        if (count($posts) === 1) {
            // View one blog post
            $content = $Parsedown->text($posts[0]['post_content']);
            $blogArray .= '<h1 class="title"> '.$posts[0]["post_title"].'</h1>
                    <p class="text-muted">'.date('F j, Y', strtotime($posts[0]['post_date'])) .'</p>
                    <p>'.$content.'</p>
                    <hr />
                    <p class="text-mute">Written By ' . $posts[0]['post_author'] . '</p>';
        } elseif (empty($posts)) {
            // No Blog Posts
            $blogArray .= '<p> There are currently no blog posts.</p>';
        } else {
            // Multiple Blog Posts
            foreach ($posts as $post) {
                $content = $Parsedown->text($post['post_content']);
                $blogArray .= '<h1><a href="/blog/view/' . urlencode($post['post_id']) . '">' . htmlspecialchars($post['post_title']) . '</a></h1>
                        <p class="text-muted">' . date('F j, Y', strtotime($post['post_date'])) . '</p>
                        <p>' . $this->model->truncate($content, "<a href=\"/blog/view/" . urlencode($post['post_id']) . "\">Read more</a>") . '</p>
                        <hr />';
            }
        }
        return $blogArray;
    }

    public function inputValidation($variable, $option = null) {
        $variable = trim($variable);
        if (!empty($variable) && $variable != false) {
            $safe = htmlspecialchars($variable, ENT_QUOTES, "UTF-8");
            switch ($option) {
                case 'strict':
                    if (!v::alnum()->noWhitespace()->validate($variable)) {
                        $this->errors[] = $safe . " is not valid.";
                        $safe = "";
                    }
                    break;
                case 'int':
                    if(!v::intVal()->validate($variable)) {
                        $this->errors[] = $safe . " is not valid number.";
                        $safe = "";
                    }
                    break;
                case 'alpha':
                    if(!v::alpha()->validate($variable)) {
                        $this->errors[] = $safe . " can only include letters";
                        $safe = "";
                    }
                    break;
                /**
                 * Numbers, Letters, Forward slashes and one . in the middle
                 * True: hello.php, /dir/index2.html
                 * False: ../test.php, /dir/../../etc/passwd
                 * @param $variable
                 */
                case 'file':
                    if(!v::regex('@^[a-zA-Z/]+(\.{1}[a-zA-Z]+)?$@')->validate($variable)) {
                        $this->errors[] = $safe . " is not a valid file.";
                        $safe = "";
                    }
                    break;
            }
        } else {
            $safe = "";
            // $this->errors[] = "Missing input";
        }

        return $safe;

    }

}