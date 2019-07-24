<?php
namespace CloudSweet\SCloud\nodes;

if( !class_exists("test_node") ){
    class test_node
    {
        public static $vars = array(  );
        public static $version = "0.1";
        public static $author = "CloudSweet";

        public function __construct($vars = array(  ))
        {
            self::$vars = $vars;
        }

        public function getVersion()
        {
            return self::$version;
        }

        //返回单节点订阅URL
        public function makeUrl($password, $nodeData)
        {
            return "";
        }

        //返回订阅URL的入口
        public function makeSubscribeUrl($data)
        {
            return array(
                array(
                    "id" => "test_my_node",
                    "type" => "test_node订阅",
                    "entrance" => "type=test_node&output=test_my_node"
                )
            );
        }

        //返回全部订阅内容
        public function apiReference($type, $password, $nodeDatas)
        {
            switch($type)
            {
                case "test_my_node":
                    return "";
                default:
                    return "不存在的类型数据";
            }
        }
    }
}