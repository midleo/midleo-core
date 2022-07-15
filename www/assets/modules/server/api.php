<?php
$reltypes=array(
    "ibmmq"=>"IBM MQ",
    "imbwas"=>"IBM Websphere AS (v70, v85 or traditional-v9-0)",
    "jboss"=>"Oracle Jboss EAP",
    "wildfly"=>"Oracle Wildfly server",
    "tomcat"=>"Apache Tomcat server",
);
class serverApi{
    public static function imbwas($ver=null,$matchword=null){
        if($ver){
            $dom = new DOMDocument();
            $data=array();
            libxml_use_internal_errors(true);
            $html = $dom->loadHTMLFile("https://www.ibm.com/support/pages/fix-list-ibm-websphere-application-server-".$ver);
            $dom->preserveWhiteSpace = false; 
            $tables = $dom->getElementsByTagName('table'); 
            $rows = $tables->item(1)->getElementsByTagName('tr');
            $cols = $rows->item(1)->getElementsByTagName('td');
            $data["fixpack"]=str_replace(array("Fix Pack","Refresh Pack","Latest Release","\n","\t"," "),"",!empty($cols->item(0)->nodeValue)?$cols->item(0)->nodeValue:$cols->item(1)->nodeValue);
            if(!empty($data["fixpack"]) && strpos($data["fixpack"], $matchword) !== false){ 
                $newdata=$data["fixpack"]; 
            }
            if(strpos($newdata, ")") !== false){
                preg_match( '!\(([^\)]+)\)!', $newdata, $newdata );
                $newdata=$newdata[1];
            }
            if($newdata){
                $pdo = pdodb::connect();
                $sql = "update env_releases set latestver=?, lastcheck=now() where reltype='imbwas' and relversion=?";
                $q = $pdo->prepare($sql);
                $q->execute(array($newdata,$ver));
                pdodb::disconnect();
            }
        }
    }
    public static function ibmmq($ver=null,$matchword=null){
        if($ver){
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
               if(!empty($data["version"]) && !empty($data["fixpack"]) && strpos($data["fixpack"], $ver) !== false && strpos($data["version"], $matchword) !== false){ 
                $newdata=$data["fixpack"]; 
               }
             }
             if($newdata){
                 $pdo = pdodb::connect();
                 $sql = "update env_releases set latestver=?, lastcheck=now() where reltype='ibmmq' and relversion=?";
                 $q = $pdo->prepare($sql);
                 $q->execute(array($newdata,$ver));
                 pdodb::disconnect();
             }
        }
    }
    public static function jboss($ver=null){
    
    }
    public static function wildfly($ver=null){
    
    }
    public static function tomcat($ver=null){
        if($ver){
            $url = "https://downloads.apache.org/tomcat/tomcat-".$ver."/";
            $html = file_get_contents($url);
            $count = preg_match_all('/<a href="v([^"]+)">[^<]*<\/a>/i', $html, $files);
            $files = preg_replace('/\//', '', $files[1]);
            if(max($files)){
                $pdo = pdodb::connect();
                $sql = "update env_releases set latestver=?, lastcheck=now() where reltype='tomcat' and relversion=?";
                $q = $pdo->prepare($sql);
                $q->execute(array(max($files),$ver));
                pdodb::disconnect();
            }
        }
    }
}