<?php
namespace CloudSweet\SCloud;
use WHMCS\Database\Capsule;

if( !class_exists("Tools") ){
    class Tools
    {
        public function __construct()
        {
        }

        public static function getModuleVars($var = "")
        {
            $var = (string) trim($var);
            if( empty($var) ) 
            {
                throw new \Exception("未传入需要获取的模块变量");
            }

            $value = \Illuminate\Database\Capsule\Manager::table("tbladdonmodules")->where("module", Apps::$modulename)->where("setting", $var)->first()->value;
            return $value;
        }

        public static function safetyInput($data)
        {
            return htmlspecialchars(stripslashes(trim($data)));
        }

        public static function safetyCheck($getSign = "", $vars = array(  ), $get = false)
        {
            $getSign = (string) trim($getSign);
            foreach( $vars as $key => $value ) 
            {
                $param .= $value;
            }
            $localSign = self::getModuleVars("sign");
            $param .= date("Y-m-d");
            $param .= $localSign;
            if( $get ) 
            {
                return (string) md5($param);
            }

            if( md5($param) != $getSign ) 
            {
                throw new \Exception("安全认证失败，请刷新网页后重试", 101);
            }

            return true;
        }

        public static function getSmarty(array $page)
        {
            $templates_c = (string) $GLOBALS["templates_compiledir"];
            if( !is_readable($templates_c) || !is_writeable($templates_c) ) 
            {
                throw new \Exception("模板缓存目录 [ " . $templates_c . " ] 无法读取或写入，请检查目录权限");
            }

            if( isset($page["file"]) ) 
            {
                $smarty = new \Smarty();
                if( isset($page["vars"]) ) 
                {
                    if( is_array($page["vars"]) ) 
                    {
                        $smarty->assign($page["vars"]);
                    }
                    else
                    {
                        throw new \Exception("已定义的传值字段并非数组");
                    }

                }

                if( !isset($page["dir"]) ) 
                {
                    throw new \Exception("未定义模板位置");
                }

                $dir = $page["dir"];
                $smarty->assign(array( "systemurl" => self::getSystemURL() ));
                if( isset($page["cache"]) && $page["cache"] == true ) 
                {
                    $smarty->caching = true;
                }
                else
                {
                    $smarty->caching = false;
                }

                $smarty->compile_dir = $GLOBALS["templates_compiledir"];
                return (string) $smarty->fetch($dir . $page["file"] . ".tpl");
            }

            throw new \Exception("未定义模板文件");
        }

        public static function getSystemURL()
        {
            if( empty($GLOBALS["CONFIG"]) ) 
            {
                $result = \Illuminate\Database\Capsule\Manager::table("tblconfiguration")->where("setting", "SystemSSLURL")->first()->value;
                if( empty($result) ) 
                {
                    $result = \Illuminate\Database\Capsule\Manager::table("tblconfiguration")->where("setting", "SystemURL")->first()->value;
                }

                if( empty($result) ) 
                {
                    throw new \Exception("无法从数据库中获取 WHMCS 的地址");
                }

            }
            else
            {
                if( !empty($GLOBALS["CONFIG"]["SystemSSLURL"]) ) 
                {
                    $result = $GLOBALS["CONFIG"]["SystemSSLURL"] . "/";
                }
                else
                {
                    if( ($result = $GLOBALS["CONFIG"]["SystemURL"]) ) 
                    {
                        $result = $GLOBALS["CONFIG"]["SystemURL"] . "/";
                    }
                    else
                    {
                        throw new \Exception("无法从全局变量中获取 WHMCS 地址");
                    }

                }

            }

            return $result;
        }

        public static function outputJSON($data)
        {
            if( empty($data) ) 
            {
                throw new \Exception("未定义需要JSON输出的内容");
            }

            header("Content-type: application/json");
            exit( json_encode($data, JSON_UNESCAPED_UNICODE) );
        }

        public static function trimArray($array)
        {
            if( !is_array($array) ) 
            {
                return trim($array);
            }

            return array_map("trimArray", $array);
        }

        public function generateUUID()
        {  
            $chars = md5(uniqid(mt_rand(), true));  
            $uuid  = substr($chars,0,8) . '-';  
            $uuid .= substr($chars,8,4) . '-';  
            $uuid .= substr($chars,12,4) . '-';  
            $uuid .= substr($chars,16,4) . '-';  
            $uuid .= substr($chars,20,12);  
            return strtoupper($uuid);  
        }

        public function getEnabled($enabled)
        {
            if( $enabled == "1" ) 
            {
                $status = "已启用";
            }
            else
            {
                $status = "已禁用";
            }
            return $status;
        }

        public function getSuccessSmarty($vars, $text)
        {
            $smarty = Tools::getSmarty(
                array( 
                    "dir" => realpath(__DIR__ . "/../templates"), 
                    "file" => "/success", 
                    "vars" => array( 
                        "modulevars" => $vars, 
                        "modulename" => Apps::$modulename,
                        "text" => $text,
                    ) 
                )
            );
            return $smarty;
        }
    }
}