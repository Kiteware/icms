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

        $template = $this->container['settings']->production->site->template;

        include "templates/".$template."/head.php";
        include "templates/".$template."/header.php";
        include "templates/".$template."/pre.php";
        include "pages/".$page.".php";
        include "templates/".$template."/footer.php";
    }
}
