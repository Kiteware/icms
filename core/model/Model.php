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
| Basic Model Class Template
|
*/
class Model {
    public $text;
    public $posts;
    public $container;

    public function __construct(\Pimple\Container $container) {
        $this->container = $container;
        $this->db = $container['db'];
        $blog        = new BlogModel($this->db);
        $this->posts        =$blog->get_posts();
    }
}
