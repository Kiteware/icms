<?php
namespace Nixhatter\ICMS\admin\controller;

/**
 * Admin Pages Controller
 * Flat File Page Creation
 * @package ICMS
 * @author Dillon Aykac
 */

defined('_ICMS') or die;

use Nixhatter\ICMS as ICMS;
use Respect\Validation\Validator as v;

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
        $this->inputValidation($id, 'int');

        if(empty($this->errors)) {
            $this->id = $id;
            $this->pages = $this->model->get_page($id);
        }

    }

    public function delete($id) {
        $this->inputValidation($id, 'int');

        if(empty($this->errors)) {
            if ($this->model->delete_page($id)) {
                $response = array('result' => 'success', 'message' => 'Page Deleted');
            } else {
                $response = array('result' => "fail", 'message' => 'Could not delete page');
            }
        } else {
            $response = array('result' => "fail", 'message' => 'Invalid page ID');
        }
        exit(json_encode($response));
    }
    public function update($id) {
        if (isset($_POST['submit'])) {
            $this->inputValidation($id, 'int');

            if(empty($this->errors)) {
                $pageTitle = filter_input(INPUT_POST, 'pageTitle', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $pageURL = filter_input(INPUT_POST, 'pageURL', FILTER_SANITIZE_ENCODED);

                $pageContent = filter_input(INPUT_POST, 'pageContent');

                $pageKeywords = filter_input(INPUT_POST, 'pageKeywords', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $pageDesc = filter_input(INPUT_POST, 'pageDesc', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                $meta = array(
                    "keywords" => $pageKeywords,
                    "description" => $pageDesc
                );
                // Meta Information
                $this->model->editPageData('templates/' . $this->template . '/' . $pageURL, $meta);
                // URL has changed, delete and add a new one. Same with permissions
                if(strcmp($this->pages[$id]['url'], $pageURL) !== 0) {
                    unlink('templates/' . $this->template . '/' . $this->pages[$id]['url'] . '.php');
                    unlink('templates/' . $this->template . '/' . $this->pages[$id]['url'] . '.data');
                    $this->model->create_nav($pageTitle, $pageURL, 10, '0');
                    $this->model->delete_nav($this->pages[$id]['url']);

                    $this->users->add_usergroup($this->pages[$id]['permissions'], $pageURL);
                    $this->users->delete_usergroup($this->pages[$id]['permissions'], $this->pages[$id]['url']);

                }

                $response = array('result' => "error", 'message' => 'Error saving page');;

                if ($this->model->update_page($pageTitle, $pageContent, $pageURL, $id)) {
                    $response = array('result' => 'success', 'message' => 'Page Saved', 'location' => '/admin/pages/edit');
                    $_SESSION['message'] = ['success', 'Page Saved'];
                }
            }
            exit(json_encode($response));
        }
    }

    public function create() {
        if (isset($_POST['submit'])) {
            $response = array('result' => "fail", 'message' => 'Invalid inputs');

            $pageTitle = filter_input(INPUT_POST, 'pageTitle', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $pageURL = filter_input(INPUT_POST, 'pageURL', FILTER_SANITIZE_ENCODED);
            $pagePermission = filter_input(INPUT_POST, 'pagePermission');
            $pagePosition = filter_input(INPUT_POST, 'pagePosition', FILTER_VALIDATE_INT);
            $pageContent = filter_input(INPUT_POST, 'pageContent');
            $pageKeywords = filter_input(INPUT_POST, 'pageKeywords', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $pageDesc = filter_input(INPUT_POST, 'pageDesc', FILTER_SANITIZE_FULL_SPECIAL_CHARS);


            $pagePermission = $this->inputValidation($pagePermission, 'alpha');

            $pagePosition = $this->inputValidation($pagePosition, 'int');

            if ($this->emptyCheck($pageTitle, $pageURL, $pagePermission, $pagePosition, $pageContent, $pageKeywords, $pageDesc)) {
                // Usergroup Permissions
                //$userArray = explode(', ', $pagePermission); //split string into array seperated by ', '
                //foreach ($userArray as $usergroup) //loop over values {
                $this->users->add_usergroup($pagePermission, $pageURL);
                //}
                $meta = array(
                    "keywords" => $pageKeywords,
                    "description" => $pageDesc
                );
                // Meta Information
                $this->model->editPageData('templates/'.$this->template.'/'.$pageURL, $meta);

                // Generate the page
                $this->model->new_page($pageTitle, $pageURL, $pageContent);
                $this->model->create_nav($pageTitle, $pageURL, $pagePosition, '0');

                $response = array('result' => 'success', 'message' => 'A new page is born', 'location' => '/admin/pages/edit');
                $_SESSION['message'] = ['success', 'A new page is born'];

            }

            exit(json_encode($response));

        }
    }

    public function menu() {
        /**
         * Delete
         */
        if (isset($_POST['nav_delete'])) {
            $nav_id = filter_input(INPUT_POST, 'nav-id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if ($this->model->delete_nav($nav_id)) {
                $response = array('result' => 'success', 'message' => 'Navigation Deleted');
            } else {
                $response = array('result' => "fail", 'message' => 'Could not delete the menu item');
            }

            exit(json_encode($response));
        }
        /**
         * Create/Update
         */
        if (isset($_POST['nav_create'])) {

            $navPosition = filter_input(INPUT_POST, 'nav-position-required', FILTER_VALIDATE_INT);

            if(!v::intVal()->between(0, 10)->validate($navPosition)) {
                $this->errors[] = 'Position must be between 1 and 10. ';
            }

            $navName = filter_input(INPUT_POST, 'nav-name-required', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $navLink = filter_input(INPUT_POST, 'nav-link-required', FILTER_SANITIZE_URL);
            $parent = filter_input(INPUT_POST, 'parent', FILTER_VALIDATE_INT);
            $nav_id = filter_input(INPUT_POST, 'nav-id', FILTER_VALIDATE_INT);
            $response = array('result' => "fail", 'message' => 'Could not create navigation');

            // Make sure all variables are filled in
            if ($this->emptyCheck($navPosition, $navName, $navLink)) {
                if(!empty($nav_id)) {
                    // Navigation already exists
                    if($this->model->update_nav($navName, $navLink, $parent, $navPosition, $nav_id )) {
                        $response = array('result' => 'success', 'message' => 'Navigation Updated');
                    }
                } else {
                    // New navigation item
                    if ($this->model->create_nav($navName, $navLink, $navPosition, $parent)) {
                        $response = array('result' => 'success', 'message' => 'Navigation Created');
                    }
                }
            }

            exit(json_encode($response));
        }
    }
}
