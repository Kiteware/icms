<?php
class Route {
    public $model;
    public $view;
    public $controller;

    public function __construct($model, $view, $controller, $admin) {
        if ($admin) {
            require __DIR__."/model/".$model.".php";
            require "admincp/AdminView.php";
            require "admincp/controller/".$controller.".php";
            $this->model = $model;
            $this->view = $view;
            $this->controller = $controller;
        } else {
            require "model/".$model.".php";
            require "view/View.php";
            require "controller/".$controller.".php";
            $this->model = $model;
            $this->view = $view;
            $this->controller = $controller;
        }
    }
}