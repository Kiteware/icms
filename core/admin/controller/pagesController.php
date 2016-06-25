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
    protected $settings;
    private $users;

    public function __construct(ICMS\model\PagesModel $model) {
        $this->model = $model;
        $this->pages = $model->get_pages();
        $this->users = $model->users;
        $this->settings = $model->container['settings'];

        $newFileName = 'templates/'.$this->settings->production->site->template.'/index.php';

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
        die();
    }
    public function update() {
        //TODO: Validate pageContent
        if (isset($_POST['submit']) && !empty($_POST['pageURL']) && !empty($_POST['pageContent']) ) {
            $pageUrl = $this->strictValidation($_POST['pageURL']);
            //$text = htmlspecialchars($_POST['pageContent']);
            $text = $_POST['pageContent'];
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
        die();
    }

    public function create() {
        if (isset($_POST['submit'])) {
            $config = \HTMLPurifier_Config::createDefault();
            $purifier = new \HTMLPurifier($config);

            if (!empty($_POST['pageTitle']) || !empty($_POST['pageURL']) || !empty($_POST['pageContent'])
                || !empty($_POST['pagePermission']) || !empty($_POST['pagePosition']) ) {

                if (v::alnum()->notEmpty()->validate($_POST['pageTitle'])) {
                    $title = $_POST['pageTitle'];
                } else {
                    $errors[] = 'Invalid title.';
                }
                $url = $this->strictValidation($_POST['pageURL']);

                $pageContent = $purifier->purify($_POST['pageContent']);

                if (v::alnum(',')->validate($_POST['pagePermission'])) {
                    $permission = $_POST['pagePermission'];
                } else {
                    $errors[] = 'Permissions must be an integer from 1 - X';
                }
                if (v::intVal()->validate($_POST['pagePosition'])) {
                    $position = $_POST['pagePosition'];
                } else {
                    $errors[] = 'invalid page position';
                }
            } else {
                $errors[] = 'All fields are required.';
            }
            if (empty($errors)) {
                // Usergroup Permissions
                $userArray = explode(', ', $permission); //split string into array seperated by ', '
                foreach($userArray as $usergroup) //loop over values
                {
                    $this->users->add_usergroup($usergroup, $url);
                }
                // Meta Information
                $keywords = $this->postValidation($_POST['pageKeywords']);
                $description = $this->postValidation($_POST['pageDesc']);
                $this->model->editPageData($url, "keywords", $keywords);
                $this->model->editPageData($url, "description", $description);

                // Generate the page
                $this->model->generate_page($title, $url, $pageContent);
                $this->model->create_nav($title, "/user/".$url, $position);
                $response = array('result' => "success", 'message' => 'A new page is born');


            }  elseif (empty($errors) === false) {
                $response = array('result' => "fail", 'message' => implode($errors));
            }
            echo(json_encode($response));
            die();
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
            die();
        }
        /**************************************************************
        Create new Menu
         ***************************************************************/
        if (!empty($_POST['nav_create'])) {
            if(!v::intVal()->between(0, 10)->validate($_POST['nav_position'])) {
                $errors[] = 'Position must be between 1 and 10. ';
            }
            if(!v::alnum()->notEmpty()->validate($_POST['nav_name'])) {
                $errors[] = 'Invalid name.';
            }
            if(!v::regex('/^[\w-\/]+$/')->noWhitespace()->validate($_POST['nav_link'])) {
                $errors[] = 'Invalid link.';
            }
            if (empty($errors)) {
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
                $response = array('result' => "fail", 'message' => implode($errors));
            }
            echo(json_encode($response));
            die();
        }
    }
}
