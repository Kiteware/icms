<?php if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
} ?>
<div class="box">
    <div class="box-header">Edit Users</div>
    <div class="box-body">
        <?php
        if (!empty($this->controller->id)) {
            $user = $this->controller->user;

            echo('<h2>Edit '.$user['username'].'</h2>');
            ?>
            <form action="/admin/user/update" class="no-reload-form" method="post" >
                <fieldset class="form-group">
                    <label>Username:</label>
                    <input name="username" type="text" class="form-control" value="<?php echo $user['username']?>"/>
                </fieldset>
                <fieldset class="form-group">
                    <label>Full Name:</label>
                    <input name="fullName" type="text" class="form-control" value="<?php echo $user['full_name']?>"/>
                </fieldset>
                <fieldset class="form-group">
                    <label>Gender:</label>
                    <input name="gender" type="text" class="form-control" value="<?php echo $user['gender']?>"/>
                </fieldset>
                <fieldset class="form-group">
                    <label>Bio:</label>
                    <textarea name="bio" rows="10" class="form-control"><?php echo $user['bio']?></textarea>
                </fieldset>
                <fieldset class="form-group">
                    <label>Image Location:</label>
                    <input name="imageLocation" type="text" class="form-control" value="<?php echo $user['image_location']?>"/>
                </fieldset>
                <fieldset class="form-group">
                    <label>User ID:</label>
                    <input name="userID" type="text" class="form-control" value="<?php echo $this->controller->id?>"/>
                </fieldset>
                <fieldset class="form-group">
                    <label>Usergroup</label>
                    <input name="usergroup" type="text" class="form-control" value="<?php echo $user['usergroup']?>"/>
                </fieldset>
                <fieldset class="form-group">
                    <label>IP</label>
                    <input name="ip" type="text" class="form-control" value="<?php echo $user['ip']?>" readonly />
                </fieldset>
                <button type="submit" class="btn btn-primary">Update</button>
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
                                <td>'.date('F j, Y', strtotime($user['time'])). '</td>
                                <td><a href="/admin/user/edit/' .$user['id']. '">Edit</a></td>
                                <td><a  onClick=\'ajaxCall("/admin/user/delete/' .$user['id'].'", "manage-users")\'>Delete</a></td>
                             </tr>');
                }
                ?>
                </tbody>
            </table>
        <?php }  ?>
    </div>
</div>
