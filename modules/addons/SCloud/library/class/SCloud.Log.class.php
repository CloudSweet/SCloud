<?php
namespace CloudSweet\SCloud;

class Log
{
	public static function log($level, $data)
    {
        try
        {
            \Illuminate\Database\Capsule\Manager::table("mod_SCloud_logs")->insert(
                array(
                    "level" => $level,
                    "log" => $data,
                    "created_at" => \Carbon\Carbon::now(),
                )
            );

        }
        catch( \Exception $e ) 
        {
            return false;
        }
    }
}