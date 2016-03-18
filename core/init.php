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
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), 'core', 'core/model', 'admincp')));
spl_autoload_register();
date_default_timezone_set('America/New_York');

require "vendor/autoload.php";

ob_start();