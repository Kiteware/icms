<?php
namespace Nixhatter\ICMS;

class Router {
    private $table = array();

    public function __construct() {
        //$this->table['controller'] = new Route('Model', 'home', 'Controller');
        //$this->table['blog'] = new Route('BlogModel', 'blog', 'BlogController');
    }

    public function getRoute($model, $controller, $admin) {
        $model = strtolower($model);
        $controller = strtolower($controller);

        if ($admin) {
            if (empty($model)) {
                $this->table['controller'] = new Route('Nixhatter\ICMS\model\PagesModel', 'admin', 'pagesController', true);
                return $this->table['controller'];
            } else {
                $this->table[$controller] = new Route('Nixhatter\ICMS\model\\'.ucfirst($model) . 'Model', $controller, $model . 'Controller', true);
                return $this->table[$controller];
            }
        } else {
            if ($model == "blog") {
              $this->table['controller'] = new Route('Nixhatter\ICMS\model\BlogModel', 'blog', 'blogController', false);
              return $this->table['controller'];
            } else {
                $this->table[$controller] = new Route('Nixhatter\ICMS\model\\'.ucfirst($model) . 'Model', $controller, $controller . 'Controller', false);
                return $this->table[$controller];
            }
        }

    }
}
