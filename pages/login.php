<?php
use Nixhatter\ICMS;
if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
}
?>
<div id="center-form">
    <h1 id="form-header">Login</h1>
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
        <button type="submit" class="btn btn-primary btn-block btn-lg">Login</button>
        <hr>
        <p class="small">
            <a href="/user/recover">Forgot your username/password?</a><br />
            <a href="/user/register">Not registered yet? Sign up here!</a>
        </p>
    </form>
</div>

