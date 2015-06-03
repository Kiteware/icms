<?php
    if (isset($user['id']) && isset($user['usergroup']) && $permissions->has_access($user['id'], 'administrator', $user['usergroup'])) {
        $_SESSION['token'] = microtime();
        if (defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
            $salt = '$2y$11$' . substr(md5(uniqid(mt_rand(), true)), 0, 22);
            $hashed = crypt($_SESSION['token'], $salt);
        }

        include "../templates/admin/head.php";
        include "../templates/admin/topbar.php";
        include "../includes/admin_menu.php";

        $basePath      = "admincp/";
        if(isset($user['id'])) $userID = $user['id'];
        if(isset($user['usergroup'])) $usergroup = $user['usergroup'];

            if (isset($_GET['page'])) {
                $page        = $_GET['page'];
                if (substr($page, -4) == ".php") {
                    $page = substr($page, 0, -4);
                }
                        $files = $settings->production->site->admin;
                        if (in_array($page.".php", $files)) {
                            include $page.".php";
                        }
            } else {
                   include "admin.php";
            }
    ?>
        <script type="text/javascript" src="../templates/admin/js/bottom.js"></script>
    <?php
    } else {
        echo "ACCESS DENIED";
    }
?>
