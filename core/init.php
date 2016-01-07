<?php
namespace Nix\Icms;

session_start();
spl_autoload_extensions(".php");
spl_autoload_register();
date_default_timezone_set('America/New_York');

require 'classes/iniParser.php';
require __DIR__.'/../vendor/autoload.php';
require 'Autoloader.php';

$container = new \Pimple\Container();
$container['parser'] = function ($c) {
    return new \iniParser(__DIR__.'/../core/configuration.php');
};

$settings = $container['parser']->parse();

require 'classes/users.php';
require 'classes/general.php';
require 'classes/bcrypt.php';
require 'classes/template.php';
require 'classes/permissions.php';
require 'classes/addons.php';
require "Macaw.php";
require __DIR__."/Router.php";
require "FrontController.php";
require "admincp/AdminController.php";
require __DIR__."/Route.php";

require "Database.php";


//$users        = new Users\Users($db);
//$pages        = new Pages\Pages($db);
$general    = new General\General();
$bcrypt    = new Bcrypt\Bcrypt(12);
$template    = new Template\Template();
//$permissions    = new \Nix\Icms\Permissions\permissions($db);
//$addon    = new Addons\Addons($db);



$errors = array();


ob_start();
