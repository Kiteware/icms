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
                <div>
                    <label for="current-password">Current password:</label>
                    <input type="password" class="form-control"  name="current-password"  id="current-password">

                    <label for="password">New password:</label>
                    <input type="password" class="form-control" name="password" id="password">

                    <label for="password-again">Re enter password:</label>
                    <input type="password" class="form-control"  name="password-again" id="password-again">
                </div>
                <button type="submit" name="submit" class="btn btn-primary btn-block btn-lg">Change password</button>
            </form>
        </div>
    </div>
</div>