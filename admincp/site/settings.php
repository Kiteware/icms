<?php
if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
}
if (isset($_POST['submit'])) {
    $failed = false;
    $failedArray = array();
//if any field is empty
    foreach ($_POST as $key => $field) {
        if (strlen($field) > 0) {
            // this field is set
        } else {
            $failed = true;
            $failedArray[] = $key;
        }
    }
    if ($failed == True) {
        echo '<div class="highlight">
    		<p>Update failed <br />
    		These fields need to be filled: <br />';
        foreach ($failedArray as $fail) {
            echo $fail . " ";
        }
        echo '</p></div>';

    } else {
        //Encryption is just a POC right now, still in development
        $secret_key = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");

        // Create the initialization vector for added security.
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $encrypted_string = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $secret_key, $_POST['dbpassword'], MCRYPT_MODE_CBC, $iv);
        $encrypted_string = $iv . $encrypted_string;
        // config file
        $file = '../core/configuration.php';

        // Writing to config file
        $data =
            "environment = production

[production]

site.name = \"" . $_POST['sitename'] . "\"
site.cwd = \"" . $_POST['cwd'] . "\"
site.url = \"" . $_POST['url'] . "\"
site.email = \"" . $_POST['email'] . "\"
site.template = \"default\"
site.core = \"[activate.php, addons, admincp, blog.php, change-password.php, cms.sql, confirm-recover.php, core, images, includes, index.php, install.php, login.php, logout.php, members.php, pages, profile.php, recover.php, register.php, search.php, settings.php]\"
site.page = [home.php]
site.admin = \"[admin.php, edit_blog.php, edit_menu.php, edit.php, edit_permissions.php, edit_user.php, generate.php, index.php, new_blog.php, create.php, new_user.php, settings.php, template.php]\"
site.version = \"0.4.1\"
database.name = \"" . $_POST['dbname'] . "\"
database.user = \"" . $_POST['dbuser'] . "\"
database.password = \"" . base64_encode($encrypted_string) . "\"
database.host = \"" . $_POST['dbhost'] . "\"
database.port = \"" . $_POST['dbport'] . "\"
debug = \"false\"";

        // Write the contents back to the file
        if (!file_put_contents($file, $data)) {
            echo "The configuration file could not be created. Check Permissions";
            if (file_exists($temp_name))
                unlink($temp_name);
            exit;
        }

        $dbhost    = $_POST['dbconnection'];
        $dbname        = $_POST['dbname'];
        $dbuser        = $_POST['dbuser'];
        $dbpass        = $_POST['dbpassword'];
        try {
            $dbh = new pdo( 'mysql:host='.$dbhost.';dbname='.$dbname,
                $dbuser,
                $dbpass,
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            echo("<script> successAlert();</script>");
        } catch (PDOException $ex) {
            echo "fail";
        }
        echo("<script> successAlert();</script>");
    }
} elseif (isset($_POST['cwd'])) {
    //Scan Root folder
    $files = scandir("../");
    $pages = scandir("../pages/");
    //Scan Admin folder
    $dir=getcwd();
    $admin = scandir($dir);


    $lines = file('../core/configuration.php');
    $result = '';

    foreach ($admin as $key=>&$value) {
        if (strlen($value) < 3) {
            unset($admin[$key]);
        }
    }

    foreach($lines as $line) {
        if(substr($line, 0, 9) == 'site.core') {
            $result .= "site.core = [".implode (", ", $files)."]\n";
        } elseif (substr($line, 0, 10) == 'site.pages'){
            $result .= "site.pages = [".implode (", ", $pages)."]\n";
        } elseif (substr($line, 0, 10) == 'site.admin') {
            $result .= "site.admin = [".implode (", ", $admin)."]\n";
        } else {
            $result .= $line;
        }
    }
    //echo $result;
    file_put_contents('../core/configuration.php', $result);
    echo("<script> successAlert();</script>");
}
?>
<div id="content">
    <div class="box">
        <div class="box-header">Settings</div>
        <div class="box-body">
            <strong>Warning - Saving incorrect settings can break your website!</strong>
            <form  method="post" action="" name="post" enctype="multipart/form-data">
                <fieldset>
                    <h2 class="fs-title">Enter information about your website</h2>
                    <h3 class="fs-subtitle">Site Name : Site Location : URL : email</h3>
                    <input type="text" name="sitename" value="<?php echo $this->settings->production->site->name ?>" />
                    <input type="text" name="cwd" value="<?php echo $this->settings->production->site->cwd ?>" />
                    <input type="text" name="url" value="<?php echo $this->settings->production->site->url ?>" />
                    <input type="text" name="email" value="<?php echo $this->settings->production->site->email ?>" />
                </fieldset>
                <fieldset>
                    <h2 class="fs-title">Database</h2>
                    <h3 class="fs-subtitle">MySQL user/database information</h3>
                    <input type="text" name="dbhost" value="<?php echo $this->settings->production->database->connection ?>" />
                    <input type="text" name="dbname" value="<?php echo $this->settings->production->database->name ?>" />
                    <input type="text" name="dbuser" value="<?php echo $this->settings->production->database->user?>" />
                    <input type="password" name="dbpassword" value="<?php echo $this->settings->production->database->password?>" />
                </fieldset>
                <br />
                <input type="submit" name="submit" class="submit action-button" value="Submit" />
            </form>
            <br />
            <form  method="post" action="" name="post" enctype="multipart/form-data">
                <input type="submit" name="cwd" class="submit" value="Scan Working Directory" />
            </form>

        </div>
    </div>
</div>
