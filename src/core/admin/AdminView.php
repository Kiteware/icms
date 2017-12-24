<?php
/**
 * View
 * Basic View Class - Called on /index.php
 *
 * @package ICMS
 * @author Dillon Aykac
 */
namespace Nixhatter\ICMS\admin;

defined('_ICMS') or die;

class AdminView
{
    public $user;
    private $controller;
    private $settings;
    private $model;

    public function __construct($controller)
    {
        $this->controller = $controller;
        $this->settings = $controller->model->container['settings'];
        $this->model    = $controller->model;
    }

    public function render($page)
    {
        if ($this->controller->logged_in() === true) {
            $this->user = $this->model->container['user'];
        }

        if (!empty($_SESSION['message'])) {
            $this->controller->alert($_SESSION['message'][0], $_SESSION['message'][1]);
            $_SESSION['message'] = null;
        }

        include "../core/admin/template/head.php";
        include "../core/admin/template/topbar.php";
        include "../core/admin/template/pre.php";

        // Only include a file that exists
        if (file_exists("../core/admin/".$this->controller->getName()."/".$page.".php")) {
            include "../core/admin/".$this->controller->getName()."/".$page.".php";
        } else {
            exit('Page does not exist');
        }
        include "../core/admin/template/footer.php";
    }
}
