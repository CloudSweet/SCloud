<?php
namespace CloudSweet\SCloud;

class AdminArea
{

    public static $actions = array( "node_info", "node_info_edit", "node_config", "node_config_edit", "group_info", "group_info_edit", "group_nodes", 
    "group_nodes_edit" );

    public static function checkUpdate()
    {
        try
        {
            
            $data = array(
                "status" => "success",
                "version" => Apps::getVersion()
            );
            Tools::outputJSON($data);

        }
        catch( \Exception $e ) 
        {
            Tools::outputJSON(array( "status" => "error", "msg" => $e->getMessage() ));
        }
    }

    public static function getLogData($vars)
    {
        try
        {
            $array = \Illuminate\Database\Capsule\Manager::table("mod_SCloud_logs")->get();
            Tools::outputJSON(array( "status" => "success", "msg" =>  $array ));
        }
        catch( \Exception $e ) 
        {
            Tools::outputJSON(array( "status" => "error", "msg" => $e->getMessage() ));
        }
    }

    public static function delLogData($vars)
    {
        try
        {
            $select = (string) Tools::safetyInput($_REQUEST["select"]);
            if( empty($select) ) 
            {
                throw new \Exception("您还未选择需要删除的记录");
            }

            $selects = explode("|", $select);
            $selects = array_filter($selects);
            $i = 0;
            foreach( $selects as $key => $value ) 
            {
                \WHMCS\Database\Capsule::table("mod_SCloud_logs")->where("id", $value)->delete();
                $i++;
            }
            Tools::outputJSON(array( "status" => "success", "rows" => $i ));
        }
        catch( \Exception $e ) 
        {
            Tools::outputJSON(array( "status" => "error", "msg" => $e->getMessage() ));
        }
    }

