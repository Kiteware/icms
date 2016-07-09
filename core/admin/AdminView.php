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

class AdminView {
    public $user;
    private $controller;
    private $settings;
    private $model;

    public function __construct($controller) {
        $this->controller = $controller;
        $this->settings = $controller->model->container['settings'];
        $this->model    = $controller->model;
    }

    public function render($page) {
        if ($this->controller->logged_in() === true) {
            $this->user = $this->model->container['user'];
        }
        include "templates/admin/head.php";
        include "templates/admin/topbar.php";
        include "templates/admin/pre.php";

        // Only include a file that exists
        if(file_exists("../core/admin/".$this->controller->getName()."/".$page.".php")){
            include "../core/admin/".$this->controller->getName()."/".$page.".php";
        } else {
            echo("page does not exist");
            exit();
        }
        include "templates/admin/footer.php";
        if(isset($_GET['success'])) {
            $this->controller->success();
        }
    }
}
