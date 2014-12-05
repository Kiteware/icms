<?php
if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
}
$posts        =$blog->get_posts();
?>
<div class="wrapper">
    <section class="content">
        <article>
            <h1>Welcome</h1>
            <p>ICMS was made to help kickstart websites. A simple and fast engine that supports user registration, permission levels,
                blogging, and static page creation.</p>
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
            foreach ($posts as $post) {
            ?>
            <div class="post-info right">
                <?php echo date('F j, Y', strtotime($post['post_date'])) ?>
            </div>
            <h1><?php echo $post['post_name']?></h1>
            <hr />
            <p>
                <?php echo $post['post_content']?> <br />
                <a href="index.php?page=blog&postid=<?php echo $post['post_id']?>">Read more</a>
                <?php
                }
                ?>
            </p>

        </article>
    </section>
</div>
</body>
