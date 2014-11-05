<?php if(count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400); 
    exit('400: Bad Request'); 
    } ?>
<?php 
if (isset($_POST['submit'])) {

	if(empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])){

		$errors[] = 'All fields are required.';

	}else{
        
        if ($users->user_exists($_POST['username']) === true) {
            $errors[] = 'That username already exists';
        }
        if(!ctype_alnum($_POST['username'])){
            $errors[] = 'Please enter a username with only alphabets and numbers';	
        }
        if (strlen($_POST['password']) <6){
            $errors[] = 'Your password must be atleast 6 characters';
        } else if (strlen($_POST['password']) >18){
            $errors[] = 'Your password cannot be more than 18 characters long';
        }
        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
            $errors[] = 'Please enter a valid email address';
        }else if ($users->email_exists($_POST['email']) === true) {
            $errors[] = 'That email already exists.';
        }
	}

	if(empty($errors) === true){
		
		$username 	= htmlentities($_POST['username']);
		$password 	= $_POST['password'];
		$email 		= htmlentities($_POST['email']);

		$users->register($username, $password, $email, $settings->production->site->url, $settings->production->site->name, $settings->production->site->email );
		header('Location: index.php?page=register.php&success');
		exit();
	}
}
?>
<div class="wrapper">
    <section class="content">
        <article>
		<div id="form-header">Register</div>
		
		<?php
		if (isset($_GET['success']) && empty($_GET['success'])) {
		  echo '<center>Thank you for registering. Please check your email.</center>';
		}
		?>
		<form method="post" action="">
			<h4>Username:</h4>
			<input type="text" name="username" value="<?php if(isset($_POST['username'])) echo htmlentities($_POST['username']); ?>" >
			<h4>Password:</h4>
			<input type="password" name="password" />
			<h4>Email:</h4>
			<input type="text" name="email" value="<?php if(isset($_POST['email'])) echo htmlentities($_POST['email']); ?>"/>	
			<br /><br />
            <input type="submit" name="submit" value="Register" />
		</form>
		<?php 
		if(empty($errors) === false){
			echo '<p>' . implode('</p><p>', $errors) . '</p>';	
		}

		?>
            </article>
        </section>
</div>