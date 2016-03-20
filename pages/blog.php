<?php
if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
}
$posts = $this->model->posts;
?>
<div class="wrapper">
    <section class="content">
        <article>
            <?php
            if (!is_array($posts)) {
                ?>
                <h1> <a href="/blog/view/<?php echo $post['post_id']?>"><?php echo $posts['post_name'] ?></a></h1>
                <p><?php echo $posts['post_content'] ?></p>
                <p class="date">created: <?php echo date_format(date_create($posts['post_date']), "F j, Y") ?></p>
                <hr>
                <?php
            } else {
                foreach ($posts as $post) {
                    ?>
                    <h1><a href="/blog/view/<?php echo $post['post_id']?>"><?php echo $post['post_name'] ?></a></h1>
                    <p><?php echo $post['post_content'] ?></p>
                    <p class="date">created: <?php echo date_format(date_create($post['post_date']), "F j, Y") ?></p>
                    <?php
                }
            }
            ?>
        </article>
    </section>
</div>
