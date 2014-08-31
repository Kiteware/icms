<?php if(count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400); 
    exit('400: Bad Request'); 
    } ?>
<?php
        echo ('<h2>Write New Post</h2>');

        // check for a submitted form
        if(isset($_POST['add_post'])){
                $postName = $_POST['postName'];
                $postContent = $_POST['html'];

                //Check to make sure fields are filled in       
                if(empty($postName) OR empty ($postContent)){
                        echo ('Make sure you filled out all the fields!');
                }
                else{
                
					$blog->newBlogPost($postName, $postContent);
					{ header("Location: index.php");}
                }
        }
?>
<script src="../includes/editor/js/main.js"></script>
<script src="../includes/editor/js/showdown.js"></script>
<div id="content">
  <div class="box">
    <div class="box-header">New Blog Entry</div>
    <div class="box-body">
    <form action="index.php?page=new_blog.php" method="post" name="post" enctype="multipart/form-data">
        <p>Title:<br />
        <input name="postName" type="text" size="45" />         <input name="add_post" type="submit" value="Save"/>
        </p>

       <div id="left-column">
			<div id="top_panels_container">
				<div class="top_panel" id="quick-reference">
					<div class="close">×</div>
					<table>
						<tr>
							<td>
								<pre><code><span class="highlight">*</span>This is italicized<span class="highlight">*</span>, and <span class="highlight">**</span>this is bold<span class="highlight">**</span>.</code></pre>
							</td>
							<td><p>Use <code>*</code> or <code>_</code> for emphasis.</p></td>
						</tr>
						<tr>
							<td>
								<pre><code>This is a first level header
<span class="highlight">============================</span></code></pre>
							</td>
							<td><p>You can alternatively put hash marks at the beginning of the line: <code>#&nbsp;H1</code>, <code>##&nbsp;H2</code>, <code>###&nbsp;H3</code>...</p></td>
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
							<td><p>Unordered (bulleted) lists use asterisks, pluses, and hyphens (<code>*</code>, <code>+</code>, and <code>-</code>) as list markers.</p></td>
						</tr>
						<tr>
							<td>
								<pre><code><span class="highlight">1. </span>Ordered list item
<span class="highlight">2. </span>Ordered list item</code></pre>
							</td>
							<td><p>Ordered (numbered) lists use regular numbers, followed by periods, as list markers.</p></td>
						</tr>
						<tr>
							<td><pre><code><span class="highlight">    </span>/* This is a code block */</code></pre></td>
							<td><p>Indent four spaces for a preformatted block.</p></td>
						</tr>
						<tr>
							<td><pre><code>Let's talk about <span class="highlight">`</span>&lt;html&gt;<span class="highlight">`</span>!</code></pre></td>
							<td><p>Use backticks for inline code.</p></td>
						</tr>
						<tr>
							<td>
								<pre><code><span class="highlight">![Valid XHTML](http://w3.org/Icons/valid-xhtml10)</span></code></pre>
							</td>
							<td><p>Images are exactly like links, but they have an exclamation point in front of them.</p></td>
						</tr>
					</table>
					<p><a href="http://daringfireball.net/projects/markdown/syntax" target="_blank">Full Markdown documentation</a></p>
				</div>
			</div>
			<div class="wrapper">
				<div class="topbar hidden-when-fullscreen">
					<div class="buttons-container">
						<a href="#" class="button toppanel" data-toppanel="quick-reference">Quick Reference</a>
						<a href="#" class="button icon-arrow-expand feature" data-feature="fullscreen" data-tofocus="markdown" title="Go fullscreen"></a>
						<div class="clear"></div>
					</div>
				</div>
				<textarea id="markdown" class="full-height" placeholder="Write Markdown">Hello World
=================================

You can either use the editor on the left, which uses Markdown or edit the HTML directly on the right!

Getting started
---------------

### Buttons you might want to use

- **Quick Reference**: that's a reminder of the most basic rules of Markdown
- **HTML | Preview**: *HTML* to see the markup generated from your Markdown text, *Preview* to see how it looks like
</textarea>
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
						<a href="#" class="button icon-arrow-down-a feature" data-feature="auto-scroll" title="Toggle auto-scrolling to the bottom of the preview panel"></a>
						<a href="#" class="button icon-arrow-expand feature" data-feature="fullscreen" data-tofocus="" title="Go fullscreen"></a><!-- data-tofocus is set dynamically by the HTML/preview switch -->
						<div class="clear"></div>
					</div>
				</div>
				<div class="bottom-bar hidden-when-fullscreen">
					<div class="word-count"></div>
					<div class="clear"></div>
				</div>
				<textarea name="html" id="html" class="full-height"></textarea>
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
				<a href="#" class="button icon-arrow-expand feature" data-feature="fullscreen" title="Exit fullscreen"></a>
				<div class="clear"></div>
			</div>
		</div>
		<div class="bottom-bar visible-when-fullscreen">
			<div class="word-count"></div>
			<div class="clear"></div>
		</div>
</form>
</div>
</div>
</div>
<script>
$(document).ready(function() {

	app = {

		// Web app variables
		supportsLocalStorage: ("localStorage" in window && window.localStorage !== null),

		init: function() {
			editor.init();
		},

		// Save a key/value pair in localStorage (either Markdown text or enabled features)
		save: function(key, value) {
			if (!this.supportsLocalStorage) return false;

			// Even if localStorage is supported, using it can still throw an exception if disabled or the quota is exceeded
			try {
				localStorage.setItem(key, value);
			} catch (e) {}
		},

		// Restore the editor's state from localStorage (saved Markdown and enabled features)
		restoreState: function(c) {
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
		updateMarkdownPreview: function(html) {
			editor.markdownPreview.html(html);
			editor.updateWordCount(editor.markdownPreview.text());
		}

	};

	app.init();

});</script>
