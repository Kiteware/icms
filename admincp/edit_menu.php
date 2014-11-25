<?php if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
    } ?>
<?php
    /**************************************************************
	Update Menu
	***************************************************************/
    if (isset($_POST['update'])) { //if yes is submitted...
        $Name = $_POST['nav_name']; //get post id
        $Link = $_POST['nav_link'];
        $Position = $_POST['nav_position'];
        //echo confirmation if successful
        if ($pages->update_nav($Name, $Link, $Position)) {
            echo("<script> successAlert();</script>");
        } else {
            echo 'Update Failed.';
        }
    }
    /**************************************************************
	DELETE Menu
	***************************************************************/
    if (isset($_POST['nav_delete'])) { //if yes is submitted...
        $url = $_POST['nav_link']; //get post id
        //echo confirmation if successful
        $pages->delete_nav($url);
    }
    /**************************************************************
	Create new Menu
	***************************************************************/
    if (isset($_POST['create'])) { //if yes is submitted...
        $Name = $_POST['nav_name'];
        $Link = $_POST['nav_link'];
        $Position = $_POST['nav_position'];

        $pages->delete_nav($Link);

        //echo confirmation if successful
        $pages->create_nav($Name, $Link, $Position);
        echo("<script> successAlert();</script>");
    }
