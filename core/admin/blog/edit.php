<?php
if (count(get_included_files()) ==1) {
	header("HTTP/1.0 400 Bad Request", true, 400);
	exit('400: Bad Request');
}
/* ------------------
Edit Blog
Allows you to edit any of the current blog posts
Actions:
    - Edit
    - Save
    - Delete
------------------ */
?>
<div id="content">
	<div class="box">
		<div class="box-header">Edit Blog Entry</div>
		<div class="box-body">
			<?php
			/******************************************
			 * $_GET['action'] = edit/delete
			 * $_GET['ID'] = id of selected post
			 *****************************************/
			//gets the post id from the url
			if (isset($this->model->id)) {
				$ID = $this->model->id;
				$action = $this->model->action; // gets action from url, edit or delete
				if ($action == "edit") {
					$selectPost = $this->model->posts;
					?>
					<form action="/admin/blog/update/<?php echo $ID ?>" class="no-reload-form" method="post" enctype="multipart/form-data">
						<fieldset class="form-group">
							<label for="postName">Title</label>
							<input type="text" class="form-control" name="postName" id="postName" value="<?php echo $selectPost[0]['post_name'] ?>">
						</fieldset>
						<fieldset class="form-group">
							<label for="postContent">Content</label>
							<textarea class="form-control" name="postContent"><?php echo $selectPost[0]['post_content'] ?></textarea>
						</fieldset>
						<button name="add_post" type="submit" class="btn btn-primary">Publish</button>
					</form>
					<?php
				}
			}
			/****************************************
			DEFAULT PAGE (NO $_GET EXISTS YET)
			 *****************************************/
			else {
			echo ('<h2> Manage Posts </h2>');
			$query = $this->model->get_posts();
			?>
			<table class="table table-striped" id="manage-posts">
				<thead>
				<tr>
					<th>Title</th>
					<th>Edit</th>
					<th>Delete</th>
				</tr>
				</thead>
				<tbody>
				<?php
				foreach ($query as $showPost) {
					//displaying posts
					echo ('<tr><td>'.$showPost['post_name']. '</td>
                            <td> <a href="/admin/blog/edit/' .$showPost['post_id']. '">Edit</a></td>
                            <td> <a onClick=\'ajaxCall("/admin/blog/delete/' .$showPost['post_id'].'", "manage-posts")\'> Delete </a></td>

                            </tr>');
				}
				echo("</tbody></table>");
				}
				?>
		</div>
	</div>
</div>