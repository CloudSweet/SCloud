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

        public function getVersion()
        {
            return self::$version;
        }

        public function makeUrl($password, $nodeData)
        {
            $uuid = self::makeUUIDFromPassword($password);
            $configoptiontable = $nodeData["configoptiontable"];
            $json = str_replace('\'', '"', $configoptiontable);
            $json = str_replace("&quot;", '"', $json);
            $json = preg_replace('/\s/', "", $json);
            $json = preg_replace('/([\w_0-9]+):/', '"\1":',$json);
            $json = preg_replace('/:([^\[|{|"][\d.]+)/', ':"\1"', $json);
            $json = json_decode($json, true);
            $advancedconfigoptiontable = $nodeData["advancedconfigoptiontable"];
            $advancedconfigoptiontable = str_replace('\'', '"', $advancedconfigoptiontable);
            $advancedconfigoptiontable = str_replace("&quot;", '"', $advancedconfigoptiontable);
            $advancedconfigoptiontable = preg_replace('/\s/', "", $advancedconfigoptiontable);
            $advancedconfigoptiontable = preg_replace('/([\w_0-9]+):/', '"\1":',$advancedconfigoptiontable);
            $advancedconfigoptiontable = preg_replace('/:([^\[|{|"][\d.]+)/', ':"\1"', $advancedconfigoptiontable);
            $advancedconfigoptiontable = json_decode($advancedconfigoptiontable, true);
            if(!$json)
            {
                return array(
                    array(
                        "type" => "错误",
                        "subscribe" => "节点数据解析失败"
                    )
                );
            }
            if(!$advancedconfigoptiontable)
            {
                return array(
                    array(
                        "type" => "错误",
                        "subscribe" => "节点高级配置数据解析失败"
                    )
                );
            }
            if(!isset($json['inbound']) && !isset($json['inbounds']))
            {
                return array(
                    array(
                        "type" => "错误",
                        "subscribe" => "节点JSON解析失败"
                    )
                );
            }
            $v2ray_sub = self::makeV2raySubscribe($nodeData, $uuid, $advancedconfigoptiontable);
            $quan_sub = self::makeQuantumultSubscribe($nodeData, $uuid, $advancedconfigoptiontable);
            return array(
                array(
                    "type" => "V2ray订阅",
                    "subscribe" => $v2ray_sub
                ),
                array(
                    "type" => "Quantumult订阅",
                    "subscribe" => $quan_sub
                )
            );
        }

        public function makeSubscribeUrl($data)
        {
            return array(
                array(
                    "id" => "v2rayAll",
                    "type" => "V2ray订阅",
                    "entrance" => "type=v2ray_vmess&output=v2ray"
                ),
                array(
                    "id" => "v2rayQuantumult",
                    "type" => "Quantumult订阅",
                    "entrance" => "type=v2ray_vmess&output=quantumult"
                )
            );
        }

        public function apiReference()
        {

        }

        public function makeV2raySubscribe($nodeData, $uuid, $advancedconfigoptiontable)
        {
            $nodeArray = array(
                "add" => $nodeData["ip"],
                "id"  => $uuid,
                "net" => $advancedconfigoptiontable["network"],
                "port"=> $nodeData["port"],
                "ps"  => $nodeData["name"],
                "file"=> $advancedconfigoptiontable["host"],
                "host"=> $advancedconfigoptiontable["path"],
                "tls" => $advancedconfigoptiontable["tls"],
                "type" => $advancedconfigoptiontable["type"],
                "aid" => $advancedconfigoptiontable["alterID"],
                "v"   => 2
            );
            return "vmess://".base64_encode(json_encode($nodeArray));
        }

        public function makeQuantumultSubscribe($nodeData, $uuid, $advancedconfigoptiontable)
        {
            $groupName = "V2Ray";
            $obfsHost = $advancedconfigoptiontable["host"];
            //xxx|服务器地址|端口|伪装类型|tls|host|路径|传输协议|流量倍率|额外id
            //0  |1.      |2.  |3.     |4. |5.  |6.  |7.    |8.     |9
            $str = $nodeData["name"] . ' = vmess, ' . $nodeData["ip"] . ', ' . $nodeData["port"] . ', none, "' . $uuid . '", group=' . $groupName;
            if($advancedconfigoptiontable["tls"]){
                $str .= ', over-tls=true, tls-host=' . $advancedconfigoptiontable["host"];
            }
            if($advancedconfigoptiontable["network"] != "tcp"){
                $str .= ', certificate=1, obfs=ws, obfs-path="' . $advancedconfigoptiontable["path"] . '", obfs-header="Host: ' . $obfsHost . '[Rr][Nn]User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 12_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148"';
            }
            return "vmess://".base64_encode($str);  
        }

        public function makeUUIDFromPassword($password)
        {
            $chars = md5($password);
            $uuid  = substr($chars,0,8) . '-';  
            $uuid .= substr($chars,8,4) . '-';  
            $uuid .= substr($chars,12,4) . '-';  
            $uuid .= substr($chars,16,4) . '-';  
            $uuid .= substr($chars,20,12);  
            return strtoupper($uuid);  
        }

    }
}