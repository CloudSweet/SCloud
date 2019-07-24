<?php
namespace CloudSweet\SCloud;

if( !class_exists("Subscribe") ){
    class Subscribe
    {
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

        private function getDecryptedWHMCSPassword($string)
        {
            $cc_encryption_hash = $this->cc_encryption_hash;
            $key = md5(md5($cc_encryption_hash)) . md5($cc_encryption_hash);
            $hash_key = $this->_hash($key);
            $hash_length = strlen($hash_key);
            $string = base64_decode($string);
            $tmp_iv = substr($string, 0, $hash_length);
            $string = substr($string, $hash_length, strlen($string) - $hash_length);
            $iv = "";
            $out = "";
            for( $c = 0; $c < $hash_length; $c++ ) 
            {
                $ivValue = (isset($tmp_iv[$c]) ? $tmp_iv[$c] : "");
                $hashValue = (isset($hash_key[$c]) ? $hash_key[$c] : "");
                $iv .= chr(ord($ivValue) ^ ord($hashValue));
            }
            $key = $iv;
            for( $c = 0; $c < strlen($string); $c++ ) 
            {
                if( $c != 0 && $c % $hash_length == 0 ) 
                {
                    $key = $this->_hash($key . substr($out, $c - $hash_length, $hash_length));
                }
                $out .= chr(ord($key[$c % $hash_length]) ^ ord($string[$c]));
            }
            return $out;
        }

        private function _hash($string)
        {
            if( function_exists("sha1") ) 
            {
                $hash = sha1($string);
            }
            else
            {
                $hash = md5($string);
            }
            $out = "";
            $c = 0;
            while( $c < strlen($hash) ) 
            {
                $out .= chr(hexdec($hash[$c] . $hash[$c + 1]));
                $c += 2;
            }
            return $out;
        }

        public function returnSubscribe($get)
        {
            if(!isset($get['type']) || !isset($get['token']) || !isset($get['sid']) || !isset($get['output']))
            {
                return "未传入关键数据";
            }
            if( !class_exists("CloudSweet\\SCloud\\nodes\\" . $get['type']) )
            {
                return "模块不存在";
            }
            else
            {
                if(!is_numeric($get['sid']))
                {
                    return "传入的sid非法";
                }
                $db = new \PDO('mysql:host=' . $this->db_host . ';port=' . $this->db_port . ';dbname=' . $this->db_name, $this->db_username, $this->db_password);
                $service = $db->prepare('SELECT * FROM `tblhosting` WHERE `id` = :sid');
                $service->bindValue(':sid', $get['sid']);
                $service->execute();
                $service = $service->fetch();
                if (!$service)
                {
                    return "服务不存在";
                }
                if ($service["domainstatus"] != 'Active') 
                {
                    return "服务不处于激活状态";
                }
                $self_password = $this->getDecryptedWHMCSPassword($service["password"]);
                if(md5($self_password) != $get['token'])
                {
                    return "传入token不匹配";
                }
                $package = $db->prepare('SELECT * FROM `tblproducts` WHERE `id` = :packageid');
                $package->bindValue(':packageid', $service["packageid"]);
                $package->execute();
                $package = $package->fetch();
                if (!$package)
                {
                    return "产品不存在";
                }
                $package_groups = $package["configoption4"];
                $package_bandwidth = $package["configoption1"]; //总流量
                //分割分组与节点
                $groupNodes = explode("|", $package_groups);
                $groups = $db->prepare('SELECT * FROM `mod_SCloud_groups` ORDER BY id ASC');
                $groups->execute();
                $groups = $groups->fetchAll();
                if (!$groups)
                {
                    return "节点分组表不存在";
                }
                $groupsOutput = array( );
                $nodesOutput = array( );
                foreach ($groups as $key => $value) {
                    if( in_array( $value['id'], $groupNodes ) ){
                        $groupNodes = explode("|", $value['nodes']);
                        $nodes = $db->prepare('SELECT * FROM `mod_SCloud_nodes` ORDER BY id ASC');
                        $nodes->execute();
                        $nodes = $nodes->fetchAll();
                        if (!$nodes)
                        {
                            return "节点表不存在";
                        }
                        foreach ($nodes as $key1 => $value1) {
                            if( in_array( $value1['id'], $groupNodes ) ){
                                $class = "";
                                if( class_exists("CloudSweet\\SCloud\\nodes\\" . $value1['type']) )
                                {
                                    $class = "CloudSweet\\SCloud\\nodes\\" . $value1['type'];
                                    $aclass = new $class();
                                    $nodesOutput[$key1] = array(
                                        "value" => $value1
                                    );
                                }
                            }
                        }
                    }
                }
                $class = "CloudSweet\\SCloud\\nodes\\" . $get['type'];
                $aclass = new $class();
                $subscribe = $aclass->apiReference($get['output'], $self_password, $nodesOutput);
                return $subscribe;
            }
        }
    }

}