    public static function output($vars)
    {
        try
        {
            $info = "";
            if ( $_REQUEST["action"] != "" )
            {
                //"updated_at" => \Carbon\Carbon::now()
                switch ( $_REQUEST["action"] )
                {
                    case "node_info":
                        $nodeid = (int) Tools::safetyInput( $_REQUEST["id"] );
                        if( empty($nodeid) ) 
                        {
                            throw new \Exception( "传入 ID 不存在" );
                        }

                        Tools::safetyCheck($_REQUEST["sign"], array( $nodeid ));
                        $node = Nodes::where("id", $nodeid)->first();
                        if(empty($node)){
                            throw new \Exception( "传入 ID 不存在于数据库中" );
                        }else{
                            $smarty = Tools::getSmarty(
                                array( 
                                    "dir" => realpath(__DIR__ . "/../templates"), 
                                    "file" => "/manage", 
                                    "vars" => array( 
                                        "active" => "home",
                                        "modulevars" => $vars, 
                                        "modulename" => Apps::$modulename,
                                        "templates" => array( 
                                            "id" => $nodeid,
                                            "sign" => $_REQUEST["sign"],
                                            "node" => $node 
                                        ), 
                                        "page" => array(
                                            "name" => "node_info"
                                        )
                                    ) 
                                )
                            );
                        }
                        break;
                    case "node_info_edit":
                        $nodeid = (int) Tools::safetyInput( $_REQUEST["id"] );
                        if( empty($nodeid) ) 
                        {
                            throw new \Exception( "传入 ID 不存在" );
                        }

                        Tools::safetyCheck($_REQUEST["sign"], array( $nodeid ));
                        $node = Nodes::where("id", $nodeid)->first();
                        if(empty($node)){
                            throw new \Exception( "传入 ID 不存在于数据库中" );
                        }else{
                            $config = explode("|", $_REQUEST["info"]);
                            if(count($config) != 5){
                                throw new \Exception( "传入配置长度不为 5 ，请检查并重写" );
                            }else{
                                Nodes::where("id", $nodeid)->update( array( "name" => $config[0], "uuid" => $config[1], "type" => $config[2], "ip" => $config[3], "port" => $config[4]) );
                                Log::log("AdminArea", "节点信息修改，节点 ID ：" . $nodeid );
                                $smarty = Tools::getSuccessSmarty($vars, "节点信息更新成功");
                            }
                        }
                        break;
                    case "node_config":
                        $nodeid = (int) Tools::safetyInput( $_REQUEST["id"] );
                        if( empty($nodeid) ) 
                        {
                            throw new \Exception( "传入 ID 不存在" );
                        }

                        Tools::safetyCheck($_REQUEST["sign"], array( $nodeid ));
                        $node = Nodes::where("id", $nodeid)->first();
                        if(empty($node)){
                            throw new \Exception( "传入 ID 不存在于数据库中" );
                        }else{
                            $smarty = Tools::getSmarty(
                                array( 
                                    "dir" => realpath(__DIR__ . "/../templates"), 
                                    "file" => "/manage", 
                                    "vars" => array( 
                                        "active" => "home",
                                        "modulevars" => $vars, 
                                        "modulename" => Apps::$modulename,
                                        "templates" => array( 
                                            "id" => $nodeid,
                                            "sign" => $_REQUEST["sign"],
                                            "node" => $node 
                                        ), 
                                        "page" => array(
                                            "name" => "node_config"
                                        )
                                    ) 
                                )
                            );
                        }
                        break;
                    case "node_config_edit":
                        $nodeid = (int) Tools::safetyInput( $_REQUEST["id"] );
                        if( empty($nodeid) ) 
                        {
                            throw new \Exception( "传入 ID 不存在" );
                        }

                        Tools::safetyCheck($_REQUEST["sign"], array( $nodeid ));
                        $node = Nodes::where("id", $nodeid)->first();
                        if(empty($node)){
                            throw new \Exception( "传入 ID 不存在于数据库中" );
                        }else{
                            Nodes::where("id", $nodeid)->update( array( "configoptiontable" => $_REQUEST["info"] ) );
                            Log::log("AdminArea", "节点配置文件修改，节点 ID ：" . $nodeid );
                            $smarty = Tools::getSuccessSmarty($vars, "配置文件更新成功");
                        }
                        break;
                    case "group_info":
                        $groupid = (int) Tools::safetyInput( $_REQUEST["id"] );
                        if( empty($groupid) ) 
                        {
                            throw new \Exception( "传入 ID 不存在" );
                        }

                        Tools::safetyCheck($_REQUEST["sign"], array( $groupid ));
                        $group = Groups::where("id", $groupid)->first();
                        if(empty($group)){
                            throw new \Exception( "传入 ID " . $groupid . " 不存在于数据库中" );
                        }else{
                            $smarty = Tools::getSmarty(
                                array( 
                                    "dir" => realpath(__DIR__ . "/../templates"), 
                                    "file" => "/manage", 
                                    "vars" => array( 
                                        "active" => "groups",
                                        "modulevars" => $vars, 
                                        "modulename" => Apps::$modulename,
                                        "templates" => array( 
                                            "id" => $groupid,
                                            "sign" => $_REQUEST["sign"],
                                            "group" => $group 
                                        ), 
                                        "page" => array(
                                            "name" => "group_info"
                                        )
                                    ) 
                                )
                            );
                        }
                        break;
                    case "group_info_edit":
                        $groupid = (int) Tools::safetyInput( $_REQUEST["id"] );
                        if( empty($groupid) ) 
                        {
                            throw new \Exception( "传入 ID 不存在" );
                        }

                        Tools::safetyCheck($_REQUEST["sign"], array( $groupid ));
                        $group = Nodes::where("id", $groupid)->first();
                        if(empty($group)){
                            throw new \Exception( "传入 ID 不存在于数据库中" );
                        }else{
                            $config = explode("|", $_REQUEST["info"]);
                            if(count($config) != 1){
                                throw new \Exception( "传入配置长度不为 1 ，请检查并重写" );
                            }else{
                                Groups::where("id", $groupid)->update( array( "updated_at" => \Carbon\Carbon::now(), "name" => $config[0] ) );
                                Log::log("AdminArea", "分组信息修改，分组 ID ：" . $groupid );
                                $smarty = Tools::getSuccessSmarty($vars, "分组信息更新成功");
                            }
                        }
                        break;
                    case "group_nodes":
                        $groupid = (int) Tools::safetyInput( $_REQUEST["id"] );
                        if( empty($groupid) ) 
                        {
                            throw new \Exception( "传入 ID 不存在" );
                        }
                        Tools::safetyCheck($_REQUEST["sign"], array( $groupid ));
                        $group = Groups::where("id", $groupid)->first();
                        $groupNodes = explode("|", $group['nodes']);
                        $nodes = Nodes::orderBy("id", "ASC")->get();
                        $nodesOutput = array( );
                        foreach ($nodes as $key => $value) {
                            $checked = false;
                            if( in_array( $value['id'], $groupNodes ) ){
                                $checked = true;
                            }
                            array_push(
                                $nodesOutput, 
                                array( 
                                    "id" => $value['id'], 
                                    "name" => $value['name'], 
                                    "uuid" => $value['uuid'], 
                                    "checked" => $checked
                                )
                            );
                        }
                        if(empty($group)){
                            throw new \Exception( "传入 ID " . $groupid . " 不存在于数据库中" );
                        }else{
                            $smarty = Tools::getSmarty(
                                array( 
                                    "dir" => realpath(__DIR__ . "/../templates"), 
                                    "file" => "/manage", 
                                    "vars" => array( 
                                        "active" => "groups",
                                        "modulevars" => $vars, 
                                        "modulename" => Apps::$modulename,
                                        "templates" => array( 
                                            "id" => $groupid,
                                            "sign" => $_REQUEST["sign"],
                                            "group" => $group,
                                            "nodes" => $nodesOutput
                                        ), 
                                        "page" => array(
                                            "name" => "group_nodes"
                                        )
                                    ) 
                                )
                            );
                        }
                        break;
                    case "group_nodes_edit":
                        $groupid = (int) Tools::safetyInput( $_REQUEST["id"] );
                        if( empty($groupid) ) 
                        {
                            throw new \Exception( "传入 ID 不存在" );
                        }

                        Tools::safetyCheck($_REQUEST["sign"], array( $groupid ));
                        $group = Nodes::where("id", $groupid)->first();
                        if(empty($group)){
                            throw new \Exception( "传入 ID 不存在于数据库中" );
                        }else{
                            $nodes = "";
                            foreach ($_REQUEST['nodes'] as $key => $value) {
                                $nodes .= $value . "|";
                            }
                            $nodes = substr( $nodes, 0, strlen($nodes) - 1 ); 
                            Groups::where("id", $groupid)->update( array( "updated_at" => \Carbon\Carbon::now(), "nodes" => $nodes ) );
                            Log::log("AdminArea", "分组节点修改，分组 ID ：" . $groupid );
                            $smarty = Tools::getSuccessSmarty($vars, "分组信息更新成功");
                        }
                        break;
                    
                }
            }
            else
            {
                $nodes = Nodes::orderBy("id", "ASC")->get();
                $nodesOutput = array(  );
                foreach( $nodes as $key => $value ) 
                {
                    $nodeid = $value->id;
                    $sign = Tools::safetyCheck("", array( $value->id ), true);
                    $status = Tools::getEnabled( $value->enabled );
                    switch($value->transferMode){
                        case 0:
                            $transferMode = "已禁用";
                            break;
                        case 1:
                            $transferMode = "已启用";
                            break;
                        case 2:
                            $transferMode = "只允许中转";
                            break;
                    }
                    if( !class_exists("CloudSweet\\SCloud\\nodes\\" . $value->type) ){
                        $type = "<font color='red'>" . $value->type . "</font></br>(模块不存在)";
                    }else{
                        $type = "<font color='green'>" . $value->type . "</font>";
                    }
                    array_push(
                        $nodesOutput, 
                        array( 
                            "id" => $nodeid, 
                            "type" => $type, 
                            "sign" => $sign,
                            "status" => $status,
                            "transferMode" => $transferMode,
                            "value" => $value
                        )
                    );
                }
                $groups = Groups::orderBy("id", "ASC")->get();
                $groupsOutput = array(  );
                foreach( $groups as $key => $value ) 
                {
                    $groupid = $value->id;
                    $sign = Tools::safetyCheck("", array( $value->id ), true);
                    $status = Tools::getEnabled( $value->enabled );
                    array_push(
                        $groupsOutput, 
                        array( 
                            "id" => $groupid, 
                            "sign" => $sign,
                            "status" => $status,
                            "value" => $value
                        )
                    );
                }
                $smarty = Tools::getSmarty(
                    array( 
                        "dir" => realpath(__DIR__ . "/../templates"), 
                        "file" => "/manage", 
                        "vars" => array( 
                            "active" => "home",
                            "modulevars" => $vars, 
                            "modulename" => Apps::$modulename,
                            "info" => $info,
                            "templates" => array( 
                                "nodes" => $nodesOutput,
                                "groups" => $groupsOutput 
                            ), 
                            "page" => array(
                                "name" => "home"
                            )
                        ) 
                    )
                );
            }
        }
        catch( \Exception $e ) 
        {
            $smarty = Tools::getSmarty(
                array( 
                    "dir" => realpath(__DIR__ . "/../templates"), 
                    "file" => "/error", 
                    "vars" => array( 
                        "modulename" => Apps::$modulename,
                        "page" => "adminarea", 
                        "info" => $e->getMessage() 
                    ) 
                )
            );
        }
        echo $smarty;
    }

