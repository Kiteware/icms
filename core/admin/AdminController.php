<?php
namespace Nixhatter\ICMS\admin;
use Nixhatter\ICMS as ICMS;

class AdminController {
    private $controller;
    private $view;
    private $page;
    private $users;

    public function __construct(ICMS\Router $router, $controller, $action = null, $id = null) {
        /**
         * Create a DI Container
        */
        $container = new \Pimple\Container();

        $container['settings'] = function ($c) {
            $parser = new \IniParser('core/configuration.php');
            return $parser->parse();
        };

        // Add our database connection to the container
        $container['db'] = function ($c) {
            $database = new ICMS\Database($c['settings']);
            return $database->load();
        };

        //All admin actions require the users and permissions classes
        $this->users = new \Nixhatter\ICMS\model\UserModel($container);

        $container['user'] = function ($c) {
            return $this->users->userdata($this->users->user_id);
        };


        if(isset($this->users->user_id)) {
            $user = $container['user'];
            $user_id = $user['id'];
            $usergroup = $user['usergroup'];
            //Check if a person has access
            if (isset($user_id) && isset($usergroup) && $this->users->has_access($user_id, 'administrator', $usergroup)) {
                // Create a new Route based on the model and controller
                $route = $router->getRoute($controller, $action, true);
                //Grab the MVC names from the Route
                $modelName = $route->model;
                $controllerName = $route->controller;
                $this->page = $route->view;
                //Create the appropriate classes
                $model = new $modelName($container);
                $this->controller = new $controllerName($model);
                $this->view = new AdminView($this->controller, $container);
                // If there's an action, call it
                if (!empty($action)) $this->controller->{$action}($id);
            } else {
                header("Location: /");
                die();
            }
        } else {
            header("Location: /");
            die();
        }
    }

    public function output() {
        $this->view->render($this->page);
    }
}
