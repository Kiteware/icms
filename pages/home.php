<?php
use Nixhatter\ICMS;
if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
}
?>
<div class="container">
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
            ?>
			<hr />
            <h1><?php echo $post['post_name']?></h1>
            <p>
                <?php echo $post['post_content']?> <br />
                <a href="/blog/view/<?php echo $post['post_id']?>">Read more</a>
                <p><?php echo date('F j, Y', strtotime($post['post_date'])) ?></p>
                <?php
                }
                ?>
            </p>
        </article>
    </section>
</div>
