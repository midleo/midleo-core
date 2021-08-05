<?php
$modulelist["tibco"]["name"]="TIBCO EMS controller";
include_once "api.php";
class tibco{
    public static function execJava($connd,$command){ 
        if(file_exists(dirname(__FILE__)."/resources/midleo_tibco.jar")){
          if(!empty($connd) && !empty($command)){
            if(function_exists('exec')) {
              exec("java -jar ".dirname(__FILE__)."/resources/midleo_tibco.jar '".$connd."' '".$command."'", $output);
              return $output;
            } else {
              return array("err"=>true, "log"=>"Exec function is not enabled.");
            }
          } else {
            return array("err"=>true, "log"=>"Please specify connection details and commands");
          }
        } else {
          return array("err"=>true, "log"=>"MidlEO tibco class does not exist!");
        }
    } 
    public static function acl_deploy(){


    }
}