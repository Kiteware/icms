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
            <p><?php echo $post['post_content'] ?></a> <br/>
                created: <?php echo date_format(date_create($post['post_date']), "F j, Y") ?></p>
        <?php
        } else {
            foreach ($posts as $post) {
                ?>

                <p><?php echo $post['post_content'] ?></a> <br/>
                    created: <?php echo date_format(date_create($post['post_date']), "F j, Y") ?></p>
            <?php
            }
        }
        ?>
    </section>
</div>
</body>
