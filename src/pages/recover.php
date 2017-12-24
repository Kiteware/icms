<?php defined('_ICMS') or die; ?>

<div class="container section-lg">
    <div class="row">
        <div id="center-form">
            <h1 id="form-header">Recover Account</h1>
            <hr>
            <form method="post" action="/user/recover">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" value="<?php if (isset($_GET['email'])) {
    echo htmlspecialchars($_GET['email']);
} ?>" />
                <button type="submit" name="login" class="btn btn-primary btn-block btn-lg">Recover</button>
                <a href="/user/register">Not registered yet? Sign up here!</a>
            </form>
        </div>
    </div>
</div>
