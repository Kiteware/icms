<?php if(count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
} ?>
<div class="wrapper">
    <section class="content">
        <article>
    <h1>Activate your account</h1>

    <?php

    if (isset($_GET['success']) === true && empty ($_GET['success']) === true) {
        ?>
        <h3>Your account is now active!</h3>
    <?php

    } else if (isset ($_GET['email'], $_GET['email_code']) === true) {

        $email		=trim($_GET['email']);
        $email_code	=trim($_GET['email_code']);

        if ($users->email_exists($email) === false) {
            $errors[] = 'Sorry, we couldn\'t find that email address';
        } else if ($users->activate($email, $email_code) === false) {
            $errors[] = 'Sorry, we have failed to activate your account';
        }

        if(empty($errors) === false){

            echo '<p>' . implode('</p><p>', $errors) . '</p>';

        } else {
            header('Location: index.php?page=activate.php?success');
            exit();
        }

    } else {
        header('Location: index.php');
        exit();
    }
    ?>
            </article>
        </section>

</div>