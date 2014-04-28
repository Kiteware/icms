<?php
require_once('base.php');
class Widget extends Base{
    var $widgetPath='';
    var $widgetName='';
    function setWidgetPath($widgetName)
    {
        $this->widgetPath='widgets/'.$widgetName.'/';
        $this->widgetName=$widgetName;
    }
    function getWidgetPath()
    {
        return $this->widgetPath;
    }
    function display()
    {
        echo 'this will be default output of widget if this function is not overrided by derived class';
    }
    function run($widgetName,$params)// this function will be called by template function class to display widget
    {
    $this->parameters=$params;
    $this->setWidgetPath($widgetName);
    $this->display();
	}
}
?>
