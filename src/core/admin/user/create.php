<?php defined('_ICMS') or die; ?>
<div class="box">
    <div class="box-header">Add User</div>
    <div class="box-body">
        <form action="/admin/user/create" method="post" class="reload-form">
            <fieldset class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control" value="<?php if (isset($_POST['username'])) {
    echo htmlentities($_POST['username']);
} ?>" required />
            </fieldset>
            <fieldset class="form-group">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" class="form-control" value="<?php if (isset($_POST['email'])) {
    echo htmlentities($_POST['email']);
} ?>" required />
            </fieldset>
            <fieldset class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" value="<?php if (isset($_POST['password'])) {
    echo htmlentities($_POST['password']);
} ?>" required />
            </fieldset>
            <button name="submit" type="submit" class="btn btn-primary">Submit</button>
            <a href="/admin" class="btn btn-danger pull-right">Cancel</a>
        </form>
    </div>
</div>
