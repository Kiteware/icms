<?php 
    require '../core/init.php';
    include("../templates/admin/head.php"); 
    include("../templates/admin/topbar.php"); 
    include '../includes/admin_menu.php';

    $basePath      = "admincp/";
        
        if (isset($_GET['page'])) {
            $page        = $_GET['page'];
            $dir=getcwd();
            $files = scandir($dir);
            if (substr($page, -4) == ".php") {
                $page = substr($page, 0, -4);
            }
            if (in_array($page.".php", $files)) {
                include $page.".php";
            }  
        } else {
               include "admin.php";
        } 
        
?>
<?php include("../templates/default/footer.php"); ?>
<script type="text/javascript" src="../templates/admin/js/main.js"></script> 