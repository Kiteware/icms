<?php
use Nix\Icms;

class AdminController {
    private $controller;
    private $view;
    private $db;
    private $settings;
    private $page;
    private $users;
    private $user;
    public $general;

    public function __construct(Router $router, $controller, $action = null, $id = null) {
        // Create a DI Container
        $container = new \Pimple\Container();
        // Add the config parser, not really needed.
        $container['parser'] = function ($c) {
            return new \iniParser(__DIR__.'/../core/configuration.php');
        };

        // Store all our settings from the config file
        $this->settings = $container['parser']->parse();

        // Add our database connection to the container
        $container['db'] = function ($c) {
            $database = new Database($this->settings);
            return $database->load();
        };
        //Load the database connection
        $this->db = $container['db'];

        //All admin actions require the users and permissions classes
        $this->users        = new UserModel($container);

        //Check if a session id is set
        $user = $this->user;
        $user_id = $usergroup = "";
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
                $this->view = new AdminView($controller, $model);
                $this->view->set_settings($this->settings);
                // If there's an action, call it
                if (!empty($action)) $this->controller->{$action}($id);
            } else {
                die();
            }
        } else {
            die();
        }
    }

    public function output() {
        global $general;
        $permissions = $this->users;
        //This allows for some consistent layout generation code
        $template = $this->settings->production->site->template;
        $general    = new general();
        $settings   = $this->settings;

        if ($general->logged_in() === true) {
            $user_id    = $_SESSION['id'];
            $this->user = $this->users->userdata($user_id);

        }

        include "templates/admin/head.php";
        include "templates/admin/topbar.php";
        include "templates/admin/menu.php";
        echo $this->view->render($this->page);
        include "templates/admin/footer.php";
    }
}
