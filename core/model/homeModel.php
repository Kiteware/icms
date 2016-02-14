<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */

/*
|--------------------------------------------------------------------------
| Model
|--------------------------------------------------------------------------
|
| Basic Model Class - Called on /index.php
|
*/
class homeModel {
    public $posts;
    public $container;

    public function __construct(Pimple\Container $container) {
        $this->container = $container;
        $blog        = new BlogModel($container);
        $this->posts = $blog->get_posts();
    }
}
