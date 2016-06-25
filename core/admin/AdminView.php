<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */
namespace Nixhatter\ICMS\admin;
/*
|--------------------------------------------------------------------------
| View
|--------------------------------------------------------------------------
|
| Basic View Class - Called on /index.php
|
*/
class AdminView {
    public $user;
    private $controller;
    private $settings;
    private $container;
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
        if(file_exists($_SERVER['DOCUMENT_ROOT']."/core/admin/".$this->controller->getName()."/".$page.".php")){
            include $_SERVER['DOCUMENT_ROOT']."/core/admin/".$this->controller->getName()."/".$page.".php";
        } else {
            echo("page does not exist");
            die();
        }
        include "templates/admin/footer.php";
    }
}
