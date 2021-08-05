<?php
$modulelist["mqdeploy"]["name"]="MQSC/PCF Deploy module";
include_once "api.php";
  class IBMMQ{
    public static function execJava($type,$connd,$mqsc){ 
      if(file_exists(dirname(__FILE__)."/resources/midleo.jar")){
        if(!empty($connd) && !empty($mqsc) && ($type=="READ" || $type=="WRITE")){
          if(function_exists('exec')) {
            exec("java -jar ".dirname(__FILE__)."/resources/midleo.jar '".$connd."' \"".$mqsc."\"", $output);
            return $output;
          } else {
            return array("err"=>true, "log"=>"Exec function is not enabled.");
          }
        } else {
          return array("err"=>true, "log"=>"Please specify connection details and mqsc commands");
        }
      } else {
        return array("err"=>true, "log"=>"MidlEO pcf class does not exist!");
      }
    } 
  }