<?php
require 'core/init.php';
include("templates/default/head.php");
include("templates/default/header.php");
include("templates/default/menu.php");

$general->logged_in_protect();
$userID         ="";
$usergroup      ="";
if(isset($user['id'])) $userID = $user['id'];
if(isset($user['usergroup'])) $usergroup = $user['usergroup'];
if (isset($_GET['page'])) {
    $page        = $_GET['page'];
    //$page       = preg_replace('/^[a-zA-Z0-9]+(-[a-zA-Z0-9]+)*$/i', '', $page);

    if (substr($page, -4) == ".php") {
        $page = substr($page, 0, -4);
    }
    if ($permissions->has_access("", $page, "guest") or $permissions->user_access($userID, $page) or $permissions->has_access($userID, $page, $usergroup)) {
        $dir=getcwd();
        $files = scandir($dir);
        $pages = scandir($dir."/pages/");
        $addon_page = $addon->get_addon_location($page);
        if (in_array($page.".php", $files)) {
            include $page.".php";
        } else if (in_array($page.".php", $pages)){
            include "pages/".$page.".php";
        } else if ($addon_page != null) {
            include "$addon_page";
        }
        else {
            header("HTTP/1.0 400 Bad Request", true, 400);
            exit('page cannot be found');
        }
    } else {
        echo "ACCESS DENIED";
    }
} else {
    include "pages/home.php";
}

?>
<?php include("templates/default/footer.php"); ?>
