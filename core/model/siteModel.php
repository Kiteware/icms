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

    public function __construct($db) {
        $blog        = new BlogModel($db);
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
}
