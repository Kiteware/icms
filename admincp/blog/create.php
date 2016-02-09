<?php
if (count(get_included_files()) ==1) {
	header("HTTP/1.0 400 Bad Request", true, 400);
	exit('400: Bad Request');
}
/* ------------------
Create Blog Post
Add a new post into the blogs table
Actions:
    - Save
------------------ */
?>
<script src="/includes/editor/js/main.js"></script>
<script src="/includes/editor/js/showdown.js"></script>
<div id="content">
	<div class="box">
		<div class="box-header">New Blog Entry</div>
		<div class="box-body">
			<form action="" method="post" name="post" enctype="multipart/form-data">
				<fieldset class="form-group">
					<label for="postName">Title</label>
					<input type="text" class="form-control" name="postName" id="postName">
				</fieldset>
				<fieldset class="form-group">
					<label for="postContent">Content</label>
					<textarea class="form-control" name="postContent" cols="50" rows="10"></textarea>
				</fieldset>
				<button name="add_post" type="submit" class="btn btn-primary">Publish</button>

		</div>
	</div>
</div>
<script>
	var simplemde = new SimpleMDE();
</script>