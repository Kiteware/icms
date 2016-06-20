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

require ("vendor/autoload.php");
try {
    $parser = new \IniParser('core/configuration.php');
} catch (Exception $e) {
    // Could not load config file, die
    die();
}if($parser->parse()["production"]["debug"] === "true") {
    error_reporting(-1);
    ini_set('display_errors', 'On');
}

$_SESSION['i18n'] = "en";

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
ob_start();