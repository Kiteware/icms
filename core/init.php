<?php
/*
|--------------------------------------------------------------------------
| Init
|--------------------------------------------------------------------------
|
| We load all common dependencies used throughout ICMS
|
*/
date_default_timezone_set('America/Toronto');

// Redirect everyone to the non www version
if (substr($_SERVER['HTTP_HOST'], 0, 4) === 'www.') {
    header("HTTP/1.1 301 Moved Permanently");
    header('Location: http'.(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 's':'').'://' . substr($_SERVER['HTTP_HOST'], 4).$_SERVER['REQUEST_URI']);
    exit;
}
session_set_cookie_params(3600,"/");
session_start();

require ("vendor/autoload.php");

spl_autoload_register(function ($class) {

    // project-specific namespace prefix
    $prefix = 'Nixhatter\\ICMS\\';

    // base directory for the namespace prefix
    $base_dir = __DIR__ . '/';

    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($class, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});


$_SESSION['i18n'] = "en";

ob_start();