<?php if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
}
if (isset($_POST['current_password'])) $current_password = $_POST['current_password'];
if (isset($_POST['password'])) $password = trim($_POST['password']);
if (isset($_POST['password_again'])) $password_again = trim($_POST['password_again']);

?>
<div class="wrapper">
    <section class="content">
        <?php
        if (empty($_POST) === false) {

            if (!isset($current_password) || !isset($password) || !isset($password_again)) {
                $errors[] = 'All fields are required';

            } elseif ($bcrypt->verify($current_password, $user['password']) === true) {

                if ($password != $password_again) {
                    $errors[] = 'Your passwords do not match';
                } elseif (strlen($password) < 6) {
                    $errors[] = 'Your password must be at least 6 characters';
                } elseif (strlen($password) >18) {
                    $errors[] = 'Your password cannot be more than 18 characters';
                }
            } else {
                $errors[] = 'Your current password is incorrect';
            }
        }

        if (isset($_GET['success']) === true) {
            echo '<p>Your password has been changed!</p>';
        } else {?>
            <?php
            if (empty($_POST) === false && empty($errors) === true) {
                $users->change_password($user['id'], $password);
                header('Location: index.php?page=change-password.php&success');
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

                <h4>Re enter password:</h4>
                <input type="password" name="password_again">

                <input type="submit" value="Change password">
            </form>
        <?php
        }
        ?>
    </section>
</div>
