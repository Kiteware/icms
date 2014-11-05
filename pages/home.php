<?php
if(count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400); 
    exit('400: Bad Request'); 
    }
?> 
<div class="wrapper">
  <section class="content">
    <article>
    <h1>Welcome</h1>
    <p>ICMS was made to help kickstart websites. Instead of starting from scratch and going on a hide and seek mission for code snippets, I want to just load up ICMS, remove things I don't need and get to work styling.</p>
       <blockquote>
        All content not saved will be lost. - Nintendo
      </blockquote>
    
        <code data-lang="php" class="lang">
    $login = $users->login($username, $password);
		if ($login === false) {
			$errors[] = 'Sorry, that username/password is invalid';
		}
        </code>

<?php
        $posts 		=$blog->get_posts();
        foreach ($posts as $post) {
			$content = htmlentities($post['post_content']);
			?>
    <div class="post-info right">
      <?php echo date('F j, Y', strtotime($post['post_date'])) ?>
    </div>
    <h1><?php echo $post['post_name']?></h1>
    <hr />
		<p>
			<?php echo html_entity_decode($content)?> <br />
            <a href="#">Read more</a>
			<?php
		} 
		?>
		</p>
        
</article>
</section>
</div>
</body>