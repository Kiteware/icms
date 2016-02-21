<?php

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
            require "admincp/AdminView.php";
            require "admincp/controller/" . $controller . ".php";
            $this->model = $model;
            $this->view = $view;
            $this->controller = $controller;
        } else {
            require "view/View.php";
            if (file_exists("core/controller/" . $controller . ".php")) {
                require "controller/" . $controller . ".php";
                $this->controller = $controller;
            } else {
                require "controller/Controller.php";
                $this->controller = "Controller";
            }
        }

        $this->model = $model;
        $this->view = $view;
    }
}
