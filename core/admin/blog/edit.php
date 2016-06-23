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
						<input type="text" class="form-control" name="postName" id="postName" value="<?php echo $selectPost[0]['post_title'] ?>">
					</fieldset>
					<fieldset class="form-group">
						<label for="postContent">Content</label>
						<textarea class="form-control" name="postContent"><?php echo $selectPost[0]['post_content'] ?></textarea>
					</fieldset>
					<fieldset class="form-group">
						<label for="postDesc">Meta Description</label>
						<input type="text" class="form-control" name="postDesc" id="postDesc" value="<?php echo $selectPost[0]['post_description'] ?>">
					</fieldset>
					<button name="submit" type="submit" value="publish" class="btn btn-primary">Publish</button>
					<button name="submit" type="submit" value="draft" class="btn btn-warning">Draft</button>
				</form>
				<?php
			}
		}
		/****************************************
		DEFAULT PAGE (NO $_GET EXISTS YET)
		 *****************************************/
		else {
		echo('
		<div class="col-sm-11">
			<h2> Manage Posts </h2>
		</div>
		<div class="col-sm-1">
			<h2><a class="btn btn-primary" href="/admin/blog/create" role="button">New Blog Post</a></h2>
		</div>');
		$query = $this->model->get_posts();
		if (!empty($query)) { ?>
		<table class="table table-striped" id="manage-posts">
			<thead>
			<tr>
				<th>Title</th>
				<th>Status</th>
				<th>Posted</th>
				<th>Author</th>
				<th>Edit</th>
				<th>Delete</th>
			</tr>
			</thead>
			<tbody>
			<?php
			foreach ($query as $showPost) {
				//displaying posts
				if($showPost['post_published'] == 1) $published = "published"; else { $published = "draft"; }
				echo('<tr><td>' . $showPost['post_title'] . '</td>
						<td>'. $published .'</td>
						<td>'. $showPost['post_date'] .'</td>
						<td>'. $showPost['post_author'] .'</td>
						<td> <a href="/admin/blog/edit/' . $showPost['post_id'] . '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
						<td> <a onClick=\'ajaxCall("/admin/blog/delete/' . $showPost['post_id'] . '", "manage-posts")\'> <i class="fa fa-trash" aria-hidden="true"></i> </a></td>
						</tr>');
			}
			echo("</tbody></table>");
			} else {
				echo "<h4>You have no blog posts, <a href='/admin/blog/create'>create one?</a> </h4>";
			}
			} ?>
	</div>
</div>
