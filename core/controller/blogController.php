<?php
namespace Nixhatter\ICMS\controller;

/**
 * Blog Controller
 * Create/Edit/Update websites blogs
 * @package ICMS
 * @author Dillon Aykac
 */

defined('_ICMS') or die;

use Nixhatter\ICMS\model;
use Respect\Validation\Validator as v;

class BlogController extends Controller{

    public $data;
    public $blogPage;
    public $posts;


    public function getName() {
        return 'BlogController';
    }

    public function __construct(model\BlogModel $model) {
        $this->model = $model;
        $this->posts = $this->model->get_published();
        $this->page = "blog";
        $this->blogPage = $this->compilePosts($this->posts);
        $this->view();
    }

    public function view($id = NULL) {
        if (!empty($id)) {
            if (v::intVal()->validate($id)) {
            $this->posts = $this->model->get_post($id);
                if(empty($this->posts)) {
                    $this->alert("error", 'Post does not exist');
                } else {
                    $this->data = (object)[
                        "keywords" => $this->posts[0]['post_title'],
                        "description" => $this->posts[0]['post_description'],
                        ];
                    $this->blogPage = $this->compilePosts($this->posts);
                }
            } else {
                $this->alert("error", 'Invalid post ID');
            }
        } else {
            $this->blogPage = $this->compilePosts($this->posts);
        }

    }

    public function rss() {
        $feed = new \Bhaktaraz\RSSGenerator\Feed();
        $siteURL = $this->model->container["settings"]->production->site->url;
        $channel = new \Bhaktaraz\RSSGenerator\Channel();
        $channel
            ->title($this->model->container["settings"]->production->site->name . " Blog")
            ->description($this->model->container["settings"]->production->site->description)
            ->url("http://".$siteURL)
            ->appendTo($feed);

        foreach ($this->posts as $post) {
            // RSS item
            $item = new \Bhaktaraz\RSSGenerator\Item();
            $item
                ->title($post['post_title'])
                ->description($post['post_description'])
                ->url("http://".$siteURL."/blog/view/" . $post['post_id'])
                ->enclosure('')
                ->appendTo($channel);
        }
        echo $feed;
        exit();
    }
}
