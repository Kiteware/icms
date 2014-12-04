<?php
if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
}

if (isset($_POST['postid'])) {
    $postid = $_POST['postid'];
    $post = $blog->get_post($postid);
} else {
    $posts        =$blog->get_posts();
}
?>
<body>
<div class="wrapper">
    <section class="content">
        <?php
        if (isset($postid)) {
            ?>
            <p><?php echo htmlentities($post['post_content']) ?></a> <br/>
                created: <?php echo date('F j, Y', $post['post_date']) ?></p>
        <?php
        } else {
            foreach ($posts as $post) {
                ?>

                <p><?php echo htmlentities($post['post_content']) ?></a> <br/>
                    created: <?php echo date('F j, Y', $post['post_date']) ?></p>
            <?php
            }
        }
        ?>
    </section>
</div>
</body>
