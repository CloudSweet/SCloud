<?php 
namespace CloudSweet\SCloud;

class Schema
{
    public $schema = "";

    public function __call($method, $args)
    {
        if( $schema == "" ) 
        {
            $schema = \WHMCS\Database\Capsule::schema();
        }

        return $schema->$method(...$args);
    }

    public static function __callStatic($name, $arguments)
    {
        return \WHMCS\Database\Capsule::schema()->$name(...$arguments);
    }

}
?>