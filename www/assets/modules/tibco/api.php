<?php
class Class_tibapi{
  public static function getPage($thisarray){
    global $website;
    global $maindir;
    global $env;
    session_start();
    if(!empty($thisarray["p1"]) && !empty($_SESSION['user'])) {
        header('Content-type:application/json;charset=utf-8'); 
    switch($thisarray["p1"]) {
        case 'readtibobj':  Class_tibapi::readObj($thisarray["p2"],$thisarray["p3"]);  break;
        case 'add':  Class_tibapi::add($thisarray["p2"]);  break;
        case 'update':  Class_tibapi::update($thisarray["p2"]);  break;
        case 'dell':  Class_tibapi::dell($thisarray["p2"]);  break;
        case 'acl':  Class_tibapi::createACL();  break;
        case 'readtibacl':  Class_tibapi::readtibACL($thisarray["p2"]);  break;
        case 'updatetibacl':  Class_tibapi::updatetibACL();  break;
        case 'createtibacl':  Class_tibapi::createtibACL();  break;
        case 'deployselected': Class_tibapi::deploySelected();  break;
        case 'deltibacl': Class_tibapi::deltibACL();  break;
        default: echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"please use the API correctly."));exit;
                    }
   } else { echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"please use the API correctly."));exit;  }
  }
 public static function readObj($d1,$d2){
   if($d2=="one"){
    $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
    $sql= "SELECT id,srv,proj,objname,objtype,jobrun,jobid,".(DBTYPE=='oracle'?"to_char(objdata) as objdata":"objdata")." FROM tibco_obj where id=? and objtype=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($data->objid,$d1));
    if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      $objects=json_decode($row['objdata'],true);
      $objects['name']=$row['objname'];
      $objects['appsrv']=$row['srv'];
      $objects['id']=$row['id'];
      $objects['proj']=$row['proj'];
      $objects['srv']=$row['srv'];
      $objects['jobrun']=$row['jobrun'];
      $objects['jobid']=$row['jobid'];
    }
    pdodb::disconnect();
    echo json_encode($objects,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
   } else {
    $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
    $sql= "SELECT id,srv,proj,objname,objtype,jobrun,jobid,".(DBTYPE=='oracle'?"to_char(objdata) as objdata":"objdata")." FROM tibco_obj where objtype=?".(!empty($data->projid)?" and proj='".$data->projid."'":"");
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($d1));
    $zobj = $stmt->fetchAll();
    $data=array();
    foreach($zobj as $val) { 
      $data['objects'][$val['srv']][$val['objname']]=json_decode($val['objdata'],true);
      $data['objects'][$val['srv']][$val['objname']]['name']=$val['objname'];
      $data['objects'][$val['srv']][$val['objname']]['srv']=$val['srv'];
      $data['objects'][$val['srv']][$val['objname']]['id']=$val['id'];
      $data['objects'][$val['srv']][$val['objname']]['type']=$val['objtype'];
      $data['objects'][$val['srv']][$val['objname']]['proj']=$val['proj'];
      $data['objects'][$val['srv']][$val['objname']]['appsrv']=$val['srv'];
      $data['objects'][$val['srv']][$val['objname']]['jobrun']=$val['jobrun'];
      $data['objects'][$val['srv']][$val['objname']]['jobid']=$val['jobid'];
      $newdata[]=$data['objects'][$val['srv']][$val['objname']];
      $keys[]=array_keys($data['objects'][$val['srv']][$val['objname']]);
    }
    if(is_array($keys)){
     foreach(textClass::getL2Keys($keys) as $key){
     $d[$key]=""; 
     }
    }   
    pdodb::disconnect();
    if(!empty($d)){
      echo "[".json_encode($d,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE).",".ltrim(json_encode($newdata,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE),"[");
    } else {
      if(is_array($newdata)){
      echo "[".ltrim(json_encode($newdata,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE),"[");  
      } else {
        echo "[]";
      }
    }
  
   }
 }
 public static function add($d1){
  header("Content-Type: text/html; charset=UTF-8");
  $pdo = pdodb::connect();
  $data = json_decode(file_get_contents("php://input"));
  foreach($data->tibq as $key=>$val){
    $newobjects[$key]=$val;
  }
   $sql="insert into tibco_obj (proj,srv,objname,objtype,objdata,projinfo) values(?,?,?,?,?,'system')";
    $q = $pdo->prepare($sql);
    $q->execute(array(htmlspecialchars($data->projid),htmlspecialchars($data->tibq->srv),htmlspecialchars($data->tibq->name),htmlspecialchars($d1),json_encode($newobjects,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE)));
    gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>htmlspecialchars($data->projid)), "Created new Tibco Queue:<a href='/env/tibqueues/".htmlspecialchars($data->projid)."'>".htmlspecialchars($data->tibq->name)."</a>");
    if(!empty(htmlspecialchars($data->tibq->tags))){
      gTable::dbsearch(htmlspecialchars(!empty($data->tibq->name)?$data->tibq->name:$data->tibq->srv),$_SERVER["HTTP_REFERER"],htmlspecialchars($data->tibq->tags));
    }
  echo $d1." was created.";
  pdodb::disconnect();
 }
 public static function update($d1){
  header("Content-Type: text/html; charset=UTF-8");
  $pdo = pdodb::connect();
  $nowtime = new DateTime();
  $now=$nowtime->format('Y-m-d H:i').":00";
  $data = json_decode(file_get_contents("php://input"));
  $newobjects=array();
  foreach($data->tibq as $key=>$val){
    if(!empty($val)){ 
      $newobjects[$key]=$val;
    }
 //   if(empty($val)){unset($newobjects[$key]);}
  }
    unset($newobjects['id']);
    unset($newobjects['srv']);
    $sql= "update tibco_obj set srv=?,objname=?,objdata=?,proj=?,changed='".$now."' where id=?";
    $stmt = $pdo->prepare($sql);
    if($stmt->execute(array($data->tibq->srv,$data->tibq->name,json_encode($newobjects,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE),$data->projid,$data->tibq->id))){
      gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>htmlspecialchars($data->projid)), "Updated Tibco ".$d1.":<a href='/env/tibqueues/".htmlspecialchars($data->projid)."'>".htmlspecialchars($data->tibq->name)."</a>");
      if(!empty(htmlspecialchars($data->tibq->tags))){
        gTable::dbsearch(htmlspecialchars(!empty($data->tibq->name)?$data->tibq->name:$data->tibq->srv),$_SERVER["HTTP_REFERER"],htmlspecialchars($data->tibq->tags));
      }
      echo "Object was changed.";
    } else {
      echo "Unable to update object.";
    }
  pdodb::disconnect();
  }
  public static function dell($d1){
    header("Content-Type: text/html; charset=UTF-8");
    $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));    
      $sql="delete from tibco_obj where id=?";
      $stmt = $pdo->prepare($sql);
      if($stmt->execute(array($data->id))){
        gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>htmlspecialchars($data->projid)), "Deleted Tibco ".$d1.":<a href='/env/tibqueues/".htmlspecialchars($data->projid)."'>".htmlspecialchars($data->tibqn)."</a>");
        echo "Object was deleted.";
      } else {
        echo "Unable to delete object.";
      }
    pdodb::disconnect();
  }
  public static function createACL(){
    global $env;
    $pdo = pdodb::connect();
    $now=date('Y-m-d H:i:s');
    $data = json_decode(file_get_contents("php://input"));
      header("Access-Control-Allow-Origin: *");
      header("Content-Type: text/html; charset=UTF-8");
      $sql= "SELECT objname,objtype,aclname,acltype,perm FROM tibco_acl where proj=? and id=?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array($data->projid,htmlspecialchars($data->tibacl->id)));
      if($zobj = $stmt->fetchAll()){
      $arrayvars=json_decode("[{}]",true);
      $sql="select varname,varvalue,isarray from mqenv_vars where proj=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($data->projid)); 
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
        $str="";
        $str.="# This file lists all permissions on topics and/or \n";
        $str.="# queues for all users and/or group \n";
        $str.="# Project:".$data->projid." \n";
        $str.="# Released by:".$data->user." \n";
        $str.="# Released on:".$now." \n\n";
        foreach($zobj as $val) {
           $perm="";
           foreach(json_decode($val['perm'],true) as $thisval){ $perm.=$thisval.","; }
           $perm = rtrim($perm, ",");
           $str.=strtoupper($val['objtype'])."=".$val['objname']." ".strtoupper($val['acltype'])."=".$val['aclname']." PERM=".$perm." \n";
        }
        //echo $str;
         if(is_array($menudataenv)){ foreach($menudataenv as $keyenv=>$valenv){
            $strarr[$valenv['nameshort']]=textClass::stage_array($str,$arrayvars,$valenv['nameshort']);
          }
            echo json_encode(array('success'=>true,'type'=>"success",'resp'=>$strarr),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
          } else {
           $str=textClass::stage_array($str,$arrayvars,"");
            echo json_encode(array('success'=>true,'type'=>"success",'resp'=>$str),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
          } 
      } else {
        echo json_encode(array('success'=>false,'type'=>"error",'resp'=>"No such data" ),JSON_UNESCAPED_UNICODE);
      }

    pdodb::disconnect();
  }
  public static function deploySelected($inpdata=null){
    if(!$inpdata){
      $data = json_decode(file_get_contents("php://input"));
    } else {
      $data = json_decode($inpdata);
    }
    $err = ""; 
    if(!empty($data->srv)){
      $pdo = pdodb::connect();  
      $srv=explode("#", $data->srv);
      $connd='{"type":"'.strtoupper($data->type).'","tibcosrv":"'.$srv[0].'","tibcoport":"'.$srv[1].'","tibcousr":"'.$srv[2].'","tibcopass":"'.documentClass::decryptIt($srv[3]).'","sslcipher":"'.$srv[7].'","sslkey":"'.$srv[5].'","sslpass":"'.$srv[6].'","ssl":"'.($srv[4]=="1"?"yes":"no").'"}';
      $str=""; $i=0;
      $allobj=array();
      foreach($data->objects as $key=>$val){
        $sql= "SELECT objname,objtype,".(DBTYPE=='oracle'?"to_char(objdata) as objdata":"objdata")." FROM tibco_obj where proj=? and id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($data->appl,$val));
        if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
          $objects=json_decode($row['objdata'],true); 
          unset($objects['proj']);
          unset($objects['active']);
          unset($objects['srv']);
          unset($objects['tags']);
          $allobj[]=$objects;
        $i++;
        }
      }
      $str=json_encode(array("data"=>$allobj),true);
      $output=tibco::execJava($connd,$str); 
      if(!empty($output)){
        if($data->job){ return $output[0];} else { echo $output[0]; }
      } else {
        if($data->job){
          return json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"Error occured: No output, please check if you have installed java and configured everything correctly.<br>Read carefully section => Important information for deployment process"));
        } else {
          echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"Error occured: No output, please check if you have installed java and configured everything correctly.<br>Read carefully section => Important information for deployment process"));
        }
      }
      $depltype=$i>0?1:0;
      $sql="insert into env_deployments(proj,packuid,deplobjects,deplby,depltype,deplenv) values(?,?,?,?,?,?)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(htmlspecialchars($data->appl),htmlspecialchars($srv[0]),$i,$_SESSION['user'],$depltype,"TIBCO EMS"));
      gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>htmlspecialchars($data->appl)), "Installed ".$i." tibco objects on:".htmlspecialchars($srv[0]));
      pdodb::disconnect();
    } else {
      if($data->job){
        return json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"Please specify Tibco EMS Server"));
      } else {
        echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"Please specify Tibco EMS Server"));
      }
    }
    exit;
  }
  public static function readtibACL($d1){
    if($d1=="one"){
      $pdo = pdodb::connect();
      $data = json_decode(file_get_contents("php://input"));
        $sql="select t.id,t.srv,t.objtype,t.objname,t.acltype,t.aclname,t.perm,t.changed,".(DBTYPE=='oracle'?"to_char(t.projinfo) as info":"t.projinfo")." from tibco_acl t where t.id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(htmlspecialchars($data->id)));
        $data=array();
       if($zobj = $stmt->fetch(PDO::FETCH_ASSOC)){
        if($zobj['perm']){
          $perm=json_decode($zobj['perm'],true);
          foreach($perm as $key=>$val){ $perm[$val]=$val; } 
          $d['perm']=$perm;
          $d['selectedtibaclm']=!empty($d['perm'])?json_decode($zobj['perm'],true):array();
         }
         $d["id"]=$zobj["id"];
         $d["srv"]=$zobj["srv"];
         $d["objtype"]=$zobj["objtype"];
         $d["objname"]=$zobj["objname"];
         $d["acltype"]=$zobj["acltype"];
         $d["aclname"]=$zobj["aclname"];
         $d["changed"]=$zobj["changed"];
         $d["projinfo"]=$zobj["projinfo"];
        echo json_encode($d,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
      }
      pdodb::disconnect();
      exit;
    } else {
      $pdo = pdodb::connect();
      $data = json_decode(file_get_contents("php://input"));
        $sql="select t.id,t.srv,t.objtype,t.objname,t.acltype,t.aclname,t.perm,t.changed from tibco_acl t";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
      $data=array();
      $data = $stmt->fetchAll(PDO::FETCH_CLASS);
      pdodb::disconnect();
      echo json_encode($data,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
      exit;
    }
  }
  public static function updatetibACL(){
    header("Content-Type: text/html; charset=UTF-8");
    $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
    $now=date('Y-m-d H:i:s');
    $sql="update tibco_acl set srv=?, objname=?, objtype=?, aclname=?, acltype=?, perm=?, changed=? where id=?";
    $q = $pdo->prepare($sql);
    if($q->execute(array(htmlspecialchars($data->tibacl->srv),htmlspecialchars($data->tibacl->objname),htmlspecialchars($data->tibacl->objtype),htmlspecialchars($data->tibacl->aclname),htmlspecialchars($data->tibacl->acltype),$data->perm,$now,htmlspecialchars($data->tibacl->id)))){ 
      echo "ACL ".htmlspecialchars($data->tibacl->objname)." updated";
      gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>htmlspecialchars($data->thisapp)), "Updated tibco ACL with name:<a href='/env/tibacl/".htmlspecialchars($data->thisapp)."'>".htmlspecialchars($data->tibacl->objname)."</a>");
    } else { 
      echo "ACL cannot be updated";
    }
    pdodb::disconnect();
    exit;
  }
  public static function createtibACL(){
    header("Content-Type: text/html; charset=UTF-8");
    $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
    $sql="insert into tibco_acl (proj,srv,objname,objtype,aclname,acltype,perm,projinfo) values(?,?,?,?,?,?,?,?)";
    $q = $pdo->prepare($sql);
    if($q->execute(array(htmlspecialchars($data->thisapp),htmlspecialchars($data->tibacl->srv),htmlspecialchars($data->tibacl->objname),htmlspecialchars($data->tibacl->objtype),htmlspecialchars($data->tibacl->aclname),htmlspecialchars($data->tibacl->acltype),$data->perm,htmlspecialchars($data->tibacl->info)))){ 
      echo "ACL ".htmlspecialchars($data->tibacl->objname)." created";
      gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>htmlspecialchars($data->thisapp)), "Defined Tibco ACL with name:<a href='/env/tibacl/".htmlspecialchars($data->thisapp)."'>".htmlspecialchars($data->tibacl->objname)."</a>");
    } else { 
      echo "ACL cannot be created";
    }
    pdodb::disconnect();
    exit;
  }
  public static function deltibACL(){
    header("Content-Type: text/html; charset=UTF-8");
    $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
      $sql="delete from tibco_acl where id=?";
      $q = $pdo->prepare($sql);
      if($q->execute(array(htmlspecialchars($data->id)))){
        gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>htmlspecialchars($data->projid)), "Deleted Tibco ACL with name:<a href='/env/tibacl/".htmlspecialchars($data->projid)."'>".htmlspecialchars($data->objname)."</a>");
        echo "ACL configuration was deleted";
      } else {
        echo "Error deleting ACL configuration";
      }
    pdodb::disconnect();
    exit;
  }
}