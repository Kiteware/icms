<?php
use Nix\Icms;

if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
    }
?>
<div class="wrapper">
    <section class="content">
        <article>
		<div id="form-header">Register</div>

		<?php
        if (isset($_GET['success']) && empty($_GET['success'])) {
          echo 'Thank you for registering. Please check your email. <br />It should be instant, so please check your spam folder!';
        }
        ?>
		<form method="post" action="">
			<h4>Username:</h4>
			<input type="text" name="username" value="<?php if(isset($username)) echo htmlentities($username); ?>" >
			<h4>Password:</h4>
			<input type="password" name="password" />
			<h4>Email:</h4>
			<input type="text" name="email" value="<?php if(isset($email)) echo htmlentities($email); ?>"/>
			<br /><br />
            <input type="submit" name="submit" value="Register" />
		</form>
		<?php
        if (empty($errors) === false) {
            echo '<p>' . implode('</p><p>', $errors) . '</p>';
        }
        ?>
            </article>
        </section>
</div>
