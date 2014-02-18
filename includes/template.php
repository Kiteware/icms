<?php
require_once('base.php');
class Template extends Base{
    //All CMS template management related functions will be here.
    var $templateName='default';//by default template would be "default" template
    function show()
    {
        require_once($this->getCurrentTemplatePath().'index.php');
    }
    function getCurrentTemplatePath()
    {
        return 'templates/'.$this->templateName.'/';
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
