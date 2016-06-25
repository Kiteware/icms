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
    private $controller;
    private $container;
    private $user;
    private $model;
    private $settings;

    public function __construct($model, $controller) {
        $this->model        = $model;
        $this->controller   = $controller;
        $this->container    = $model->container;
        $this->settings     = $this->container['settings'];
    }

    public function render($page) {
        if ($this->controller->logged_in() === true) {
            $this->user = $this->container['user'];
        }

        $language = $_SESSION['i18n'];
        if (file_exists("localization/".$language."/". $page . ".php")) {
            include "localization/" . $language . "/" . $page . ".php";
        }

        $template = $this->settings->production->site->template;
        // If the controller has set any specific metadata elements, grab them
        $data = $this->controller->data;
        if (file_exists("templates/".$template."/". $page . ".php")) {
            $page_type = "template";
            if(empty($data)){
                $parser = new \IniParser("templates/".$template."/". $page . ".data");
                $data = $parser->parse();
            }

        } else if (file_exists("pages/" . $page . ".php")) {
            $page_type = "page";
            if(empty($data)) {
                $parser = new \IniParser("pages/" . $page . ".data");
                $data = $parser->parse();
            }
        }

        include "templates/".$template."/head.php";
        include "templates/".$template."/topbar.php";
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
