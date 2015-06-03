<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */

/*
|--------------------------------------------------------------------------
| Activate Controller
|--------------------------------------------------------------------------
|
| Activate Controller Class - Called on /Activate
|
*/
class ActivateController extends Controller{
    private $model;

    public function __construct(UserModel $model) {
        $this->model = $model;
        if(isset($_SESSION['id'])) {
            header ("Location: /");
        }
    }
}