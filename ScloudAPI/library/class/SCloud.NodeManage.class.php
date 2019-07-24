<?php
namespace CloudSweet\SCloud;

if( !class_exists("NodeManage") ){
    class NodeManage
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

        public function returnNodeManage($get)
        {

        }
    }
}