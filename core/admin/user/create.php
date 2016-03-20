<?php if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
} ?>
<div id="content">
    <div class="box">
        <div class="box-header">Add User</div>
        <div class="box-body">
            <form action="/admin/user/create" method="post" id="reload-form" enctype="multipart/form-data">
                <fieldset class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" value="<?php if(isset($_POST['username'])) echo htmlentities($_POST['username']); ?>" >
                </fieldset>
                <fieldset class="form-group">
                    <label>email</label>
                    <input type="text" name="email" class="form-control" value="<?php if(isset($_POST['email'])) echo htmlentities($_POST['email']); ?>"/>
                </fieldset>
                <fieldset class="form-group">
                    <label>password</label>
                    <input type="password" name="password" class="form-control" value="<?php if(isset($_POST['password'])) echo htmlentities($_POST['password']); ?>"/>
                </fieldset>
                <button name="submit" type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>