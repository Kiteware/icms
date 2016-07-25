<?php defined('_ICMS') or die; ?>
<div class="box">
    <div class="box-header">Edit Users</div>
    <div class="box-body">
        <?php
        if (!empty($this->controller->id)) {
            $user = $this->controller->user;

            echo('<h2>Edit '.$user['username'].'</h2>');
            ?>
            <form action="/admin/user/update" class="no-reload-form" method="post" enctype="multipart/form-data">
                <fieldset class="form-group">
                    <label for="username">Username:</label>
                    <input name="username" id="username" class="form-control" value="<?php echo $user['username']?>" required/>
                </fieldset>
                <fieldset class="form-group">
                    <label for="full-name"> Full Name:</label>
                    <input name="fullName" id="full-name" class="form-control" value="<?php echo $user['full_name']?>" required/>
                </fieldset>
                <fieldset class="form-group">
                    <label for="gender">Gender:</label>
                    <input name="gender" id="gender" class="form-control" value="<?php echo $user['gender']?>"/>
                </fieldset>
                <fieldset class="form-group">
                    <label for="bio">Bio:</label>
                    <textarea name="bio" id="bio" rows="10" class="form-control"><?php echo $user['bio']?></textarea>
                </fieldset>
                <fieldset class="form-group">
                    <label for="image">Image Location:</label>
                    <input name="imageLocation" id="image" class="form-control" value="<?php echo $user['image_location']?>"/>
                </fieldset>
                <fieldset class="form-group">
                    <label for="user-id">User ID:</label>
                    <input name="userID" id="user-id" type="text" class="form-control" value="<?php echo $this->controller->id?>"/>
                </fieldset>
                <fieldset class="form-group">
                    <label for="usergroup">Usergroup</label>
                    <input name="usergroup" id="usergroup" class="form-control" value="<?php echo $user['usergroup']?>" required/>
                </fieldset>
                <fieldset class="form-group">
                    <label for="ip">IP</label>
                    <input name="ip" id="ip" class="form-control" value="<?php echo $user['ip']?>" readonly />
                </fieldset>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="/admin" class="btn btn-danger pull-right">Cancel</a>
            </form>
            <?php
        }

        /****************************************
         * DEFAULT PAGE (NO $_GET EXISTS YET)
         *****************************************/
        else {
            $users = $this->controller->members;
            ?>
            <h2> Manage Users </h2>
            <p>We have a total of <strong><?php echo $this->controller->memberCount; ?></strong> registered users. </p>
            <table class="table table-striped" id="manage-users">
                <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Username</th>
                    <th>Joined Date</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($users as $user) {
                    echo    ('<tr><td>'.$user['full_name'].' </td>
                                <td><p><a href="/user/profile/view/'.$user['username'].'">'.$user['username'].'</a></td>
                                <td>'.date('F j, Y', strtotime($user['joined'])). '</td>
                                <td><a href="/admin/user/edit/' .$user['id']. '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
                                <td><a  onClick=\'ajaxCall("/admin/user/delete/' .$user['id'].'", "manage-users")\'><i class="fa fa-trash" aria-hidden="true"></i></a></td>
                             </tr>');
                }
                ?>
                </tbody>
            </table>
        <?php }  ?>
    </div>
</div>
