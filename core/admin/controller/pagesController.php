<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */
namespace Nixhatter\ICMS\admin\controller;
use Nixhatter\ICMS as ICMS;
use Respect\Validation\Validator as v;

/*
|--------------------------------------------------------------------------
| Admin Pages Controller
|--------------------------------------------------------------------------
|
| Admin Pages Controller Class - Called on /admin
|
*/
class pagesController extends Controller{
    public $model;
    public $user_id;
    public $id;
    public $pages;
    public $settings;
    public $template;
    private $users;

    public function __construct(ICMS\model\PagesModel $model) {
        $this->model = $model;
        $this->pages = $model->get_pages();
        $this->users = $model->users;
        $this->settings = $model->container['settings'];
        $this->template = $this->settings->production->site->template;

        $newFileName = 'templates/'.$this->template.'/index.php';

        $config = \HTMLPurifier_Config::createDefault();
        $this->purifier = new \HTMLPurifier($config);

        if (!is_writable(dirname($newFileName))) {
            echo '<div class="alert alert-danger" role="alert">' . dirname($newFileName) . ' is not writeable. Check the folder permissions.</div>';
        }
    }

    public function getName() {
        return 'pages';
    }

    public function edit($id) {
        if(!empty($id) && v::intVal()->validate($id)) {
            $this->id = $id;
            $this->pages = $this->model->get_page($id);
        }
    }
    public function delete($id) {
        if(!empty($id) && v::intVal()->validate($id)) {
            if ($this->model->delete_page($id)) {
                $response = array('result' => "success", 'message' => 'Page Deleted');
            } else {
                $response = array('result' => "fail", 'message' => 'Could not delete page');
            }
        } else {
            $response = array('result' => "fail", 'message' => 'Invalid page ID');
        }
        echo(json_encode($response));
        exit();
    }
    public function update() {
        //TODO: Validate pageContent
        if (isset($_POST['submit']) && !empty($_POST['pageURL']) && !empty($_POST['pageContent']) ) {
            $pageUrl = $this->strictValidation($_POST['pageURL']);
            $text = $this->purifier->purify($_POST['pageContent']);
            $keywords = $this->postValidation($_POST['pageKeywords']);
            $description = $this->postValidation($_POST['pageDesc']);
            $this->model->editPageData($pageUrl, "keywords", $keywords);
            $this->model->editPageData($pageUrl, "description", $description);
            if($this->model->edit_page($pageUrl, $this->settings->production->site->cwd, $text)) {
                $response = array('result' => "success", 'message' => 'Page Saved');
            } else {
                $response = array('result' => "error", 'message' => 'Error saving page');;
            }

        }
        echo(json_encode($response));
        exit();
    }

    public function create() {
        if (isset($_POST['submit'])) {
            $response = array('result' => 'fail', 'message' => 'Unable to complete that action.');

            $pageTitle = filter_input(INPUT_POST, 'pageTitle', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $pageURL = filter_input(INPUT_POST, 'pageURL', FILTER_SANITIZE_ENCODED);
            $pagePermission = filter_input(INPUT_POST, 'pagePermission');
            $pagePosition = filter_input(INPUT_POST, 'pagePosition', FILTER_VALIDATE_INT);
            $pageContent = filter_input(INPUT_POST, 'pageContent');
            $pageKeywords = filter_input(INPUT_POST, 'pageKeywords', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $pageDesc = filter_input(INPUT_POST, 'pageDesc', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $pageURL = $this->strictValidation($pageURL);
            $pageTitle = $this->inputValidation($pageTitle);
            //$pagePermission = $this->inputValidation($pagePermission);

            $Parsedown = new \Parsedown();

            $pageContent =  $Parsedown->text($pageContent);
            $pageContent = $this->purifier->purify($pageContent);

            if (!v::alnum(',')->validate($pagePermission)) {
                $pagePermission = "";
                $this->errors[] = "Separate usergroups by commas";
            }

            $pagePosition = $this->inputValidation($pagePosition, 'int');
            if (empty($this->errors)) {
                // Usergroup Permissions
                $userArray = explode(', ', $pagePermission); //split string into array seperated by ', '
                foreach ($userArray as $usergroup) //loop over values
                {
                    $this->users->add_usergroup($usergroup, $pageURL);
                }
                $meta = array(
                    "keywords" => $pageKeywords,
                    "description" => $pageDesc
                );
                // Meta Information
                $this->model->editPageData('templates/'.$this->template.'/'.$pageURL, $meta);

                // Generate the page
                $this->model->generate_page($pageTitle, $pageURL, $pageContent);
                $this->model->create_nav($pageTitle, "/user/" . $pageURL, $pagePosition);
                $response = array('result' => "success", 'message' => 'A new page is born');

            } elseif (!empty($this->errors)) {
                $response = array('result' => "fail", 'message' => implode($this->errors));
            }
            exit(json_encode($response));
        }
    }

    public function menu() {
        /**************************************************************
        DELETE Menu
         ***************************************************************/
        if (!empty($_POST['nav_delete'])) {
            if(v::regex('/^[\w-\/]+$/')->noWhitespace()->validate($_POST['nav_link'])) {
                $url = $this->postValidation($_POST['nav_link']);
                if ($this->model->delete_nav($url)) {
                    $response = array('result' => "success", 'message' => 'Navigation Deleted');
                } else {
                    $response = array('result' => "fail", 'message' => 'Navigation failed to delete. ');
                }
            } else {
                $response = array('result' => "fail", 'message' => 'Invalid URL/Link. ');
            }
            echo(json_encode($response));
            exit();
        }
        /**************************************************************
        Create new Menu
         ***************************************************************/
        if (!empty($_POST['nav_create'])) {
            if(!v::intVal()->between(0, 10)->validate($_POST['nav_position'])) {
                $this->errors[] = 'Position must be between 1 and 10. ';
            }
            if(!v::alnum()->notEmpty()->validate($_POST['nav_name'])) {
                $this->errors[] = 'Invalid name.';
            }
            if(!v::regex('/^[\w-\/]+$/')->noWhitespace()->validate($_POST['nav_link'])) {
                $this->errors[] = 'Invalid link.';
            }
            if (empty($this->errors)) {
                $Name = $this->postValidation($_POST['nav_name']);
                $Link = $this->postValidation($_POST['nav_link']);
                $Position = $this->postValidation($_POST['nav_position']);

                if(!empty($_POST['is_update']) && v::regex('/^[\w-\/]+$/')->noWhitespace()->validate($_POST['is_update'])) {
                    $this->model->delete_nav($this->postValidation($_POST['is_update']));
                }

                if ($this->model->create_nav($Name, $Link, $Position)) {
                    $response = array('result' => "success", 'message' => 'Navigation Created!');
                } else {
                    $response = array('result' => "fail", 'message' => 'Could not create navigation');
                }
            } else {
                $response = array('result' => "fail", 'message' => implode($this->errors));
            }
            echo(json_encode($response));
            exit();
        }
    }
}
