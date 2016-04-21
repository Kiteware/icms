<?php
if (count(get_included_files()) ==1) {
	header("HTTP/1.0 400 Bad Request", true, 400);
	exit('400: Bad Request');
}
?>
<div class="container">
	<div id="center-form">
		<h2 id="form-header">Register</h2>
		<hr>
		<form method="post" action="/user/register">
			<h4>Username:</h4>
			<input type="text" name="username" class="form-control input-lg" >
			<h4>Password:</h4>
			<input type="password" name="password" class="form-control input-lg">
			<h4>Email:</h4>
			<input type="text" name="email" class="form-control input-lg" >
			By clicking <strong class="label label-primary">Register</strong>, you agree to the <a href="#" data-toggle="modal" data-target="#t_and_c_m">Terms and Conditions</a> set out by this site, including our Cookie Use.
			<hr>
			<input type="submit" value="Register" class="btn btn-primary btn-block btn-lg" tabindex="7">
		</form>
	</div>
</div>
