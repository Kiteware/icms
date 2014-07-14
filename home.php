<?php 
require 'core/init.php';
$general->logged_out_protect();
$posts 		=$blog->get_posts();
$username 	= htmlentities($user['username']); 
include("templates/default/head.php"); 
include("templates/default/header.php"); 

?>
<body>	
	<div id="container">
		<h1>Hello <?php echo $username, '!'; ?></h1>
	</div>
	<?php 
		foreach ($posts as $post) {
			$content = htmlentities($post['post_content']);
			?>

			<p><?php echo $content?></a> <br />created: <?php echo date('F j, Y', $post['post_date']) ?></p>
			<?php
		}

		?>
</body>
<?php include("templates/default/footer.php"); ?>
