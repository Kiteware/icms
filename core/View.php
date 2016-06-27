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

    public function __construct($model, $controller, $page) {
        $this->model        = $model;
        $this->controller   = $controller;
        $this->container    = $model->container;
        $this->settings     = $this->container['settings'];
        $this->page         = $page;
    }

    public function render() {
        $page = $this->page;
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
        $customPage = "templates/".$template.'/'. $page;
        $defaultPage = "pages/" . $page;

        /**
         * A user can supply a custom page in the template folder
         * This allows the site to be customizable without affecting
         * the update process.
         */
        if (file_exists( $customPage . '.php')) {
            $page_type = 'template';
        } else if (file_exists($defaultPage . '.php')) {
            $page_type = 'page';
        }

        // Check for custom meta tags
        if(empty($data)) {
            if (file_exists($customPage . '.data')){
                $dataParser = new \IniParser($customPage . ".data");
            } elseif (file_exists($defaultPage . '.data')) {
                $dataParser = new \IniParser($defaultPage . '.data');
            }
            $data = $dataParser->parse();
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
