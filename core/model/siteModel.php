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
class SiteModel {
    public $text;
    public $posts;
    private $db;
    public $container;

    public function __construct(\Pimple\Container $container) {
        $this->container = $container;
        $this->db = $container['db'];
        $blog        = new BlogModel($container);
        $this->posts        =$blog->get_posts();
    }
    //All CMS template management related functions will be here.
    public function show($template)
    {
        require_once $this->getCurrentTemplatePath($template).'index.php';
    }
    public function getCurrentTemplatePath($template)
    {
        return 'templates/'.$template.'/';
    }
//this will set template which we want to use
    public function setTemplate($templateName)
    {
        $this->templateName=$templateName;
    }
    public function appOutput()
    {
        require_once 'includes/application.php';
        $app=new Application();
        $app->run();
    }
    public function editTemplate($file, $content)
    {
        try {
            if(file_put_contents($file, $content)) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }
}
