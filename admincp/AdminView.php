<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */

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
    private $general;
    private $users;
    public $model;

    public function __construct($controller, \Pimple\Container $globals) {
        $controller->setGlobals($globals);
        $this->controller = $controller;
        $this->general = $globals['general'];
        $this->settings = $globals['settings'];
        $this->users    = $globals['users'];
        $this->model    = $controller->model;
    }

    public function render($page) {
        //$posts = $this->model->posts;

        if ($this->general->logged_in() === true) {
            $user_id    = $_SESSION['id'];
            $this->user = $this->users->userdata($user_id);

        }
        include "templates/admin/head.php";
        include "templates/admin/topbar.php";
        include "templates/admin/menu.php";
        // Only include a file that exists
        if(file_exists($_SERVER['DOCUMENT_ROOT']."/admincp/".$this->controller->getName()."/".$page.".php")){
            include $_SERVER['DOCUMENT_ROOT']."/admincp/".$this->controller->getName()."/".$page.".php";
        } else {
            echo("page does not exist");
            die();
        }
        include "templates/admin/footer.php";
        return '';
    }

}
