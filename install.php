<?php 
if (isset($_POST['delete']) && $_POST['delete'] == 'yes') {
    unlink(__FILE__);
    unlink("cms.sql");
    header("Location: index.php");
    exit;
}
if (isset($_POST['submit'])) {
    foreach($_POST as $key => $field) {
        // You can check if it is empty using foreach alone
        if (strlen($field) > 0) {
            // this field is set
        } else {
            echo $key." not set. ";
        }
    }
    $file = 'core/configuration.ini';

    // Append a new person to the file
    $data = 
"environment = production

[production]

site.name = \"". $_POST['sitename'] ."\"
site.cwd = \"". $_POST['cwd'] ."\"
site.url = \"". $_POST['url'] ."\"
database.name = \"". $_POST['dbname'] ."\"
database.user = \"". $_POST['dbuser'] ."\"
database.password = \"". $_POST['dbpassword'] ."\"
database.connection = \"". $_POST['dbconnection'] ."\"
debug = \"false\"";
    // Write the contents back to the file
    file_put_contents($file, $data);
    // configuration
    $dbtype		= "sqlite";
    $dbhost 	= $_POST['dbconnection'];
    $dbname		= $_POST['dbname'];
    $dbuser		= $_POST['dbuser'];
    $dbpass		= $_POST['dbpassword'];
    // Name of the file
    $filename = 'cms.sql';
    
    // Connect to MySQL server
    $connection = mysqli_connect($dbhost, $dbuser, $dbpass) or die('Error connecting to MySQL server: ' . mysqli_error($connection));
    // Select database
    mysqli_select_db($connection, $dbname) or die('Error selecting MySQL database: ' . mysqli_error($connection));
    
    // Temporary variable, used to store current query
    $templine = '';
    // Read in entire file
    $lines = file($filename);
    // Loop through each line
    foreach ($lines as $line)
    {
    // Skip it if it's a comment
    if (substr($line, 0, 2) == '--' || $line == '')
        continue;
    
    // Add this line to the current segment
    $templine .= $line;
    // If it has a semicolon at the end, it's the end of the query
    if (substr(trim($line), -1, 1) == ';')
    {
        // Perform the query
        mysqli_query($connection, $templine) or print('Error performing query \'<strong>' . $templine . '\': ' . mysqli_error($connection) . '<br /><br />');
        // Reset temp variable to empty
        $templine = '';
    }
    }
     echo "Tables imported successfully";
    
    // database connection
    $conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbuser,$dbpass);
    
    // new data
    $username =  $_POST['username'];
    $fullname =  $_POST['fullname'];
    $email =  $_POST['email'];
    $password =  $_POST['password'];
    $usergroup = "administrator";
    
    // query
    $sql = "INSERT INTO `users` (username, full_name, email, password, usergroup) VALUES (:username,:fullname,:email,:password,:usergroup)";
    $query = $conn->prepare($sql);
    $query->execute(array(':username'=>$username,
                        ':fullname'=>$fullname,
                        ':email'=>$email,
                        ':password'=>$password,
                        ':usergroup'=>$usergroup));
    

    echo '';
    
    echo '
    <div class="cd-popup" role="alert">
    	<div class="cd-popup-container">
    		<p>Installation was succesful! Please delete this install file!</p>
    		<ul class="cd-buttons">
    			<li><a href="#" onclick="deleteInstall();" >Delete Install</a></li>
    			<li><a href="index.php">Go to Site</a></li>
    		</ul>
    	</div> <!-- cd-popup-container -->
    </div> <!-- cd-popup -->';
}
if (isset($_POST['dbcheck'])) {
    $dbhost 	= $_POST['dbconnection'];
    $dbname		= $_POST['dbname'];
    $dbuser		= $_POST['dbuser'];
    $dbpass		= $_POST['dbpassword'];
    $mysqlImportFilename ='cms.sql';
    try{
        $dbh = new pdo( 'mysql:host='.$dbhost.';dbname='.$dbname,
                        $dbuser,
                        $dbpass,
                        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        
        //echo json_encode(array('outcome' => true, 'message' => 'Connection Succesfull'));
        echo "success";
    }
    catch(PDOException $ex){
        //echo json_encode(array('outcome' => false, 'message' => 'Unable to connect'));
        echo "fail";
    }
    exit();
}
?>
<html>
<head>
<style>
/*basic reset*/
* {margin: 0; padding: 0;}

html {
	height: 100%;
	/*background = gradient + image pattern combo*/
	background: #23272D;
}

body {
	font-family: arial, verdana;
}
/*form styles*/
#msform {
	width: 400px;
	margin: 50px auto;
	text-align: center;
	position: relative;
}
#msform fieldset {
	background: white;
	border: 0 none;
	border-radius: 3px;
	box-shadow: 0 0 15px 1px rgba(0, 0, 0, 0.4);
	padding: 20px 30px;
	
	box-sizing: border-box;
	width: 80%;
	margin: 0 10%;
	
	/*stacking fieldsets above each other*/
	position: absolute;
}
/*Hide all except first fieldset*/
#msform fieldset:not(:first-of-type) {
	display: none;
}
/*inputs*/
#msform input, #msform textarea {
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
#msform .action-button {
	width: 100px;
	background: #4576D1;
	font-weight: bold;
	color: white;
	border: 0 none;
	border-radius: 1px;
	cursor: pointer;
	padding: 10px 5px;
	margin: 10px 5px;
}
#msform .action-button:hover, #msform .action-button:focus {
	box-shadow: 0 0 0 2px white, 0 0 0 3px #4576D1;
}
/*headings*/
.fs-title {
	font-size: 15px;
	text-transform: uppercase;
	color: #4576D1;
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
	background: #4576D1;
	color: white;
}
/* -------------------------------- 

xnugget info 

-------------------------------- */
.cd-nugget-info {
  text-align: center;
  position: absolute;
  width: 100%;
  height: 50px;
  line-height: 50px;
  bottom: 0;
  left: 0;
}
.cd-nugget-info a {
  position: relative;
  font-size: 14px;
  color: #5e6e8d;
  -webkit-transition: all 0.2s;
  -moz-transition: all 0.2s;
  transition: all 0.2s;
}
.no-touch .cd-nugget-info a:hover {
  opacity: .8;
}
.cd-nugget-info span {
  vertical-align: middle;
  display: inline-block;
}
.cd-nugget-info span svg {
  display: block;
}
.cd-nugget-info .cd-nugget-info-arrow {
  fill: #5e6e8d;
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
@media only screen and (min-width: 1170px) {
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
.cd-popup-container .cd-popup-close::before {
  -webkit-transform: rotate(45deg);
  -moz-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  -o-transform: rotate(45deg);
  transform: rotate(45deg);
  left: 8px;
}
.cd-popup-container .cd-popup-close::after {
  -webkit-transform: rotate(-45deg);
  -moz-transform: rotate(-45deg);
  -ms-transform: rotate(-45deg);
  -o-transform: rotate(-45deg);
  transform: rotate(-45deg);
  right: 8px;
}
.is-visible .cd-popup-container {
  -webkit-transform: translateY(0);
  -moz-transform: translateY(0);
  -ms-transform: translateY(0);
  -o-transform: translateY(0);
  transform: translateY(0);
}
@media only screen and (min-width: 1170px) {
  .cd-popup-container {
    margin: 8em auto;
  }
}
</style>
</head>
<body>
<br />

<form id="msform"  method="post" action="install.php" name="post" enctype="multipart/form-data">
	<!-- progressbar -->
	<ul id="progressbar">
		<li class="active">Installer</li>
		<li>Database</li>
		<li>Create User</li>
	</ul>
	<!-- fieldsets -->
	<fieldset>
		<h2 class="fs-title">Enter information about your website</h2>
		<h3 class="fs-subtitle">Site Name : Site Location : URL </h3>
		<input type="text" name="sitename" placeholder="Site Name" />
		<input type="text" name="cwd" placeholder="<?php echo getcwd(); ?>" />
		<input type="text" name="url" placeholder="http://url.com" />
		<input type="button" name="next" class="next action-button" value="Next" />
	</fieldset>
	<fieldset>
		<h2 class="fs-title">Database</h2>
		<h3 class="fs-subtitle"><div id="message"></div></div></h3>
        <input type="text" name="dbconnection" placeholder="localhost" />
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
var $form = $("#msform")

$(".next").click(function(){
    if($('#message').text() != "fail") {
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
    		step: function(now, mx) {
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
    		complete: function(){
    			current_fs.hide();
    			animating = false;
    		}, 
    		//this comes from the custom easing plugin
    		easing: 'easeInOutBack'
    	});
     }
});

$(".previous").click(function(){
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
		step: function(now, mx) {
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
		complete: function(){
			current_fs.hide();
			animating = false;
		}, 
		//this comes from the custom easing plugin
		easing: 'easeInOutBack'
	});
});
$(".submit").click(function(){
	//return false;
})
function deleteInstall()
{
    window.location.href = "index.php";
    $.post( "install.php", { delete: "yes"} );
}
function dbConnection() {
    var connection = $("#msform").find('input[name="dbconnection"]').val();  
    var username = $("#msform").find('input[name="dbuser"]').val();  
    var password = $("#msform").find('input[name="dbpassword"]').val();  
    var dbname = $("#msform").find('input[name="dbname"]').val();  
    // you can check the validity of username and password here
    $.post("",{dbcheck:"yes", dbuser:username, dbpassword:password, dbconnection:connection, dbname:dbname},        
    function(data) {
        $("#message").append(data);
        if(data == "success") {
            document.getElementById("databaseButton").style.display="none";
            document.getElementById("nextButton").style.display="inline";
        } else if (data == "fail") {
        }
    });
}
function checkIfEmpty(inputArea) {
    var arrayLength = inputArea.length;
    allFilled = new Boolean(true);
    for (var i = 0; i < arrayLength; i++) { 
        var input = document.getElementsByName(inputArea[i])[0].value;
        if(input.length == 0) {
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