<body>	
<div id="content">
  <div class="box">
    <div class="box-header">Admin Panel</div>
    <div class="box-body">
		<h1>Edit Permissions</h1>
		<p> </p>

<?php


/**************************************************************
	DELETE CONFIRMATION CHECK
	***************************************************************/ 
	if(isset($_POST['yes'])){ //if yes is submitted...
		$ID = $_POST['userID']; //get post id
        $pageName = $_POST['pageName'];
		//echo confirmation if successful
		if($permissions->delete_permission($ID, $pageName)){
			echo 'Permission has been successfully deleted.<br />';
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
		$ID = $_GET['userID']; //gets the post id from the url


		if($action == "delete"){
		
			$selectPermission = $permissions->get_permission($ID);
			//Confirm with user they want to delete, if yes refresh and do isset['yes']
			echo ('Are you sure you want to permanently delete '.$selectPermission['userID'].'?
				<form action="index.php?page=edit_permissions.php" method="post" name="post">
				<input name="userID" type="hidden" value="'.$selectPermission['userID'].'">
                <input name="pageName" type="hidden" value="'.$selectPermission['pageName'].'">
				<input name="yes" type="submit" value="Yes" />
				<input name="no" ONCLICK="history.go(-1)" type="button" value="No" />
				</form>');
		}

		else{
			if(isset($_POST['update'])){
				$userID = $_POST['userID'];
				$pageName = $_POST['pageName'];

				if($permissions->update_pernission($userID, $pageName)) {	
					echo ('Permission successfully updated! 
					Go back to <a href="index.php?page=edit_permissions.php">
						Manage Users</a>');
				  }
			}
				
		}

		$action = $_GET['action'];
		$ID = $_GET['userID']; 

		if($action == "edit"){
			$selectPermission = $permissions->get_permission($ID);
			
			echo('<h2>Edit '.$selectPermission['userID'].'</h2>');
			echo($selectPermission['pageName']);
		
		//form
		echo ('<form action="" method="post" name="post">
			<p>User ID:<br />
			<input name="userID" type="text" size="45" value="'.$selectPermission['userID'].'"/>
			</p>

			<p>Page Name:<br />
			<input name="pageName" type="text" size="45" value="'.$selectPermission['pageName'].'"/>
			</p>
			<input name="update" type="submit" value="update"/>
		</form>');
		}
	}

	/****************************************
	DEFAULT PAGE (NO $_GET EXISTS YET)
	*****************************************/
	else {
		echo ('<h2> Manage Permissions </h2>');
        $query = $permissions->get_permissions();
		foreach ($query as $showPermissions){
			echo ($showPermissions['userID'].' '.$showPermissions['pageName'].'
		      <a href="index.php?page=edit_permissions.php&action=edit&userID='.$showPermissions['userID'].'">Edit</a>
			- <a href="index.php?page=edit_permissions.php&action=delete&userID='.$showPermissions['userID'].'">Delete</a>
			<br /><br />');
		}
	}	

if (isset($_POST['submit'])) {

	if(empty($_POST['userID']) || empty($_POST['pageName'])){

		$errors[] = 'All fields are required.';

	}

	if(empty($errors) === true){
		
		$userID 	= htmlentities($_POST['userID']);
		$pageName	= htmlentities($_POST['pageName']);

		$permissions->add_permission($userID, $pageName);
		
		header('Location: index.php?page=edit_permissions.php');
		exit();
	}
}
?>
        <h2>Add a new Permission</h2>
		<form method="post" action="">
			<h4>user ID:</h4>
			<input type="text" name="userID" value="<?php if(isset($_POST['userID'])) echo htmlentities($_POST['userID']); ?>" >
			<h4>Page Name:</h4>
			<input type="text" name="pageName" value="<?php if(isset($_POST['pageName'])) echo htmlentities($_POST['pageName']); ?>"/>	
			<br>
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
