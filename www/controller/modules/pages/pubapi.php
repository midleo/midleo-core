<?php
class Class_pubapi{
  public static function getPage($thisarray){
    header('Content-type:application/json;charset=utf-8');  
    global $website;
    global $maindir;
    session_start();
    $err = array();
    $msg = array();
    if(!empty($thisarray["p1"])) {
    switch($thisarray["p1"]) { 
      case 'getallusrgr':  Class_pubapi::readAllusrGr($thisarray["p2"]);  break;
      case 'getallgr':  Class_pubapi::readAllGr($thisarray["p2"]);  break;
      case 'searchtags':  Class_pubapi::readTags($thisarray["p2"]);  break;
      case 'searchreq':  Class_pubapi::readRequests($thisarray["p2"]);  break;
      case 'searchreqeff':  Class_pubapi::readRequestsEff($thisarray["p2"]);  break;
      case 'getallapp':  Class_pubapi::readAllapps($thisarray["p2"]);  break;
      case 'listallapp':  Class_pubapi::listAllapps($thisarray["p2"]);  break;
      case 'getallappsrv':  Class_pubapi::readAllAppsrv($thisarray["p2"]);  break;
      case 'getallsrv':  Class_pubapi::readAllsrv($thisarray["p2"]);  break;
      case 'getallpack':  Class_pubapi::readAllpack($thisarray["p2"]);  break;
      case 'searchproj': Class_pubapi::readAllproj($thisarray["p2"]);  break;
      case 'getodcfg': Class_pubapi::readODCfg();  break;
      case 'getallplaces': Class_pubapi::readPlaces();  break;
      case 'getdbcfg': Class_pubapi::readDBCfg();  break;
      case 'getgitcfg': Class_pubapi::readGITCfg();  break;
      case 'captcha': Class_pubapi::captcha(); break;
      case 'updatesrv': Class_pubapi::updateSrv(); break;
      default: echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"please use the API correctly."));exit;
                    }
  } else { echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"please use the API correctly."));exit;  }
  }
  public static function readTags($d1){
    if(isset($_POST['search'])){
      $pdo = pdodb::connect();
      $data=array();
      $sql="select what,swhere,tags from search WHERE tags like'%".htmlspecialchars($_POST['search'])."%' limit 20";
      $q = $pdo->prepare($sql);
      $q->execute();
        if($zobj = $q->fetchAll()){  
          foreach($zobj as $val) {  
            $temp = explode(',', $val['tags']);
            foreach($temp as $valin){
              $data[]=array("what"=>$val['what'],"where"=>$val['swhere'],"name"=>$valin);
            }
        }
      }
      echo json_encode($data,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
      pdodb::disconnect();
      clearSESS::template_end();
      exit;
    }
  }
  public static function readAllusrGr($d1){
    if(isset($_POST['search'])){
    $pdo = pdodb::connect();
    $data=array();
    $sql="select mainuser,fullname,email,avatar from users WHERE fullname like'%".htmlspecialchars($_POST['search'])."%' limit 10";
    $q = $pdo->prepare($sql);
    $q->execute();
      if($zobj = $q->fetchAll()){  
        foreach($zobj as $val) {  
         $data[]=array("name"=>$val['fullname'],"nameid"=>$val['mainuser'],"email"=>$val['email'],"avatar"=>!empty($val['avatar'])?$val['avatar']:"/assets/images/avatar.svg","type"=>"user");
      }
    }
    if($d1=="all"){
      $sql="select group_latname,group_name,group_email,group_avatar from user_groups WHERE group_name like'%".htmlspecialchars($_POST['search'])."%' limit 10";
      $q = $pdo->prepare($sql);
      $q->execute();
      if($zobj = $q->fetchAll()){  
        foreach($zobj as $val) {  
         $data[]=array("name"=>$val['group_name'],"nameid"=>$val['group_latname'],"email"=>$val['group_email'],"avatar"=>!empty($val['group_avatar'])?$val['group_avatar']:"/assets/images/avatar.svg","type"=>"group");
      }
     }
    }
    echo json_encode($data,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
    pdodb::disconnect();
    clearSESS::template_end();
      exit;
   }
  }
  public static function readAllGr($d1){
    if(isset($_POST['search'])){
    $pdo = pdodb::connect();
    $data=array();
    $sql="select group_latname,group_name from user_groups WHERE group_name like '%".htmlspecialchars($_POST['search'])."%' or group_latname like '%".htmlspecialchars($_POST['search'])."%' limit 10";
    $q = $pdo->prepare($sql);
    $q->execute();
    if($zobj = $q->fetchAll()){  
      foreach($zobj as $val) {  
       $data[]=array("name"=>$val['group_name'],"nameid"=>$val['group_latname']);
     }
    }
    echo json_encode($data,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
    pdodb::disconnect();
    clearSESS::template_end();
    exit;
   }
  }
  public static function readRequests($d1){
    if(isset($_POST['search'])){
      $pdo = pdodb::connect();
      $data=array();
      $sql="select sname, reqname from requests WHERE sname like'%".htmlspecialchars($_POST['search'])."%' or reqname like'%".htmlspecialchars($_POST['search'])."%' limit 10";
      $q = $pdo->prepare($sql);
      $q->execute();
      if($zobj = $q->fetchAll()){  
        foreach($zobj as $val) {  
         $data[]=array("name"=>$val['sname']." ".$val['reqname'],"nameid"=>$val['sname']);
       }
      }
      $data[]=array("name"=>"General/Other","nameid"=>"xxx");
      echo json_encode($data,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
      pdodb::disconnect();
      clearSESS::template_end();
      exit;
   }
  }
  public static function readRequestsEff($d1){
    if(isset($_POST['search'])){
      $pdo = pdodb::connect();
      $data=array();
      $sql="select t.reqid,s.reqname,s.sname from requests_efforts t, requests s where t.effuser=? and s.sname=t.reqid and s.projapproved='1' and s.projconfirmed='1' and (s.sname like'%".htmlspecialchars($_POST['search'])."%' or s.reqname like'%".htmlspecialchars($_POST['search'])."%') limit 10";
      $q = $pdo->prepare($sql);
      $q->execute(array($_SESSION["user"]));
      if($zobj = $q->fetchAll()){  
        foreach($zobj as $val) {  
         $data[]=array("name"=>$val['sname']." ".$val['reqname'],"nameid"=>$val['sname']);
       }
      }
      $data[]=array("name"=>"General/Other","nameid"=>"xxx");
      echo json_encode($data,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
      pdodb::disconnect();
      clearSESS::template_end();
      exit;
   }
  }
  public static function readAllapps($d1){
    if(isset($_POST['search'])){
      $pdo = pdodb::connect();
      $data=array();
      $sql="select appcode,appname from config_app_codes where appcode like '%".htmlspecialchars($_POST['search'])."%' or appname like '%".htmlspecialchars($_POST['search'])."%'  limit 10";
      $q = $pdo->prepare($sql);
      $q->execute();
      if($zobj = $q->fetchAll()){  
        foreach($zobj as $val) {  
         $data[]=array("name"=>$val['appname'],"nameid"=>$val['appcode']);
       }
      }
      $data[]=array("name"=>"General/Other","nameid"=>"xxx");
      echo json_encode($data,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
      pdodb::disconnect();
      clearSESS::template_end();
      exit;
   }
  }
  public static function captcha(){
    session_start();
    $num1=rand(1,20);
    $num2=rand(1,20); 
    $captcha_total=$num1+$num2;  
    $math = "$num1"." + "."$num2"." =";  
    $_SESSION['rand_code'] = $captcha_total;
    $font = 'assets/fonts/ubuntu-regular-webfont.woff';
    $image = imagecreatetruecolor(120, 30);
    $black = imagecolorallocate($image, 0, 0, 0);
    $color = imagecolorallocate($image, 23, 162, 184);
    $white = imagecolorallocate($image, 255, 255, 255);
    
    imagefilledrectangle($image,0,0,399,99,$white);
    imagettftext ($image, 16, 0, 10, 25, $color, $font, $math );
    
    header("Content-type: image/png");
    imagepng($image);
  }
  public static function readAllAppsrv($d1){
    if(isset($_POST['search'])){
      $pdo = pdodb::connect();
      $data=array();
      $sql="select id,serverdns,qmname,info,appsrvname from env_appservers where (qmname like'%".htmlspecialchars($_POST['search'])."%' or info like'%".htmlspecialchars($_POST['search'])."%' or appsrvname like'%".htmlspecialchars($_POST['search'])."%') ".(!empty($d1)?" and serv_type='".$d1."'":"");
      $q = $pdo->prepare($sql);
      $q->execute();
      if($zobj = $q->fetchAll()){  
        foreach($zobj as $val) {  
         $data[]=array("name"=>$val['qmname'],"nameid"=>$val['id'],"appsrvname"=>$val['appsrvname']);
       }
      }
      echo json_encode($data,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
      pdodb::disconnect();
      clearSESS::template_end();
      exit;
   }
  }
  public static function readPlaces(){
    if(isset($_POST['search'])){
      $pdo = pdodb::connect();
      $data=array();
      $sql="select placename,plcity, pluid from env_places where (placename like'%".htmlspecialchars($_POST['search'])."%' or plregion like'%".htmlspecialchars($_POST['search'])."%' or plcity like'%".htmlspecialchars($_POST['search'])."%') ";
      $q = $pdo->prepare($sql);
      $q->execute();
      if($zobj = $q->fetchAll()){  
        foreach($zobj as $val) {  
         $data[]=array("name"=>$val['placename']."@".$val['plcity'],"nameid"=>$val['pluid']);
       }
      }
      echo json_encode($data,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
      pdodb::disconnect();
      clearSESS::template_end();
      exit;
   }
  }
  public static function readAllproj($d1){
    if(isset($_POST['search'])){
      $pdo = pdodb::connect();
      $data=array();
      $sql="select projcode,projname,budget,budgetspent from config_projects where (projcode like'%".htmlspecialchars($_POST['search'])."%' or projinfo like'%".htmlspecialchars($_POST['search'])."%') and projduedate>=? limit 10";
      $q = $pdo->prepare($sql);
      $q->execute(array(date("Y-m-d")));
      if($zobj = $q->fetchAll()){  
        foreach($zobj as $val) {  
         $data[]=array("name"=>$val['projname']."(".$val["projcode"].")","nameid"=>$val['projcode'],"budget"=>$val['budget'],"budgetspent"=>$val['budgetspent']);
       }
      }
      $data[]=array("name"=>"General/Run","nameid"=>"xxx","budget"=>"0","budgetspent"=>"0");
      echo json_encode($data,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
      pdodb::disconnect();
      clearSESS::template_end();
      exit;
   }
  }
  public static function updateSrv(){
    $json = file_get_contents('php://input');
    $data = json_decode($json); 
    if(!empty($data)){
      $pdo = pdodb::connect();
      $temparr["count"]=gTable::countAll("env_servers"," where serverid='".$data->uid."'");
      if($temparr["count"]>0){
        $sql="update env_servers set servertype=?,serverdns=?,serverip=?,serverprog=?,serverdisc=?,servernet=?, servupdated=now(), groupid=? where serverid=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($data->hw_info->servtype,$data->hw_info->name,$data->net_info->dns,json_encode($data->installed_software,true),json_encode($data->hw_info->disk_partitions,true),json_encode($data->net_info->if_addresses,true),$data->groupid,$data->uid));
      } else {
        $sql="insert into env_servers (serverid,serverdns,servertype,serverip,serverprog,serverdisc,servernet,groupid,updperiod) values (?,?,?,?,?,?,?,?,?)";
        $q = $pdo->prepare($sql);
        $q->execute(array($data->uid,$data->hw_info->name,$data->hw_info->servtype,$data->net_info->dns,json_encode($data->installed_software,true),json_encode($data->hw_info->disk_partitions,true),json_encode($data->net_info->if_addresses,true),$data->groupid,$data->updint));
      }
      pdodb::disconnect();
      echo json_encode(array("log"=>"Updated machine:".$data->hw_info->name),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
      clearSESS::template_end();
      exit;
    } else {
      echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"please use the API correctly."));
      exit;
    }
  }
  public static function readAllsrv($d1){
    if(isset($_POST['search'])){
      $pdo = pdodb::connect();
      $data=array();
      $sql="select serverdns,serverid,servertype,serverip from env_servers where serverdns like'%".htmlspecialchars($_POST['search'])."%' ".(!empty($d1)?" and servertype='".$d1."'":"");
      $q = $pdo->prepare($sql);
      $q->execute();
      if($zobj = $q->fetchAll()){  
        foreach($zobj as $val) {  
         $data[]=array("label"=>$val['serverdns']." (".$val['servertype'].")","nameid"=>$val['serverid'],"name"=>$val['serverdns'],"srvip"=>$val['serverip']);
       }
      }
      echo json_encode($data,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
      pdodb::disconnect();
      clearSESS::template_end();
      exit;
   }
  }
  public static function listAllapps($d1){
      $pdo = pdodb::connect();
      $data=array();
      $sql="select appcode,appinfo from config_app_codes";
      $q = $pdo->prepare($sql);
      $q->execute();
      if($zobj = $q->fetchAll()){  
        foreach($zobj as $val) {  
         $data[]=array("name"=>$val['appinfo'],"nameid"=>$val['appcode']);
       }
      }
      echo json_encode($data,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
      pdodb::disconnect();
      clearSESS::template_end();
      exit;
  }
  public static function readODCfg(){
    global $website;
    if($website['odappid']){
      echo json_encode(array("appid"=>$website['odappid'],"tenantid"=>$website['odtenid'],"endpoint"=>$website['odauthep'],"redirecturi"=>$_SERVER["REQUEST_SCHEME"]."://".$_SERVER["HTTP_HOST"]."/onedrive/auth"),true);
    } else {
      echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"OneDrive is not configured."));
    }
    exit;
  }
  public static function readDBCfg(){
    global $website;
    if($website['dbclid']){
      echo json_encode(array("clientid"=>$website['dbclid']),true);
    } else {
      echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"DropBox is not configured."));
    }
    exit;
  }
  public static function readGITCfg(){
    global $website;
    if($website['gitreposurl']){
      echo json_encode(array("type"=>$website['gittype'],"baseurl"=>$website['gitreposurl'],"projectid"=>$website['gitpjid'],"token"=>$website['gittoken']),true);
    } else {
      echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"GIT is not configured."));
    }
    exit;
  }
  public static function readAllpack($d1=null){
    $pdo = pdodb::connect();
    $data=array();
    $sql="select packuid,packname,srvtype from env_packages".(!empty($d1)?" where proj='".$d1."'":"");
    $q = $pdo->prepare($sql);
    $q->execute();
    if($zobj = $q->fetchAll()){  
      foreach($zobj as $val) {  
       $data[]=array("name"=>$val['packname'],"nameid"=>$val['packuid'],"srvid"=>$val['srvtype']);
     }
    }
    echo json_encode($data,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
    pdodb::disconnect();
    clearSESS::template_end();
    exit;
  }
}