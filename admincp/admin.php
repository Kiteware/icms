<?php if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
} ?>
<div id="content">
    <div class="box">
        <div class="box-header">Admin Panel</div>
        <div class="box-body">
            <?php
            if (empty($errors) === false) {
                echo '<p>' . implode('</p><p>', $errors) . '</p>';
            }
            ?>
            <p>Welcome to the ICMS Administrator panel. <br />
            GitHub: <a href="https://github.com/Nixhatter/CMS">https://github.com/Nixhatter/CMS </a><br />
            Support: dillon@nixx.co</p>
        </div>
    </div>
    <div id="content_left">
        <div class="box">
            <div class="box-header">Menu Manager</div>
            <div class="box-body">
                <table>
                    <tr><th>Name</th><th>URL</th><th>Positions</th><th>Edit</th><th>Delete</th></tr>
                    <?php
                    $navigation        =$this->model->list_nav();
                    foreach ($navigation as $showNav) {
                        //displaying posts
                        echo "
            <tr><td>".($showNav['nav_name']."</td>
			<td>".$showNav['nav_link']."</td>
			<td>".$showNav['nav_position']."</td>
            <td><a onClick='editNav(\"".$showNav['nav_name']."\", \"".$showNav['nav_link']."\", \"".$showNav['nav_position']."\", \"".$showNav['nav_permission']."\");'>edit</a></td>
            <td><a onClick='deleteNav(\"".$showNav['nav_link']."\");'> Delete </a></td></tr>");
                    }
                    ?></table>
                <button id= "edit_menu" class="edit_menu"> New Menu</button>
                <div style="display: none" class="hidden_menu">
                    <form  id="menu_manager" action="index.php?page=edit_menu.php" method="post" name="post">
                        Name <input id="nav_name" name="nav_name" type="text" size="15"/> <br />
                        Link <input id="nav_link" name="nav_link" type="text" size="15"/> <br />
                        Position <input id="nav_position" name="nav_position" type="text" size="5"/> <br />
                        <input type="hidden" name="create" id="create" value="yes"/>
                        <input name="submit" id="submit" type="submit" value="create"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="content_right">
        <div class="box">
            <div class="box-header">New Blog Post</div>
            <div class="box-body">
                <form action="index.php?page=new_blog.php" method="post" name="post">
                    <p>Title:<br />
                        <input name="postName" type="text" size="45" />
                    </p>

                    <p>Preview:<br />
                        <textarea name="postPreview" cols="50" rows="3"></textarea>
                    </p>

                    <p>Content:<br />
                        <textarea name="postContent" cols="50" rows="10"></textarea>
                    </p>

                    <input name="add_post" type="submit" value="Add Post"/>
                </form>
            </div>
        </div>
    </div>
</div>
