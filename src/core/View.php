<?php
namespace Nixhatter\ICMS;

/**
 * View
 *
 * @package ICMS
 * @author Dillon Aykac
 */

defined('_ICMS') or die;

class View
{
    private $controller;
    private $container;
    private $user;
    private $model;
    private $settings;
    private $usermodel;

    public function __construct($model, $controller, $page, $usermodel)
    {
        $this->model        = $model;
        $this->controller   = $controller;
        $this->container    = $model->container;
        $this->settings     = $this->container['settings'];
        $this->usermodel    = $usermodel;
        if (!empty($controller->page)) {
            $this->page     = $controller->page;
        } else {
            $this->page = $page;
        }
    }

    public function render()
    {
        $page = $this->page;
        if ($this->controller->logged_in() === true) {
            $this->user = $this->container['user'];
        }

        # If an internationalization file exists, use it
        $language = $_SESSION['i18n'];
        if (file_exists("../localization/".$language."/". $page . ".php")) {
            include "../localization/" . $language . "/" . $page . ".php";
        }

        # Set the custom template, but default it if it doesn't exist
        $template = $this->settings->production->site->template;
        if (!file_exists("templates/".$template."/index.php")) {
            $template = 'white';
        }
        // If the controller has set any specific metadata elements, grab them
        $data = $this->controller->data;
        $customPage = "templates/".$template.'/'. $page;
        $defaultPage = "../pages/" . $page;

        /**
         * A user can supply a custom page in the template folder
         * This allows the site to be customizable without affecting
         * the update process.
         */
        if (file_exists($customPage . '.php')) {
            $page_type = 'template';
        } elseif (file_exists($defaultPage . '.php')) {
            $page_type = 'page';
        }

        // Check for custom meta tags
        if (empty($data)) {
            $data = new \stdClass();
            $data->keywords = "ICMS";
            $data->description = "Check out our open source content management system";
            if (file_exists($customPage . '.data')) {
                $dataParser = new \IniParser($customPage . ".data");
                $data = $dataParser->parse();
            } elseif (file_exists($defaultPage . '.data')) {
                $dataParser = new \IniParser($defaultPage . '.data');
                $data = $dataParser->parse();
            }
        }

        if (!empty($_SESSION['message'])) {
            $this->controller->alert($_SESSION['message'][0], $_SESSION['message'][1]);
            $_SESSION['message'] = null;
        }

        include "templates/".$template."/head.php";
        include "templates/".$template."/topbar.php";
        include "templates/".$template."/pre.php";
        if ($page_type == "template") {
            include "templates/".$template."/". $page . ".php";
        } elseif ($page_type == "page") {
            include "../pages/" . $page . ".php";
        } else {
            header("Location: /404");
            exit();
        }
        include "templates/".$template."/post.php";
        include "templates/".$template."/footer.php";
    }
}
