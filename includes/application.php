<?php
    require_once('base.php');
    class Application extends Base{
        //here we can write as many functions as we want and those functions will be called by user directly.
        function run()
        {
            if(isset($_REQUEST['task']))
            {
                $task=$_REQUEST['task'];
                switch($task)
                {
                    case 'addcontent':$this->addcontent();break;
                    case 'anyothertask':$this->anyothertask();break;
                    default: $this->display();break;
                }    
            }
            else
            {
                $this->display();
            }
        }
        function addcontent()
        {
            echo 'here add content functionality will takes place';
        }
        function display()
        {
            echo 'it is default task of application';
        }
        function anyothertask()
        {
            echo 'this is another task here can be written a complete php program against that task';
        }
    }
?>
