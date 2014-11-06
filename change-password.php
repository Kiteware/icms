<?php if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
} ?>
<div class="wrapper">
    <?php
    if (empty($_POST) === false) {

        if (empty($_POST['current_password']) || empty($_POST['password']) || empty($_POST['password_again'])) {

            $errors[] = 'All fields are required';

        } elseif ($bcrypt->verify($_POST['current_password'], $user['password']) === true) {

            if (trim($_POST['password']) != trim($_POST['password_again'])) {
                $errors[] = 'Your new passwords do not match';
            } elseif (strlen($_POST['password']) < 6) {
                $errors[] = 'Your password must be at least 6 characters';
            } elseif (strlen($_POST['password']) >18) {
                $errors[] = 'Your password cannot be more than 18 characters long';
            }

        } else {
            $errors[] = 'Your current password is incorrect';
        }
    }

    if (isset($_GET['success']) === true && empty ($_GET['success']) === true ) {
        echo '<p>Your password has been changed!</p>';
    } else {?>
        <?php
        if (empty($_POST) === false && empty($errors) === true) {
            $users->change_password($user['id'], $_POST['password']);
            header('Location: change-password.php?success');
        } elseif (empty ($errors) === false) {

            echo '<p>' . implode('</p><p>', $errors) . '</p>';

        }
        ?>
        <div id="form-header">Change Password</div>
        <form action="" method="post">
            <h4>Current password:</h4>
            <input type="password" name="current_password">

            <h4>New password:</h4>
            <input type="password" name="password">

            <h4>New password again:</h4>
            <input type="password" name="password_again">

            <input type="submit" value="Change password">
        </form>
    <?php
    }
    ?>
</div>
