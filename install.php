<?php
session_start();
date_default_timezone_set('America/New_York');
/**if (!file_put_contents('/var/www/core/configuration.php', "test")) {
    echo "Write permissions failed, please check them!";
    exit;
}**/
if (isset($_POST['delete']) && $_POST['delete'] == 'yes') {
    unlink(__FILE__);
    unlink("cms.sql");
    header("Location: index.php");
    exit;
}
if (isset($_POST['submit'])) {
    // VALIDATION
    $failed = false;
    $errors[] = array();
    //Check if anything's empty
    foreach ($_POST as $key => $field) {
        if (strlen($field) < 0) {
            $failed = true;
            $errors[] = $key;
        }
    }
    //email validation
    if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
        $failed = true;
        $errors[] = 'Please enter a correctly formatted email';
    } elseif (!ctype_alnum($_POST['username'])) {
        $failed = true;
        $errors[] = 'Please enter a username with only alphabets and numbers';
    } elseif (!ctype_alnum($_POST['sitename'])) {
        $failed = true;
        $errors[] = 'Please enter a site name with only alphabets and numbers';
    } elseif (filter_var($_POST['url'], FILTER_VALIDATE_URL) ===  false) {
        $failed = true;
        $errors[] = 'Please enter a valid URL';
    }
    if ($failed == True) {
        echo '<div class="highlight">
    		<p>Installation failed <br />
    		These fields need to be filled: <br />';
        if (empty($errors) === false) {
            echo '<p>' . implode('</p><p>', $errors) . '</p>';
        }
        echo '</p></div>';

    } else {
        // configuration
        $dbhost = $_POST['dbconnection'];
        $dbuser = $_POST['dbuser'];
        $dbpass = $_POST['dbpassword'];
        $dbport = $_POST['dbport'];

        if(isset($_POST['dbname'])) {
          try {
              $conn = new pdo( 'mysql:host='.$dbhost.';port='.$dbport.';',
                $dbuser,
                $dbpass
              );
              // set the PDO error mode to exception
              $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

              $dbname = "icms";
              $dbuser = "icmsuser";
              $dbpass = substr(hash('sha512',rand()),0,18);

              $sql = "DROP DATABASE IF EXISTS ".$dbname.";";
              $conn->exec($sql);
              $sql = "CREATE DATABASE IF NOT EXISTS ".$dbname.";";
              $conn->exec($sql);
              $sql = 'GRANT ALL PRIVILEGES ON icms.* TO '.$dbuser.'@localhost IDENTIFIED BY "'.$dbpass.'";
                GRANT ALL PRIVILEGES ON icms.* TO '.$dbuser.'@"%" IDENTIFIED BY "'.$dbpass.'";';
              $conn->exec($sql);
              $sql = 'FLUSH PRIVILEGES;';
              $conn->exec($sql);
              $conn = null;
              echo "Database created successfully<br>";
            }
          catch(PDOException $e) {
              echo "Error creating database. " . $sql . "<br>" . $e->getMessage();
              exit();
            }

        } else {
          $dbname = $_POST['dbname'];
        }
        try {
        // database connection
        $conn = new PDO("mysql:host=".$dbhost.";port=".$dbport.";dbname=".$dbname.";", $dbuser, $dbpass);
        $conn->exec('SET foreign_key_checks = 0');

        // Code to delete all data from a database
        // No longer needed because we just drop the table
        // But we'll keep it here because it really does no harm
        /** $result = $conn->query("SHOW TABLES");
        $row = $result->fetch(PDO::FETCH_ASSOC);
        if (!empty($row)) {
            foreach ($row as $table) {
                $conn->exec('DROP TABLE ' . $table);
            }
        }
        **/
        $conn->exec('SET foreign_key_checks = 1');

        // Name of the file
        $filename = 'cms.sql';

        $queries = getQueriesFromSQLFile($filename);

        foreach ($queries as $query) {
            try {
                $conn->exec($query);
            } catch (Exception $e) {
                echo $e->getMessage() . "<br /> <p>The" . $query . " </p>";
                exit();
            }
        }
        require 'core/model/UserModel.php';
        $bcrypt = new UserModel($conn);

        // new data
        $username = $_POST['username'];
        $fullname = $_POST['fullname'];
$email = $_POST['email'];
        $password = $_POST['password'];
        $usergroup = "administrator";
        $confirmed = "1";

        $password_hash = $bcrypt->genHash($password);

        // query
        $sql = "INSERT INTO `users` (username, full_name, email, password, usergroup, confirmed) VALUES (:username,:fullname,:email,:password,:usergroup,:confirmed)";
        $query = $conn->prepare($sql);
        $query->execute(array(':username' => $username,
            ':fullname' => $fullname,
            ':email' => $email,
            ':password' => $password_hash,
            ':usergroup' => $usergroup,
            ':confirmed' => $confirmed));
          } catch(PDOException $e) {
              echo "Error filling up the database. <br>" . $e->getMessage();
              exit();
          }

          // config file
          $file = 'core/configuration.php';

          //Encryption is just a POC right now, still in development
          $secret_key = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");

          // Create the initialization vector for added security.
          $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
          $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
          $encrypted_string = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $secret_key, $dbpass, MCRYPT_MODE_CBC, $iv);
          $encrypted_string = $iv . $encrypted_string;

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
site.pages = \"[home.php]\"
site.admin = \"[admin.php, edit_blog.php, edit_menu.php, edit_page.php, edit_permissions.php, edit_user.php, generate.php, index.php, new_blog.php, new_page.php, new_user.php, settings.php, template.php]\"
site.version = \"0.4.1\"
database.name = \"" . $dbname . "\"
database.user = \"" . $dbuser . "\"
database.password = \"" . base64_encode($encrypted_string) . "\"
database.host = \"" . $dbhost . "\"
database.port = \"" . $dbport . "\"
debug = \"false\"";

          // Write the contents back to the file
          if (!file_put_contents($file, $data)) {
              echo "The configuration file could not be created. Check Permissions";
              if (file_exists($temp_name))
                  unlink($temp_name);
              exit;
          }

        echo '
    <div class="cd-popup" role="alert">
    	<div class="cd-popup-container">
    		<p>Installation was successful! Please delete this install file!</p>
    		<ul class="cd-buttons">
    			<li><a href="#" onclick="deleteInstall();" >Delete Install</a></li>
    			<li><a href="index.php">Go to Site</a></li>
    		</ul>
    	</div>
    </div>';
    }
}
if (isset($_POST['dbcheck'])) {
    $dbhost     = $_POST['dbconnection'];
    //$dbname     = $_POST['dbname'];
    $dbuser     = $_POST['dbuser'];
    $dbpass     = $_POST['dbpassword'];
    $dbport     = $_POST['dbport'];
    try {
        $dbh = new pdo( 'mysql:host='.$dbhost.';port='.$dbport.';',
            $dbuser,
            $dbpass,
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        echo "success";
    } catch (PDOException $ex) {
        echo "fail";
    }
    exit();
}
function getQueriesFromSQLFile($sqlfile)
{
    if (is_readable($sqlfile) === false) {
        throw new Exception($sqlfile . 'does not exist or is not readable.');
    }
    # read file into array
    $file = file($sqlfile);

    # import file line by line
    # and filter (remove) those lines, beginning with an sql comment token
    $file = array_filter($file,
        create_function('$line',
            'return strpos(ltrim($line), "--") !== 0;'));

    # and filter (remove) those lines, beginning with an sql notes token
    $file = array_filter($file,
        create_function('$line',
            'return strpos(ltrim($line), "/*") !== 0;'));

    # this is a whitelist of SQL commands, which are allowed to follow a semicolon
    $keywords = array(
        'ALTER', 'CREATE', 'DELETE', 'DROP', 'INSERT',
        'REPLACE', 'SELECT', 'SET', 'TRUNCATE', 'UPDATE', 'USE'
    );

    # create the regular expression for matching the whitelisted keywords
    $regexp = sprintf('/\s*;\s*(?=(%s)\b)/s', implode('|', $keywords));

    # split there
    $splitter = preg_split($regexp, implode("\r\n", $file));

    # remove trailing semicolon or whitespaces
    $splitter = array_map(create_function('$line',
            'return preg_replace("/[\s;]*$/", "", $line);'),
        $splitter);

    # remove empty lines

    return array_filter($splitter, create_function('$line', 'return !empty($line);'));
}
?>
<html>
<head>
<style>
* {margin: 0; padding: 0;}
html {
    height: 100%;
    background: #303030;
    font-family: arial, verdana;
}
.highlight {
    color: red;
    font-weight:700;
}
/*form styles*/
#installer {
    width: 400px;
    margin: 50px auto;
    text-align: center;
    position: relative;
}
#installer fieldset {
    background: white;
    border: 0 none;
    border-radius: 3px;
    box-shadow: 0 0 15px 1px rgba(0, 0, 0, 0.4);
    padding: 20px 30px;

    box-sizing: border-box;
    width: 80%;
    margin: 0 10%;

    /*stacking above each other*/
    position: absolute;
}
/*Hide all except first fieldset*/
#installer fieldset:not(:first-of-type) {
    display: none;
}
/*inputs*/
#installer input, #installer textarea {
    padding: 15px;
    border: 1px solid #ccc;
    border-radius: 3px;
    margin-bottom: 10px;
    width: 100%;
    box-sizing: border-box;
    color: #2C3E50;
    font-size: 13px;
}
/*buttons*/
#installer .action-button {
    width: 100px;
    background: #1d60a4;
    font-weight: bold;
    color: white;
    border: 0 none;
    border-radius: 1px;
    cursor: pointer;
    padding: 10px 5px;
    margin: 10px 5px;
}
#installer .action-button:hover, #installer .action-button:focus {
    box-shadow: 0 0 0 2px white, 0 0 0 3px #1d60a4;
}
/*headings*/
.fs-title {
    font-size: 15px;
    text-transform: uppercase;
    color: #1d60a4;
    margin-bottom: 10px;
}
.fs-subtitle {
    font-weight: normal;
    font-size: 13px;
    color: #666;
    margin-bottom: 20px;
}
/*progressbar*/
#progressbar {
    margin-bottom: 30px;
    overflow: hidden;
    /*CSS counters to number the steps*/
    counter-reset: step;
}
#progressbar li {
    list-style-type: none;
    color: white;
    text-transform: uppercase;
    font-size: 9px;
    width: 33.33%;
    float: left;
    position: relative;
}
#progressbar li:before {
    content: counter(step);
    counter-increment: step;
    width: 20px;
    line-height: 20px;
    display: block;
    font-size: 10px;
    color: #333;
    background: white;
    border-radius: 3px;
    margin: 0 auto 5px auto;
}
/*progressbar connectors*/
#progressbar li:after {
    content: '';
    width: 100%;
    height: 2px;
    background: white;
    position: absolute;
    left: -50%;
    top: 9px;
    z-index: -1; /*put it behind the numbers*/
}
#progressbar li:first-child:after {
    /*connector not needed before the first step*/
    content: none;
}
/*marking active/completed steps green*/
/*The number of the step and the connector before it = green*/
#progressbar li.active:before,  #progressbar li.active:after{
    background: #1d60a4;
    color: white;
}
/* --------------------------------
Main components
-------------------------------- */
header {
    height: 200px;
    line-height: 200px;
    text-align: center;
    background-color: #5e6e8d;
    color: #FFF;
}
header h1 {
    font-size: 20px;
    font-size: 1.25rem;
}

