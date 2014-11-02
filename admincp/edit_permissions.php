<?php if(count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400); 
    exit('400: Bad Request'); 
    } ?>
<body>	
<div id="content">
<div id="content_left">
  <div class="box">
    <div class="box-header">Admin Panel</div>
    <div class="box-body">
		<h1>Edit Permissions</h1>
		<p> </p>

<?php


/**************************************************************
	DELETE CONFIRMATION CHECK
	***************************************************************/ 
	if(isset($_POST['delete_user'])){ 
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
	if($check==true & !empty($_GET['action']) & !empty($_GET['userID'])){

		$action = $_GET['action']; // gets action from url, edit or delete
		$ID = $_GET['userID']; //gets the post id from the url


		if($action == "delete"){
		
			$selectPermission = $permissions->get_permission($ID);
			//Confirm with user they want to delete, if yes refresh and do isset['yes']
			echo ('Are you sure you want to permanently delete '.$selectPermission['userID'].'?
				<form action="index.php?page=edit_permissions.php" method="post" name="post">
				<input name="userID" type="hidden" value="'.$selectPermission['userID'].'">
                <input name="pageName" type="hidden" value="'.$selectPermission['pageName'].'">
				<input name="delete_user" type="submit" value="delete_user" />
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
        </div>
        </div>
        </div>
  <div id="content_right">
 <div class="box">
    <div class="box-header">New Blog</div>
    <div class="box-body">
  <?php
  /**************************************************************
	USERGROUP EDIT
	***************************************************************/ 
	if(isset($_POST['delete_usergroup'])){ 
		$ID = $_POST['usergroupID']; //get post id
        $pageName = $_POST['pageName'];
		//echo confirmation if successful
		$permissions->delete_usergroup($ID, $pageName);
		echo 'Usergroup has been successfully deleted.<br />';
	}


	/******************************************
	 ONCE $_GET HAS BEEN IS NOT EMPTY...
		$_GET['action'] = edit/delete
		$_GET['ID'] = id of selected post
	************************************************************/

	$check= !empty($_GET);
	if($check==true & !empty($_GET['action']) & !empty($_GET['usergroupID'])){

		$action = $_GET['action']; 
		$ID = $_GET['usergroupID'];
        $pageName =  $_GET['pageName'];


		if($action == "delete"){

			//Confirm with user they want to delete, if yes refresh and do isset['yes']
			echo ('Are you sure you want to permanently delete '.$ID.'?
				<form action="index.php?page=edit_permissions.php" method="post" name="post">
				<input name="usergroupID" type="hidden" value="'.$ID.'">
                <input name="pageName" type="hidden" value="'.$pageName.'">
				<input name="delete_usergroup" type="submit" value="delete_usergroup" />
				<input name="no" ONCLICK="history.go(-1)" type="button" value="No" />
				</form>');
		}

		else{
			if(isset($_POST['update'])){
				$newUsergroupID = $_POST['newUsergroupID'];
				$newPageName = $_POST['newPageName'];
                $oldUsergroupID = $_POST['oldUsergroupID'];
                $oldPageName = $_POST['oldPageName'];

				$permissions->delete_usergroup($oldUsergroupID, $oldPageName);
                $permissions->add_usergroup($newUsergroupID, $newPageName);
				echo ('Permission successfully updated!
                Go back to <a href="index.php?page=edit_permissions.php">
						Manage Users</a>');

			}
				
		}

		if($action == "edit"){		
			echo('<h2>Edit '.$ID.'</h2>');
			echo($pageName);
		
    		echo ('<form action="" method="post" name="post">
    			<p>User ID:<br />
    			<input name="newUsergroupID" type="text" size="45" value="'.$ID.'"/>
    			</p>
    			<p>Page Name:<br />
    			<input name="newPageName" type="text" size="45" value="'.$pageName.'"/>
    			</p>
    			<input name="update" type="submit" value="update"/>
    			<input type="hidden" name="oldUsergroupID" value="'.$ID.'">
    			<input type="hidden" name="oldPageName" value="'.$pageName.'">
    		</form>');
		}
	}

	/****************************************
	DEFAULT PAGE (NO $_GET EXISTS YET)
	*****************************************/
	else {
		echo ('<h2> Manage Permissions </h2> <table>
        <tr><th>Usergroup</th><th>Page Name</th><th>Edit</th><th>Delete</th></tr>');
        $query = $permissions->get_usergroups();
        $temp = '';
		foreach ($query as $showPermissions){
		  echo('<tr>');
		      if ($temp == $showPermissions['usergroupID']) {
		          echo ('<td></td><td>'.$showPermissions['pageName'].'</td>
                    <td><a href="index.php?page=edit_permissions.php&action=edit&usergroupID='.$showPermissions['usergroupID'].'&pageName='.$showPermissions['pageName'].'">Edit</a></td>
                    <td><a href="index.php?page=edit_permissions.php&action=delete&usergroupID='.$showPermissions['usergroupID'].'&pageName='.$showPermissions['pageName'].'">Delete</a></td>');
		      } else {
			     echo ('<td>'.$showPermissions['usergroupID'].'</td> 
                    <td>'.$showPermissions['pageName'].'</td>
                    <td><a href="index.php?page=edit_permissions.php&action=edit&usergroupID='.$showPermissions['usergroupID'].'&pageName='.$showPermissions['pageName'].'">Edit</a></td>
                    <td><a href="index.php?page=edit_permissions.php&action=delete&usergroupID='.$showPermissions['usergroupID'].'&pageName='.$showPermissions['pageName'].'">Delete</a></td>');
                 }
            echo('</tr>');
            $temp = $showPermissions['usergroupID'];
		}
        echo ('</table>');
	}	
    ?>        
    <?php
        if (isset($_POST['submit_usergroup'])) {
        	if(empty($_POST['usergroupID']) || empty($_POST['pageName'])){
        		$errors[] = 'All fields are required.';
        	}
  
        	if(empty($errors) === true){
        		
        		$usergroupID = htmlentities($_POST['usergroupID']);
        		$pageName	= htmlentities($_POST['pageName']);
        
        		$permissions->add_usergroup($usergroupID, $pageName);
        		
        		header('Location: index.php?page=edit_permissions.php');
        		exit();
        	}
        }
        ?>
        <h2>Add a new Usergroup</h2>
		<form method="post" action="">
			<h4>usergroup ID:</h4>
			<input type="text" name="usergroupID" value="<?php if(isset($_POST['usergroupID'])) echo htmlentities($_POST['usergroupID']); ?>" />
			<h4>Page Name:</h4>
			<input type="text" name="pageName" value="<?php if(isset($_POST['pageName'])) echo htmlentities($_POST['pageName']); ?>" />	
			<br />
			<input type="submit" name="submit_usergroup" />
		</form>

		<?php 
		if(empty($errors) === false){
			echo '<p>' . implode('</p><p>', $errors) . '</p>';	
		}

		?>
    </div>
    </div>
	</div>
    </div>
</body>
</html>
