<?php
namespace CloudSweet\SCloud\nodes;
use WHMCS\Database\Capsule;

if( !class_exists("v2ray_vmess") ){
    class v2ray_vmess
    {
    	public static $vars = array(  );
        public static $version = "0.1";
        public static $author = "CloudSweet";

        public function __construct($vars = array(  ))
        {
            self::$vars = $vars;
        }

        public function getVersion(){
            return self::$version;
        }

        public function makeSubscribeUrl($vars){
        	return "233";
        }

        public function apiReference(){

        }

    }
}