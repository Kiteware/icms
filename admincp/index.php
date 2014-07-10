<?php
require '../core/init.php';
include("../templates/default/head.php"); 
?>

<body>	
	<div id="container">
		<?php include '../includes/admin_menu.php'; ?>
		<h1>Admin Panel</h1>
		
		<?php 
		if(empty($errors) === false){
			echo '<p>' . implode('</p><p>', $errors) . '</p>';	
		}

		?>

	</div>
</body>
<?php include("../templates/default/footer.php"); ?>
