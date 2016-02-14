<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */

/*
|--------------------------------------------------------------------------
| Controller
|--------------------------------------------------------------------------
|
| Basic Controller Class - Called on /index.php
|
*/
class homeController {
    private $model;

    public function getName() {
        return 'Controller'; //In the real world this may well be get_class($this), and this method defined in a parent class.
    }

    public function __construct(homeModel $model) {
        $this->model = $model;
        $this->model->posts = $model->posts;

    }
}