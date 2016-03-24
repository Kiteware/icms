<?php if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
} ?>
<div id="content">
    <div class="box">
        <div class="box-header">Edit Users</div>
        <div class="box-body">
            <?php
            if (isset($this->model->id)) {
                $selectUser = $this->model->userdata($this->model->id);

                echo('<h2>Edit '.$selectUser['username'].'</h2>');
                ?>
                <form action="/admin/user/update" class="no-reload-form" method="post" >
                    <fieldset class="form-group">
                        <label>Username:</label>
                        <input name="username" type="text" class="form-control" value="<?php echo $selectUser['username']?>"/>
                    </fieldset>
                    <fieldset class="form-group">
                        <label>Full Name:</label>
                        <input name="fullName" type="text" class="form-control" value="<?php echo $selectUser['full_name']?>"/>
                    </fieldset>
                    <fieldset class="form-group">
                        <label>Gender:</label>
                        <input name="gender" type="text" class="form-control"value="<?php echo $selectUser['gender']?>"/>
                    </fieldset>
                    <fieldset class="form-group">
                        <label>Bio:</label>
                        <textarea name="bio" rows="10" class="form-control"><?php echo $selectUser['bio']?></textarea>
                    </fieldset>
                    <fieldset class="form-group">
                        <label>Image Location:</label>
                        <input name="imageLocation" type="text" class="form-control" value="<?php echo $selectUser['image_location']?>"/>
                    </fieldset>
                    <fieldset class="form-group">
                        <label>User ID:</label>
                        <input name="userID" type="text" class="form-control"value="<?php echo $this->model->id?>"/>
                    </fieldset>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
                <?php
            }

            /****************************************
            DEFAULT PAGE (NO $_GET EXISTS YET)
             *****************************************/
            else {
                $users = $this->model->get_users();
                ?>
                <h2> Manage Users </h2>
                <p>We have a total of <strong><?php echo $this->model->memberCount; ?></strong> registered users. </p>
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
                    foreach ($users as $showUsers) {
                        echo ('<tr><td>'.$showUsers['full_name'].' </td>
                        <td><p><a href="/user/profile/view/'.$showUsers['username'].'">'.$showUsers['username'].'</a></td>
                        <td>'.date('F j, Y', $showUsers['time']). '</td>
                        <td><a href="/admin/user/edit/' .$showUsers['id']. '">Edit</a></td>
                        <td><a  onClick=\'ajaxCall("/admin/user/delete/' .$showUsers['id'].'", "manage-users")\'>Delete</a></td>

                        </tr>');
                    }
                    ?>
                    </tbody>
                </table>
                <?php
            }
            ?>
        </div>
    </div>
</div>
