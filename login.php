<?php if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
    }
use Respect\Validation\Validator as v;
$username_validator = v::alnum()->noWhitespace();

if (empty($_POST) === false) {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) === true || empty($password) === true) {
        $errors[] = 'Sorry, but we need your username and password.';
    } elseif ($users->user_exists($username) === false) {
        $errors[] = 'Sorry that username doesn\'t exists.';
    } elseif ($username_validator->validate($username) === false) {
        $errors[] = 'Invalid username';
    } elseif ($users->email_confirmed($username) === false) {
        $errors[] = 'Sorry, but you need to activate your account.
					 Please check your email.';
    } else {
        if (strlen($password) > 18) {
            $errors[] = 'The password should be less than 18 characters, without spacing.';
        }
        $login = $users->login($username, $password);
        if ($login === false) {
            $errors[] = 'Sorry, that username/password is incorrect';
        } else {
            session_regenerate_id(true);// destroying the old session id and creating a new one
            $_SESSION['id'] =  $login;
            if (isset($_GET['from'])) {
                header('Location: index.php?page='.$_GET['from']);
            } else {
                header('Location: index.php');
            }
            exit();
        }
    }
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
		    <a href="index.php?page=confirm-recover.php">Forgot your username/password?</a><br />
            <a href="index.php?page=register.php">Not registered yet? Sign up here!</a>
        </form>
    </div>
