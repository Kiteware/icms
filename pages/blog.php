<?php
if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
}
$Parsedown = new Parsedown();
$posts = $this->model->posts;
?>
<div class="container section-md">
    <div class="row">
        <section class="content">
            <article>
                <?php
                if (count($posts) === 1) {
                    // View one blog post
                    $content = $Parsedown->text($posts[0]['post_content']); ?>
                    <h1 class="title"> <?php echo $posts[0]['post_title'] ?></h1>
                    <p class="text-muted"><?php echo date('F j, Y', strtotime($posts[0]['post_date'])) ?></p>
                    <p><?php echo $content ?></p>
                    <?php
                } elseif (empty($posts)) {
                    // No Blog Posts ?>
                    <p> There are currently no blog posts.</p>
                    <?php
                } else {
                    // Multiple Blog Posts
                    foreach ($posts as $post) {
                        $content = $Parsedown->text($post['post_content']); ?>
                        <h1><a href="/blog/view/<?php echo $post['post_id']?>"><?php echo $post['post_title']?></a></h1>
                        <p class="text-muted"><?php echo date('F j, Y', strtotime($post['post_date'])) ?></p>
                        <p><?php echo $this->model->truncate($content,"<a href=\"/blog/view/".$post['post_id']."\">Read more</a>") ?></p>
                        <hr />
                    <?php   }
                } ?>
            </article>
        </section>
    </div>
</div>
