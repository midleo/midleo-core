<?php
$reltypes=array(
    "ibmmq"=>"IBM MQ",
    "jboss"=>"Oracle Jboss EAP",
    "wildfly"=>"Oracle Wildfly server",
    "tomcat"=>"Apache Tomcat server",
);
class serverApi{
    public static function ibmmq(){
       $dom = new DOMDocument();
       $data=array();
       libxml_use_internal_errors(true);
       $html = $dom->loadHTMLFile("https://www.ibm.com/support/pages/recommended-fixes-ibm-mq");
       $dom->preserveWhiteSpace = false; 
       $tables = $dom->getElementsByTagName('table'); 
       $rows = $tables->item(0)->getElementsByTagName('tr'); 
       foreach ($rows as $row) 
       {  
          $cols = $row->getElementsByTagName('td'); 
          $data["version"]=$cols->item(0)->nodeValue; 
          $data["fixpack"]=str_replace(array("Fix Pack","Latest Release","\n","\t"," "),"",$cols->item(1)->nodeValue); 
          if(!empty($data["version"]) && !empty($data["fixpack"])){ $newdata[]=$data; }
        }
        if($newdata){
            $pdo = pdodb::connect();
            $sql = "update env_releases set latestver=?, lastcheck=now() where reltype='ibmmq'";
            $q = $pdo->prepare($sql);
            $q->execute(array(json_encode($newdata, true)));
            pdodb::disconnect();
        }
    }
    public static function jboss(){
    
    }
    public static function wildfly(){
    
    }
    public static function tomcat(){
    
    }

}