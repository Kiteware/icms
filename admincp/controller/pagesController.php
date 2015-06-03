<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */

/*
|--------------------------------------------------------------------------
| Admin Pages Controller
|--------------------------------------------------------------------------
|
| Admin Pages Controller Class - Called on /admin
|
*/
class pagesController {
    private $model;
    public $user_id;


    public function __construct(PagesModel $model) {
        $this->model = $model;
        $this->model->pages = $model->get_pages();
       // $this->user_id    = $_SESSION['id'];      //put in general

    }

    public function success() {
        echo ("success");
    }
    public function edit($id) {
        $this->model->action = "edit";
        $this->model->id = $id;
        $this->model->pages = $this->model->get_page($id);
    }
    public function delete($id) {
        $this->model->delete_page($id);
    }
    public function update() {
        $settings = $this->model->container['parser']->parse();

        if (isset($_POST['action'])) {
            $action = $_POST['action'];

            if (isset($_POST['pageURL'])) {
                $pageUrl = $_POST['pageURL'];
            } else {
                $errors[] = 'URL is Required';
            }
            if (empty($errors) === true) {
                //User wants to delete the given URL
                if ($action == "delete") {
                    $this->model->delete_nav("index.php?page=".$pageUrl);
                    \Nix\Icms\Permissions::delete_all_page_permissions($pageUrl);
                    if ($this->model->delete_page($pageUrl, $settings->production->site->cwd)) {
                        echo("<script> successAlert();history.go(-1);</script>");
                    } else {
                        $errors[] = 'Delete page MAY HAVE failed. Return to the Edit pages to find out! ';
                    }
                }
                //User has edited a file and wants to save it
                elseif ($action == "update") {
                    if (isset($_POST['text'])) {
                        $text = $_POST['text'];
                        if($this->model->edit_page($pageUrl, $settings->production->site->cwd, $text)) {
                            echo("<script> successAlert();</script>");
                        } else {
                            $errors[] = 'Failed updating the page.';
                        }
                    } else {
                        $errors[] = 'Text is Required';
                    }

                }
            }
        }
        if (empty($errors) === false) {
            echo '<p>' . implode('</p><p>', $errors) . '</p>';
        }
    }

    public function create() {
        if (isset($_POST['submit'])) {

            $config = HTMLPurifier_Config::createDefault();
            $purifier = new HTMLPurifier($config);

            if (!isset($_POST['title']) || !isset($_POST['url']) || !isset($_POST['editPage']) || !isset($_POST['permission']) || !isset($_POST['position']) ) {

                $errors[] = 'All fields are required.';

            } else {

                if (v::alnum()->notEmpty()->validate($_POST['title'])) {
                    $title = $_POST['title'];
                } else {
                    $errors[] = 'invalid title';
                }
                if (v::alnum()->notEmpty()->validate($_POST['url'])) {
                    $url = $_POST['url'];
                } else {
                    $errors[] = 'invalid URL';
                }

                $editPage = $purifier->purify($_POST['editPage']);

                if (v::alnum(',')->validate($_POST['permission'])) {
                    $permission = $_POST['permission'];
                } else {
                    $errors[] = 'invalid permissions';
                }
                if (v::int()->validate($_POST['position'])) {
                    $position = htmlentities($_POST['position']);
                } else {
                    $errors[] = 'invalid position';
                }
            }
            if (empty($errors) === true) {
                $userArray = explode(', ', $permission); //split string into array seperated by ', '
                foreach($userArray as $usergroup) //loop over values
                {
                    \Nix\Icms\Permissions::add_usergroup($usergroup, $url);
                }

                $this->model->create_page($title, $url, $editPage);
                $pageArray = $this->model->get_page($url);
                $this->model->generate_page($pageArray['title'], $url, $pageArray['content']);
                $url = "pages/".$url;
                $this->model->create_nav($title, $url, $position);
                echo("<script> successAlert();</script>");

            }
        }
    }

}