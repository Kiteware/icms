<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */

/*
|--------------------------------------------------------------------------
| View
|--------------------------------------------------------------------------
|
| Basic View Class - Called on /index.php
|
*/
class AdminView {
    private $model;
    private $route;
    private $settings;

    public function __construct($route, $model) {
        $this->route = $route;
        $this->model = $model;
    }

    public function render($page) {
        //$posts = $this->model->posts;
        // Only include a file that exists
        if(file_exists($_SERVER['DOCUMENT_ROOT']."/admincp/".$this->route."/".$page.".php")){
            include $_SERVER['DOCUMENT_ROOT']."/admincp/".$this->route."/".$page.".php";
        } else {
            echo("page does not exist");
        }
        return '';
    }

    public function set_settings($settings) {
        $this->settings = $settings;
    }
    public function get_settings() {
        return $this->settings;
    }
}
