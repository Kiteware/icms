<?php defined('_ICMS') or die; ?>
<div id="container">
    <div class="col-md-6">
        <div class="box">
            <div class="box-header">Add a Specific User Permission</div>
            <div class="box-body">
                <form method="post" action="/admin/user/permissions/create" class="reload-form" name="user-permissions" enctype="multipart/form-data">
                    <fieldset class="form-group">
                        <label for="userID">user ID:</label>
                        <input type="text" name="userID" id="userID" class="form-control" value="<?php if (isset($_POST['userID'])) {
    echo htmlentities($_POST['userID']);
} ?>" required />
                    </fieldset>
                    <fieldset class="form-group">
                        <label for="userPageName">Page Name:</label>
                        <input type="text" name="pageName" id="userPageName" class="form-control" value="<?php if (isset($_POST['pageName'])) {
    echo htmlentities($_POST['pageName']);
} ?>" required />
                    </fieldset>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
        <div class="box">
            <div class="box-header">Manage User Permissions</div>
            <div class="box-body" id="user-permissions">
                <table class="table table-striped" >
                    <tr>
                        <th>User ID</th>
                        <th>Page Name</th>
                        <th>Delete</th>
                    </tr>
                    <?php
                    $userPermissions = $this->model->get_permissions();
                    foreach ($userPermissions as $userPermission) {
                        echo('<tr> <form method="post" action="/admin/user/permissions/delete" class="reload-form" name="user-permissions">');
                        echo('<td>'.$userPermission['userID'].'</td>
                                    <td>'.$userPermission['pageName'].'</td>
                                    <td> <button type="submit" class="btn btn-primary">Delete</button></td>');
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
                <form method="post" action="/admin/user/usergroup/create" class="reload-form" name="usergroup-permissions" enctype="multipart/form-data">
                    <fieldset class="form-group">
                        <label for="usergroupID">usergroup ID:</label>
                        <input type="text" name="usergroupID" id="usergroupID" class="form-control" value="<?php if (isset($_POST['usergroupID'])) {
                    echo htmlentities($_POST['usergroupID']);
                } ?>" required />
                    </fieldset>
                    <fieldset class="form-group">
                        <label for="usergroupPageName">Page Name:</label>
                        <input type="text" name="pageName" id="usergroupPageName" class="form-control" value="<?php if (isset($_POST['pageName'])) {
                    echo htmlentities($_POST['pageName']);
                } ?>" required />
                    </fieldset>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
        <div class="box">
            <div class="box-header">Manage Usergroup Permissions</div>
            <div class="box-body" id="usergroup-permissions">
                <table class="table table-striped">
                    <tr>
                        <th>Usergroup</th>
                        <th>Page Name</th>
                        <th>Delete</th>
                    </tr>
                    <?php
                    $query = $this->model->get_usergroups();
                    foreach ($query as $showPermissions) {
                        echo('<tr> <form action="/admin/user/usergroup/delete" method="post" class="reload-form" name="usergroup-permissions">');
                        echo('<td>'.$showPermissions['usergroupID'].'</td>
                                    <td>'.$showPermissions['pageName'].'</td>
                                    <td><button type="submit" class="btn btn-primary">Delete</button></td>');

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