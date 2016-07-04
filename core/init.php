<?php
/*
|--------------------------------------------------------------------------
| Init
|--------------------------------------------------------------------------
|
| We load all common dependencies used throughout ICMS
|
*/

defined('_ICMS') or die;

date_default_timezone_set('America/Toronto');

// Redirect everyone to the non www version
if (substr($_SERVER['HTTP_HOST'], 0, 4) === 'www.') {
    header("HTTP/1.1 301 Moved Permanently");
    header('Location: http'.(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 's':'').'://' . substr($_SERVER['HTTP_HOST'], 4).$_SERVER['REQUEST_URI']);
    exit;
}
// session name has a big performance hit
session_name("CMSID");
session_set_cookie_params(36000,"/");
session_start();

require ("autoload.php");
require ("../vendor/autoload.php");

$_SESSION['i18n'] = "en";

ob_start();