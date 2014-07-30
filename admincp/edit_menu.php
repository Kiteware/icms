<?php
   require '../core/init.php';
    /**************************************************************
	Update Menu
	***************************************************************/ 
	if(isset($_POST['update'])){ //if yes is submitted...
		$Name = $_POST['nav_name']; //get post id
        $Link = $_POST['nav_link'];
        $Position = $_POST['nav_position'];
        $Permission = $_POST['nav_permission'];
		//echo confirmation if successful
		if($pages->update_nav($Name, $Link, $Position, $Permission)){
			echo 'Menu has been updated<br />';
		} else {
			echo 'Update Failed.';
		}
	}
    /**************************************************************
	DELETE Menu
	***************************************************************/ 
	if(isset($_POST['nav_delete'])){ //if yes is submitted...
		$url = $_POST['nav_link']; //get post id
		//echo confirmation if successful
		$pages->delete_nav($url);
	}
    /**************************************************************
	Create new Menu
	***************************************************************/ 
	if(isset($_POST['create'])){ //if yes is submitted...
		$Name = $_POST['nav_name'];
        $Link = $_POST['nav_link'];
        $Position = $_POST['nav_position'];
        $Permission = $_POST['nav_permission'];
		//echo confirmation if successful
		$pages->create_nav($Name, $Link, $Position, $Permission);
	}
