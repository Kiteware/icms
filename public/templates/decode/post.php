<?php
/**
 * Post.php
 * Included after the content but before the footer
 */
?>
<section class="bg-dark section-md">
    <div class="container">
        <h2 class="margin0 text-center ">Join our Mailing List</h2>
        <div class="subscribe wow fadeInUp subscribe-form">
            <form class="form-inline" role="form" action="/home/home/subscribe" method="post" id="subscribe">
                <input type="text" name="email" placeholder="email">
                <button type="submit">Subscribe</button>
            </form>
            <div id="response" class="text-center"></div>
        </div>
    </div>
</section>