    public static function node_create()
    {
        $node = \Illuminate\Database\Capsule\Manager::table("mod_SCloud_nodes")->insertGetId(
            array(
                "uuid" => Tools::generateUUID(),
                "name" => "新节点",
                "ip" => "127.0.0.1",
                "port" => "12345",
                "type" => "v2ray_vmess",
                "u" => 0,
                "d" => 0,
                "created_at" => \Carbon\Carbon::now(),
            )
        );
        Log::log("AdminArea", "节点创建，节点 ID ：" . $node );
        Tools::outputJSON(array( "status" => "success" ));
    }

    public static function node_status_change()
    {
        try
        {
            $nodeid = (int) Tools::safetyInput( $_REQUEST["id"] );
            if( empty($nodeid) ) 
            {
                throw new \Exception( "传入 ID 不存在" );
            }

            Tools::safetyCheck( $_REQUEST["sign"], array( $nodeid ) );
            $node = Nodes::where("id", $nodeid)->first();
            if(empty($node)){
                throw new \Exception( "传入 ID 不存在于数据库中" );
            }else{
                $enabled = 0;
                if($node->enabled == 0){
                    $enabled = 1;
                }
                Nodes::where("id", $nodeid)->update( array( "enabled" => $enabled ) );
                Log::log("AdminArea", "节点状态修改，节点 ID ：" . $nodeid . " ，修改后状态：" . Tools::getEnabled( $enabled ));
                Tools::outputJSON(array( "status" => "success" ));
            }
        }
        catch( \Exception $e ) 
        {
            Tools::outputJSON(array( "status" => "error", "msg" => $e->getMessage() ));
        }
    }

