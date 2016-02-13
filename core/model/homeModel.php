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
    public $text;
    public $posts;

    public function __construct($db) {
        $blog        = new BlogModel($db);
        $this->posts        =$blog->get_posts();
        $this->text = 'Hello world!';
    }
}