.cd-popup-trigger {
    display: block;
    width: 170px;
    height: 50px;
    line-height: 50px;
    margin: 3em auto;
    text-align: center;
    color: #FFF;
    font-size: 14px;
    font-size: 0.875rem;
    font-weight: bold;
    text-transform: uppercase;
    border-radius: 50em;
    background: #35a785;
    box-shadow: 0 3px 0 rgba(0, 0, 0, 0.07);
}
@media only screen and (min-width: 1000px) {
    .cd-popup-trigger {
        margin: 6em auto;
    }
}

/* --------------------------------
popup
-------------------------------- */
.cd-popup {
    position: fixed;
    left: 0;
    top: 0;
    height: 100%;
    width: 100%;
    background-color: rgba(94, 110, 141, 0.9);
    opacity: 1;
    visibility: visible;
    z-index:10;
    -webkit-transition: opacity 0.3s 0s, visibility 0s 0.3s;
    -moz-transition: opacity 0.3s 0s, visibility 0s 0.3s;
    transition: opacity 0.3s 0s, visibility 0s 0.3s;
}
.cd-popup-container {
    position: relative;
    width: 90%;
    max-width: 400px;
    margin: 4em auto;
    background: #FFF;
    border-radius: .25em .25em .4em .4em;
    text-align: center;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
    -webkit-transform: translateY(-40px);
    -moz-transform: translateY(-40px);
    -ms-transform: translateY(-40px);
    -o-transform: translateY(-40px);
    transform: translateY(-40px);
    /* Force Hardware Acceleration in WebKit */
    -webkit-backface-visibility: hidden;
    -webkit-transition-property: -webkit-transform;
    -moz-transition-property: -moz-transform;
    transition-property: transform;
    -webkit-transition-duration: 0.3s;
    -moz-transition-duration: 0.3s;
    transition-duration: 0.3s;
}
.cd-popup-container p {
    padding: 3em 1em;
}
.cd-popup-container .cd-buttons:after {
    content: "";
    display: table;
    clear: both;
}
.cd-popup-container .cd-buttons li {
    float: left;
    width: 50%;
    list-style: none;
}
.cd-popup-container .cd-buttons a {
    display: block;
    height: 60px;
    line-height: 60px;
    text-transform: uppercase;
    color: #FFF;
    -webkit-transition: background-color 0.2s;
    -moz-transition: background-color 0.2s;
    transition: background-color 0.2s;
}
.cd-popup-container .cd-buttons li:first-child a {
    background: #fc7169;
    border-radius: 0 0 0 .25em;
}
.no-touch .cd-popup-container .cd-buttons li:first-child a:hover {
    background-color: #fc8982;
}
.cd-popup-container .cd-buttons li:last-child a {
    background: #b6bece;
    border-radius: 0 0 .25em 0;
}
.no-touch .cd-popup-container .cd-buttons li:last-child a:hover {
    background-color: #c5ccd8;
}
.cd-popup-container .cd-popup-close {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 30px;
    height: 30px;
}
.cd-popup-container .cd-popup-close::before, .cd-popup-container .cd-popup-close::after {
    content: '';
    position: absolute;
    top: 12px;
    width: 14px;
    height: 3px;
    background-color: #8f9cb5;
}
@media only screen and (min-width: 1170px) {
    .cd-popup-container {
        margin: 8em auto;
    }
}
</style>
</head>
<body>
<form id="installer"  method="post" action="install.php" name="post" enctype="multipart/form-data">
    <!-- progressbar -->
    <ul id="progressbar">
        <li class="active">Installer</li>
        <li>Database</li>
        <li>Create User</li>
    </ul>
    <!-- fieldsets -->
    <fieldset>
        <h2 class="fs-title">Enter information about your website</h2>
        <h3 class="fs-subtitle">
            <?php
            echo "PHP Version: ";
            if (version_compare(phpversion(), '5.4.0', '<')) {
                echo "<b>Error, please upgrade! </b>";
            } else {
                echo "Compatible";
            }
            echo "<br /> Is config file writeable: ";
            $configuration = 'core/configuration.php';
            if (!is_writable(dirname($configuration))) {
                echo dirname($configuration) . ' must be writable';
            } else {
                echo "Success";
            }
            ?>
        </h3>
        <input type="text" name="sitename" placeholder="Site Name" />
        <input type="text" name="cwd" value="<?php echo getcwd(); ?>" />
        <input type="text" name="url" value="<?php echo "http://$_SERVER[HTTP_HOST]" ?>" />
        <input type="button" name="next" class="next action-button" value="Next" />
    </fieldset>
    <fieldset>
        <h2 class="fs-title">Database</h2>
        <h3 class="fs-subtitle"><div id="message"></div></h3>
        <input type="text" name="dbconnection" value="localhost" />
        <input type="text" name="dbport" value="3306" />
        <input type="text" name="dbname" placeholder="Database Name" />
        <input type="text" name="dbuser" placeholder="Database User" />
        <input type="password" name="dbpassword" placeholder="Password" />
        <input type="button" name="previous" class="previous action-button" value="Previous" />
        <input type="button" id="databaseButton" name="dbCheck" class="action-button" onclick="JavaScript:dbConnection();" value="Connect" />
        <input type="button" id="nextButton" name="next" class="next action-button" value="Next" style="display:none" />
    </fieldset>
    <fieldset>
        <h2 class="fs-title">Create Admin User</h2>
        <h3 class="fs-subtitle">Write this down!</h3>
        <input type="text" name="username" placeholder="Username" />
        <input type="text" name="fullname" placeholder="Full Name" />
        <input type="text" name="email" placeholder="Email" />
        <input type="password" name="password" placeholder="Password" />
        <input type="button" name="previous" class="previous action-button" value="Previous" />
        <input type="submit" name="submit" class="submit action-button" value="Submit" />
    </fieldset>
