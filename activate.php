<?php if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
}
if (isset ($_GET['email'], $_GET['email_code'])) {
    $email = trim($_GET['email']);
    $email_code = trim($_GET['email_code']);
    $email_exists = $users->email_exists($email);
    $email_activate = $users->activate($email, $email_code);
}
?>
<div class="wrapper">
    <section class="content">
            <h1>Activate your account</h1>
            <?php
            //if the success header was sent
            if (isset($_GET['success']) === true) {
                ?>
                <h2>Your account is now active!</h2>
                <?php
            //If there is sufficient information to activate the account
            } elseif (isset ($email, $email_code) === true) {
                if ($email_exists === false) {
                    $errors[] = 'Sorry, that email was not found';
                } elseif ($email_activate === false) {
                    $errors[] = 'Sorry, account activation has failed';
                }

                if (empty($errors) === false) {

                    echo '<p>' . implode('</p><p>', $errors) . '</p>';

                } else {
                    header('Location: index.php?page=activate.php&success');
                    exit();
                }
                // If not filled in, send them back to the main page
            } else {
                header('Location: index.php');
                exit();
            }
            ?>
    </section>
</div>
