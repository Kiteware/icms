<?php if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
} ?>
<div class="wrapper">
    <section class="content">
      <article>
            <div id="form-header">Change Password</div>
            <form action="/user/changepassword" method="post">
                <h4>Current password:</h4>
                <input type="password" name="current_password">

                <h4>New password:</h4>
                <input type="password" name="password">

                <h4>Re enter password:</h4>
                <input type="password" name="password_again">

                <input type="submit" value="Change password">
            </form>
      </article>
    </section>
</div>
