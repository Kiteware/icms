<?php
    require_once('base.php');
    class Application extends Base{
        //here we can write as many functions as we want and those functions will be called by user directly.
        function run()
        {
            $method=(isset($_REQUEST['task']))?$_REQUEST['task']:'display';
            $this->$method();
        }
        function display()
        {
            echo 'this is base display';
        }
 
    }
?>
