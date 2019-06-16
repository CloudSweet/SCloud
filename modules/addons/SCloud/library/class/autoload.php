<?php 
if( !defined("WHMCS") ) 
{
    exit( "Access denied." );
}
if( !defined("ROOTDIR") ) 
{
    define("ROOTDIR", dirname(dirname(dirname(dirname(__DIR__)))));
}

spl_autoload_register(function($className)
{
    $className = explode("\\", $className);
    $fileName = $className[1];
    $i = 0;
    foreach ($className as $value) {
        $i ++;
        if($i >= 3){
            $fileName .= "." . $value;
        }
    }
    $fileName .= ".class.php";
    $filePath = realpath(__DIR__ . "/" . $fileName);
    $filePath2 = realpath(__DIR__ . "/nodes/" . $fileName);
    if( is_file($filePath) ) 
    {
        include_once($filePath);
    }
    else if( is_file($filePath2) ) 
    {
        include_once($filePath2);
    }
}
);
?>