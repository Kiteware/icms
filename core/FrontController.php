<?php

class FrontController {
    private $controller;
    private $view;
    private $container;
    private $settings;
    private $page;
    private $users;
    public $general;

    public function __construct(Router $router, $model, $controller, $action = null, $id = null) {
        $container = new \Pimple\Container();
        $container['parser'] = function ($c) {
            return new \iniParser(__DIR__.'/../core/configuration.php');
        };
        $this->settings = $container['parser']->parse();

        $container['db'] = function ($c) {
            $database = new Database($this->settings);
            return $database->load();
        };
        $this->container = $container;
        $this->users  = new UserModel($container);

        $user_id = $usergroup = "";

        if(isset($_SESSION['id'])) {
            $user = $this->users->userdata($_SESSION['id']);
            $userID = $user['id'];
            $usergroup = $user['usergroup'];
            $full_name = $user['full_name'];
        }
            if ($this->users->has_access($userID, $controller, $usergroup)) {
                $route = $router->getRoute($model, $controller, false);
                $modelName = $route->model;
                $controllerName = $route->controller;
                //$viewName = $route->view;
                $this->page = $route->view;

                $model = new $modelName($container);
                $this->controller = new $controllerName($model);
                $this->view = new View($controller, $model);
                $this->view->set_settings($this->settings);

                if (!empty($action)) $this->controller->{$action}($id);

            } else {
                echo "ACCESS DENIED";
                exit;
            }
        }

    public function output() {
        global $general;
        //This allows for some consistent layout generation code
        $template = $this->settings->production->site->template;
        $general    = new general();
        $pages        = new pagesModel($this->container);

        if ($general->logged_in() === true) {
            $user_id    = $_SESSION['id'];
            $user        = $this->users->userdata($user_id);
        }

        $navigation = $pages->list_nav();

        include "templates/".$template."/head.php";
        include "templates/".$template."/header.php";
        include "templates/".$template."/menu.php";
        echo $this->view->render($this->page);
        include "templates/".$template."/footer.php";
    }
}
