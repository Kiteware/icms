<?php
use Nix\Icms;
if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
}
?>
<div class="wrapper">
		<?php
        if (empty($errors) === false) {
            echo '<p>' . implode('</p><p>', $errors) . '</p>';
        }
        ?>
        <div id="form-header">Login</div>
		<form method="post" action="">
			<h4>Username:</h4>
			<input type="text" name="username" value="<?php if(isset($_POST['username'])) echo htmlentities($_POST['username']); ?>" />
			<h4>Password:</h4>
			<input type="password" name="password" />
			<br>
			<input type="submit" name="submit" value="Login" />
		<br /><br />
		    <a href="/user/confirm-recover">Forgot your username/password?</a><br />
            <a href="/user/register">Not registered yet? Sign up here!</a>
        </form>
    </div>
