<?php
use Nixhatter\ICMS;
use Respect\Validation\Validator as v;

defined('_ICMS') or die;

$userdata    = $this->controller->user;

$filters = array(
    'username'   => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    'full_name'   => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    'gender'   => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    'bio'   => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    'image_location' => FILTER_SANITIZE_ENCODED
);

$sUserdata = filter_var_array($userdata, $filters);
?>
<div class="container content">
    <div class="row">
        <div class="col-md-12">
            <h1><?php echo $sUserdata['username']; ?>'s Profile</h1>
            <div id="profile_picture">
                <?php
                $image = $this->controller->inputValidation($sUserdata['image_location'], 'file');
                if (file_exists($sUserdata['image_location'])) {
                    echo "<img src='/".$sUserdata['image_location']."' alt='".$sUserdata['username']."/'s avatar''>";
                }
                ?>
            </div>
            <div id="personal_info">

                <h3>Username:</h3>
                <p><?php echo $sUserdata['username']; ?></p>


                <h3>Full Name:</h3>
                <p><?php echo $sUserdata['full_name']; ?></p>

                <h3>Gender: </h3>
                <p><?php echo $sUserdata['gender']; ?></p>

                <h3>Bio: </h3>
                <p><?php echo $sUserdata['bio']; ?></p>

            </div>
        </div>
    </div>
</div>