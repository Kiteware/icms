<?php
require 'core/init.php';
$general->logged_in_protect();
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="css/style.css" >
	<title>Confirm password recover</title>
</head>
<body>	
	<div id="container">
		<?php include 'includes/menu.php'; ?>
		<?php
		if (isset($_GET['success']) === true && empty($_GET['success']) === true) {
			?>	
			<h3>Thanks, please check your email to confirm your request for a password.</h3>
			<?php
		} else {
			?>
		    <h2>Recover Username / Password</h2>
		    <p>Enter your email below so we can confirm your request.</p>
		    <hr />
		    <?php
			if (isset($_POST['email']) === true && empty($_POST['email']) === false) {
				if ($users->email_exists($_POST['email']) === true){
					$users->confirm_recover($_POST['email']);

					header('Location:confirm-recover.php?success');
					exit();
					
				} else {
					echo 'Sorry, that email doesn\'t exist.';
				}
			}
			?>
			<form action="" method="post">
			<ul>
				<li>
					<input type="text" required name="email">
				</li>
				<li><input type="submit" value="Recover"></li>
			</ul>
			</form>
			<?php	
		}
		?>

	</div>
</body>
</html>