<?php
use Nixhatter\ICMS;
use Respect\Validation\Validator as v;


if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
}

$profile_data    = array();
$profile_data    = $this->model->user;

?>
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
                <h3>Username:</h3>
                <p><?php if(!empty($profile_data['username'])) echo $profile_data['username'], ' '; ?></p>

                <?php
            }
            if (!empty($profile_data['full_name'])) {
                ?>
                <h3>Full Name:</h3>
                <p><?php echo $profile_data['full_name']; ?></p>
                <?php
            }
            if ($profile_data['gender'] != 'undisclosed') {
                ?>
                <h3>Gender: </h3>
                <p><?php echo $profile_data['gender']; ?></p>
            <?php }
            if (!empty($profile_data['bio'])) {
                ?>
                <h3>Bio: </h3>
                <p><?php echo $profile_data['bio']; ?></p>
                <?php
            }
            ?>
        </div>
    </article>
</section>
