<?php
if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
}
$Parsedown = new Parsedown();
$posts = $this->model->posts;
$content = $Parsedown->text($posts['post_content']);
?>
<section class="content">
    <article>
    <?php
        if (!is_array($posts)) {
            ?>
            <h1 class="title"> <a href="/blog/view/<?php echo $post['post_id']?>"><?php echo $posts['post_name'] ?></a></h1>
            <hr>
            <p><?php echo $content ?></p>
            <p class="date"><?php echo date_format(date_create($posts['post_date']), "F j, Y") ?></p>
            <hr>
            <?php
        } elseif (empty($posts)) {
            ?>
            <p> There are currently no blog posts.</p>
            <?php
        }
        else {
            foreach ($posts as $post) {
                $content = $Parsedown->text($post['post_content']); ?>
                <hr />
                <h1><?php echo $post['post_name']?></h1>
                <p class="text-muted"><?php echo date('F j, Y', strtotime($post['post_date'])) ?></p>
                <p><?php echo $this->model->truncate($content,"<a href=\"/blog/view/".$post['post_id']."\">Read more</a>") ?></p>
    <?php   }
        } ?>
    </article>
</section>
