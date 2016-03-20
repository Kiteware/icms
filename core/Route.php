<?php
namespace Nixhatter\ICMS;

/**
 * Class Route
 * Basic Routing Model
 *  Model is passed in by the FrontController
 *  Controller is determined by the name of the page+Controller
 *  View is determined by the page in the "pages" folder
 */
class Route {
    public $model;
    public $view;
    public $controller;

    public function __construct($model, $view, $controller, $admin)
    {
        if ($admin) {
            //require "admin/controller/" . $controller . ".php";
            $this->model = $model;
            $this->view = $view;
            $this->controller = 'Nixhatter\\ICMS\\admin\\controller\\'.$controller;
        } else {
            if (file_exists("core/controller/" . $controller . ".php")) {
                //require "controller/" . $controller . ".php";
                $this->controller = 'Nixhatter\\ICMS\\controller\\'.$controller;
            } else {
                //require "controller/Controller.php";
                $this->controller = "Nixhatter\\ICMS\\controller\\Controller";
            }
        }

        $this->model = $model;
        $this->view = $view;
    }
}
