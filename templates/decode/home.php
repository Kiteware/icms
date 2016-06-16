<?php
use Nixhatter\ICMS;
if (count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
}
$Parsedown = new Parsedown();
?>
<div id="banner">
    <div class="container">
        <div class="col-md-6 col-sm-12">
            <h1>Our First Bootstrap <br /><strong>Theme</strong>  <br />is now available!</h1>
            <p class="muted">Check out more information <a href="#">here</a>.</p>
            <a class="btn btn-app-download" href="#">
                <i class="fa fa-amazon" aria-hidden="true"></i>
                <strong>Buy Now</strong>
                <span>from Amazon!</span>
            </a>
            <a class="btn btn-app-download" href="#">
                <i class="fa fa-download" aria-hidden="true"></i>
                <strong>Buy Now</strong>
                <span>*it's actually free</span>
            </a>
        </div>
        <div class="col-md-5 col-md-offset-1 col-sm-12">
            <img src="/templates/decodecustom/images/decode-book.png" class="img-responsive">
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-lg-9 col-sm-12">
            <section>
                <article>
                    <h1>ICMS Decode Theme</h1>
                    <p>Introducing the new Decode theme, based on http://decodeseries.com. It offers a very modern feel for ICMS. Integrating Bootstrap to make designing even easier for the user. </p>

                    <?php
                    foreach ($this->model->posts as $post) {
                        $content = $Parsedown->text($post['post_content']); ?>
                        <hr />
                        <h1><?php echo $post['post_name']?></h1>
                        <p class="text-muted"><?php echo date('F j, Y', strtotime($post['post_date'])) ?></p>
                        <p><?php echo $this->model->truncate($content,"<a href=\"/blog/view/".$post['post_id']."\">Read more</a>") ?></p>
                    <?php } ?>
                </article>
            </section>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card hovercard">
                <div class="christine">
                </div>
                <div class="avatar">
                    <img alt="Author" src="/templates/decode/images/generic_avatar.png">
                </div>
                <div class="info">
                    <div class="title">
                        <a target="_blank" href="http://">Bob Smith</a>
                    </div>
                    <div class="desc">About 1</div>
                    <div class="desc">About 2</div>
                </div>
                <div class="bottom">
                    <a class="btn btn-primary btn-twitter btn-sm" href="https://twitter.com/#">
                        <i class="fa fa-twitter"></i>
                    </a>
                    <a class="btn btn-warning btn-sm" rel="publisher" href="#">
                        <i class="fa fa-linkedin" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>