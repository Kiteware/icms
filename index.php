<?php 
require 'core/init.php';
include("templates/default/head.php"); 
include("templates/default/header.php"); 
    
    $general->logged_in_protect();
    $basePath      = "";
    $userID = isset($user['id']);
        
        if (isset($_GET['page'])) {
            $page        = $_GET['page'];
            if (substr($page, -4) == ".php") {
                $page = substr($page, 0, -4);
            }
                if ($permissions->has_access($userID, $page, "0") or $permissions->has_access("3", $page, "guest") ) {
                    $dir=getcwd();
                    $files = scandir($dir);
                    if (in_array($page.".php", $files)) {
                        include $page.".php";
                    }  
            } else {
                echo "ACCESS DENIED";   
            }
        } else {
               include "home.php";
        } 
        
?>
<?php include("templates/default/footer.php"); ?>
