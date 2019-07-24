<?php
//diskusage upload
//bwusage download

namespace CloudSweet\SCloud;

class Servers
{
    public static $params = array(  );

    public function __construct($params = array(  ))
    {
        self::$params = $params;
    }

    public static function MetaData()
    {
        return array( 
            "DisplayName" => Apps::$modulename, 
            "APIVersion" => "1.1", 
            "RequiresServer" => false 
        );
    }

    public static function ConfigOptions()
    {
        return array( 
            "Bandwidth" => array( 
                "FriendlyName" => "流量限制", 
                "Type" => "text", 
                "Size" => "25", 
                "Description" => "<br />允许使用的流量数量，单位：MB", "Default" => "102400" 
            ),
            "ClientLimit" => array( 
                "FriendlyName" => "客户端限制", 
                "Type" => "text", 
                "Size" => "25", 
                "Description" => "<br />允许同时连接节点的客户端数量，单位：个", "Default" => "5" 
            ),
            "TransferLimit" => array( 
                "FriendlyName" => "转发规则数量", 
                "Type" => "text", 
                "Size" => "25", 
                "Description" => "<br />允许使用的转发规则数量，单位：个", "Default" => "0" 
            ),
            "NodeGroups" => array( 
                "FriendlyName" => "可使用分组", 
                "Type" => "text", 
                "Size" => "25", 
                "Description" => "<br />请不要填写，前往控制插件配置", "Default" => "" 
            ),
            "Notices" => array( 
                "FriendlyName" => "产品公告", 
                "Type" => "textarea",
                "Rows" => "3", 
                "Cols" => "25",
                "Description" => "添加格式: <code>产品公告</code>, 每行一个公告，留空不显示", "Default" => "" 
            ),
            "Documents" => array( 
                "FriendlyName" => "产品文档", 
                "Type" => "textarea",
                "Rows" => "3", 
                "Cols" => "25",
                "Description" => "添加格式: <code>产品文档|文档链接</code>, 每行一个文档，留空不显示", "Default" => "" 
            ),
        );
    }

    public static function CreateAccount()
    {
        try
        {
            if( self::$params["status"] == "Active" || self::$params["status"] == "Suspended" ) 
            {
                throw new \Exception("当前产品已是开通状态, 请尝试删除后再次开通");
            }

            $sid = self::$params["serviceid"];
            \WHMCS\Database\Capsule::table("tblhosting")->where("id", $sid)->update( array( "username" => Tools::generateUUID() ) );

        }
        catch( \Exception $e ) 
        {
            logModuleCall("SCloud", "CreateAccount", $params, $e->getMessage(), $e->getTraceAsString());
            return $e->getMessage();
        }
        return "success";
    }

    public static function SuspendAccount()
    {
        try
        {
            if( self::$params["status"] != "Active" ) 
            {
                throw new \Exception("由于产品并非激活状态，无法为你暂停此产品");
            }
        }
        catch( \Exception $e ) 
        {
            logModuleCall("SCloud", "SuspendAccount", $params, $e->getMessage(), $e->getTraceAsString());
            return $e->getMessage();
        }
        return "success";
    }

    public static function UnsuspendAccount()
    {
        try
        {
            if( self::$params["status"] != "Suspended" ) 
            {
                throw new \Exception("由于产品并非暂停状态，因此无法为你解除暂停");
            }
        }
        catch( \Exception $e ) 
        {
            logModuleCall("SCloud", "UnsuspendAccount", $params, $e->getMessage(), $e->getTraceAsString());
            return $e->getMessage();
        }
        return "success";
    }

    public static function TerminateAccount()
    {
        try
        {
            $sid = self::$params["serviceid"];
            $checkAuth = Authnum::where("sid", $sid)->first();
            if( !empty($checkAuth) ) 
            {
                Authnum::where("sid", $sid)->delete();
            }
        }
        catch( \Exception $e ) 
        {
            logModuleCall("SCloud", "TerminateAccount", $params, $e->getMessage(), $e->getTraceAsString());
            return $e->getMessage();
        }
        return "success";
    }

    public static function ChangePassword()
    {
        try
        {
        }
        catch( \Exception $e ) 
        {
            logModuleCall("SCloud", "ChangePassword", $params, $e->getMessage(), $e->getTraceAsString());
            return $e->getMessage();
        }
        return "success";
    }

    public static function ChangePackage()
    {
        try
        {
            if( self::$params["status"] != "Active" && self::$params["status"] != "Suspended" ) 
            {
                throw new \Exception("由于产品尚未开通，因此无法为你更改套餐");
            }
        }
        catch( \Exception $e ) 
        {
            logModuleCall("SCloud", "ChangePackage", $params, $e->getMessage(), $e->getTraceAsString());
            return $e->getMessage();
        }
        return "success";
    }

    public static function AdminServicesTabFields()
    {
        try
        {
            return array( "上传使用量" => self::$params["diskusage"], "下载使用量" => self::$params["bwusage"] );
        }
        catch( \Exception $e ) 
        {
            logModuleCall("SCloud", "AdminServicesTabFields", $params, $e->getMessage(), $e->getTraceAsString());
            return array( "error" => $e->getMessage() );
        }
    }

