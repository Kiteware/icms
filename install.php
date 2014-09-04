<?php 
if (isset($_POST['delete']) && $_POST['delete'] == 'yes') {
    unlink(__FILE__);
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

site.name = ". $_POST['sitename'] ."
site.cwd = ". $_POST['cwd'] ."
site.url = ". $_POST['url'] ."
database.name = ". $_POST['dbname'] ."
database.user = ". $_POST['dbuser'] ."
database.password = ". $_POST['dbpassword'] ."
database.connection = ". $_POST['dbconnection'] ."
debug = false";
    // Write the contents back to the file
    file_put_contents($file, $data);
    // configuration
    $dbtype		= "sqlite";
    $dbhost 	= $_POST['dbconnection'];
    $dbname		= $_POST['dbname'];
    $dbuser		= $_POST['dbuser'];
    $dbpass		= $_POST['dbpassword'];
    $mysqlImportFilename ='cms.sql';
    
    // database connection
    $conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbuser,$dbpass);

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    // query
    $sql = "/*
SQLyog Ultimate v11.42 (64 bit)
MySQL - 10.0.13-MariaDB : Database - cms
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`cms` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `cms`;

/*Table structure for table `Settings` */

DROP TABLE IF EXISTS `Settings`;

CREATE TABLE `Settings` (
  `usergroups` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `Settings` */

/*Table structure for table `navigation` */

DROP TABLE IF EXISTS `navigation`;

CREATE TABLE `navigation` (
  `nav_name` char(15) NOT NULL,
  `nav_link` varchar(30) NOT NULL,
  `nav_position` int(2) unsigned NOT NULL,
  `nav_permission` int(2) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `navigation` */

insert  into `navigation`(`nav_name`,`nav_link`,`nav_position`,`nav_permission`) values ('Home','index.php',1,1),('Admin','admincp/index.php',5,5);

/*Table structure for table `pages` */

DROP TABLE IF EXISTS `pages`;

CREATE TABLE `pages` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `url` varchar(15) NOT NULL,
  `constants` text NOT NULL,
  `content` text NOT NULL,
  `ip` varchar(15) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pages` */

/*Table structure for table `permissions` */

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `userID` int(11) DEFAULT NULL,
  `pageName` varchar(20) DEFAULT NULL,
  `usergroupID` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `permissions` */

insert  into `permissions`(`userID`,`pageName`,`usergroupID`) values (NULL,'login','guest'),(NULL,'register','guest'),(NULL,'confirm-recover','guest'),(NULL,'change-password','user'),(NULL,'settings','user'),(NULL,'administrator','administrator'),(NULL,'logout','guest');

/*Table structure for table `posts` */

DROP TABLE IF EXISTS `posts`;

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_name` varchar(100) NOT NULL,
  `post_preview` text NOT NULL,
  `post_content` text NOT NULL,
  `post_date` datetime NOT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Data for the table `posts` */

insert  into `posts`(`post_id`,`post_name`,`post_preview`,`post_content`,`post_date`) values (6,'Hello World','Lorem ipsum dolor sit amet, per ea nusquam eligendi offendit. Duo cu menandri urbanitas. Cu unum consetetur cum. Has at sale singulis democritum. Cu velit interpretaris mei. Ut gubergren disputationi conclusionemque eum, ei discere accusam pertinacia pro. Per ad paulo iracundia.','Lorem ipsum dolor sit amet, per ea nusquam eligendi offendit. Duo cu menandri urbanitas. Cu unum consetetur cum. Has at sale singulis democritum. Cu velit interpretaris mei. Ut gubergren disputationi conclusionemque eum, ei discere accusam pertinacia pro. Per ad paulo iracundia.\r\n\r\nAccusata facilisis vix ne, sit at nonumy melius. Inani altera quidam te per, cu nec veniam tractatos democritum, agam movet ne nam. Phaedrum dignissim persecuti vix no, vel id ferri verear incorrupte, porro reformidans no qui. Harum laboramus ex mea, no mea evertitur consequuntur. Pro ne illum scripta postulant, usu equidem repudiandae ei, nobis adolescens inciderint has et. Menandri comprehensam no ius, duo ad tale simul.\r\n\r\nDico tempor gloriatur mei at. Mutat partem consetetur at duo. Porro salutandi abhorreant qui eu, eu pri saepe dicunt concludaturque, iriure regione ex mei. Pro ea dicat mnesarchum, nec vitae dolore tacimates ex, quo dolore perfecto posidonium an. Natum error nam no, an quo clita ceteros, ridens lucilius deterruisset vis at. Scripta integre eos in, mea dico summo consequat at, ad augue atomorum consequat mea. Ad essent laoreet est.\r\n\r\nAffert perfecto no mel, ex sit falli nostrum dignissim, ad nec mentitum complectitur. Nisl mundi nullam mei at, legere repudiare mei ea, pro in verterem quaestio conclusionemque. Quod nonumes antiopam eu est, consul adipisci eloquentiam qui cu. Semper noluisse apeirian usu ad. Sonet propriae invenire usu ne, consul tacimates pertinax id vis. Sit ut copiosae eloquentiam definitionem, cu electram eloquentiam mei, ancillae evertitur eu sea.\r\n\r\nTe qui possit invenire definitiones, facilisi efficiantur intellegebat id vim, suas solum deterruisset vel cu. Exerci nullam qualisque eu vim. Dico porro viderer eam ex, te per ignota cetero. Cu vis graece democritum, ei mel unum dicunt, volumus noluisse repudiare eu mel.','2014-07-30 14:20:59'),(7,'test','','<h1>Minimalist Online Markdown Editor</h1>\r\n\r\n<p>This is the <strong>simplest</strong> and <strong>slickest</strong> online Markdown editor.  </p>\r\n\r\n<h2>Getting started</h2>\r\n\r\n<h3>How?</h3>\r\n\r\n<p>Just start typing in the left panel.</p>\r\n\r\n<h3>Buttons you might want to use</h3>\r\n\r\n<ul>\r\n<li><strong>Quick Reference</strong>: that\'s a reminder of the most basic rules of Markdown</li>\r\n<li><strong>HTML | Preview</strong>: <em>HTML</em> to see the markup generated from your Markdown text, <em>Preview</em> to see how it looks like</li>\r\n</ul>\r\n\r\n<h3>Privacy</h3>\r\n\r\n<ul>\r\n<li>No data is sent to any server � everything you type stays in your browser</li>\r\n<li>The editor automatically saves what you write locally in case you accidentally close it. <br />\r\nIf using a public computer, either empty the left panel before leaving the editor or use your browser\'s privacy mode</li>\r\n</ul>','2014-08-08 12:13:29');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(18) NOT NULL,
  `full_name` varchar(32) NOT NULL,
  `gender` varchar(15) NOT NULL DEFAULT 'undisclosed',
  `bio` text NOT NULL,
  `image_location` varchar(125) NOT NULL DEFAULT 'avatars/default_avatar.png',
  `password` varchar(512) NOT NULL,
  `email` varchar(1024) NOT NULL,
  `email_code` varchar(100) NOT NULL,
  `time` int(11) NOT NULL,
  `confirmed` int(11) NOT NULL DEFAULT '0',
  `generated_string` varchar(35) NOT NULL DEFAULT '0',
  `ip` varchar(32) NOT NULL,
  `permission` int(2) NOT NULL DEFAULT '0',
  `usergroup` varchar(20) DEFAULT 'user',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `users` */

insert  into `users`(`id`,`username`,`full_name`,`gender`,`bio`,`image_location`,`password`,`email`,`email_code`,`time`,`confirmed`,`generated_string`,`ip`,`permission`,`usergroup`) values (1,'user','Dillon','male','','avatars/default_avatar.png','$2y$12$5976570425302383fd200OMWk2I.17wrOMh5McG9fmdV8X2KA3Mra','user@nixx.co','code_5302383fd1ff02.02414527',1392654399,1,'0','127.0.0.1',0,'administrator');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;";
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    $query = $conn->prepare($sql);
    $query->execute();
    
    // new data
    $username =  $_POST['username'];
    $fullname =  $_POST['fullname'];
    $email =  $_POST['email'];
    $password =  $_POST['password'];
    
    // query
    $sql = "INSERT INTO `users` (username, full_name, email, password) VALUES (:username,:fullname,:email,:password)";
    $query = $conn->prepare($sql);
    $query->execute(array(':username'=>$username,
                        ':fullname'=>$fullname,
                        ':email'=>$email,
                        ':password'=>$password));
    

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

xpopup 

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
<!-- multistep form -->
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

<!-- jQuery -->
<script src="http://thecodeplayer.com/uploads/js/jquery-1.9.1.min.js" type="text/javascript"></script>
<!-- jQuery easing plugin -->
<script src="http://thecodeplayer.com/uploads/js/jquery.easing.min.js" type="text/javascript"></script>
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