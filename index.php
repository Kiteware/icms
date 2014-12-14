<?php
require "core/init.php";

$template = $settings->production->site->template;
include "templates/".$template."/head.php";
include "templates/".$template."/header.php";
include "templates/".$template."/menu.php";

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
        $addon_page = $addon->get_addon_location($page);
        $core = $settings->production->site->core;
        $pages = $settings->production->site->pages;
        if (in_array($page.".php", $core)) {
            include $page.".php";
        } elseif (in_array($page.".php", $pages)) {
            include "pages/".$page.".php";
        } elseif ($addon_page != null) {
            include "$addon_page";
        } else {
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
<?php include "templates/default/footer.php"; ?>
