<?php
require_once(__DIR__ . "/library/class/autoload.php");

function SCloud_config(){
    $app = new CloudSweet\SCloud\Apps();
    return $app->config();
}

if( !function_exists("SCloud_activate") ) 
{
    function SCloud_activate()
    {
        $app = new CloudSweet\SCloud\Apps($vars);
        return $app->activate();
    }
}

if( !function_exists("SCloud_deactivate") ) 
{
    function SCloud_deactivate()
    {
        $app = new CloudSweet\SCloud\Apps($vars);
        return $app->deactivate();
    }
}

if( !function_exists("SCloud_upgrade") ) 
{
    function SCloud_upgrade($vars)
    {
        $app = new CloudSweet\SCloud\Apps($vars);
        return $app->upgrade();
    }
}

if( !function_exists("SCloud_output") ) 
{
    function SCloud_output($vars)
    {
        $app = new CloudSweet\SCloud\Apps($vars);
        return $app->output();
    }
}

?>