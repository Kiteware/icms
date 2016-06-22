<?php if (count(get_included_files()) ==1) {
	header("HTTP/1.0 400 Bad Request", true, 400);
	exit('400: Bad Request');
} ?>
<div class="box">
	<div class="box-header">New Blog Entry</div>
	<div class="box-body">
		<form action="/admin/blog/create" method="post" class="reload-form" enctype="multipart/form-data">
			<fieldset class="form-group">
				<label for="postName">Title</label>
				<input type="text" class="form-control" name="postName" id="postName">
			</fieldset>
			<fieldset class="form-group">
				<label for="postContent">Content</label>
				<textarea class="form-control" name="postContent"></textarea>
			</fieldset>
			<fieldset class="form-group">
				<label for="postDesc">Meta Description</label>
				<input type="text" class="form-control" name="postDesc" id="postDesc">
			</fieldset>
			<button name="submit" type="submit" value="publish" class="btn btn-primary">Publish</button>
			<button name="submit" type="submit" value="draft" class="btn btn-warning">Draft</button>
	</div>
</div>