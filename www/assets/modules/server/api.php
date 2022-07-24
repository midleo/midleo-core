<?php
$reltypes=array(
    "ibmmq"=>"IBM MQ",
    "ibmace"=>"IBM App connect (11, 12)",
    "ibmiib"=>"IBM Integration Bus (9, 10)",
    "imbwas"=>"IBM Websphere AS (v70, v85 or traditional-v9-0)",
    "jboss"=>"Oracle Jboss EAP (7.0, 7.1, 7.2, 7.3, 7.4)",
    "wildfly"=>"Oracle Wildfly server",
    "tomcat"=>"Apache Tomcat server",
    "nginx"=>"Nginx Web server",
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
    public static function jboss($ver=null,$arrver=array("7.4"=>0,"7.3"=>1,"7.2"=>2,"7.1"=>3,"7.0"=>4)){
        if($ver){
            $dom = new DOMDocument();
            $data="";
            libxml_use_internal_errors(true);
            $html=file_get_contents("https://access.redhat.com/articles/2332721");
            $html=preg_replace('%<sup.*?</sup>%i', '', $html);
            $dom->loadHTML($html);
            $dom->preserveWhiteSpace = false;
            $tables = $dom->getElementsByTagName('table'); 
            $rows = $tables->item($arrver[$ver])->getElementsByTagName('tr'); 
            $i=0;
            foreach ($rows as $row) 
            {
                $cols = $row->getElementsByTagName('td'); 
                if (DateTime::createFromFormat('F j, Y', $cols->item(1)->nodeValue) !== false) {
                    $i++;
                    if($i==1){ $data=$ver.".".str_replace(array("Update ","Refresh Pack","Latest Release","\n","\t"," "),"",$cols->item(0)->nodeValue);}
                }
            }
            if($data){
                $pdo = pdodb::connect();
                $sql = "update env_releases set latestver=?, lastcheck=now() where reltype='jboss' and relversion=?";
                $q = $pdo->prepare($sql);
                $q->execute(array($data,$ver));
                pdodb::disconnect();
            }
        }
    }
    public static function wildfly($ver=null){
        $dom = new DOMDocument();
        $data="";
        libxml_use_internal_errors(true);
        $html = $dom->loadHTMLFile("https://docs.wildfly.org");
        $dom->preserveWhiteSpace = false; 
        $tables = $dom->getElementsByTagName('table'); 
        $rows = $tables->item(0)->getElementsByTagName('tr');
        $cols = $rows->item(1)->getElementsByTagName('td');
        $data=str_replace(array("Fix Pack","Refresh Pack","Latest Release","Release",".Final","\n","\t"," "),"",$cols->item(0)->nodeValue?$cols->item(0)->nodeValue:"");
        if($data){
            $pdo = pdodb::connect();
            $sql = "update env_releases set latestver=?, lastcheck=now() where reltype='wildfly'";
            $q = $pdo->prepare($sql);
            $q->execute(array($data));
            pdodb::disconnect();
        }
    }
    public static function nginx($ver=null){
        $dom = new DOMDocument();
        $data="";
        libxml_use_internal_errors(true);
        $html = $dom->loadHTMLFile("http://nginx.org/en/download.html");
        $dom->preserveWhiteSpace = false; 
        $tables = $dom->getElementsByTagName('table'); 
        $rows = $tables->item(0)->getElementsByTagName('tr');
        $cols = $rows->item(0)->getElementsByTagName('td');
        $data=trim(str_replace(array("nginx","pgp","-","\t"," ","\t\n\r","\n","\r"),"",strval($cols->item(1)->nodeValue)),chr(0xC2).chr(0xA0));
        if($data){
            $pdo = pdodb::connect();
            $sql = "update env_releases set latestver=?, lastcheck=now() where reltype='nginx'";
            $q = $pdo->prepare($sql);
            $q->execute(array($data));
            pdodb::disconnect();
        }
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
    public static function ibmace($ver=null){
        if($ver){
            $dom = new DOMDocument();
            $data="";
            libxml_use_internal_errors(true);
            $html = $dom->loadHTMLFile("https://www.ibm.com/support/pages/fix-list-ibm-app-connect-enterprise-version-".$ver."0");
            $dom->preserveWhiteSpace = false; 
            $tables = $dom->getElementsByTagName('table'); 
            $rows = $tables->item(0)->getElementsByTagName('tr');
            $cols = $rows->item(0)->getElementsByTagName('td');
            $data=str_replace(array("Fix Pack","Refresh Pack","Latest Release","Release","\n","\t"," "),"",$cols->item(0)->nodeValue?$cols->item(0)->nodeValue:"");
            if($data){
                $pdo = pdodb::connect();
                $sql = "update env_releases set latestver=?, lastcheck=now() where reltype='ibmace' and relversion=?";
                $q = $pdo->prepare($sql);
                $q->execute(array($data,$ver));
                pdodb::disconnect();
            }
        }
    }
    public static function ibmiib($ver=null){
        if($ver){
            $dom = new DOMDocument();
            $data="";
            libxml_use_internal_errors(true);
            $html = $dom->loadHTMLFile("https://www.ibm.com/support/pages/fix-list-ibm-integration-bus-version-".$ver."0");
            $dom->preserveWhiteSpace = false; 
            $tables = $dom->getElementsByTagName('table'); 
            $rows = $tables->item(0)->getElementsByTagName('tr');
            $cols = $rows->item(0)->getElementsByTagName('td');
            $data=str_replace(array("Fix Pack","Refresh Pack","Latest Release","Release","\n","\t"," "),"",$cols->item(0)->nodeValue?$cols->item(0)->nodeValue:"");
            if($data){
                $pdo = pdodb::connect();
                $sql = "update env_releases set latestver=?, lastcheck=now() where reltype='ibmiib' and relversion=?";
                $q = $pdo->prepare($sql);
                $q->execute(array($data,$ver));
                pdodb::disconnect();
            }
        }
    }
}