</form>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js" type="text/javascript"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js" type="text/javascript"></script>
<script>
    //jQuery time
    var current_fs, next_fs, previous_fs; //fieldsets
    var left, opacity, scale; //fieldset properties which we will animate
    var animating; //flag to prevent quick multi-click glitches
    var $form = $("#installer")

    $(".next").click(function () {
        if ($('#message').text() != "fail") {
            if(animating) return false;
            animating = true;

            current_fs = $(this).parent();
            next_fs = $(this).parent().next();

            //activate next step on progressbar using the index of next_fs
            $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

            //show the next fieldset
            next_fs.show();
            //hide the current fieldset with style
            current_fs.animate({opacity: 0}, {
                step: function (now, mx) {
                    //as the opacity of current_fs reduces to 0 - stored in "now"
                    //1. scale current_fs down to 80%
                    scale = 1 - (1 - now) * 0.2;
                    //2. bring next_fs from the right(50%)
                    left = (now * 50)+"%";
                    //3. increase opacity of next_fs to 1 as it moves in
                    opacity = 1 - now;
                    current_fs.css({'transform': 'scale('+scale+')'});
                    next_fs.css({'left': left, 'opacity': opacity});
                },
                duration: 800,
                complete: function () {
                    current_fs.hide();
                    animating = false;
                },
                //this comes from the custom easing plugin
                easing: 'easeInOutBack'
            });
        }
    });

    $(".previous").click(function () {
        if(animating) return false;
        animating = true;

        current_fs = $(this).parent();
        previous_fs = $(this).parent().prev();

        //de-activate current step on progressbar
        $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

        //show the previous fieldset
        previous_fs.show();
        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
            step: function (now, mx) {
                //as the opacity of current_fs reduces to 0 - stored in "now"
                //1. scale previous_fs from 80% to 100%
                scale = 0.8 + (1 - now) * 0.2;
                //2. take current_fs to the right(50%) - from 0%
                left = ((1-now) * 50)+"%";
                //3. increase opacity of previous_fs to 1 as it moves in
                opacity = 1 - now;
                current_fs.css({'left': left});
                previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity});
            },
            duration: 800,
            complete: function () {
                current_fs.hide();
                animating = false;
            },
            //this comes from the custom easing plugin
            easing: 'easeInOutBack'
        });
    });
    $(".submit").click(function () {
        //return false;
    })
    function deleteInstall()
    {
        window.location.href = "/";
        $.post( "install.php", { delete: "yes"} );
    }
    function dbConnection()
    {
        var connection = $("#installer").find('input[name="dbconnection"]').val();
        var dbport = $("#installer").find('input[name="dbport"]').val();
        var username = $("#installer").find('input[name="dbuser"]').val();
        var password = $("#installer").find('input[name="dbpassword"]').val();
        var dbname = $("#installer").find('input[name="dbname"]').val();
        // you can check the validity of username and password here
        $.post("",{dbcheck:"yes", dbuser:username, dbpassword:password, dbconnection:connection, dbname:dbname, dbport:dbport},
            function (data) {
                $("#message").html(data);
                if (data == "success") {
                    document.getElementById("databaseButton").style.display="none";
                    document.getElementById("nextButton").style.display="inline";
                    $("#nextButton").click();
                } else if (data == "fail") {
                    $("#message").addClass('highlight');
                    setTimeout(function () {
                        $('#message').removeClass('highlight');}, 2000);
                }
            });
    }
    function checkIfEmpty(inputArea)
    {
        var arrayLength = inputArea.length;
        allFilled = new Boolean(true);
        for (var i = 0; i < arrayLength; i++) {
            var input = document.getElementsByName(inputArea[i])[0].value;
            if (input.length == 0) {
                input = "Empty";
                allFilled = Boolean(false);
            }
        }
        if (!allFilled) {
            alert("all good");
        }
    }
</script>
</body>
</html>
