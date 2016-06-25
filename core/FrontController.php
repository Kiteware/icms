<?php
namespace Nixhatter\ICMS;

class FrontController {
    private $model;
    private $controller;
    private $view;
    private $pageName;
    private $usermodel;

    public function __construct(Router $router, $model, $controller, $action = null, $id = null) {
        $container = new \Pimple\Container();

        $container['settings'] = function ($c) {
            $parser = new \IniParser('core/configuration.php');
            return $parser->parse();
        };

        $container['db'] = function ($c) {
            $database = new Database($c['settings']);
            return $database->load();
        };

        $this->usermodel = new model\UserModel($container);

        $container['user'] = function ($c) {
            return $this->usermodel->userdata($this->usermodel->user_id);
        };

        // If the user's logged it, grab their details
        if(isset($this->usermodel->user_id)) {
            $user = $container['user'];
            $userID = $user['id'];
            $usergroup = $user['usergroup'];
        } else {
            $userID = null;
            $usergroup = null;
        }

        // A hack to allow shorter urls where the model and controller are the same
        if(empty($controller)) { $controller = $model; }

        // Checking access
        if ($this->usermodel->has_access($userID, $controller, $usergroup)) {
            /**
             * Router defines which model, controller and view to load
             */
            $route = $router->getRoute($model, $controller, false);
            /**
             * The three names given by the Router
             */
            $modelName = $route->model;
            $controllerName = $route->controller;

            /**
             * Load up the classes
             */
            $this->model = new $modelName($container);
            $this->controller = new $controllerName($this->model);
            $this->view = new View($this->model, $this->controller);

            if (!empty($action)) $this->controller->{$action}($id);
            // Grab the page name from the controller
            $this->pageName = $this->controller->page;

        } else {
            // No access
            header("Location: /");
            die();
        }
    }

    public function output() {
        echo $this->view->render($this->pageName);
    }
}
