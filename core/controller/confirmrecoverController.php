<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */

/*
|--------------------------------------------------------------------------
| ConfirmRecover Controller
|--------------------------------------------------------------------------
|
| ConfirmRecover Controller Class - Called on /ConfirmRecover
|
*/
class ConfirmRecoverController extends Controller{
    private $model;

    public function __construct(UserModel $model) {
        $this->model = $model;
        if(isset($_SESSION['id'])) {
            header ("Location: /");
        }
    }
}