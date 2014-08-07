<?php 
//$general->logged_in_protect();

if (isset($_POST['submit'])) {

	if(empty($_POST['title']) || empty($_POST['url']) || empty($_POST['content'])){

		$errors[] = 'All fields are required.';

	}

	if(empty($errors) === true){
		
		$title 	= htmlentities($_POST['title']);
		$url 	= htmlentities($_POST['url']);
		$content 	= htmlentities($_POST['content']);
        $permission = htmlentities($_POST['permission']);
        $position = htmlentities($_POST['position']);

		$pages->create_Post($title, $url, $content);
		$pageArray = $pages->fetch_Page("title, content", "url", $url);
		//print_r($pageArray);
		$pages->generate_page($pageArray['title'], $url ,$pageArray['content']);
		$pages->create_nav($title, $url, $permission, $position);
	}
}
?>
<body>	
<div id="content">
  <div class="box">
    <div class="box-header">Admin Panel</div>
    <div class="box-body">
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
            <h4>Position:</h4>
			<input type="text" name="position" value="<?php if(isset($_POST['position'])) echo htmlentities($_POST['position']); ?>"/>	
            <h4>Permission:</h4>
			<input type="text" name="permission" value="<?php if(isset($_POST['permission'])) echo htmlentities($_POST['permission']); ?>"/>	
			<h4>Content:</h4>
			<input type="text" name="content" value="<?php if(isset($_POST['content'])) echo htmlentities($_POST['content']); ?>"/>	
			<br />
			<input type="submit" name="submit" />
		</form>

		<?php 
		if(empty($errors) === false){
			echo '<p>' . implode('</p><p>', $errors) . '</p>';	
		}

		?>
    </div>
    </div>
	</div>
</body>
</html>

