<?php if (count(get_included_files()) ==1) {
	header("HTTP/1.0 400 Bad Request", true, 400);
	exit('400: Bad Request');
} ?>
<script src="../includes/editor/js/main.js"></script>
<script src="../includes/editor/js/showdown.js"></script>
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
			if (isset($_GET['action'])) {

				$action = $_GET['action']; // gets action from url, edit or delete
				$ID = $_GET['ID']; //gets the post id from the url

				if ($action == "delete") {

					$selectPost = $blog->get_post($ID);
					//Confirm with user they want to delete, if yes refresh and do isset['yes']
					echo('Are you sure you want to permanently delete ' . $selectPost['post_name'] . '?
				<form action="controller/edit_blog_controller.php" method="post" id="deleteBlogForm">
				<input name="postID" type="hidden" value="' . $selectPost['post_id'] . '">
				<input name="delete" type="submit" value="yes" />
				<input name="no" ONCLICK="history.go(-1)" type="button" value="No" />
				</form>');
				} elseif ($action == "edit") {
					$selectPost = $blog->get_post($ID);
					?>

					<h2>Edit <?php echo $selectPost['post_name'] ?></h2>

					<form action="controller/edit_blog_controller.php" method="post" id="editBlogForm">
						<p>Name:<br/>
							<input name="postName" type="text" size="45"
								   value="<?php echo $selectPost['post_name'] ?>"/>
						</p>

						<p>Post ID:<br/>
							<input name="postID" type="text" size="45" value="<?php echo $ID ?>"/>
						</p>

						<div id="left-column">
							<div id="top_panels_container">
								<div class="top_panel" id="quick-reference">
									<div class="close">�</div>
									<table>
										<tr>
											<td>
												<pre><code><span class="highlight">*</span>This is italicized<span
															class="highlight">*</span>, and <span
															class="highlight">**</span>this is bold<span
															class="highlight">**</span>.</code></pre>
											</td>
											<td><p>Use <code>*</code> or <code>_</code> for emphasis.</p></td>
										</tr>
										<tr>
											<td>
								<pre><code>This is a first level header
										<span class="highlight">============================</span></code></pre>
											</td>
											<td><p>You can alternatively put hash marks at the beginning of the line:
													<code>#&nbsp;H1</code>, <code>##&nbsp;H2</code>,
													<code>###&nbsp;H3</code>...</p></td>
										</tr>
										<tr>
											<td>
												<pre><code>This is a link to <span class="highlight">[Google](http://www.google.com)</span></code></pre>
											</td>
											<td><p></p></td>
										</tr>
										<tr>
											<td>
								<pre><code>First line.<span class="highlight">  </span>
										Second line.</code></pre>
											</td>
											<td><p>End a line with two spaces for a linebreak.</p></td>
										</tr>
										<tr>
											<td>
								<pre><code><span class="highlight">- </span>Unordered list item
										<span class="highlight">- </span>Unordered list item</code></pre>
											</td>
											<td><p>Unordered (bulleted) lists use asterisks, pluses, and hyphens (<code>*</code>,
													<code>+</code>, and <code>-</code>) as list markers.</p></td>
										</tr>
										<tr>
											<td>
								<pre><code><span class="highlight">1. </span>Ordered list item
										<span class="highlight">2. </span>Ordered list item</code></pre>
											</td>
											<td><p>Ordered (numbered) lists use regular numbers, followed by periods, as
													list markers.</p></td>
										</tr>
										<tr>
											<td>
												<pre><code><span class="highlight">    </span>/* This is a code block */</code></pre>
											</td>
											<td><p>Indent four spaces for a preformatted block.</p></td>
										</tr>
										<tr>
											<td>
												<pre><code>Let's talk about <span class="highlight">`</span>&lt;html&gt;<span
															class="highlight">`</span>!</code></pre>
											</td>
											<td><p>Use backticks for inline code.</p></td>
										</tr>
										<tr>
											<td>
												<pre><code><span class="highlight">![Valid XHTML](http://w3.org/Icons/valid-xhtml10)</span></code></pre>
											</td>
											<td><p>Images are exactly like links, but they have an exclamation point in
													front of them.</p></td>
										</tr>
									</table>
									<p><a href="http://daringfireball.net/projects/markdown/syntax" target="_blank">Full
											Markdown documentation</a></p>
								</div>
							</div>
							<div class="wrapper">
								<div class="topbar hidden-when-fullscreen">
									<div class="buttons-container">
										<a href="#" class="button toppanel" data-toppanel="quick-reference">Quick
											Reference</a>
										<a href="#" class="button icon-arrow-expand feature" data-feature="fullscreen"
										   data-tofocus="markdown" title="Go fullscreen"></a>

										<div class="clear"></div>
									</div>
								</div>
								<textarea id="markdown" class="full-height"
										  placeholder="Write Markdown"><?php echo $selectPost['post_content'] ?></textarea>
							</div>
						</div>
						<div id="right-column">
							<div class="wrapper">
								<div class="topbar hidden-when-fullscreen">
									<div class="buttons-container">
										<div class="button-group">
											<a href="#" class="button switch" data-switchto="html">HTML</a>
											<a href="#" class="button switch" data-switchto="preview">Preview</a>
										</div>
										<a href="#" class="button icon-arrow-down-a feature" data-feature="auto-scroll"
										   title="Toggle auto-scrolling to the bottom of the preview panel"></a>
										<a href="#" class="button icon-arrow-expand feature" data-feature="fullscreen"
										   data-tofocus="" title="Go fullscreen"></a><!-- data-tofocus is set dynamically by the HTML/preview switch -->
										<div class="clear"></div>
									</div>
								</div>
								<div class="bottom-bar hidden-when-fullscreen">
									<div class="word-count"></div>
									<div class="clear"></div>
								</div>
								<textarea name="html" id="html"
										  class="full-height"><?php $selectPost['post_content'] ?></textarea>

								<div id="preview" class="full-height"></div>
							</div>
						</div>
						<div class="clear"></div>
						<div class="topbar visible-when-fullscreen">
							<div class="buttons-container">
								<div class="button-group">
									<a href="#" class="button switch" data-switchto="markdown">Markdown</a>
									<a href="#" class="button switch" data-switchto="html">HTML</a>
									<a href="#" class="button switch" data-switchto="preview">Preview</a>
								</div>
								<a href="#" class="button icon-arrow-expand feature" data-feature="fullscreen"
								   title="Exit fullscreen"></a>

								<div class="clear"></div>
							</div>
						</div>
						<div class="bottom-bar visible-when-fullscreen">
							<div class="word-count"></div>
							<div class="clear"></div>
						</div>

						<input name="update" type="submit" value="update"/>
					</form>

				<?php
				}
			}
			/****************************************
			DEFAULT PAGE (NO $_GET EXISTS YET)
			 *****************************************/
			else {
			echo ('<h2> Manage Posts </h2>');
			$query = $blog->get_posts();
			?>
			<table>
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
					echo ('<tr><td>'.$showPost['post_name'].'</td>
			<td> <a href="index.php?page=edit_blog&action=edit&ID='.$showPost['post_id'].'">Edit</a></td>
			<td><a href="index.php?page=edit_blog&action=delete&ID='.$showPost['post_id'].'">Delete</a></td>
			</tr>');
				}
				echo("</tbody></table>");
				}

				?>
		</div>
	</div>
</div>
<script>
	function getURLParameter(name)
	{
		return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null
	}

	action = getURLParameter('action');

	$(document).ready(function () {
		if (action == "edit") {
			app = {

				// Web app variables
				supportsLocalStorage: ("localStorage" in window && window.localStorage !== null),

				init: function () {
					editor.init();
				},

				// Save a key/value pair in localStorage (either Markdown text or enabled features)
				save: function (key, value) {
					if (!this.supportsLocalStorage) return false;

					// Even if localStorage is supported, using it can still throw an exception if disabled or the quota is exceeded
					try {
						localStorage.setItem(key, value);
					} catch (e) {}
				},

				// Restore the editor's state from localStorage (saved Markdown and enabled features)
				restoreState: function (c) {
					var restoredItems = {};

					if (this.supportsLocalStorage) {
						// Even if localStorage is supported, using it can still throw an exception if disabled
						try {
							restoredItems.markdown = localStorage.getItem("markdown");
							restoredItems.isAutoScrolling = localStorage.getItem("isAutoScrolling");
							restoredItems.isFullscreen = localStorage.getItem("isFullscreen");
							restoredItems.activePanel = localStorage.getItem("activePanel");
						} catch (e) {}
					}

					c(restoredItems);
				},

				// Update the preview panel with new HTML
				updateMarkdownPreview: function (html) {
					editor.markdownPreview.html(html);
					editor.updateWordCount(editor.markdownPreview.text());
				}

			};

			app.init();
		}

	});

	//JQUERY SUBMIT FORM
	// Attach a submit handler to the form
	$( "#editBlogForm" ).submit(function( event ) {
// Stop form from submitting normally
		event.preventDefault();
// Get some values from elements on the page:
		var $form = $( this ),
			postName = $form.find( "input[name='postName']" ).val(),
			postID = $form.find( "input[name='postID']" ).val(),
			html = $form.find( "textarea[name='html']" ).val(),
			url = $form.attr( "action" );
// Send the data using post
		var posting = $.post( url, {
			postName: postName,
			postID: postID,
			html: html,
			action: 'update',
			token: '<?php echo $hashed;?>'
		} );
// Put the results in a div
		posting.done(function( data ) {
			var content = $( data );
			$( "#result" ).empty().append( content );
		});
	});
	//JQUERY DELETE BLOG FORM
	// Attach a submit handler to the form
	$( "#deleteBlogForm" ).submit(function( event ) {
// Stop form from submitting normally
		event.preventDefault();
// Get some values from elements on the page:
		var $form = $( this ),
			postID = $form.find( "input[name='postID']" ).val(),
			url = $form.attr( "action" );
// Send the data using post
		var posting = $.post( url, {
			postID: postID,
			action: 'delete',
			token: '<?php echo $hashed;?>'
		} );
// Put the results in a div
		posting.done(function( data ) {
			var content = $( data );
			$( "#result" ).empty().append( content );
		});
	});
</script>
