<?php 
require 'core/init.php';
$general->logged_out_protect();

$username 	= htmlentities($user['username']); 
include("templates/default/head.php"); 
include("templates/default/header.php"); 

?>
<body>	
	<div id="container">
		<h1>Hello <?php echo $username, '!'; ?></h1>
	</div>
</body>
<?php include("templates/default/footer.php"); ?>
