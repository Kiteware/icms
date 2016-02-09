<?php if (count(get_included_files()) ==1) {
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
		<div class="box-header">Admin Panel</div>
		<div id="result"></div>
		<div class="box-body">
			<?php
			/******************************************
			 * $_GET['action'] = edit/delete
			 * $_GET['ID'] = id of selected post
			 *****************************************/
            $ID = $this->model->id; //gets the post id from the url
			if ($ID) {
				$action = $this->model->action; // gets action from url, edit or delete
                if ($action == "edit") {
					$selectPost = $this->model->posts;
					?>
                <form action="/admin/blog/update" id="edit_blog">
					<h2>Edit <?php echo $selectPost[0]['post_name'] ?></h2>

                    <div id="toolbar">
                        <span id="mode" class="icon-mode"></span>
                        <span id="hinted" class="icon-pre disabled" title="Toggle Markdown Hints"></span>
                    </div>

                    <!-- <div id="custom-toolbar" class="pen-menu pen-menu" style="display: block; top: 20px; left: 10px;">
                      <i class="pen-icon icon-insertimage" data-action="insertimage"></i>
                      <i class="pen-icon icon-blockquote" data-action="blockquote"></i>
                      <i class="pen-icon icon-h2" data-action="h2"></i>
                      <i class="pen-icon icon-h3" data-action="h3"></i>
                      <i class="pen-icon icon-p active" data-action="p"></i>
                      <i class="pen-icon icon-code" data-action="code"></i>
                      <i class="pen-icon icon-insertorderedlist" data-action="insertorderedlist"></i>
                      <i class="pen-icon icon-insertunorderedlist" data-action="insertunorderedlist"></i>
                      <i class="pen-icon icon-inserthorizontalrule" data-action="inserthorizontalrule"></i>
                      <i class="pen-icon icon-indent" data-action="indent"></i>
                      <i class="pen-icon icon-outdent" data-action="outdent"></i>
                      <i class="pen-icon icon-bold" data-action="bold"></i>
                      <i class="pen-icon icon-italic" data-action="italic"></i>
                      <i class="pen-icon icon-underline" data-action="underline"></i>
                      <i class="pen-icon icon-createlink" data-action="createlink"></i>
                    </div> -->

                    <div id="post_html" data-toggle="pen" data-placeholder="im a placeholder">
                        <?php echo $selectPost[0]['post_content'] ?>
                    </div>
                    <label>
				        <span>&nbsp;</span><input type="submit" id="submit_button" value="Submit" />
			        </label>
                </form>
                    <div id="results"></div>


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
			<table class="table table-striped">
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
			<td><a href="/admin/blog/delete/' .$showPost['post_id'].'">Delete</a></td>
			</tr>');
				}
				echo("</tbody></table>");
				}

				?>
		</div>
	</div>
</div>
<script src="/includes/pen.js"></script>
<script src="/includes/markdown.js"></script>
<script type="text/javascript">
    $("#submit_button").on('click', function(e) {
        e.preventDefault();

        //get input field values data to be sent to server
        post_data = {
            'post_name': '<?php echo $selectPost[0]['post_name'] ?>',
            'post_id': '<?php echo $ID ?>',
            'post_content': $('#post_html').html()
        };

        //Ajax post data to server
        $.post('/admin/blog/update', post_data, function (response) {
            if (response.type == 'error') { //load json data from server and output message
                output = '<div class="error">' + response.text + '</div>';
            } else {
                output = '<div class="success">' + response.text + '</div>';
            }
            $("#results").hide().html(output).slideDown();
        }, 'json');

    });

    // config
    var options = {
        // toolbar: document.getElementById('custom-toolbar'),
        editor: document.querySelector('[data-toggle="pen"]'),
        debug: true,
        list: [
            'insertimage', 'blockquote', 'h2', 'h3', 'p', 'code', 'insertorderedlist', 'insertunorderedlist', 'inserthorizontalrule',
            'indent', 'outdent', 'bold', 'italic', 'underline', 'createlink'
        ]
    };

    // create editor
    var pen = window.pen = new Pen(options);

    pen.focus();

    // toggle editor mode
    document.querySelector('#mode').addEventListener('click', function() {
        var text = this.textContent;

        if(this.classList.contains('disabled')) {
            this.classList.remove('disabled');
            pen.rebuild();
        } else {
            this.classList.add('disabled');
            pen.destroy();
        }
    });

    // toggle editor mode
    document.querySelector('#hinted').addEventListener('click', function() {
        var pen = document.querySelector('.pen')

        if(pen.classList.contains('hinted')) {
            pen.classList.remove('hinted');
            this.classList.add('disabled');
        } else {
            pen.classList.add('hinted');
            this.classList.remove('disabled');
        }
    });
</script>