    public static function node_delete()
    {
        try
        {
            $nodeid = (int) Tools::safetyInput( $_REQUEST["id"] );
            if( empty($nodeid) ) 
            {
                throw new \Exception( "传入 ID 不存在" );
            }

            Tools::safetyCheck($_REQUEST["sign"], array( $nodeid ));
            $node = Nodes::where("id", $nodeid)->first();
            if(empty($node)){
                throw new \Exception( "传入 ID 不存在于数据库中" );
            }else{
                Nodes::where("id", $nodeid)->delete();
                Log::log("AdminArea", "节点删除，节点 ID ：" . $node->id );
                Tools::outputJSON(array( "status" => "success" ));
            }
        }
        catch( \Exception $e ) 
        {
            Tools::outputJSON(array( "status" => "error", "msg" => $e->getMessage() ));
        }
    }

    public static function group_create()
    {
        $group = \Illuminate\Database\Capsule\Manager::table("mod_SCloud_groups")->insertGetId(
            array(
                "name" => "新分组",
                "nodes" => "",
                "created_at" => \Carbon\Carbon::now(),
            )
        );
        Log::log("AdminArea", "分组创建，分组 ID ：" . $group );
        Tools::outputJSON(array( "status" => "success" ));
    }

    public static function group_status_change()
    {
        try
        {
            $nodeid = (int) Tools::safetyInput( $_REQUEST["id"] );
            if( empty($nodeid) ) 
            {
                throw new \Exception( "传入 ID 不存在" );
            }

            Tools::safetyCheck( $_REQUEST["sign"], array( $nodeid ) );
            $node = Groups::where("id", $nodeid)->first();
            if(empty($node)){
                throw new \Exception( "传入 ID 不存在于数据库中" );
            }else{
                $enabled = 0;
                if($node->enabled == 0){
                    $enabled = 1;
                }
                Groups::where("id", $nodeid)->update( array( "enabled" => $enabled, "updated_at" => \Carbon\Carbon::now() ) );
                Log::log("AdminArea", "分组状态修改，分组 ID ：" . $nodeid . " ，修改后状态：" . Tools::getEnabled( $enabled ));
                Tools::outputJSON(array( "status" => "success" ));
            }
        }
        catch( \Exception $e ) 
        {
            Tools::outputJSON(array( "status" => "error", "msg" => $e->getMessage() ));
        }
    }

    public static function group_delete()
    {
        try
        {
            $nodeid = (int) Tools::safetyInput( $_REQUEST["id"] );
            if( empty($nodeid) ) 
            {
                throw new \Exception( "传入 ID 不存在" );
            }

            Tools::safetyCheck($_REQUEST["sign"], array( $nodeid ));
            $node = Groups::where("id", $nodeid)->first();
            if(empty($node)){
                throw new \Exception( "传入 ID 不存在于数据库中" );
            }else{
                Groups::where("id", $nodeid)->delete();
                Log::log("AdminArea", "分组删除，分组 ID ：" . $node->id );
                Tools::outputJSON(array( "status" => "success" ));
            }
        }
        catch( \Exception $e ) 
        {
            Tools::outputJSON(array( "status" => "error", "msg" => $e->getMessage() ));
        }
    }

}
?>