<?php
use Nixhatter\ICMS;
if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
} ?>
<div class="container section-md">
    <div class="row">
<div id="center-form">
    <h2 id="form-header">Change Password</h2>
    <hr>
    <form action="/user/changepassword" method="post">
        <h4>Current password:</h4>
        <input type="password" class="form-control"  name="current_password">

        <h4>New password:</h4>
        <input type="password" class="form-control"  name="password">

        <h4>Re enter password:</h4>
        <input type="password" class="form-control"  name="password_again">

        <button type="submit" class="btn btn-primary btn-block btn-lg">Change password</button>
    </form>
</div>
