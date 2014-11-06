<?php
 if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
    }
$posts        =$blog->get_posts();
?>
<body>
	<div id="container">
		<?php
        foreach ($posts as $post) {
            $content = htmlentities($post['post_content']);
            ?>

			<p><?php echo $content?></a> <br />created: <?php echo date('F j, Y', $post['post_date']) ?></p>
			<?php
        }

        ?>

	</div>
</body>
