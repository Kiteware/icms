<?php
session_start();
require 'classes/iniParser.php';
$parser = new \iniParser(__DIR__.'/../core/configuration.php');
$settings = $parser->parse();

require 'connect/database.php';
require 'classes/users.php';
require 'classes/general.php';
require 'classes/bcrypt.php';
require 'classes/blog.php';
require 'classes/pages.php';
require 'classes/template.php';
require 'classes/permissions.php';
require 'classes/addons.php';

// error_reporting(0);
$users        = new Users($db);
$blog        = new Blog($db);
$pages        = new Pages($db);
$general    = new General();
$bcrypt    = new Bcrypt(12);
$template    = new Template();
$permissions    = new Permissions($db);
$addon    = new Addons($db);

$errors = array();

if ($general->logged_in() === true) {
    $user_id    = $_SESSION['id'];
    $user        = $users->userdata($user_id);

}

ob_start();
