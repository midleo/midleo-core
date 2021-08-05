<?php
#https://cloud01.midleo.com/ibmmq/rest/v1/admin/qmgr/VMQAP01/queue?attributes=*&status=*&applicationHandle=*
#https://www.ibm.com/support/pages/sites/default/files/inline-files/Using%20the%20MQ%209.1.5%20CD%20REST%20API%20in%20Linux%20with%20no%20security%20(for%20Testing%20environments).pdf
#https://www.ibm.com/support/knowledgecenter/SSFKSJ_9.0.0/com.ibm.mq.ref.adm.doc/q129080_.html
#http://guide2.webspheremq.fr/wp-content/uploads/2017/04/Open-Source-Monitoring-for-MQ.pdf
#https://www.mqweb.org/api/index.html
#http://www.mqseries.net/phpBB2/viewtopic.php?t=76519&sid=0f7166f1520f17d4a8b3950b78002e68
#https://curl.trillworks.com/
#http://www.mqseries.net/phpBB2/viewtopic.php?p=436531#436531

$modulelist["mqrest"]["name"]="IBM MQ Rest controller";
$modulelist["mqconf"]["name"]="IBM MQ Resource controller";
class mqRest{
    public static function mq_session($user,$pass,$hostname,$sessdir,$ssl="0") {
		if(!file_exists($sessdir."cookiemq-".$hostname.".txt") || !isset($_COOKIE['mqsession'.$hostname])){
	    if(file_exists($sessdir."cookiemq-".$hostname.".txt")){ unlink($sessdir."cookiemq-".$hostname.".txt"); };
		$data_string = json_encode(array("username" => $user,"password"=>$pass));
        setcookie("mqsession".$hostname,"active", time()+1*60*24*COOKIE_TIME_OUT, "/");
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, "http".($ssl=1?"s":"")."://".$hostname.":".($ssl=1?"9443":"9080")."/ibmmq/rest/v1/login");    
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json','Content-Type: application/json'));   
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
		curl_setopt($ch, CURLOPT_POST, true);                                                                   
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);     
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $sessdir."cookiemq-".$hostname.".txt");
		curl_setopt($ch, CURLOPT_COOKIEJAR, $sessdir."cookiemq-".$hostname.".txt");
		curl_setopt($ch, CURLOPT_USERAGENT, 'Vasilev MQ Agent');
		$result = curl_exec($ch);
		curl_close($ch);  
		if(!empty($result)){ 
		return $result;
		}
		}
	}
	public static function mq_host($hostname,$sessdir,$ssl="0") {
		if(!file_exists($sessdir."cookiemq-".$hostname.".txt")){ return false; }
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, "http".($ssl=1?"s":"")."://".$hostname.":".($ssl=1?"9443":"9080")."/ibmmq/rest/v1/installation?attributes=extended"); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
		curl_setopt($ch, CURLOPT_COOKIEFILE, $sessdir."cookiemq-".$hostname.".txt");
		curl_setopt($ch, CURLOPT_COOKIEJAR, $sessdir."cookiemq-".$hostname.".txt");  
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Vasilev MQ Agent');
		$result = curl_exec($ch);
		curl_close($ch);  
		return $result;
	}
	public static function mq_qmgr($hostname,$qmgr,$sessdir,$ssl="0") {
		if(!file_exists($sessdir."cookiemq-".$hostname.".txt")){ return false; }
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, "http".($ssl=1?"s":"")."://".$hostname.":".($ssl=1?"9443":"9080")."/ibmmq/rest/v1/qmgr/".$qmgr."?attributes=extended"); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
		curl_setopt($ch, CURLOPT_COOKIEFILE, $sessdir."cookiemq-".$hostname.".txt");
		curl_setopt($ch, CURLOPT_COOKIEJAR, $sessdir."cookiemq-".$hostname.".txt");  
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Vasilev MQ Agent');
		$result = curl_exec($ch);
		curl_close($ch);  
		return $result;
	}
	public static function mq_queues($hostname,$qmgr,$sessdir,$queue="",$ssl="0",$system="no") {
		if(!file_exists($sessdir."cookiemq-".$hostname.".txt")){ return false; }
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, "http".($ssl=1?"s":"")."://".$hostname.":".($ssl=1?"9443":"9080")."/ibmmq/rest/v1/qmgr/".$qmgr."/queue".(!empty($queue)?"/".$queue:"")); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
		curl_setopt($ch, CURLOPT_COOKIEFILE, $sessdir."cookiemq-".$hostname.".txt");
		curl_setopt($ch, CURLOPT_COOKIEJAR, $sessdir."cookiemq-".$hostname.".txt");  
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Vasilev MQ Agent');
		$result = curl_exec($ch);
		curl_close($ch); 
		if(!empty($result)){
			$queues=array();
			$objects=json_decode($result,true);
			foreach($objects['queue'] as $key=>$value){
				((substr($value['name'], 0, 6) == 'SYSTEM' && $system=="no")  ? "" : array_push($queues,$value['name']));
			}
			sort($queues);
			return json_encode($queues,true);
		} else {
			return $result;
		}
	}
	public static function mq_change($hostname,$qmgr,$sessdir,$queue,$arrdata,$ssl="0"){
		if(!file_exists($sessdir."cookiemq-".$hostname.".txt")){ return false; }
		mqRest::mq_token($hostname,$sessdir);
		if(!empty($_SESSION['ibmcsrftoken'.$hostname])){
		    $data_string = json_encode($arrdata);
			$ch = curl_init(); 
			curl_setopt($ch, CURLOPT_URL, "http".($ssl=1?"s":"")."://".$hostname.":".($ssl=1?"9443":"9080")."/ibmmq/rest/v1/qmgr/".$qmgr."/queue/".$queue);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Accept: application/json',
			'Content-Type: application/json',
			'ibm-mq-rest-csrf-token: '.$_SESSION['ibmcsrftoken'.$hostname])
			);   
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");  
			curl_setopt($ch, CURLOPT_POST, true);                                                                   
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);     
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_COOKIEFILE, $sessdir."cookiemq-".$hostname.".txt");
			curl_setopt($ch, CURLOPT_COOKIEJAR, $sessdir."cookiemq-".$hostname.".txt");  
			$result = curl_exec($ch);
			curl_close($ch);  
			return $result;
		} else {
			echo "There was a problem while getting the token. Please delete ibmcsrftoken* cookie and try again.";
		}
	}
	public static function mq_token($hostname,$sessdir){
		if(!isset($_COOKIE['ibmcsrftoken'.$hostname])){
			setcookie("ibmcsrftoken".$hostname,"active", time()+1*60*24*COOKIE_TIME_OUT, "/");
			$lines = file($sessdir."cookiemq-".$hostname.".txt");
			foreach($lines as $line) {
				if($line[0] != '#' && substr_count($line, "\t") == 6) {
					$tokens = explode("\t", $line);
					$tokens = array_map('trim', $tokens);
					if($tokens[5]=="csrfToken"){ 
					$_SESSION['ibmcsrftoken'.$hostname]=$tokens[6];
					} else {
					$_SESSION['ibmcsrftoken'.$hostname]="";
					}
				}
			}
		}
	}
}
class mqConf{
  public static function mqsc($proj,$qm,$objtype,$objid=false,$zobj=array()){
	global $env;
    $err="";
    $str="";
	$pdo = pdodb::connect();
	$arrayvars=json_decode("[{}]",true);
    $sql="select varname,varvalue,isarray from mqenv_vars where proj=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($proj));
    if($zobjin = $q->fetchAll()){
     foreach($zobjin as $valin) {
      if($valin['isarray']==1){
        $arrayvars["{".$valin['varname']."}"]=array("isarray"=>$valin['isarray'],"val"=>json_decode($valin['varvalue'],true));
       } else {
        $arrayvars["{".$valin['varname']."}"]=array("isarray"=>$valin['isarray'],"val"=>$valin['varvalue']);
       }
     }
	} 
	if (!empty($env)) {  $menudataenv = json_decode($env, true); } else {  $menudataenv = array(); }
	if(empty($zobj)){
     if($objid){
        $sql= "SELECT qmgr,objname,objtype,".(DBTYPE=='oracle'?"to_char(objdata) as objdata":"objdata")." FROM mqenv_mq_".$objtype." where qmgr=? and proj=? and id=?";
        $stmt = $pdo->prepare($sql);
		$stmt->execute(array($qm,$proj,$objid));
		$zobj = $stmt->fetchAll();
     } else {
        $sql= "SELECT qmgr,objname,objtype,".(DBTYPE=='oracle'?"to_char(objdata) as objdata":"objdata")." FROM mqenv_mq_".$objtype." where qmgr=? and proj=?";
        $stmt = $pdo->prepare($sql);
		$stmt->execute(array($qm,$proj));
		$zobj = $stmt->fetchAll();
	 }
    }
    if(is_array($zobj) && !empty($zobj)){
      foreach($zobj as $row) { 
         $objects=json_decode($row['objdata'],true); 
         unset($objects['qm']);
         unset($objects['env']);
         unset($objects['tags']); 
		 unset($objects['proj']); 
		 unset($objects['jobid']); 
         if($row['objtype']!="group"){ $i++;
            if($row['objtype']=="sub"){
              $str.="DELETE SUB('".strtoupper($row['objname'])."');";
            }
            if($objtype=="qm"){
              $str.="ALTER QMGR ";
            } else {
              $str.="DEFINE ".strtoupper($row['objtype'])."('".strtoupper($row['objname'])."') ";
            }
            if(!empty($objects['chltype'])){
              $str.="  CHLTYPE(".strtoupper($objects['chltype']).")   ";
              unset($objects['chltype']);
            }
            if(!empty($objects['maxmsgl'])){
              $str.="   MAXMSGL(".str_replace("?","",mb_convert_encoding($objects['maxmsgl'], 'ASCII','UTF-8')).")   ";
              unset($objects['maxmsgl']);
            }
            unset($objects['name']);
            unset($objects['type']);
            unset($objects['active']);
            foreach($objects as $keyoin=>$valoin){
             if(!empty($valoin)){
               if($keyoin=="trigger"){
                 $str.="  ".strtoupper($valoin)."  ";
               } else {
                 $str.="  ".strtoupper($keyoin)."(".(!preg_match('/[^A-Za-z0-9]/', $valoin)?($keyoin=="mcauser"?"'".$valoin."'":strtoupper($valoin)):"'".$valoin."'").") ";
               }
             }
           }
           if($objtype=="qm"){
            $str.=";";
           } else {
            $str.="  REPLACE;";
           }
        } else{
           $err="There was some problem in connection. Please contact the developer.";
        }
	 }
	 if(is_array($menudataenv)){ foreach($menudataenv as $keyenv=>$valenv){
		$strarr[$valenv['nameshort']]=textClass::stage_array($str,$arrayvars,$valenv['nameshort']);
	  }
	  return array('str'=>$strarr,"err"=>$err);
	} else {
		$str=textClass::stage_array($str,$arrayvars,"");
		return array('str'=>$str,"err"=>$err);
	}
   }
    pdodb::disconnect();
  }
}