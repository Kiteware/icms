<?php defined('_ICMS') or die; ?>
<div class="box">
	<div class="box-header">New Blog Entry</div>
	<div class="box-body">
		<form action="/admin/blog/create" method="post" class="no-reload-form">
			<fieldset class="form-group">
				<label for="postName">Title</label>
				<input type="text" class="form-control" name="postName" id="postName" required />
			</fieldset>
			<fieldset class="form-group">
				<label for="postContent">Content</label>
				<textarea class="form-control" name="postContent" id="postContent"></textarea>
			</fieldset>
			<fieldset class="form-group">
				<label for="postDesc">Meta Description</label>
				<input type="text" class="form-control" name="postDesc" id="postDesc" required />
			</fieldset>
			<button name="submit" type="submit" value="publish" class="btn btn-primary">Publish</button>
			<button name="submit" type="submit" value="draft" class="btn btn-warning">Draft</button>
			<a href="/admin" class="btn btn-danger pull-right">Cancel</a>
	</div>
</div>