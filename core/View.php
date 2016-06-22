<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */
namespace Nixhatter\ICMS;
/*
|--------------------------------------------------------------------------
| View
|--------------------------------------------------------------------------
|
| Basic View Class for the front end
|
*/
class View {
    public $controller;
    private $container;
    private $user;
    private $model;
    private $data;

    public function __construct($model, $controller) {
        $this->model        = $model;
        $this->controller   = $controller;
        $this->container    = $model->container;
    }

    public function render($page) {
        if ($this->controller->logged_in() === true) {
            $user_id    = $_SESSION['id'];
            $this->user = $this->container['user']->userdata($user_id);
        }

        $language = $_SESSION['i18n'];
        if (file_exists("localization/".$language."/". $page . ".php")) {
            include "localization/" . $language . "/" . $page . ".php";
        }

        $template = $this->container['settings']->production->site->template;
        $this->data         = $this->controller->data;
        if (file_exists("templates/".$template."/". $page . ".php")) {
            $page_type = "template";
            if(empty($this->data)){
                $parser = new \IniParser("templates/".$template."/". $page . ".data");
                $this->data = $parser->parse();
            }

        } else if (file_exists("pages/" . $page . ".php")) {
            $page_type = "page";
            if(empty($this->data)) {
                $parser = new \IniParser("pages/" . $page . ".data");
                $this->data = $parser->parse();
            }
        }
        $data = $this->data;
        include "templates/".$template."/head.php";
        include "templates/".$template."/topbar.php";
        include_once("templates/admin/analyticstracking.php");
        include "templates/".$template."/pre.php";
        if ($page_type == "template") {
            include "templates/".$template."/". $page . ".php";
        } else if ($page_type == "page") {
            include "pages/" . $page . ".php";
        } else {
            echo "Page not found.";
        }
        include "templates/".$template."/post.php";
        include "templates/".$template."/footer.php";
    }
}
