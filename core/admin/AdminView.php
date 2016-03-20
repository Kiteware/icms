<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */
namespace Nixhatter\ICMS\Admin;
/*
|--------------------------------------------------------------------------
| View
|--------------------------------------------------------------------------
|
| Basic View Class - Called on /index.php
|
*/
class AdminView {
    private $controller;
    private $settings;
    private $users;
    private $container;
    private $model;

    public function __construct($controller) {
        $this->controller = $controller;
        $this->settings = $controller->model->container['settings'];
        $this->users    = $controller->model->container['users'];
        $this->model    = $controller->model;
    }

    public function render($page) {
        if ($this->controller->logged_in() === true) {
            $user_id    = $_SESSION['id'];
            $this->user = $this->users->userdata($user_id);

        }
        include "templates/admin/head.php";
        include "templates/admin/topbar.php";
        include "templates/admin/menu.php";
        // Only include a file that exists
        if(file_exists($_SERVER['DOCUMENT_ROOT']."/core/admin/".$this->controller->getName()."/".$page.".php")){
            include $_SERVER['DOCUMENT_ROOT']."/core/admin/".$this->controller->getName()."/".$page.".php";
        } else {
            echo("page does not exist");
            die();
        }
        include "templates/admin/footer.php";
        return '';
    }
}
