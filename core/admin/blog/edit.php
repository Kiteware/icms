<?php defined('_ICMS') or die; ?>
<div class="box">
	<div class="box-header">Edit Blog Entry</div>
	<div class="box-body">
		<?php
		/******************************************
		 * $_GET['action'] = edit/delete
		 * $_GET['ID'] = id of selected post
		 *****************************************/
		//gets the post id from the url
		if (!empty($this->controller->id)) {
			$ID = $this->controller->id;
			$selectPost = $this->controller->posts;

			?>
			<form action="/admin/blog/update/<?php echo $ID ?>" class="no-reload-form" method="post">
				<div class="row">
					<div class="col-sm-8">
						<fieldset class="form-group">
							<label for="postName">Title</label>
							<input type="text" class="form-control" name="postName" id="postName" value="<?php echo $selectPost[0]['post_title'] ?>" />
						</fieldset>
					</div>
					<div
						class="col-sm-4">
						<fieldset class="form-group">
							<label>Status</label>
							<p><?php echo $this->controller->published ?>
								<a href="/blog/view/<?php echo $ID ?>" target="_blank" class="btn btn-info">View</a></p>
						</fieldset>

					</div>
				</div>
				<fieldset class="form-group">
					<label for="postContent">Content</label>
					<textarea class="form-control" name="postContent" id="postContent"><?php echo $selectPost[0]['post_content'] ?></textarea>
				</fieldset>
				<fieldset class="form-group">
					<label for="postDesc">Meta Description</label>
					<input type="text" class="form-control" name="postDesc" id="postDesc" value="<?php echo $selectPost[0]['post_description'] ?>" />
				</fieldset>
				<button name="submit" type="submit" value="publish" class="btn btn-primary">Publish</button>
				<button name="submit" type="submit" value="draft" class="btn btn-warning">Draft</button>
				<a href="/admin" class="btn btn-danger pull-right">Cancel</a>
			</form>
			<?php
		}
		/****************************************
		DEFAULT PAGE (NO $_GET EXISTS YET)
		 *****************************************/
		else {
		echo('
		<div class="pull-left">
			<h2> Manage Posts </h2>
		</div>
		<div class="pull-right">
			<h2><a class="btn btn-primary" href="/admin/blog/create" role="button">New Blog Post</a></h2>
		</div>');
		$allBlogPosts = $this->controller->posts;
		if (!empty($allBlogPosts)) { ?>
		<table class="table table-striped" id="manage-posts">
			<thead>
			<tr>
				<th>Title</th>
				<th>Status</th>
				<th>Posted</th>
				<th>Author</th>
				<th>View</th>
				<th>Edit</th>
				<th>Delete</th>
			</tr>
			</thead>
			<tbody>
			<?php
			foreach ($allBlogPosts as $post) {
				//displaying posts
				if($post['post_published'] === '1') $published = "published"; else { $published = "draft"; }
				echo('<tr><td>' . $post['post_title'] . '</td>
						<td>'. $published .'</td>
						<td>'. $post['post_date'] .'</td>
						<td>'. $post['post_author'] .'</td>
						<td><a href="/blog/view/'. $post['post_id'] .'"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
						<td> <a href="/admin/blog/edit/' . $post['post_id'] . '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
						<td> <a onClick=\'ajaxCall("/admin/blog/delete/' . $post['post_id'] . '", "manage-posts")\'> <i class="fa fa-trash" aria-hidden="true"></i> </a></td>
						</tr>');
			}
			echo("</tbody></table>");
			} else {
				echo "<h4>You have no blog posts, <a href='/admin/blog/create'>create one?</a> </h4>";
			}
			} ?>
	</div>
</div>
