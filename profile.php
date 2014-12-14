<?php if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
    }

use Respect\Validation\Validator as v;

if (isset($_GET['username']) && empty($_GET['username']) === false) { // Putting everything in this if block.

    $username   = htmlentities($_GET['username']); // sanitizing the user inputed data (in the Url)
    if ($users->user_exists($username) === false) { // If the user doesn't exist
        header('Location:index.php'); // redirect to index page. Alternatively you can show a message or 404 error
        die();
    } else {
        $profile_data    = array();
        $profile_data    = $users->userdata($user_id);
    }

    ?>
    <div class="wrapper">
        <section class="content">

        <h1><?php echo $profile_data['username']; ?>'s Profile</h1>

	    	<div id="profile_picture">

	    		<?php
                    $image = $profile_data['image_location'];
                if (v::exists()->validate($image)) {
                    echo "<img src='$image'>s";
                }
                ?>
	    	</div>
	    	<div id="personal_info">
	    		<?php if (!empty($profile_data['username'])) {?>

		    		<span><strong>Name</strong>: </span>
		    		<span><?php if(!empty($profile_data['username'])) echo $profile_data['username'], ' '; ?></span>
		    		<br>
                <?php
                }

                if (!empty($profile_data['full_name'])) {
                    ?>
                    <span><strong>Full Name:</strong> </span>
                    <span><?php echo $profile_data['full_name']; ?></span>

                    <br>

	    		<?php
                }

                if ($profile_data['gender'] != 'undisclosed') {
                ?>
		    		<span><strong>Gender</strong>: </span>
		    		<span><?php echo $profile_data['gender']; ?></span>

		    		<br>
	    		<?php }

                if (!empty($profile_data['bio'])) {
                    ?>
		    		<span><strong>Bio</strong>: </span>
		    		<span><?php echo $profile_data['bio']; ?></span>
		    		<?php
                }
                ?>
	    	</div>
            </section>
	    </div>
<?php
} else {
    header('Location: index.php'); // redirect to index if there is no username in the Url
}
