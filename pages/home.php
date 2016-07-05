<?php defined('_ICMS') or die; ?>

<div class="container">
    <div class="row">
        <div class="content">
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

                <hr />

                <?php echo $this->controller->blogPage;  ?>
            </article>
        </div>
    </div>
</div>
