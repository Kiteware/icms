<?php
/*
|--------------------------------------------------------------------------
| Init
|--------------------------------------------------------------------------
|
| We load all common dependencies used throughout ICMS
|
*/
session_start();
spl_autoload_extensions(".php");
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), './core', './core/model', './core/classes')));
spl_autoload_register();
date_default_timezone_set('America/New_York');

// Load Composer files
require __DIR__.'/../vendor/autoload.php';
require "admincp/AdminController.php";
require "Database.php";

$container = new \Pimple\Container();
$container['parser'] = function ($c) {
    return new \iniParser(__DIR__.'/../core/configuration.php');
};

ob_start();
