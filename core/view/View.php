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
class View {
    private $model;
    private $route;
    private $settings;

    public function __construct($route, $model) {
        $this->route = $route;
        $this->model = $model;
    }

    public function render($page) {
        //$posts = $this->model->posts;
        include $_SERVER['DOCUMENT_ROOT']."/pages/".$page.".php";
        return '<a href="' . $this->route . '/textclicked">' . $this->route . '</a>';
    }

    public function set_settings($settings) {
        $this->settings = $settings;
    }
    public function get_settings() {
        return $this->settings;
    }
}