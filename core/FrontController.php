<?php
namespace Nixhatter\ICMS;

defined('_ICMS') or die;

class FrontController {
    private $model;
    private $controller;
    private $view;
    private $usermodel;

    public function __construct($model, $controller, $action = null, $arg = null) {

        $router = new Router();

        /**
         * Create a DI Container
         */
        $container = new \Pimple\Container();

        $container['settings'] = function ($c) {
            $parser = new \IniParser('../core/configuration.php');
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

        $userID = null;
        $usergroup = null;
        // If the user's logged it, grab their details
        if(isset($this->usermodel->user_id)) {
            $user = $container['user'];
            $userID = $user['id'];
            $usergroup = $user['usergroup'];
        }

        // A hack for a shorter blog url
        if($model === 'blog') {
            if ($controller === 'home') {

            } elseif (!empty($controller)) {
                $arg = $action;
                $action = $controller;
                $controller = $model;
            } else {
                $controller = 'blog';
            }
        }

        // A hack to allow shorter urls where the model and controller are the same
        // TODO: Refactor
        if($model === "home") {
            $controller = $model;
            $model = 'blog';
        } elseif(empty($controller)) {
            $controller = $model;
            $model = 'user';
        }


        // Checking access
        if ($this->usermodel->has_access($userID, $controller, $usergroup)) {
            /**
             * Router defines which model, controller and view to load
             */
            $route = $router->getRoute($model, $controller, false);
            /**
             * two names given by the Router
             */
            $modelName = $route->model;
            $controllerName = $route->controller;
            /**
             * Load up the classes
             */
            $this->model = new $modelName($container);
            $this->controller = new $controllerName($this->model);
            $this->view = new View($this->model, $this->controller, $controller, $this->usermodel);

            if (!empty($action)) $this->controller->{$action}($arg);

        } else {
            // No access
            header("Location: /404");
            exit();
        }
    }

    public function output() {
        echo $this->view->render();
    }
}
