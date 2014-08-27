<?php 
    require '../core/init.php';
    $general->logged_out_protect();
    if (isset($user['id']) && isset($user['usergroup']) && $permissions->has_access($user['id'], 'administrator', $user['usergroup'])) {
        include("../templates/admin/head.php"); 
        include("../templates/admin/topbar.php"); 
        include("../includes/admin_menu.php");
    
        $basePath      = "admincp/";
        if(isset($user['id'])) $userID = $user['id'];
        if(isset($user['usergroup'])) $usergroup = $user['usergroup'];
                
            if (isset($_GET['page'])) {
                $page        = $_GET['page'];
                if (substr($page, -4) == ".php") {
                    $page = substr($page, 0, -4);
                }
             
                        $dir=getcwd();
                        $files = scandir($dir);
                        if (in_array($page.".php", $files)) {
                            include $page.".php";
                        }  
            } else {
                   include "admin.php";
            }
    ?>
    <script type="text/javascript" src="../templates/admin/js/main.js"></script> 
    <?php 
    } else {
        echo "ACCESS DENIED";   
    }
?>