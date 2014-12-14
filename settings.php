<?php if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
} ?>
<body>
<div class="wrapper">
<?php
if (isset($_GET['success']) && empty($_GET['success'])) {
    echo '<h3>Your details have been updated!</h3>';
} else {

    if (empty($_POST) === false) {
        use Respect\Validation\Validator as v;
        $fullname_validator = v::alpha()->notEmpty()->noWhitespace()->between(3,25);
        $username_validator = v::alnum()->notEmpty()->noWhitespace()->between(3,25);

        if (isset($_POST['username'])) {
            if ($username_validator->validate(strlen($_POST['username'])) === false) {
                $errors[] = 'Please enter your username with only letters under 25 characters!';
            }
        }
        if (isset($_POST['full_name'])) {
            if ($fullname_validator->validate(strlen($_POST['full_name'])) === false) {
                $errors[] = 'Please enter your Full Name with only letters!';
            }
        }
        if (isset($_POST['gender']) && !empty($_POST['gender'])) {

            $allowed_gender = array('undisclosed', 'Male', 'Female');

            if (in_array($_POST['gender'], $allowed_gender) === false) {
                $errors[] = 'Please choose a Gender from the list';
            }

        }

        if (isset($_FILES['myfile']) && !empty($_FILES['myfile']['name'])) {

            $name            = $_FILES['myfile']['name'];
            $tmp_name        = $_FILES['myfile']['tmp_name'];
            $allowed_ext    = array('jpg', 'jpeg', 'png', 'gif' );
            $a                = explode('.', $name);
            $file_ext        = strtolower(end($a)); unset($a);
            $file_size        = $_FILES['myfile']['size'];
            $path            = "avatars";

            if (in_array($file_ext, $allowed_ext) === false) {
                $errors[] = 'Image file type not allowed';
            }

            if ($file_size > 2097152) {
                $errors[] = 'File size must be under 2mb';
            }

        } else {
            $newpath = $user['image_location'];
        }

        if (empty($errors) === true) {

            if (isset($_FILES['myfile']) && !empty($_FILES['myfile']['name']) && $_POST['use_default'] != 'on') {

                $newpath = $general->file_newpath($path, $name);

                move_uploaded_file($tmp_name, $newpath);

            } elseif (isset($_POST['use_default']) && $_POST['use_default'] === 'on') {
                $newpath = 'images/avatars/default_avatar.png';
            }

            $username    = htmlentities(trim($_POST['username']));
            $full_name        = htmlentities(trim($_POST['full_name']));
            $gender        = htmlentities(trim($_POST['gender']));
            $bio            = htmlentities(trim($_POST['bio']));
            $image_location    = htmlentities(trim($newpath));

            $users->update_user($username, $full_name, $gender, $bio, $image_location, $user_id);
            header('Location: index.php?page=settings.php&success');
            exit();

        } elseif (empty($errors) === false) {
            echo '<p>' . implode('</p><p>', $errors) . '</p>';
        }
    }
    ?>

    <div id="form-header">Settings</div>

    <form action="" method="post" enctype="multipart/form-data">

        <div id="profile_picture">
            <?php
            if (!empty ($user['image_location'])) {
                $image = $user['image_location'];
                echo "<img src='$image'>";
            }
            ?>

            <input type="file" name="myfile" />

            <?php if ($image != 'images/avatars/default_avatar.png') { ?>

                <input type="checkbox" name="use_default" id="use_default" /> <label for="use_default">Use default picture</label>

            <?php
            }
            ?>

        </div>

        <div id="personal_info">
            <h3 >Profile Information </h3>

            <h4>Username:</h4>
            <input type="text" name="username" value="<?php if (isset($_POST['username']) ) {echo htmlentities(strip_tags($_POST['username']));} else { echo $user['username']; }?>">

            <h4>Full name: </h4>
            <input type="text" name="full_name" value="<?php if (isset($_POST['full_name']) ) {echo htmlentities(strip_tags($_POST['full_name']));} else { echo $user['full_name']; }?>">

            <h4>Gender:</h4>
            <?php
            $gender    = $user['gender'];
            $options    = array("undisclosed", "Male", "Female");
            echo '<select name="gender">';
            foreach ($options as $option) {
                if ($gender == $option) {
                    $sel = 'selected="selected"';
                } else {
                    $sel='';
                }
                echo '<option '. $sel .'>' . $option . '</option>';
            }
            ?>
            </select>

            <h4>Bio:</h4>
            <textarea name="bio" cols="40" rows="10"> <?php if (isset($_POST['bio']) ) {echo htmlentities(strip_tags($_POST['bio']));} else { echo $user['bio']; }?></textarea>
        </div>
        <div class="clear"></div>
        <input type="submit" value="Update">

    </form>
    </div>
    </body>
    </html>
<?php
}
