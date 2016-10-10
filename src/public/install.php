<?php
defined('_ICMS') or die;

require('../vendor/ircmaxell/password-compat/lib/password.php');

date_default_timezone_set('America/New_York');

/* Pre-Install Check */
if (!is_writable('../core/init.php')) {
    exit( "Could not write to configuration file. Common errors include permissions, and php session.save_path. Check the error logs for more information.");
}
if(!file_exists('../database.sql')) {
    exit( "Database file is missing.");
}

/* Generate Password Hash */
function genHash($password) {
    $hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
    return $hash;
}

/* .SQL Restore Function */
function getQueriesFromSQLFile($sqlfile) {
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

/* Test the connection to the database */
if (isset($_POST['db-check'])) {
    $dbHost = filter_input(INPUT_POST, 'db-host');
    $dbUser = filter_input(INPUT_POST, 'db-user');
    $dbPass = filter_input(INPUT_POST, 'db-password');
    $dbPort = filter_input(INPUT_POST, 'db-port');

    try {
        $dbh = new pdo( 'mysql:host='.$dbHost.';port='.$dbPort.';',
            $dbUser,
            $dbPass,
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        echo "Successfully connected!";
    } catch (PDOException $ex) {
        echo "Connection failed";
    }
    exit();
}

/* Form Submission */
if (isset($_POST['submit'])) {

    $failed = false;
    $errors[] = array();

    //Check if anything's empty
    foreach ($_POST as $key => $field) {
        if (strlen($field) < 0) {
            $failed = true;
            $errors[] = $key;
        }
    }

    $username = filter_input(INPUT_POST, 'user-name');
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $siteName = filter_input(INPUT_POST, 'site-name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $url = filter_input(INPUT_POST, 'url', FILTER_SANITIZE_URL);
    $dbHost = filter_input(INPUT_POST, 'db-host', FILTER_SANITIZE_URL);
    $dbName = filter_input(INPUT_POST, 'db-name');
    $dbUser = filter_input(INPUT_POST, 'db-user');
    $dbPass= filter_input(INPUT_POST, 'db-password');
    $dbPort = filter_input(INPUT_POST, 'db-port', FILTER_VALIDATE_INT);
    $fullName = filter_input(INPUT_POST, 'full-name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'password');


    // Form Validation
    $url_safe_chars = '/^[a-zA-Z0-9][a-zA-Z0-9\-\_\.\:\/]+$/';
    $safe_chars = '/^[a-zA-Z0-9-_]+$/';
    if (empty($email)) {
        $failed = true;
        $errors[] = 'Please enter a correctly formatted email';
    } elseif (empty($username) || !preg_match($safe_chars, $username)) {
        $failed = true;
        $errors[] = 'Invalid username.';
    } elseif (empty($description)) {
        $failed = true;
        $errors[] = 'Site description can\'t be empty.';
    } elseif (empty($siteName)) {
        $failed = true;
        $errors[] = 'Site Name can\'t be empty.';
    } elseif (empty($url) || !preg_match($url_safe_chars, $url)) {
        $failed = true;
        $errors[] = 'Please enter a valid URL.';
    } elseif (empty($dbHost) || !preg_match($url_safe_chars, $dbHost)) {
        $failed = true;
        $errors[] = 'Database host is invalid.';
    } elseif (empty($dbUser) || !preg_match($safe_chars, $dbUser)) {
        $failed = true;
        $errors[] = 'Database user is invalid.';
    } elseif (empty($dbPort)) {
        $failed = true;
        $errors[] = 'Incorrect database port.';
    } elseif (empty($fullName)) {
        $failed = true;
        $errors[] = 'Name field is empty.';
    } elseif (empty($email)) {
        $failed = true;
        $errors[] = 'Email field is empty.';
    } elseif (empty($password)) {
        $failed = true;
        $errors[] = 'Password field is empty.';
    }

    if ($failed) {
        echo '<div class="highlight"> 
        <p>Installation Filed <br />';
        if (empty($errors) === false) {
            echo "<pre>"; print_r($errors); echo "</pre>";
        }
        echo '</p></div>';
    } else {
        /**
         * The user can supply the root sql user.
         * This will allow ICMS to create the database and a new user on the fly.
         * The dbname must be blank and user must be root.
         */
        if($_POST['dbisroot'] === 'yes') {
            try {
                $conn = new pdo( 'mysql:host='.$dbHost.';port='.$dbPort.';',
                    $dbUser,
                    $dbPass
                );
                // set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $randNumber = substr(hash('sha512',rand()),3,8);
                if (!empty($_POST['dbname'])) {
                    $dbName = $_POST['dbname'];
                } else {
                    $dbName = "icms".$randNumber;
                }
                $dbUser = "icms".$randNumber;
                $dbPass = substr(hash('sha512',rand()),0,18);

                $sql = "DROP DATABASE IF EXISTS ".$dbName.";";
                $conn->exec($sql);
                $sql = "CREATE DATABASE IF NOT EXISTS ".$dbName." CHARACTER SET utf8 COLLATE utf8_general_ci;";
                $conn->exec($sql);
                $sql = 'GRANT ALL PRIVILEGES ON '.$dbName.'.* TO '.$dbUser.'@localhost IDENTIFIED BY "'.$dbPass.'"; 
                GRANT ALL PRIVILEGES ON '.$dbName.'.* TO '.$dbUser.'@"%" IDENTIFIED BY "'.$dbPass.'";';
                $conn->exec($sql);
                $sql = 'FLUSH PRIVILEGES;';
                $conn->exec($sql);
                $conn = null;
            }
            catch(PDOException $e) {
                exit( "Error creating database. " . $sql . "<br>" . $e->getMessage());

            }
        } else if (!empty($dbName)) {
            echo '5';
            $conn = new PDO("mysql:host=".$dbHost.";port=".$dbPort.";dbname=".$dbName.";", $dbUser, $dbPass);
            $conn->exec('SET foreign_key_checks = 0');

            $result = $conn->query("SHOW TABLES");
            $row = $result->fetch(PDO::FETCH_ASSOC);
            if (!empty($row)) {
                foreach ($row as $table) {
                    $conn->exec('DROP TABLE ' . $table);
                }
            }

            $conn->exec('SET foreign_key_checks = 1');
            $conn = null;
        } else {
            exit("Incorrect Database Credentials given");
        }

        try {
            $conn = new PDO("mysql:host=".$dbHost.";port=".$dbPort.";dbname=".$dbName.";", $dbUser, $dbPass);

            $queries = getQueriesFromSQLFile('../database.sql');

            foreach ($queries as $query) {
                try {
                    $conn->exec($query);
                } catch (Exception $e) {
                    echo $e->getMessage() . "<br /> <p>The" . $query . " </p>";
                    exit();
                }
            }

            $userGroup = "administrator";
            $confirmed = "1";
            $password_hash = genHash($password);

            $sql = "INSERT INTO `users` (username, full_name, email, password, usergroup, confirmed) 
                    VALUES (:username,:fullname,:email,:password,:usergroup,:confirmed)";
            $query = $conn->prepare($sql);
            $query->execute(array(':username' => $username,
                ':fullname' => $fullName,
                ':email' => $email,
                ':password' => $password_hash,
                ':usergroup' => $userGroup,
                ':confirmed' => $confirmed));
        } catch(PDOException $e) {
            exit( "Error filling up the database. <br>");
        }
        // config file
        $file = '../core/configuration.php';

        //Encryption is just a POC right now, still in development
        $secret_key = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");

        // Create the initialization vector for added security.
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $encrypted_string = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $secret_key, $dbPass, MCRYPT_MODE_CBC, $iv);
        $encrypted_string = $iv . $encrypted_string;

        // Writing to config file
        $data = "<?php 
environment = production 
[production] 
 
site.name = \"" . $siteName . "\" 
site.description = \"" . $description . "\" 
site.url = \"" . $url . "\" 
site.email = \"" . $email . "\" 
site.template = \"default\" 
site.language = \"EN-US\" 
site.analytics = \"\" 
database.name = \"" . $dbName . "\" 
database.user = \"" . $dbUser . "\" 
database.password = \"" . base64_encode($encrypted_string) . "\" 
database.host = \"" . $dbHost . "\" 
database.port = \"" . $dbPort . "\" 
email.host = \"smtp.gmail.com\" 
email.port = \"587\" 
email.auth = \"\" 
email.user = \"\" 
email.pass = \"\" 
email.clientid = \"\" 
email.clientsecret = \"\" 
email.refreshtoken = \"\" 
addons.mailchimpapi = \"\" 
addons.mailchimplistid = \"\" 
debug = \"false\"";

        // Write the configuration file
        if (!file_put_contents($file, $data)) {
            exit("The configuration file could not be created. Check File Permissions");
        }

        if(file_exists("../database.sql")) {
            unlink("../database.sql");
        }
        unlink(__FILE__);

        echo ' 
    <div class="cd-popup" role="alert"> 
      <div class="cd-popup-container"> 
        <p>Installation was successful!</p> 
        <ul class="cd-buttons"> 
          <li><a href="https://github.com/Nixhatter/CMS/blob/master/README.md" >Read the Doc</a></li> 
          <li><a href="/">Go to Site</a></li> 
        </ul> 
      </div> 
    </div>';
    }
}
?>
<!-- Form Template by Atakan: http://codepen.io/atakan/pen/gqbIz -->
<html>
<head>
    <style>
        * {margin: 0; padding: 0;}
        html {
            height: 100%;
            background: #303030;
        }
        .highlight {
            background: #ffecec;
            border-radius:5px;
            border:1px solid #e74c3c;
            color: #555;
            font-weight:500;
            padding: 5px;
        }
        /*form styles*/
        #installer {
            width: 600px;
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
            display:inline-block;
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
            background: #2C3E50;
        }
        /*headings*/
        h1 {
            font-size: 15px;
            text-transform: uppercase;
            color: #1d60a4;
            margin-bottom: 10px;
        }
        h2 {
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
        label {
            display: inline-block;
            text-align: right;
        }​
        @media only screen and (min-width: 1170px) {
            .cd-popup-container {
                margin: 8em auto;
            }
        }
    </style>
</head>
<body>
<form id="installer"  method="post" action="">
    <!-- progressbar -->
    <ul id="progressbar">
        <li class="active">Installer</li>
        <li>Database</li>
        <li>Create User</li>
    </ul>
    <fieldset>
        <h1>Enter information about your website</h1>
        <h2>
            <?php
            echo "PHP Version: ";
            if (version_compare(phpversion(), '5.4.0', '<')) {
                echo "<b>Error, please upgrade! </b>";
            } else {
                echo "Compatible";
            }
            echo "<br /> Is config file writeable: ";
            $configuration = '../core/configuration.php';
            if (!is_writable(dirname($configuration))) {
                echo dirname($configuration) . ' must be writable';
            } else {
                echo "Yes";
            }
            ?>
        </h2>
        <input type="text" name="site-name" placeholder="Site Name" class="required" />
        <input type="text" name="description" placeholder="Site Description" class="required" />
        <input type="text" name="url" value="<?php echo "$_SERVER[HTTP_HOST]" ?>" class="required" />
        <input type="button" name="next" class="next action-button" value="Next" />
    </fieldset>
    <fieldset>
        <h1>Database</h1>
        <h2><div id="message">You can either specify the usual details, or provide the root SQL user/pass and we will create the database/user for you.</div></h2>
        <input type="text" name="db-host" value="localhost" class="required" />
        <input type="text" name="db-port" value="3306" class="required" />
        <input type="text" name="db-name" placeholder="Database Name (optional)" />
        <label>Generate the SQL user/table? (root user must be provided)</label><input type="checkbox" name="dbisroot" value="yes">
        <input type="text" name="db-user" placeholder="Database User" class="required" />
        <input type="password" name="db-password" placeholder="Password" class="required" />
        <input type="button" name="previous" class="previous action-button" value="Previous" />
        <input type="button" id="databaseButton" name="db-check" class="action-button" onclick="JavaScript:dbConnection();" value="Connect" />
        <input type="button" id="nextButton" name="next" class="next action-button" value="Next" style="display:none" />
    </fieldset>
    <fieldset>
        <h1>Create Admin User</h1>
        <h2>Write this down!</h2>
        <input type="text" name="user-name" placeholder="Username" class="required" />
        <input type="text" name="full-name" placeholder="Full Name" class="required" />
        <input type="text" name="email" placeholder="Email" class="required" />
        <input type="password" name="password" placeholder="Password" class="required" />
        <input type="button" name="previous" class="previous action-button" value="Previous" />
        <input type="submit" name="submit" class="submit action-button" value="Submit" />
    </fieldset>
</form>

<!-- jQuery --><script src="/templates/admin/js/jquery-2.2.4.min.js"></script>
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
    });

    function dbConnection()
    {
        var host = $("#installer").find('input[name="db-host"]').val();
        var dbport = $("#installer").find('input[name="db-port"]').val();
        var username = $("#installer").find('input[name="db-user"]').val();
        var password = $("#installer").find('input[name="db-password"]').val();
        var dbname = $("#installer").find('input[name="db-name"]').val();
        // you can check the validity of username and password here
        $.post('',{'db-check':'', 'db-user':username, 'db-password':password, 'db-host':host, 'db-name':dbname, 'db-port':dbport},
            function (data) {
                $("#message").html(data);
                if (data == "Successfully connected!") {
                    document.getElementById("databaseButton").style.display="none";
                    document.getElementById("nextButton").style.display="inline";
                    $("#nextButton").click();
                } else if (data == "Connection failed") {
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

    $("#isntaller").submit(function(){
        var isFormValid = true;

        $(".required input").each(function(){
            if ($.trim($(this).val()).length == 0){
                $(this).addClass("highlight");
                isFormValid = false;
            }
            else{
                $(this).removeClass("highlight");
            }
        });

        if (!isFormValid) alert("Please fill in all the required fields (indicated by *)");

        return isFormValid;
    });

    $(document).ready(function() {
        $(window).keydown(function(event){
            if(event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });
    });

</script>
</body>
</html>
<?php exit(); ?>