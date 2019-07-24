<?php
namespace CloudSweet\SCloud;
use WHMCS\Database\Capsule;

if( !class_exists("Apps") ){
    class Apps
    {
        public static $vars = array(  );
        public static $modulename = "SCloud";
        public static $version = "0.1";
        public static $author = "CloudSweet";

        public function __construct($vars = array(  ))
        {
            self::$vars = $vars;
        }

        public function getVersion(){
            return self::$version;
        }

        public function config(){
            $configarray = array(
                "name" => "SCloud管理",
                "description" => "管理你的SCloud节点</br><b>禁用插件将删除你的所有数据！</b>",
                "version" => self::$version,
                "author" => self::$author,
                "language" => "english",
                "fields" => array( 
                    "option1" => array (
                            "FriendlyName" => "2", 
                            "Type" => "radio", 
                            "Default" => "1",
                            "Options" =>
                                    array(
                                       "123",
                                       "456"
                                    ), 
                            "Description" => "233"),                             
                ));
            return $configarray;
        }

        public static function activate()
        {
            try
            {
                Schema::dropIfExists("mod_SCloud_nodes");
                Schema::create("mod_SCloud_nodes", function(\Illuminate\Database\Schema\Blueprint $table){
                    $table->increments("id");
                    $table->string("uuid");
                    $table->string("country");
                    $table->string("type");
                    $table->string("name")->nullable()->default("noname");
                    $table->string("ip")->nullable()->default("127.0.0.1");
                    $table->integer("port")->nullable();
                    $table->integer("enabled")->default("0");
                    $table->integer("transferMode")->default("0");
                    $table->text("configoptiontable")->nullable();
                    $table->text("advancedconfigoptiontable")->nullable();
                    $table->integer("u")->default("0");
                    $table->integer("d")->default("0");
                    $table->date("created_at");
                    $table->date("updated_at");
                });
                Schema::dropIfExists("mod_SCloud_logs");
                Schema::create("mod_SCloud_logs", function(\Illuminate\Database\Schema\Blueprint $table){
                    $table->increments("id");
                    $table->string("level");
                    $table->string("log");
                    $table->date("created_at");
                });
                Schema::dropIfExists("mod_SCloud_groups");
                Schema::create("mod_SCloud_groups", function(\Illuminate\Database\Schema\Blueprint $table){
                    $table->increments("id");
                    $table->string("name")->nullable()->default("noname");//name
                    $table->integer("enabled")->default("0");
                    $table->string("nodes");//nodes
                    $table->date("created_at");
                    $table->date("updated_at");
                });
                Schema::dropIfExists("mod_SCloud_products");
                Schema::create("mod_SCloud_products", function(\Illuminate\Database\Schema\Blueprint $table){
                    $table->increments("id");
                    $table->string("name")->nullable()->default("noname");//name
                    $table->string("groups");//groups
                    $table->date("created_at");
                    $table->date("updated_at");
                });
                Schema::dropIfExists("mod_SCloud_transfer");
                Schema::create("mod_SCloud_transfer", function(\Illuminate\Database\Schema\Blueprint $table){
                    $table->increments("id");
                    $table->integer("uid");
                    $table->integer("sid");
                    $table->string("from");
                    $table->string("to");
                    $table->integer("fromport");
                    $table->integer("toport");
                    $table->date("created_at");
                    $table->integer("enabled")->default("0");
                });
                Schema::dropIfExists("mod_SCloud_node_traffic_log");
                Schema::create("mod_SCloud_node_traffic_log", function(\Illuminate\Database\Schema\Blueprint $table){
                    $table->increments("id");
                    $table->integer("nid");//nodeid
                    $table->integer("u")->default("0");
                    $table->integer("d")->default("0");
                    $table->date("updated_at");
                });
                Schema::dropIfExists("mod_SCloud_node_traffic_photo");
                Schema::create("mod_SCloud_node_traffic_photo", function(\Illuminate\Database\Schema\Blueprint $table){
                    $table->increments("id");
                    $table->integer("nid");//nodeid
                    $table->integer("uid");//userid
                    $table->integer("u")->default("0");
                    $table->integer("d")->default("0");
                    $table->date("created_at");
                });
                return array( "status" => "success", "description" => "模块激活成功" );
            }
            catch( \Exception $e ) 
            {
                return array( "status" => "error", "description" => "模块激活失败：" . $e->getMessage() );
            }
        }

        public static function deactivate()
        {
            try
            {
                \Illuminate\Database\Capsule\Manager::table("tbladdonmodules")->where("module", "mod_SCloud_nodes")->delete();
                Schema::dropIfExists("mod_SCloud_nodes");
                Schema::dropIfExists("mod_SCloud_logs");
                Schema::dropIfExists("mod_SCloud_products");
                Schema::dropIfExists("mod_SCloud_transfer");
                Schema::dropIfExists("mod_SCloud_node_traffic_log");
                return array( "status" => "success", "description" => "模块卸载成功" );
            }
            catch( \Exception $e ) 
            {
                return array( "status" => "error", "description" => "模块卸载失败：" . $e->getMessage() );
            }
        }

        public static function output()
        {
            $action = $_REQUEST["action"];
            if($action != NULL)
            {
                $AdminArea = new AdminArea;
                if(method_exists($AdminArea, $action))
                {
                    return AdminArea::$action(self::$vars);
                }
                elseif(in_array($action, AdminArea::$actions))
                {
                    return AdminArea::output(self::$vars);
                }
                else
                {
                    Tools::outputJSON(array( "status" => "error", "msg" => "请求的方法不存在" ));
                }
            }
            return AdminArea::output(self::$vars);
        }
    }
}