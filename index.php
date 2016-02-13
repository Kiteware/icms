<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package  Icms
 * @author   Dillon Aykac
 */

require_once __DIR__."/core/init.php";

$g_model = $g_controller = $g_action = $g_id = null;
$klein = new \Klein\Klein();

// URL Format /admin/controller/action
// example:   /admin/blog/create

$klein->respond('/[:model]?/[:controller]?/[:action]?/[:id]?', function ($request) {
    global $g_model;
    global $g_controller;
    global $g_action;
    global $g_id;
    $g_model = $request->model;
    $g_controller = $request->controller;
    $g_action = $request->action;
    $g_id = $request->id;

    if(strcmp ($g_model, "admin") == 0) {
        $frontController = new AdminController(new Router, $g_controller, $g_action, $g_id);
    } else {
        $frontController = new FrontController(new Router, $g_model, $g_controller, $g_action, $g_id);
    }
    echo $frontController->output();
});
$klein->respond('/',function ($request) {
    global $g_controller;
    global $g_action;
    global $g_id;
    global $g_model;
    $g_model    = "home";
    $g_controller = "home";
    $frontController = new FrontController(new Router, $g_model, $g_controller, $g_action, $g_id);
    echo $frontController->output();
});

$klein->dispatch();
