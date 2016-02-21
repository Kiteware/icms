<?php
use Nix\Icms;
use Respect\Validation\Validator as v;


if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
}

$profile_data    = array();
$profile_data    = $this->model->user;

?>
<div class="wrapper">
    <section class="content">
        <article>
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
        </article>
    </section>
</div>