<?php
namespace Nix\Icms;

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
spl_autoload_register();
date_default_timezone_set('America/New_York');

// Load the sites configuration file
require 'classes/iniParser.php';
// Load Composer files
require __DIR__.'/../vendor/autoload.php';

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
require __DIR__."/Router.php";
require "FrontController.php";
require "admincp/AdminController.php";
//require __DIR__."/Route.php";


$errors = array();

class MyAutoloader
{
    public static function load($className)
    {
        require  $className . '.php';
    }
}

spl_autoload_register(__NAMESPACE__ . "\\MyAutoloader::load");

$route = new Route();
require "Database.php";

ob_start();
