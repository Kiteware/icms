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
            <?php echo $posts['post_content'] ?> <br/>
                <p>created: <?php echo date_format(date_create($posts['post_date']), "F j, Y") ?></p>
        <?php
        } else {
            foreach ($posts as $post) {
                ?>
                <?php echo $post['post_content'] ?> <br/>
                    <p>created: <?php echo date_format(date_create($post['post_date']), "F j, Y") ?></p>
                    <hr>
            <?php
            }
        }
        ?>
      </article>
    </section>
</div>
