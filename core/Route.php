<?php
namespace Nixhatter\ICMS;

/**
 * Class Route
 * Basic Routing Model
 *  Model is passed in by the FrontController
 *  Controller is determined by the name of the page+Controller
 *  View is determined by the page in the "pages" folder
 */

defined('_ICMS') or die;

class Route {
    public $model;
    public $view;
    public $controller;

    public function __construct($model, $view, $controller, $admin) {

        $this->model = 'Nixhatter\\ICMS\\model\\'.$model;
        $this->view = $view;

        $this->controller = "Nixhatter\\ICMS\\controller\\Controller";

        if (file_exists("../core/controller/" . $controller . ".php")) {
            $this->controller = 'Nixhatter\\ICMS\\controller\\'.$controller;
        }

        if ($admin) {
            $this->controller = 'Nixhatter\\ICMS\\admin\\controller\\'.$controller;
        }
    }
}
