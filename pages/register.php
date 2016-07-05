<?php defined('_ICMS') or die; ?>
<div class="container section-md">
	<div class="row">
		<div id="center-form">
			<h2 id="form-header">Register</h2>
			<hr>
			<form method="post" action="/user/register">
				<fieldset class="form-group">
					<h4><label for="username">Username:</label></h4>
					<input type="text" name="username" id="username" class="form-control input-lg" >
				</fieldset>
				<fieldset class="form-group">
					<h4><label for="password">Password:</label></h4>
					<input type="password" name="password" id="password" class="form-control input-lg">
				</fieldset>
				<fieldset class="form-group">
					<h4><label for="email">Email:</label></h4>
					<input type="text" name="email" id="email" class="form-control input-lg" >
				</fieldset>
				By clicking <strong class="label label-primary">Register</strong>, you agree to the <a href="#" data-toggle="modal" data-target="#t_and_c_m">Terms and Conditions</a> set out by this site, including our Cookie Use.
				<hr>
				<input type="submit" name="submit" value="Register" class="btn btn-primary btn-block btn-lg">
			</form>
		</div>
	</div>
</div>