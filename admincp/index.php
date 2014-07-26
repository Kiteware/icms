<?php 
    require '../core/init.php';
    include("../templates/admin/head.php"); 
    include("../templates/admin/topbar.php"); 
    include '../includes/admin_menu.php';

    $controller    = "controller";
    $action        = "";
    $params        = array();
    $basePath      = "admincp/";
    
    $path = trim($_SERVER["REQUEST_URI"], "/");
    //echo $path;
    //$path = preg_replace('~?[^a-zA-Z0-9]+~', "", $path);
    //echo $path;
     
        if (strpos($path, $basePath) === 0) {
            $path = substr($path, strlen($basePath));
        }
        @list($controller, $action, $params) = explode("?", $path, 3);
        if (isset($controller)) {
           //do something
        }
        
        if (isset($action)) {
            echo $action;
            $dir=getcwd();
            $files = scandir($dir);
            if (substr($action, -4) == ".php") {
                $action = substr($action, 0, -4);
            }
            if (in_array($action.".php", $files)) {
                include $action.".php";
            }  
        } else {
                include "admin.php";
        } 
        
        if (isset($params)) {
            $this->setParams(explode("/", $params));
        }
?>
<?php include("../templates/default/footer.php"); ?>
<script type="text/javascript" src="../templates/admin/js/main.js"></script> 