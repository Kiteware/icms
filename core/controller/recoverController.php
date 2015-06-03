<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */

/*
|--------------------------------------------------------------------------
| Recover Controller
|--------------------------------------------------------------------------
|
| Recover Controller Class - Called on /Recover
|
*/
class RecoverController extends Controller{
    private $model;

    public function __construct(UserModel $model) {
        $this->model = $model;
        if(isset($_SESSION['id'])) {
            header ("Location: /");
        }
    }
}