    public static function ClientArea()
    {
        try
        {
            if( self::$params["status"] != "Active" ) 
            {
                throw new \Exception("您的产品未处于已激活状态");
            }

            $sid = self::$params["serviceid"];
            $product = \WHMCS\Database\Capsule::table("tblhosting")->where("id", $sid)->first();
            $package_bandwidth = self::$params["configoption1"];
            $package_clientlimit = self::$params["configoption2"];
            $package_transferlimit = self::$params["configoption3"];
            $package_groups = self::$params["configoption4"];
            $package_notice = Tools::trimArray(array_filter(explode(PHP_EOL, self::$params["configoption5"])));
            $package_document = Tools::trimArray(array_filter(explode(PHP_EOL, self::$params["configoption6"])));
            $uploaded = floor(Tools::convertTraffic($product->diskusage, 'bytes', 'mb'));
            $downloaded = floor(Tools::convertTraffic($product->bwusage, 'bytes', 'mb'));
            $lefted = floor(Tools::convertTraffic(self::$params['configoption1'] - $uploaded - $downloaded, 'mb', 'gb'));

            $groupNodes = explode("|", $package_groups);
            $groups = Groups::orderBy("id", "ASC")->get();
            $groupsOutput = array( );
            $classOutput = array( );
            foreach ($groups as $key => $value) {
                if( in_array( $value['id'], $groupNodes ) ){
                    $groupNodes = explode("|", $value['nodes']);
                    $nodes = Nodes::orderBy("id", "ASC")->get();
                    $nodesOutput = array( );
                    foreach ($nodes as $key1 => $value1) {
                        if( in_array( $value1['id'], $groupNodes ) ){
                            $class = "";
                            if( !class_exists("CloudSweet\\SCloud\\nodes\\" . $value1['type']) )
                            {
                                $subscribe = array(array("type" => "订阅链接错误", "subscribe" => "(模块不存在)"));
                            }
                            else
                            {
                                $class = "CloudSweet\\SCloud\\nodes\\" . $value1['type'];
                                $aclass = new $class();
                                $subscribe = $aclass::makeUrl(self::$params["password"], $value1);
                                $classOutput[$class][] = array();
                                //$subscribe = "<font color='green'>" . $value1['type'] . "</font>";
                            }
                            array_push(
                                $nodesOutput,
                                array( 
                                    "id" => $value1['id'], 
                                    "name" => $value1['name'], 
                                    "uuid" => $value1['uuid'], 
                                    "country" => $value1['country'], 
                                    "subscribe" => $subscribe,
                                    "class" => $class,
                                    "values" => $value1,
                                )
                            );
                        }
                    }
                    array_push(
                        $groupsOutput, 
                        array( 
                            "id" => $value['id'], 
                            "name" => $value['name'], 
                            "uuid" => $value['uuid'],
                            "nodes" => $nodesOutput
                        )
                    );
                }
            }
            $subscribeOutput = array( );
            foreach ($classOutput as $key => $value) {
                $class = new $key();
                $subscribe = $class::makeSubscribeUrl();
                array_push(
                    $subscribeOutput,
                    $subscribe
                );
            }
            //var_dump($subscribeOutput);
            //die();
            //package_groups

            return array( 
                "tabOverviewReplacementTemplate" => "templates/client.tpl", 
                "templateVariables" => array( 
                    "node" => $groupsOutput, 
                    "subscribe" => $subscribeOutput, 
                    "notice" => $package_notice, 
                    "document" => $package_document, 
                    'HTTP_HOST' => $_SERVER['HTTP_HOST'],
                    "subscribe_token" => md5(self::$params["password"]),
                    "templates" => array( 
                        "client_ip" => Tools::getIP(),
                        "bandwidth" => $package_bandwidth,
                        "clientlimit" => $package_clientlimit,
                        "uploaded" => $uploaded,
                        "downloaded" => $downloaded,
                        "used" => $uploaded + $downloaded,
                        "lefted" => $lefted,
                    ) 
                ) 
            );
        }
        catch( \Exception $e ) 
        {
            logModuleCall("SCloud", "ClientArea", $params, $e->getMessage(), $e->getTraceAsString());
            switch( self::$params["status"] ) 
            {
                case "Pending":
                    $msg = "当前产品尚未开通，请检查账单是否已完成支付、且管理员是否已通过你的订单审核";
                    break;
                case "Active":
                    $msg = $e->getMessage();
                    break;
                case "Suspended":
                    $msg = "当前产品处于暂停状态，暂停原因: " . self::$params["templatevars"]["suspendreason"];
                    break;
                case "Terminated":
                case "Completed":
                    $msg = "由于当前产品已终止服务，因此无法为你显示产品信息";
                    break;
                case "Cancelled":
                    $msg = "由于当前产品已取消服务，因此无法为你显示产品信息";
                    break;
                case "Fraud":
                    $msg = "当前产品被判定为欺诈订单，请联系管理员";
                    break;
                default:
                    $msg = $e->getMessage();
            }
            return array( "tabOverviewReplacementTemplate" => "templates/error.tpl", "templateVariables" => array( "info" => $msg, "page" => "clientarea" ) );
        }
    }

}
?>