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
    protected $settings;
    private $users;

    public function __construct(ICMS\model\PagesModel $model) {
        $this->model = $model;
        $this->model->pages = $model->get_pages();
        $this->users = $model->users;
        $this->settings = $model->container['settings'];

        $newFileName = 'pages/index.php';

        if (!is_writable(dirname($newFileName))) {
            echo '<div class="alert alert-danger" role="alert">' . dirname($newFileName) . ' is not writeable. Check the folder permissions.</div>';
        } else {

        }
    }
    public function getName() {
        return 'pages';
    }

    public function edit($id) {
        if(isset($id)) {
            if(v::intVal()->notEmpty()->validate($id)) {
                $this->model->action = "edit";
                $this->model->id = $id;
                $this->model->pages = $this->model->get_page($id);
            } else {
                $response = array('result' => "fail", 'message' => 'Invalid page ID');
                echo(json_encode($response));
                die();
            }
        }
    }
    public function delete($id) {
        if(v::intVal()->notEmpty()->validate($id)) {
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
        if (isset($_POST['submit'])) {
            if (!isset($_POST['pageURL']) && v::alnum()->notEmpty()->validate($_POST['pageURL'])) {
                $errors[] = 'Invalid page url';
            }
            if(!isset($_POST['pageContent'])) {
                $errors[] = 'Text is Required';
            }

            if (empty($errors) === true) {
                $pageUrl = $_POST['pageURL'];
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
            } else {
                $response = array('result' => "fail", 'message' => implode($errors));
            }
        }
        echo(json_encode($response));
        die();
    }

    public function create() {
        if (isset($_POST['submit'])) {
            $config = \HTMLPurifier_Config::createDefault();
            $purifier = new \HTMLPurifier($config);

            if (!isset($_POST['pageTitle']) || !isset($_POST['pageURL']) || !isset($_POST['pageContent'])
                || !isset($_POST['pagePermission']) || !isset($_POST['pagePosition']) ) {

                $errors[] = 'All fields are required.';

            } else {

                if (v::alnum()->notEmpty()->validate($_POST['pageTitle'])) {
                    $title = $_POST['pageTitle'];
                } else {
                    $errors[] = 'Invalid title.';
                }
                if (v::alnum()->notEmpty()->validate($_POST['pageURL'])) {
                    $url = $_POST['pageURL'];
                } else {
                    $errors[] = 'invalid URL';
                }

                $pageContent = $purifier->purify($_POST['pageContent']);

                if (v::alnum(',')->validate($_POST['pagePermission'])) {
                    $permission = $_POST['pagePermission'];
                } else {
                    $errors[] = 'Permissions must be an integer from 1 - X';
                }
                if (v::intVal()->validate($_POST['pagePosition'])) {
                    $position = htmlentities($_POST['pagePosition']);
                } else {
                    $errors[] = 'invalid page position';
                }
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
        Update Menu
         ***************************************************************/
        if (isset($_POST['nav_update'])) {
            if(!v::intVal()->between(0, 10)->validate($_POST['nav_position'])) {
                $errors[] = 'Position must be between 1 and 10.';
            }
            if(!v::alnum()->notEmpty()->validate($_POST['nav_name'])) {
                $errors[] = 'Invalid name.';
            }
            if(!v::regex('/^[\w-\/]+$/')->noWhitespace()->notEmpty()->validate($_POST['nav_link'])) {
                $errors[] = 'Invalid link or url.';
            }
            if (empty($errors)) {
                $Name = $_POST['nav_name'];
                $Link = $_POST['nav_link'];
                $Position = $_POST['nav_position'];
                //echo confirmation if successful
                if ($this->model->update_nav($Name, $Link, $Position)) {
                    $response = array('result' => "success", 'message' => 'Navigation Updated');
                } else {
                    $response = array('result' => "fail", 'message' => 'Navigation failed to update. ');
                }
            } elseif (empty($errors) === false) {
                $response = array('result' => "fail", 'message' => implode($errors));
            }
            echo(json_encode($response));
            die();
        }
        /**************************************************************
        DELETE Menu
         ***************************************************************/
        if (isset($_POST['nav_delete'])) {
            if(v::regex('/^[\w-\/]+$/')->noWhitespace()->notEmpty()->validate($_POST['nav_link'])) {
                $url = $_POST['nav_link'];
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
        if (isset($_POST['nav_create'])) {
            if(!v::intVal()->between(0, 10)->validate($_POST['nav_position'])) {
                $errors[] = 'Position must be between 1 and 10. ';
            }
            if(!v::alnum()->notEmpty()->validate($_POST['nav_name'])) {
                $errors[] = 'Invalid name.';
            }
            if(!v::regex('/^[\w-\/]+$/')->noWhitespace()->notEmpty()->validate($_POST['nav_link'])) {
                $errors[] = 'Invalid link.';
            }

            if (empty($errors)) {
                $Name = $_POST['nav_name'];
                $Link = $_POST['nav_link'];
                $Position = $_POST['nav_position'];

                $this->model->delete_nav($Link);

                if ($this->model->create_nav($Name, $Link, $Position)) {
                    $response = array('result' => "success", 'message' => 'Navigation Created!');
                } else {
                    $response = array('result' => "fail", 'message' => 'Could not create navigation');
                }
            } elseif (empty($errors) === false) {
                $response = array('result' => "fail", 'message' => implode($errors));
            }
            echo(json_encode($response));
            die();
        }
    }
}
