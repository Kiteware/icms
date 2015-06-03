<?php
use Nix\Icms;

class AdminController {
    private $controller;
    private $view;
    private $db;
    private $settings;
    private $page;
    private $users;
    public $general;

    public function __construct(Router $router, $controller, $action = null, $id = null) {
        //Load the config file
        $container = new \Pimple\Container();
        $container['parser'] = function ($c) {
            return new \iniParser(__DIR__.'/../core/configuration.php');
        };
        $this->settings = $container['parser']->parse();

        $container['db'] = function ($c) {
            $database = new \Nix\Icms\Database($this->settings);
            return $database->load();
        };
        $this->db = $container['db'];
        //Load the database connection
        //All admin actions require the users and permissions classes
        $this->users        = new \Nix\Icms\Users\Users($container);
        $permissions = new \Nix\Icms\Permissions\permissions($container);

        //Check if a session id is set
        $user_id = $usergroup = "";
        if(isset($_SESSION['id'])) {
            $user = $this->users->userdata($_SESSION['id']);
            $user_id = $user['id'];
            $usergroup = $user['usergroup'];
            //Check if a person has access
            if (isset($user_id) && isset($usergroup) && $permissions->has_access($user_id, 'administrator', $usergroup)) {
                // Create a new Route based on the model and controller
                $route = $router->getRoute($controller, $controller, true);
                //Grab the MVC names from the Route
                $modelName = $route->model;
                $controllerName = $route->controller;
                $this->page = $action;
                //Create the appropriate classes
                $model = new $modelName($container);
                $this->controller = new $controllerName($model);
                $this->view = new AdminView($controller, $model);
                $this->view->set_settings($this->settings);
                // If there's an action, call it
                if (!empty($action)) $this->controller->{$action}($id);
            }
        }
    }

    public function output() {
        global $general;
        //This allows for some consistent layout generation code
        $template = $this->settings->production->site->template;
        $general    = new \Nix\Icms\General\general();
        $settings       = $this->settings;

        if ($general->logged_in() === true) {
            $user_id    = $_SESSION['id'];
            $user        = $this->users->userdata($user_id);

        }

        include "templates/admin/head.php";
        include "templates/admin/topbar.php";
        include "templates/admin/menu.php";
        echo "THIS PAGE SHOULD BE - ".$this->page;
        return '<div>' . $this->view->render($this->page) . '</div>' . include "templates/admin/footer.php";
    }
}