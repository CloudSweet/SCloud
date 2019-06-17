<?php 

require_once(__DIR__ . "/../../addons/SCloud/library/class/autoload.php");

if( !function_exists("SCloud_MetaData") ) 
{
function SCloud_MetaData()
{
    $servers = new CloudSweet\SCloud\Servers();
    return $servers->MetaData();
}
}

if( !function_exists("SCloud_ConfigOptions") ) 
{
function SCloud_ConfigOptions()
{
    $servers = new CloudSweet\SCloud\Servers();
    return $servers->ConfigOptions();
}

}

if( !function_exists("SCloud_CreateAccount") ) 
{
function SCloud_CreateAccount(array $params)
{
    $servers = new CloudSweet\SCloud\Servers($params);
    return $servers->CreateAccount();
}

}

if( !function_exists("SCloud_SuspendAccount") ) 
{
function SCloud_SuspendAccount(array $params)
{
    $servers = new CloudSweet\SCloud\Servers($params);
    return $servers->SuspendAccount();
}

}

if( !function_exists("SCloud_UnsuspendAccount") ) 
{
function SCloud_UnsuspendAccount(array $params)
{
    $servers = new CloudSweet\SCloud\Servers($params);
    return $servers->UnsuspendAccount();
}

}

if( !function_exists("SCloud_TerminateAccount") ) 
{
function SCloud_TerminateAccount(array $params)
{
    $servers = new CloudSweet\SCloud\Servers($params);
    return $servers->TerminateAccount();
}

}

/*if( !function_exists("SCloud_ChangePassword") ) 
{
function SCloud_ChangePassword(array $params)
{
    $servers = new CloudSweet\SCloud\Servers($params);
    return $servers->ChangePassword();
}

}*/

if( !function_exists("SCloud_ChangePackage") ) 
{
function SCloud_ChangePackage(array $params)
{
    $servers = new CloudSweet\SCloud\Servers($params);
    return $servers->ChangePackage();
}

}

if( !function_exists("SCloud_AdminServicesTabFields") ) 
{
function SCloud_AdminServicesTabFields(array $params)
{
    $servers = new CloudSweet\SCloud\Servers($params);
    return $servers->AdminServicesTabFields();
}

}

if( !function_exists("SCloud_ClientArea") ) 
{
function SCloud_ClientArea(array $params)
{
    $servers = new CloudSweet\SCloud\Servers($params);
    return $servers->ClientArea();
}

}
?>