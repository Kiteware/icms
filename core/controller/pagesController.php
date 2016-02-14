<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */

/*
|--------------------------------------------------------------------------
| Admin Pages Controller
|--------------------------------------------------------------------------
|
| Admin Pages Controller Class - Called on /admin/pages
|
*/
class pagesController {
    private $model;
    public $user_id;

    public function __construct(PagesModel $model) {
        $this->model = $model;
        $this->model->posts = $model->posts;
    }
}