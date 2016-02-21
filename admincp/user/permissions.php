<?php if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
} ?>
<div id="content">
    <div class="col-md-6">
        <div class="box">
            <div class="box-header">Add a Specific User Permission</div>
            <div class="box-body">
                <form method="post" action="/admin/user/permissions/create">
                    <fieldset class="form-group">
                        <label>user ID:</label>
                        <input type="text" name="userID" class="form-control" value="<?php if(isset($_POST['userID'])) echo htmlentities($_POST['userID']); ?>" >
                    </fieldset>
                    <fieldset class="form-group">
                        <label>Page Name:</label>
                        <input type="text" name="pageName" class="form-control" value="<?php if(isset($_POST['pageName'])) echo htmlentities($_POST['pageName']); ?>"/>
                    </fieldset>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
        <div class="box">
            <div class="box-header">Manage User Permissions</div>
            <div class="box-body">
                <table class="table table-striped">
                    <tr>
                        <th>User ID</th>
                        <th>Page Name</th>
                        <th>Delete</th>
                    </tr>
                    <?php
                    $userPermissions = $this->model->get_permissions();
                    foreach ($userPermissions as $userPermission) {
                        echo('<tr> <form method="post" action="/admin/user/permissions/delete">');
                        echo ('<td>'.$userPermission['userID'].'</td>
                                    <td>'.$userPermission['pageName'].'</td>
                                    <td><input type="submit" value="Delete"></td>');
                        echo('<input type="hidden" name="pageName" value="'.$userPermission['pageName'].'" />
                           <input type="hidden" name="userID" value="'.$userPermission['userID'].'" />');
                        echo('</form> </tr>');
                    }
                    ?>
                </table>
                <?php
                if (empty($errors) === false) {
                    echo '<p>' . implode('</p><p>', $errors) . '</p>';
                }
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box">
            <div class="box-header">Add a Usergroup Permission</div>
            <div class="box-body">
                <form method="post" action="/admin/user/usergroup/create">
                    <fieldset class="form-group">
                        <label>usergroup ID:</label>
                        <input type="text" name="usergroupID" class="form-control" value="<?php if(isset($_POST['usergroupID'])) echo htmlentities($_POST['usergroupID']); ?>" />
                    </fieldset>
                    <fieldset class="form-group">
                        <label>Page Name:</label>
                        <input type="text" name="pageName" class="form-control" value="<?php if(isset($_POST['pageName'])) echo htmlentities($_POST['pageName']); ?>" />
                    </fieldset>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
        <div class="box">
            <div class="box-header">Manage Usergroup Permissions</div>
            <div class="box-body">
                <table class="table table-striped">
                    <tr>
                        <th>Usergroup</th>
                        <th>Page Name</th>
                        <th>Delete</th>
                    </tr>
                    <?php
                    $query = $this->model->get_usergroups();
                    foreach ($query as $showPermissions) {
                        echo('<tr> <form method="post" action="/admin/user/usergroup/delete">');
                        echo ('<td>'.$showPermissions['usergroupID'].'</td>
                                    <td>'.$showPermissions['pageName'].'</td>
                                    <td><input type="submit" value="Delete"></td>');

                        echo('<input type="hidden" name="pageName" value="'.$showPermissions['pageName'].'" />
                            <input type="hidden" name="usergroupID" value="'.$showPermissions['usergroupID'].'" />
                            ');
                        echo('</form> </tr>');
                    }
                    ?>
                </table>
                <?php
                if (empty($errors) === false) {
                    echo '<p>' . implode('</p><p>', $errors) . '</p>';
                }
                ?>

            </div>
        </div>
    </div>
</div>