<?php
if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
}

$posts = $this->controller->posts;
?>
<div class="container section-lg">
    <div class="row">
        <div class="content">
            <article>
                <?php echo $this->controller->blogPage; ?>
            </article>
        </div>
    </div>
</div>
