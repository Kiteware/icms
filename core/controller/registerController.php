<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */

/*
|--------------------------------------------------------------------------
| Register Controller
|--------------------------------------------------------------------------
|
| Register Controller Class - Called on /Register
|
*/
class RegisterController extends Controller{
    private $model;

    public function __construct(UserModel $model) {
        $this->model = $model;
        if(isset($_SESSION['id'])) {
            header ("Location: /");
        }
    }
}