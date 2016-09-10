<?php defined('_ICMS') or die; ?>
<div class="container section-md">
    <div class="row">
        <div id="center-form">
            <h2 id="form-header">Login</h2>
            <hr>
            <form method="post" action="/user/login">
                <fieldset class="form-group">
                    <h4><label for="username">Username</label></h4>
                    <input type="text" class="form-control" name="username" >
                </fieldset>
                <fieldset class="form-group">
                    <h4><label for="password">Password</label></h4>
                    <input type="password" class="form-control" name="password" >
                </fieldset>
                <button type="submit" name="login" class="btn btn-primary btn-block btn-lg">Login</button>
                <hr>
                <p>
                    <a href="/user/recover">Forgot your username or password?</a>
                    <br />
                    <a href="/user/register">Not registered yet? Sign up here!</a>
                </p>
            </form>
        </div>
    </div>
</div>