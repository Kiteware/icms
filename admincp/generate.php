<?php if(count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400); 
    exit('400: Bad Request'); 
    } ?>
<?php 
require '../core/init.php';
//$general->logged_in_protect();

if (isset($_POST['submit'])) {

	if(empty($errors) === true){
		
		$title 	= htmlentities($_POST['title']);
		$url 	= htmlentities($_POST['url']);
		$content 		= htmlentities($_POST['content']);

		$pages->generate_page($url);
		header('Location: create_page.php?success');
		exit();
	}
}

?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="css/style.css" >
	<title>Generate</title>
</head>
<body>	
	<div id="container">
		<?php include '../includes/menu.php'; ?>
		<h1>Create Page</h1>
		
		<?php
		if (isset($_GET['success']) && empty($_GET['success'])) {
		  echo 'Page created.';
		}
		?>

		<form method="post" action="">
			<h4>Title:</h4>
			<input type="text" name="title" value="<?php if(isset($_POST['title'])) echo htmlentities($_POST['title']); ?>" >
			<h4>URL:</h4>
			<input type="text" name="url" value="<?php if(isset($_POST['url'])) echo htmlentities($_POST['url']); ?>"/>	
			<h4>Content:</h4>
			<input type="text" name="content" value="<?php if(isset($_POST['content'])) echo htmlentities($_POST['content']); ?>"/>	
			<br>
			<input type="submit" name="submit" />
		</form>

		<?php 
		if(empty($errors) === false){
			echo '<p>' . implode('</p><p>', $errors) . '</p>';	
		}

		?>

	</div>
</body>
</html>

