<?php
namespace Nixhatter\ICMS;

class FrontController {
    private $model;
    private $controller;
    private $view;
    private $container;
    private $pageName;

    public function __construct(Router $router, $model, $controller, $action = null, $id = null) {
        $container = new \Pimple\Container();
        $this->container = $container;

        $container['settings'] = function ($c) {
            $parser = new \IniParser('core/configuration.php');
            return $parser->parse();
        };

        $container['db'] = function ($c) {
            $database = new Database($c['settings']);
            return $database->load();
        };

        $container['user'] = function ($c) {
            return new Model\UserModel($this->container);
        };

        if(isset($_SESSION['id'])) {
            $user = $container['user']->userdata($_SESSION['id']);
            $userID = $user['id'];
            $usergroup = $user['usergroup'];
        }
        if(empty($controller)) $controller = $model;
            if ($container['user']->has_access($userID, $controller, $usergroup)) {
                /**
                 * Router defines which model, controller and view to load
                 */
                $route = $router->getRoute($model, $controller, false);
                /**
                 * The three names given by the Router
                 */
                $modelName = $route->model;
                $controllerName = $route->controller;
                $this->pageName = $route->view;

                /**
                 * Load up the classes
                 */
                $this->model = new $modelName($container);
                $this->controller = new $controllerName($this->model);
                $this->view = new View($this->model, $this->controller);

                if (!empty($action)) $this->controller->{$action}($id);

            } else {
                echo "ACCESS DENIED";
                exit;
            }
        }

    public function output() {
        echo $this->view->render($this->pageName);
    }
}
