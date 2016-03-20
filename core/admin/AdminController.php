<?php
namespace Nixhatter\ICMS\Admin;
use Nixhatter\ICMS as ICMS;

class AdminController {
    private $controller;
    private $view;
    private $db;
    private $settings;
    private $page;
    private $users;
    private $container;

    public function __construct(ICMS\Router $router, $controller, $action = null, $id = null) {
        /**
         * Create a DI Container
         * Container - which will be fed
        */
        $container = new \Pimple\Container();

        $container['settings'] = function ($c) {
            $parser = new \IniParser('core/configuration.php');
            return $parser->parse();
        };
        $container['users'] = function ($c) {
            return new ICMS\model\UserModel($c);
        };
        // Store all our settings from the config file
        $this->settings = $container['settings'];

        // Add our database connection to the container
        $container['db'] = function ($c) {
            $database = new ICMS\Database($c['settings']);
            return $database->load();
        };
        $this->container = $container;

        //Load the database connection
        $this->db = $container['db'];

        //All admin actions require the users and permissions classes
        $this->users = $container['users'];

        if(isset($_SESSION['id'])) {
            $user = $this->users->userdata($_SESSION['id']);
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
        echo $this->view->render($this->page);
    }
}
