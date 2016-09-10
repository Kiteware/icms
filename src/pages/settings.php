<?php

defined('_ICMS') or die;

/**
 * Settings
 * A page where users can edit their account settings.
 */

$userdata    = $this->controller->user;

$filters = array(
    'username'   => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    'full_name'   => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    'gender'   => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    'bio'   => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    'image_location' => FILTER_SANITIZE_URL
);

$sUserdata = filter_var_array($userdata, $filters);

?>
<div class="container content section-md">
    <div class="row">
        <div id="center-form">
            <h2 id="form-header">Settings</h2>
            <hr>
            <form action="" method="post" enctype="multipart/form-data">
                <div id="profile_picture">
                    <?php
                    if (file_exists($sUserdata['image_location'])) {
                        echo "<img src='/".$sUserdata['image_location']."'>";
                    } else {
                        echo $sUserdata['image_location']."You have no avatar.";
                    }
                    ?>

                    <input type="file" class="form-control-file" name="myfile" />

                </div>
                <hr>
                <div id="personal_info">
                    <h3 >Profile Information </h3>

                    <h4>Username:</h4>
                    <input type="text" class="form-control" name="username" value="<?php  echo $sUserdata['username']; ?>">

                    <h4>Full name: </h4>
                    <input type="text" class="form-control" name="full_name" value="<?php echo $sUserdata['full_name']; ?>">

                    <h4>Gender:</h4>
                    <?php
                    $gender   =  $sUserdata['gender'];
                    $options  = array("undisclosed", "Male", "Female");
                    ?>
                    <select class="form-control" name="gender">
                        <?php
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
                    <textarea name="bio" class="form-control" cols="40" rows="10"> <?php echo $sUserdata['bio']; ?></textarea>
                </div>
                <input type="submit" name="submit" value="Update" class="btn btn-primary btn-block btn-lg">
            </form>
        </div>
    </div>
</div>