<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */
namespace Nixhatter\ICMS\controller;
use Nixhatter\ICMS\model;
use \DrewM\MailChimp\MailChimp;

/*
|--------------------------------------------------------------------------
| Controller
|--------------------------------------------------------------------------
|
| Index Controller
|
*/
class homeController extends Controller{
    public $posts;
    public $blogPage;

    public function getName() {
        return 'homeController';
    }

    public function __construct(model\homeModel $model) {
        $this->model = $model;
        $this->model->posts = $model->posts;
        $this->blogPage = $this->compilePosts($this->model->posts);
        $this->settings = $model->container['settings'];
        $this->page = "home";
    }

    // Email address verification
    public function isEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public function subscribe() {
        if (!empty($_POST)) {
            $mailchimp_api_key = $this->settings->production->addons->mailchimpapi; // enter your MailChimp API Key
            $mailchimp_list_id = $this->settings->production->addons->mailchimplistid; // enter your MailChimp List ID

            $subscriber_email = addslashes( trim( $_POST['email'] ) );

            if( !$this->isEmail($subscriber_email) ) {
                $array = array();
                $array['valid'] = 0;
                $array['message'] = 'Insert a valid email address!';
                echo json_encode($array);
                die();
            } else {
                $array = array();

                $MailChimp = new MailChimp($mailchimp_api_key);

                $result = $MailChimp->post("lists/$mailchimp_list_id/members", [
                    'email_address' => $subscriber_email,
                    'status'        => 'pending',
                ]);

                if($result == false) {
                    $array['valid'] = 0;
                    $array['message'] = 'An error occurred! Please try again later.';
                }
                else {
                    $array['valid'] = 1;
                    $array['message'] = 'Thanks for your subscription! We sent you a confirmation email.';
                }

                echo json_encode($array);
                die();
            }

        }
    }
}