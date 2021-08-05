<?php
class Class_autoapi{
  public static function getPage($thisarray){
    global $website;
    global $maindir; 
    global $typesrv;
    session_start();
    $err = array();
    $msg = array();
    header('Content-type:application/json;charset=utf-8'); 
    if(!empty($thisarray["p1"]) && !empty($_SESSION['user'])) {
    switch($thisarray["p1"]) {
      case 'addmqinv':  Class_autoapi::addMQINV();  break;
      case 'delmqinv':  Class_autoapi::delMQINV();  break;
      case 'readmqinv':  Class_autoapi::readMQINV();  break;
      case 'readcicd':  Class_autoapi::readCICD($thisarray["p2"]);  break;
      case 'addjob':  Class_autoapi::addJob($thisarray["p2"]);  break;
      default: echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"please use the API correctly."));exit;
                    }
  } else { echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"please use the API correctly."));exit;  }
  }
public static function readCICD($d1){
  global $jobstatus;
  global $typesrv;
    $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
    if(!empty($_SESSION["userdata"]["apparr"])){
    if($data->type=="disabled"){ $enabled=" and jenabled='0'";}  else { $enabled=" and jenabled='1'"; }
    $sql="select jobname,id,jobid,objname,jobtype,proj,env,deplenv,srv,jobstatus,lrun,nrun,created from env_jobs where proj in (" . str_repeat('?,', count($_SESSION["userdata"]["apparr"]) - 1) . '?' . ") ".$enabled;
    $stmt = $pdo->prepare($sql);
    if($stmt->execute($_SESSION["userdata"]["apparr"])){
      $zobj = $stmt->fetchAll();
      $data=array();
      foreach($zobj as $val) {
        $data['jobname']=$val['jobname'];
        $data['id']=$val['id'];
        $data['jobid']=$val['jobid'];
        $data['objname']=$val['objname'];
        $data['jobtype']=$val['jobtype'];
        $data['proj']=$val['proj'];
        $data['env']=$val['env'];
        $data['deplenv']=$typesrv[$val['deplenv']];
        $data['srv']=$val['srv'];
        $data['statusinfo']=$jobstatus[$val['jobstatus']]["statcolor"];
        $data['statusinfotxt']=$jobstatus[$val['jobstatus']]["name"];
        $data['lrun']=date("H:i d.m.y",strtotime($val['lrun']));
        $data['nrun']=date("H:i d.m.y",strtotime($val['nrun']));
        $data['created']=date("H:i d.m.y",strtotime($val['created']));
        $newdata[]=$data;
      }
    }
  }
    pdodb::disconnect();
    echo json_encode($newdata,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
    exit;
  }
public static function addJob($d1){
  $pdo = pdodb::connect();
  $data = json_decode(file_get_contents("php://input")); 
  if($data->deplinfo->selectedobj && is_array($data->deplinfo->selectedobj)){
  $arrayvars=json_decode("[{}]",true);
    $sql="select varname,varvalue,isarray from mqenv_vars where proj=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($data->appcode));
    if($zobjin = $q->fetchAll()){
     foreach($zobjin as $val) {
      if($val['isarray']==1){
        $arrayvars["{".$val['varname']."}"]=array("isarray"=>$val['isarray'],"val"=>json_decode($val['varvalue'],true));
      } else {
        $arrayvars["{".$val['varname']."}"]=array("isarray"=>$val['isarray'],"val"=>$val['varvalue']);
      }
     }
    }
  foreach($data->deplinfo->selectedobj as $key=>$val){ 
    if($data->objplace=="mqenv_mqfte" || $data->objplace=="tibco_obj"){
      $sql= "SELECT * FROM ".$data->objplace." where id=?";
    } else {
      $sql= "SELECT qmgr,objname,objtype FROM ".$data->objplace." where id=?"; 
    } 
    $q = $pdo->prepare($sql); 
    $q->execute(array($val));  
    if($zobj = $q->fetch(PDO::FETCH_ASSOC)){ 
      if($data->objplace=="mqenv_mqfte"){
        $temparr["srv"]=textClass::stage_array($zobj['sourceagt'],$arrayvars,$data->deplinfo->deplenv);
        $temparr["jobname"]=textClass::stage_array($zobj['mqftename'],$arrayvars,$data->deplinfo->deplenv);
        $connstr="";
      } else if($data->objplace=="tibco_obj"){ 
        $connstr=$data->deplinfo->srv;
        $srv=explode("#", $data->deplinfo->srv);
        $temparr["srv"]=textClass::stage_array($zobj["srv"],$arrayvars,$data->deplinfo->deplenv);
        $temparr["jobname"]=textClass::stage_array($zobj["objname"],$arrayvars,$data->deplinfo->deplenv);
      } else {
        $connstr=$data->deplinfo->qm;
        $srv=explode("#", $data->deplinfo->qm);
        $temparr["objsrv"]=$zobj["qmgr"];
        $sql="select serverdns,qmname from env_appservers where qmname=? and serv_type='qm'";
        $q = $pdo->prepare($sql);
        $q->execute(array($zobj["qmgr"]));
        if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
          $temparr["srv"]=textClass::stage_array($zobj["serverdns"],$arrayvars,$data->deplinfo->deplenv);
          $temparr["jobname"]=textClass::stage_array($zobj["qmname"],$arrayvars,$data->deplinfo->deplenv);
        }
      }
      if(!empty($temparr["srv"])){
       $sql="select id,jobid from env_jobs where proj=? and srv=? and env=? and objtype=? and objid=?";
       $stmt = $pdo->prepare($sql);
       $stmt->execute(array($data->appcode,$temparr["srv"],$data->deplinfo->deplenv,$data->objplace,$val));
       if($zobj = $stmt->fetch(PDO::FETCH_ASSOC)){
          $sql="update env_jobs set nrun=?, reqid=? where id=?";
          $stmt = $pdo->prepare($sql);
          $stmt->execute(array($data->deplinfo->nextrun.":00",$data->deplinfo->reqid,$zobj["id"]));
          $sql="update ".$data->objplace." set jobrun='1', jobid=? where id=?";
          $q = $pdo->prepare($sql);
          $q->execute(array($zobj["jobid"],$val)); 
       } else {
        $hash = textClass::getRandomStr();
        $sql="insert into env_jobs(jobid,proj,reqid,srv,env,jobname,deplenv,runby,jobstatus,objtype,objid,objname,nrun,connstr) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $pdo->prepare($sql); 
        $stmt->execute(array($hash,$data->appcode,$data->deplinfo->reqid,$temparr["srv"],$data->deplinfo->deplenv,$temparr["jobname"]."@".$data->deplinfo->deplenv,$data->objenv,$_SESSION["user"],"0",($data->objplace=="tibco_obj"?$data->what:$data->objplace),$val,$temparr["jobname"],$data->deplinfo->nextrun.":00",$connstr));
        $sql="update ".$data->objplace." set jobrun='1', jobid=? where id=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($hash,$val)); 
      }
     }

    }
  }
  /*
  if(!empty($data->objtype)){
    $sql="select count(id) from env_jobs where proj=? and srv=? and objtype=? and objid=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($data->appcode,$data->srv,$data->objtype,$data->objid));
    if($stmt->fetchColumn()>0){

    } else {
      $sql="insert into env_jobs(proj,srv,jobname,deplenv,runby,jobstatus,objtype,objid,objname) values(?,?,?,?,?,?,?,?,?,?)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array($data->appcode,$data->srv,$data->info,$data->env,$data->sessuser,"0",$data->objtype,$data->objid,$data->objname));
    }
  } */
} else {
  
}
  pdodb::disconnect();
  echo json_encode(array("done"),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
  exit;
}

public static function addMQINV(){
  $pdo = pdodb::connect();
  $data = json_decode(file_get_contents("php://input")); 
  $srv=explode("#", $data->deplinfo->qm);
  $arrayvars=array();
  $sql="select varname,varvalue,isarray from mqenv_vars where proj=?";
  $q = $pdo->prepare($sql); 
  $q->execute(array($srv[8])); 
  if($zobj = $q->fetchAll()){
   foreach($zobj as $val) {
   if($val['isarray']==1){
     $arrayvars["{".$val['varname']."}"]=array("isarray"=>$val['isarray'],"val"=>json_decode($val['varvalue'],true));
     } else {
     $arrayvars["{".$val['varname']."}"]=array("isarray"=>$val['isarray'],"val"=>$val['varvalue']);
     }
    }
  }
  $connd='{"type":"READ","function":"MQALL","hostname":"'.textClass::stage_array($srv[0],$arrayvars,$data->deplinfo->deplenv).'","qmanager":"'.textClass::stage_array($srv[2],$arrayvars,$data->deplinfo->deplenv).'","port":"'.textClass::stage_array($srv[1],$arrayvars,$data->deplinfo->deplenv).'","channel":"'.textClass::stage_array($srv[3],$arrayvars,$data->deplinfo->deplenv).'","sslcipher":"'.$srv[7].'","sslkey":"'.$srv[5].'","sslpass":"'.$srv[6].'","ssl":"'.($srv[4]=="1"?"yes":"no").'"}';
  $sql="select count(id) from env_jobs_mq where qmgr=? and env=?";
  $q = $pdo->prepare($sql);
  $q->execute(array(textClass::stage_array($srv[2],$arrayvars,$data->deplinfo->deplenv),$data->deplinfo->deplenv));
   if($q->fetchColumn()>0){
     $sql="update env_jobs_mq set nrun=?, jobrepeat=? where qmgr=? and env=?";
     $q = $pdo->prepare($sql);
     $q->execute(array($data->deplinfo->nextrun,$data->deplinfo->repeat,textClass::stage_array($srv[2],$arrayvars,$data->deplinfo->deplenv),$data->deplinfo->deplenv));
   } else {
    $hash = textClass::getRandomStr();
    $proj=$srv[8];
    $sql="insert into env_jobs_mq (jobid,proj,srv,env,qmgr,nrun,owner,jobrepeat,connstr) values (?,?,?,?,?,?,?,?,?)";
    $q = $pdo->prepare($sql);
    $q->execute(array($hash,$proj,textClass::stage_array($srv[0],$arrayvars,$data->deplinfo->deplenv),$data->deplinfo->deplenv,textClass::stage_array($srv[2],$arrayvars,$data->deplinfo->deplenv),$data->deplinfo->nextrun,$_SESSION["user"],$data->deplinfo->repeat,$connd));
   }
   gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>$proj), "Created MQ inventory for Qmanager <a href='/automation//ibmmq'>".textClass::stage_array($srv[2],$arrayvars,$data->deplinfo->deplenv)."</a>");
  pdodb::disconnect();
  echo json_encode(array("done"),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
  exit;
}
public static function readMQINV(){
  global $jobstatus;
  global $typejob;
  $pdo = pdodb::connect();
  $data = json_decode(file_get_contents("php://input")); 
  if(!empty($_SESSION["userdata"]["apparr"])){
  $sql="select id,jobid,qmgr,proj,srv,jobstatus,lrun,nrun,jobrepeat from env_jobs_mq where proj in (" . str_repeat('?,', count($_SESSION["userdata"]["apparr"]) - 1) . '?' . ")";
  $stmt = $pdo->prepare($sql); 
  if($stmt->execute($_SESSION["userdata"]["apparr"])){
    $zobj = $stmt->fetchAll();
    $data=array();
    foreach($zobj as $val) {
      $data['jobid']=$val['jobid'];
      $data['id']=$val['id'];
      $data['qmgr']=$val['qmgr'];
      $data['proj']=$val['proj'];
      $data['srv']=$val['srv'];
      $data['repeat']=$typejob[$val['jobrepeat']];
      $data['statusinfo']=$jobstatus[$val['jobstatus']]["statcolor"];
      $data['statusinfotxt']=$jobstatus[$val['jobstatus']]["name"];
      $data['lrun']=date("H:i d.m.y",strtotime($val['lrun']));
      $data['nrun']=date("H:i d.m.y",strtotime($val['nrun']));
      $newdata[]=$data;
    }
  }
  } else {
    $newdata=array();
  }
  pdodb::disconnect();
  echo json_encode($newdata,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
  exit;
}
public static function delMQINV(){
  $pdo = pdodb::connect();
  $data = json_decode(file_get_contents("php://input")); 
  $sql="delete from env_jobs_mq where proj=? and id=?";
  $q = $pdo->prepare($sql); 
  $q->execute(array($data->appid,$data->invid));
  gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>htmlspecialchars($data->appid)), "Deleted MQ inventory for Qmanager <a href='/automation//ibmmq'>".htmlspecialchars($data->qmgr)."</a>");
  pdodb::disconnect();
  echo json_encode(array("done"),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
  exit;
}
  
}