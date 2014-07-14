<?php 
require '../core/init.php';
//$general->logged_in_protect();

if (isset($_POST['submit'])) {
	if(empty($errors) === true){
		
		//exit();
	}
} else {
		$url = $template->getCurrentTemplatePath();
		$file = '../'.$url.'index.php';
		$text = file_get_contents($file);
		
}

?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="css/style.css" >
	<title>Edit Template</title>
</head>
<body>	
	<div id="container">
		<?php include '../includes/admin_menu.php'; ?>
		<h1>Edit Template</h1>
		
		<?php
		if (isset($_GET['success']) && empty($_GET['success'])) {
		  echo 'Page created.';
		}
		?>		
		
		<!-- HTML form -->
		<form action="" method="post" name="post">
			<p>Name:<br />
			<input name="url" type="text" size="45" value="enter url"/>
			</p>
			<p>
			<textarea name="text" ><?php echo htmlspecialchars($text) ?></textarea>
			</p>
			<input name="submit" type="submit" value="submit"/>
		</form>
		<?php 
		if(empty($errors) === false){
			echo '<p>' . implode('</p><p>', $errors) . '</p>';	
		}
		?>
	</div>
</body>
</html>

