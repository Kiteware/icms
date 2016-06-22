<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package ICMS
 * @author Dillon Aykac
 */
namespace Nixhatter\ICMS\model;
/*
|--------------------------------------------------------------------------
| Model
|--------------------------------------------------------------------------
|
| Basic Model Class Template
|
*/
class Model {
    protected $db;
    public $text;
    public $posts;
    public $container;

    public function __construct(\Pimple\Container $container) {
        $this->container    = $container;
        $this->db           = $container['db'];
        $blog               = new BlogModel($container);
        $this->posts        = $blog->get_posts();
    }

    // Creates a preview of the text
    public function truncate($string,$append="&hellip;",$length=300) {
        $trimmed_string = trim($string);
        $stripped_string = strip_tags($trimmed_string);
        if (strlen($stripped_string) < $length) $length = strlen($stripped_string)-10;
        $pos = strpos($stripped_string, ' ', $length);
        return substr($stripped_string,0,$pos )."<br />".$append;
    }
}
