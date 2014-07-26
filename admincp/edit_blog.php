<div id="content">
  <div class="box">
    <div class="box-header">Admin Panel</div>
    <div class="box-body">
<?php
	/**************************************************************
	DELETE CONFIRMATION CHECK
	***************************************************************/ 
	if(isset($_POST['yes'])){ //if yes is submitted...
		$postID = $_POST['postID']; //get post id

		//echo confirmation if successful
		if($blog->delete_posts($postID)){
			echo 'Post has been successfully deleted.<br />';
		} else {
			echo 'Delete Failed.';
		}
	}


	/******************************************
	 ONCE $_GET HAS BEEN IS NOT EMPTY...
		$_GET['action'] = edit/delete
		$_GET['ID'] = id of selected post
	************************************************************/

	$check= !empty($_GET);
	if($check==true & !empty($_GET['action'])){

		$action = $_GET['action']; // gets action from url, edit or delete
		$ID = $_GET['ID']; //gets the post id from the url


		if($action == "delete"){
		
			$selectPost = $blog->get_post($ID);
			//Confirm with user they want to delete, if yes refresh and do isset['yes']
			echo ('Are you sure you want to permanently delete '.$selectPost['post_name'].'?
				<form action="edit_blog.php" method="post" name="post">
				<input name="postID" type="hidden" value="'.$selectPost['post_id'].'">
				<input name="yes" type="submit" value="Yes" />
				<input name="no" ONCLICK="history.go(-1)" type="button" value="No" />
				</form>');
		}

		else{
			if(isset($_POST['update'])){
				$postName = $_POST['postName'];
				$postPreview = $_POST['postPreview'];
				$postContent = $_POST['postContent'];
				$postID = $_POST['postID'];
					
				if($blog->update_post($postName, $postPreview, $postContent, $postID)) {	
					echo ('Post successfully updated! 
					Go back to <a href="edit_blog.php">
						Manage Posts</a>');
				  }
			}
				
		}

		$action = $_GET['action'];
		$ID = $_GET['ID']; 

		if($action == "edit"){
			$selectPost = $blog->get_post($ID);
			
			echo('<h2>Edit '.$selectPost['post_name'].'</h2>');
			echo($selectPost['post_content']);
		
		//form
		echo ('<form action="" method="post" name="post">
			<p>Name:<br />
			<input name="postName" type="text" size="45" value="'.$selectPost['post_name'].'"/>
			</p>

			<p>Preview:<br />
			<textarea name="postPreview" cols="100" rows="3">'
				.$selectPost['post_preview'].'</textarea>
			</p>

			<p>Content:<br />
			<textarea name="postContent" cols="100" rows="10">'
				.$selectPost['post_content'].'</textarea>
			</p>
			<p>Post ID:<br />
			<textarea name="postID" cols="50" rows="1">'
				.$ID.'</textarea>
			</p>

			<input name="update" type="submit" value="update"/>
		</form>');
		}
	}


	/****************************************
	DEFAULT PAGE (NO $_GET EXISTS YET)
	*****************************************/
	else {
		echo ('<h2> Manage Posts </h2>');
		$query = $blog->get_posts();
		foreach ($query as $showPost){
			//displaying posts
			echo ($showPost['post_name'].' 
			- <a href="?action=edit&ID='.$showPost['post_id'].'">Edit</a>
			- <a href="?action=delete&ID='.$showPost['post_id'].'">Delete</a>
			<br /><br />');
		}
	}	

?>
</div>
</div>
</div>
</html>