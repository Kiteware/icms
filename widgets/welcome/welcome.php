<?php
require_once('includes/widget.php');
class WelcomeWidget extends Widget{
        //overriding base CmsWidget class display function
    function display()
    {
        $world='World!';
        // if parameters array is not empty in other words parameters do exists then assign variable $world a value by that parameter
        if(count($this->parameters)!=0)
        {
            $world=$this->parameters['hello_to'];
        }        
        echo "Hello ".$world;
        echo '<br />ICMS was made to help kickstart websites. Instead of starting from scratch and going on a hide and seek mission for code snippets, I want to just load up ICMS, remove things I don\'t need and get to work styling.';
        //here you can write a complete php application code to be displayed as widget.
    }
}
?>
