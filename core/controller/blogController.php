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

    public function rss() {
        $Parsedown = new \Parsedown();
        $feed = new \Bhaktaraz\RSSGenerator\Feed();
        $siteURL = $this->model->container["settings"]->production->site->url;
        $channel = new \Bhaktaraz\RSSGenerator\Channel();
        $channel
            ->title($this->model->container["settings"]->production->site->name . " Blog")
            ->description($this->model->container["settings"]->production->site->description)
            ->url("http://".$siteURL)
            ->appendTo($feed);

        $posts = $this->model->get_posts();
        foreach ($posts as $post) {
            $content = $Parsedown->text($post['post_content']);
            // RSS item
            $item = new \Bhaktaraz\RSSGenerator\Item();
            $item
                ->title($post['post_title'])
                ->description($this->model->posts[0]['post_description'])
                ->url("http://".$siteURL."/blog/view/" . $post['post_id'])
                ->enclosure('')
                ->appendTo($channel);
        }
        echo $feed;
        die();
    }
}
