<?php
use Nixhatter\ICMS;

$Parsedown = new Parsedown();
?>

<div class="container">
    <div class="row">
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
                foreach ($this->model->posts as $post) {
                    $content = $Parsedown->text($post['post_content']); ?>
                    <hr />
                    <h1><a href="/blog/view/<?php echo $post['post_id']?>"><?php echo $post['post_title']?></a></h1>
                    <p class="text-muted"><?php echo date('F j, Y', strtotime($post['post_date'])) ?></p>
                    <p><?php echo $this->model->truncate($content,"<a href=\"/blog/view/".$post['post_id']."\">Read more</a>") ?></p>
                <?php } ?>
            </article>
        </section>
    </div>
</div>
