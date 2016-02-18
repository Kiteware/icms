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
    private $settings;

    public function __construct(\Pimple\Container $container) {
        $this->container = $container;
        $this->db = $container['db'];
        $blog        = new BlogModel($container);
        $this->settings = $container['settings'];
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

    /**
     * @param $oldSetting The full setting as seen in the config file
     * @param $newSetting The entire line that will replace the old one
     * Source: http://stackoverflow.com/questions/3004041/how-to-replace-a-particular-line-in-a-text-file-using-php
     */
    public function editConfig($config, $newSetting) {
        $reading = fopen('core/configuration.php', 'r');
        $writing = fopen('core/configuration.tmp', 'w');

        $replaced = false;

        while (!feof($reading)) {
            $line = fgets($reading);
            if (stristr($line,$config)) {
                $line = $config . " = \"" . $newSetting . "\"\r\n";
                $replaced = true;
            }
            fputs($writing, $line);
        }
        fclose($reading); fclose($writing);
        // might as well not overwrite the file if we didn't replace anything
        if ($replaced)
        {
            rename('core/configuration.tmp', 'core/configuration.php');
        } else {
            unlink('core/configuration.tmp');
        }
    }

    public function hasConfigChanged($config1, $config2, $newSetting) {
        $oldSetting = $this->settings["production"][$config1][$config2] ;
        if ($newSetting != $oldSetting) {
            $combinedConfig = $config1.".".$config2;
            $this->editConfig($combinedConfig, $newSetting);
            return true;
        } else {
            return false;
        }
    }
}
