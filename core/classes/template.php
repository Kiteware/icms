<?php
class Template{
    //All CMS template management related functions will be here.
    function show($template)
    {
        require_once($this->getCurrentTemplatePath($template).'index.php');
    }
    function getCurrentTemplatePath($template)
    {
        return 'templates/'.$template.'/';
    }
//this will set template which we want to use
    function setTemplate($templateName)
    {
        $this->templateName=$templateName;
    }
    function appOutput() {
		require_once('includes/application.php');
		$app=new Application();
		$app->run();
	}
}
?>
