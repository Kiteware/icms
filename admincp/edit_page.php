<?php if(count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400); 
    exit('400: Bad Request'); 
    } ?>
<?php 
if (isset($_POST['submit'])) {
	if(empty($errors) === true){
		
		$url 	= htmlentities($_POST['url']);
		
		// configuration
		//$file = '../index.php';
		$file = '../'.$url.'.php';
		$text = file_get_contents($file);
		
		//exit();
	}
} else {
		$text = "";
	}
    
	$check= !empty($_GET);
	if($check==true & !empty($_GET['action'])){

		$action = $_GET['action']; // gets action from url, edit or delete
		$url = $_GET['url']; //gets the post id from the url
    
		if($action == "delete"){
 	      if($pages->delete_page($url) & $pages->delete_nav($url) & $permissions->delete_all_page_permissions($url)){
			echo 'Page has been successfully deleted.<br />';
		} else {
			echo 'Delete Failed.';
		}
		
		}
        }
?>
<script src="//cdn.ckeditor.com/4.4.3/standard/ckeditor.js"></script>    
<body>	
	<div id="content">
  <div class="box">
    <div class="box-header">Admin Panel</div>
    <div class="box-body">
		<h1>Edit Page</h1>
		
		<?php
		if (isset($_GET['success']) && empty($_GET['success'])) {
		  echo 'Page created.';
		}
		?>
		
		<?php
		
		$allpages = $pages->get_pages();
                 if (!empty($allpages)) {
            		foreach ($allpages as $showPage){
            			//displaying posts
            			echo ($showPage['title'].' - '.
                        $showPage['url'].' - '.
                        $showPage['constants'].' - '.
                        $showPage['content'].' - '.
                        $showPage['ip'].' - '.
                        $showPage['time'].'
            			- <a href="index.php?page=edit_page&action=edit&url='.$showPage['url'].'">Edit</a>
            			- <a href="index.php?page=edit_page&action=delete&url='.$showPage['url'].'">Delete</a>
            			<br /><br />');
            		}
              } else {
                echo ("No pages found.");
              }
		?>
		<!-- HTML form -->
		<form action="" method="post" name="post">
			<p>Name:<br />
			<input name="url" type="text" size="45" value="enter url"/>
			</p>
			<textarea name="text" id="editpage"><?php echo htmlspecialchars($text) ?></textarea>
			<input name="submit" type="submit" value="submit"/>
		</form>
		<?php 
		if(empty($errors) === false){
			echo '<p>' . implode('</p><p>', $errors) . '</p>';	
		}
		?>
	</div>
    </div>
    </div>
    <script type="text/javascript">
    	CKEDITOR.replace( 'editpage' );
    </script>
</body>
</html>