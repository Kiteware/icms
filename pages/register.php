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
            </article>
        </section>
</div>
