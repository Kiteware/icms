<?php if (count(get_included_files()) ==1) {
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
// config file
        $file = '../core/configuration.php';

// Writing to config file
        $data =
            "environment = production

[production]

site.name = \"" . $_POST['sitename'] . "\"
site.cwd = \"" . $_POST['cwd'] . "\"
site.url = \"" . $_POST['url'] . "\"
database.name = \"" . $_POST['dbname'] . "\"
database.user = \"" . $_POST['dbuser'] . "\"
database.password = \"" . $_POST['dbpassword'] . "\"
database.connection = \"" . $_POST['dbconnection'] . "\"
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
        header("Refresh:0");

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
<body>
<div id="content">
    <div class="box">
        <div class="box-header">Settings</div>
        <div class="box-body">
            <form  method="post" action="" name="post" enctype="multipart/form-data">
                <fieldset>
                    <h2 class="fs-title">Enter information about your website</h2>
                    <h3 class="fs-subtitle">Site Name : Site Location : URL </h3>
                    <input type="text" name="sitename" value="<?php echo $settings->production->site->name ?>" />
                    <input type="text" name="cwd" value="<?php echo $settings->production->site->cwd ?>" />
                    <input type="text" name="url" value="<?php echo $settings->production->site->url ?>" />
                </fieldset>
                <fieldset>
                    <h2 class="fs-title">Database</h2>
                    <h3 class="fs-subtitle">MySQL user/database information</h3>
                    <input type="text" name="dbconnection" value="<?php echo $settings->production->database->connection ?>" />
                    <input type="text" name="dbname" value="<?php echo $settings->production->database->name ?>" />
                    <input type="text" name="dbuser" value="<?php echo $settings->production->database->user?>" />
                    <input type="password" name="dbpassword" value="<?php echo $settings->production->database->password?>" />
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
</body>
