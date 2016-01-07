<?php if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
    }

use Respect\Validation\Validator as v;
$username_validator = v::alnum()->noWhitespace();
$password_length = v::alnum()->noWhitespace()->between(6, 18);

if (isset($_POST['submit'])) {

    if (!isset($_POST['username']) || !isset($_POST['email']) || !isset($_POST['password'])) {

        $errors[] = 'All fields are required.';

    } else {

        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];

        if ($users->user_exists($username) === true) {
            $errors[] = 'That username already exists';
        }
        if ($username_validator->validate($username) === false) {
            $errors[] = 'A username may only contain alphanumeric characters';
        }
        if ($password_length->validate(strlen($password)) === false) {
            $errors[] = 'Your password must be at least 6 characters and at most 18 characters';
        }
    }
    if (empty($errors) === true) {

        $users->register($username, $password, $email, $settings->production->site->url, $settings->production->site->name, $settings->production->site->email);

        echo("<script> successAlert();</script>");

    }
}
?>
<body>
<div id="content">
  <div class="box">
    <div class="box-header">Admin Panel</div>
    <div class="box-body">
		<h1>Add User</h1>

		<?php
        if (isset($_GET['success']) && empty($_GET['success'])) {
          echo 'User created.';
        }
        ?>

		<form method="post" action="">
			<h4>Username:</h4>
			<input type="text" name="username" value="<?php if(isset($_POST['username'])) echo htmlentities($_POST['username']); ?>" >
			<h4>email:</h4>
			<input type="text" name="email" value="<?php if(isset($_POST['email'])) echo htmlentities($_POST['email']); ?>"/>
			<h4>password:</h4>
			<input type="text" name="password" value="<?php if(isset($_POST['password'])) echo htmlentities($_POST['password']); ?>"/>
			<br>
			<input type="submit" name="submit" />
		</form>

		<?php
        if (empty($errors) === false) {
            echo '<p>' . implode('</p><p>', $errors) . '</p>';
        }

        ?>
    </div>
    </div>
	</div>
</body>
</html>
