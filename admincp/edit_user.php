<?php if(count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400); 
    exit('400: Bad Request'); 
    } ?>
<?php 
//$general->logged_out_protect();
$members 		=$users->get_users();
$member_count 	= count($members);

?>
<body>	
<div id="content">
  <div class="box">
    <div class="box-header">Admin Panel</div>
    <div class="box-body">
		<h1>Edit Users</h1>
		<p>We have a total of <strong><?php echo $member_count; ?></strong> registered users. </p>

<?php


/**************************************************************
	DELETE CONFIRMATION CHECK
	***************************************************************/ 
	if(isset($_POST['yes'])){ //if yes is submitted...
		$ID = $_POST['id']; //get post id

		//echo confirmation if successful
		if($user->delete_user($ID) &  $permissions->delete_all_user_permissions($ID)){
			echo 'User has been successfully deleted.<br />';
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
		
			$selectUser = $users->get_users($ID);
			//Confirm with user they want to delete, if yes refresh and do isset['yes']
			echo ('Are you sure you want to permanently delete '.$selectUser['post_name'].'?
				<form action="edit_blog.php" method="post" name="post">
				<input name="postID" type="hidden" value="'.$selectUser['post_id'].'">
				<input name="yes" type="submit" value="Yes" />
				<input name="no" ONCLICK="history.go(-1)" type="button" value="No" />
				</form>');
		}

		else{
			if(isset($_POST['update'])){
				$first_name = $_POST['first_name'];
				$last_name = $_POST['last_name'];
				$gender = $_POST['gender'];
				$bio = $_POST['bio'];
				$image_location = $_POST['image_location'];
				$id = $_POST['ID'];

					
				if($users->update_user($first_name, $last_name, $gender, $bio, $image_location, $id)) {	
					echo ('User successfully updated! 
					Go back to <a href="edit_user.php">
						Manage Users</a>');
				  }
			}
				
		}

		$action = $_GET['action'];
		$ID = $_GET['ID']; 

		if($action == "edit"){
			$selectUser = $users->userdata($ID);
			
			echo('<h2>Edit '.$selectUser['first_name'].'</h2>');
			echo($selectUser['last_name']);
		
		//form
		echo ('<form action="" method="post" name="post">
			<p>Name:<br />
			<input name="first_name" type="text" size="45" value="'.$selectUser['first_name'].'"/>
			</p>

			<p>Last Name:<br />
			<input name="last_name" type="text" size="45" value="'.$selectUser['last_name'].'"/>
			</p>
			<p>Gender:<br />
			<input name="gender" type="text" size="45" value="'.$selectUser['gender'].'"/>
			</p>
			<p>Bio:<br />
			<textarea name="bio" cols="100" rows="10">'
				.$selectUser['bio'].'</textarea>
			</p>
			<p>Image Location:<br />
			<input name="image_location" type="text" size="45" value="'.$selectUser['image_location'].'"/>
			</p>
			<p>Post ID:<br />
			<textarea name="ID" cols="50" rows="1">'
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
		echo ('<h2> Manage Users </h2>');
		$query = $users->get_users();
		foreach ($query as $showUsers){
			echo ($showUsers['first_name'].' 
			<p><a href="profile.php?username='.$showUsers['username'].'">'.$showUsers['username'].'</a> joined:'.date('F j, Y', $showUsers['time']).'</p>
			- <a href="index.php?page=edit_user.php&action=edit&ID='.$showUsers['id'].'">Edit</a>
			- <a href="index.php?page=edit_user.php&action=delete&ID='.$showUsers['id'].'">Delete</a>
			<br /><br />');
		}
	}	
?>
    </div>
    </div>
	</div>
</body>
</html>
