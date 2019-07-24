<?php
namespace CloudSweet\SCloud;

require "config.php";
require "library/class/autoload.php";

class ScloudAPI {

    private $db_host;
    private $db_port;
    private $db_username;
    private $db_password;
    private $db_name;
    private $cc_encryption_hash;

    public function __construct($db_host, $db_port, $db_username, $db_password, $db_name, $cc_encryption_hash)
    {
        $this->db_host = $db_host;
        $this->db_port = $db_port;
        $this->db_username = $db_username;
        $this->db_password = $db_password;
        $this->db_name = $db_name;
        $this->cc_encryption_hash = $cc_encryption_hash;
    }

    public function getResult($get)
    {
        try
        {
            if(!isset($get['action']))
            {
                return "操作请求非法";
            }
            switch ($get['action'])
            {
                case "subscribe":
                    $subscribe = new Subscribe($this->db_host, $this->db_port, $this->db_username, $this->db_password, $this->db_name, $this->cc_encryption_hash);
                    return $subscribe->returnSubscribe($get);
                case "nodeManage":
                    $nodeManage = new nodeManage($this->db_host, $this->db_port, $this->db_username, $this->db_password, $this->db_name, $this->cc_encryption_hash);
                    return $nodeManage->returnNodeManage($get);
                default :
                    return "不支持的操作";
            }
        }
        catch( \Exception $e ) 
        {
            return "发生未知错误：" . $e->getMessage();
        }
    }

    public function returnNodeInit()
    {

    }

}

//导入API数据
$api = new ScloudAPI($db_host, $db_port, $db_username, $db_password, $db_name, $cc_encryption_hash);
$result = $api->getResult($_GET);
exit($result);