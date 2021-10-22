<?php
$modulelist["projects"]["name"]="Projects api";
class Class_projectapi{
  public static function getPage($thisarray){
    global $website;
    global $maindir;
    session_start();
    $err = array();
    $msg = array();
    if(!empty($thisarray["p1"]) && !empty($_SESSION['user'])) {
    switch($thisarray["p1"]) {
      case 'groups': Class_projectapi::getGroups($thisarray["p2"]);  break;
      case 'projects': Class_projectapi::Projects($thisarray["p2"]);  break;
      case 'projtemplates': Class_projectapi::ProjTemplates($thisarray["p2"]);  break;
      case 'projtemplfinal': Class_projectapi::ProjTemplFinal($thisarray["p2"]);  break;
      case 'getallserv': Class_projectapi::ServiceList(); break;
      default: echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"please use the API correctly."));exit;
                    }
  } else { echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"please use the API correctly."));exit;  }
  }
  public static function getGroups($d1){
    if($d1=="readone"){
      header('Content-Type: application/json');
      $pdo = pdodb::connect();
      $data = json_decode(file_get_contents("php://input"));
      $sql="select ".(DBTYPE=='oracle'?"to_char(projusers) as projusers":"projusers")." from config_projects where projcode=?";
      $q = $pdo->prepare($sql);
      $q->execute(array(htmlspecialchars($data->pjid)));
      if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
        echo $zobj['projusers'];
      }
      pdodb::disconnect();
      exit;
    } elseif($d1=="delusr"){
      $pdo = pdodb::connect();
      $data = json_decode(file_get_contents("php://input"));
      $sql="select id, ".(DBTYPE=='oracle'?"to_char(projusers) as projusers":"projusers")." from config_projects where projcode=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($data->pjid));
	    if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
         if(!empty($zobj['projusers'])){ $tmp=json_decode($zobj['projusers'],true); } else { $tmp=array(); }
         if(!is_array($tmp)){ $tmp=array(); }
         unset($tmp[htmlspecialchars($data->userid)]);	
		     $sql="update config_projects set projusers=? where id=?";
		     $q = $pdo->prepare($sql);
         $q->execute(array(json_encode($tmp,true),$zobj["id"]));
         $sql="select id,pjid from ".(htmlspecialchars($data->utype)=="group"?"user_groups":"users")." where ".(htmlspecialchars($data->utype)=="group"?"group_latname":"mainuser")."=?";
          $q = $pdo->prepare($sql);
          $q->execute(array(htmlspecialchars($data->userid)));
          if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
            if(!empty($zobj['pjid'])){ $tmp=json_decode($zobj['pjid'],true); } else { $tmp=array(); }
            if(!is_array($tmp)){ $tmp=array(); }
            unset($tmp[$data->pjid]);	
            $sql="update ".(htmlspecialchars($data->utype)=="group"?"user_groups":"users")." set pjid=? where id=?";
            $q = $pdo->prepare($sql);
            $q->execute(array(json_encode($tmp,true),$zobj["id"]));
          }
       }
       gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>"system","projid"=>htmlspecialchars($data->pjid)), "Removed user <b>".htmlspecialchars($data->userid)."</b> from project <a href='/projects'>".$data->pjid."</a>");
       echo "User deleted successfully";
       pdodb::disconnect();
       exit;
      } elseif($d1=="addusr"){
        $pdo = pdodb::connect();
        $data = json_decode(file_get_contents("php://input"));
        $sql="select id, ".(DBTYPE=='oracle'?"to_char(projusers) as projusers":"projusers")." from config_projects where projcode=?";
        $q = $pdo->prepare($sql);
        $q->execute(array(htmlspecialchars($data->pjid)));
        if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
          if(!empty($zobj['projusers'])){ $tmp=json_decode($zobj['projusers'],true); } else { $tmp=array(); }
          if(!is_array($tmp)){ $tmp=array(); }
          $tmp[htmlspecialchars($data->uid)]=array("type"=>htmlspecialchars($data->utype),"uname"=>htmlspecialchars($data->uname),"uemail"=>htmlspecialchars($data->uemail),"uavatar"=>htmlspecialchars($data->avatar),"utitle"=>htmlspecialchars($data->utitle));
          $sql="update config_projects set projusers=? where id=?";
          $q = $pdo->prepare($sql);
          $q->execute(array(json_encode($tmp,true),$zobj["id"]));
          $sql="select id,pjid from ".(htmlspecialchars($data->utype)=="group"?"user_groups":"users")." where ".(htmlspecialchars($data->utype)=="group"?"group_latname":"mainuser")."=?";
          $q = $pdo->prepare($sql);
          $q->execute(array(htmlspecialchars($data->uid)));
          if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
            if(!empty($zobj['pjid'])){ $tmp=json_decode($zobj['pjid'],true); } else { $tmp=array(); }
            if(!is_array($tmp)){ $tmp=array(); }
            $tmp[$data->pjid]="1";
            $sql="update ".(htmlspecialchars($data->utype)=="group"?"user_groups":"users")." set pjid=? where id=?";
            $q = $pdo->prepare($sql);
            $q->execute(array(json_encode($tmp,true),$zobj["id"]));
          }
        }
        gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>"system","projid"=>htmlspecialchars($data->pjid)), "Added user <b>".htmlspecialchars($data->uname)."</b> from project <a href='/projects'>".$data->pjid."</a>");
        echo "User added successfully";
        pdodb::disconnect();
        exit;
      }

  }
  public static function Projects($d1){
    global $projcodes;
    if($d1=="delete"){
       $pdo = pdodb::connect();
       $data = json_decode(file_get_contents("php://input"));
       $sql="delete from config_projects where id=? and owner=?";
       $q = $pdo->prepare($sql);
       if($q->execute(array(htmlspecialchars($data->id),$_SESSION["user"]))){
        $sql="select id,pjid from users where pjid like ?";
        $q = $pdo->prepare($sql);
        $q->execute(array("%".$data->projcode."%"));
        if($zobj = $q->fetchAll()){
          foreach($zob as $val) { 
            if(!empty($val['pjid'])){ 
              $tmp=json_decode($zobj['pjid'],true);
              if(!is_array($tmp)){ $tmp=array(); }
              unset($tmp[$data->projcode]);
              $sql="update users set pjid=? where id=?";
              $q = $pdo->prepare($sql);
              $q->execute(array(json_encode($tmp,true),$zobj["id"]));
             }
          }
        }
        gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>"system"), "Deleted project:<a href='/projects'>".htmlspecialchars($data->projcode)."</a>");
      echo "Project was deleted";
     } else {
      echo "Error deleting project";
     }
     pdodb::disconnect();
   } else {
    $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input")); 
    if(!empty($_SESSION["userdata"]["pjarr"])){
      $sql="select id,projcode,projname,".(DBTYPE=='oracle'?"to_char(projinfo) as projinfo":"projinfo").",projstatus,".(DBTYPE=='oracle'?"to_char(projusers) as projusers":"projusers").",projduedate,projstartdate,owner from config_projects where (owner='".$_SESSION["user"]."' or projcode in (" . str_repeat('?,', count($_SESSION["userdata"]["pjarr"]) - 1) . '?' . ")) ".($data->type=="completed"?" and projstatus='4'":" and projstatus<>'4'");
      $stmt = $pdo->prepare($sql);
      $stmt->execute($_SESSION["userdata"]["pjarr"]);
    } else if($data->type=="own"){
      $sql="select id,projcode,projname,".(DBTYPE=='oracle'?"to_char(projinfo) as projinfo":"projinfo").",projstatus,projduedate,projstartdate,owner from config_projrequest where requser=? ".($data->type=="completed"?" and projstatus='4'":" and projstatus<>'4'");
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array($_SESSION["user"]));
    } else {
      $sql="select id,projcode,projname,".(DBTYPE=='oracle'?"to_char(projinfo) as projinfo":"projinfo").",projstatus,".(DBTYPE=='oracle'?"to_char(projusers) as projusers":"projusers").",projduedate,projstartdate,owner from config_projects where owner=? ".($data->type=="completed"?" and projstatus='4'":" and projstatus<>'4'");
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array($_SESSION["user"]));
    } 
    if($zobj = $stmt->fetchAll()){
      foreach($zobj as $val) {
      $d['projcode']=$val['projcode']; 
      $d['projname']=$val['projname'];
      $d['projinfo']=strip_tags(textClass::word_limiter($val['projinfo'],20,80));
      $d['owner']=$val['owner'];
      $d['id']=$val['id'];
      $d['projstatus']=$projcodes[$val['projstatus']]["name"];
      $d['projstatusicon']=$projcodes[$val['projstatus']]["badge"];
      $d['projusers']=!empty($val['projusers'])?json_decode($val['projusers'],true):array();
      $d['projduedate']=$val['projduedate'];
      $d['projstartdate']=$val['projstartdate'];
      $newdata[]=$d;
      }
      pdodb::disconnect();
      echo json_encode($newdata,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE); exit;
    } else {
      pdodb::disconnect();
      echo json_encode(array(),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE); exit;
      }
    }
  }
  public static function ProjTemplates($d1){
    global $stypes;
    $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
    if(!empty($data->type)){
      $sql="select id,appcode,templcode,templname,".(DBTYPE=='oracle'?"to_char(templinfo) as templinfo":"templinfo").",totalcost,".(DBTYPE=='oracle'?"to_char(servinfo) as servinfo":"servinfo").",serviceid, owner from config_projtempl where serviceid like ? ";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array("%".htmlspecialchars($data->type)."%"));
    } else {
      $sql="select id,appcode,templcode,templname,".(DBTYPE=='oracle'?"to_char(templinfo) as templinfo":"templinfo").",totalcost,".(DBTYPE=='oracle'?"to_char(servinfo) as servinfo":"servinfo").",serviceid, owner from config_projtempl where owner=? ";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array($_SESSION["user"]));
    }
    if($zobj = $stmt->fetchAll()){
      if(!empty($stypes)){ $stypes= json_decode($stypes,true);} else {$stypes=array();}
      foreach($zobj as $val) {
        $d['owner']=$val['owner'];
        $d['id']=$val['id'];
        $d['templcode']=$val['templcode']; 
        $d['appcode']=$val['appcode']; 
        $d['templname']=$val['templname'];
        $d['templinfo']=strip_tags(textClass::word_limiter($val['templinfo'],20,80));
        $d['totalcost']=$val['totalcost'];
        $d['serviceid']=array();
        if($val['serviceid']){
          foreach(json_decode($val['serviceid'],true) as $keyin=>$valin){
            if(!empty($valin)){ 
              $d['serviceid'][$valin]=$stypes[array_search($valin, array_column($stypes, 'nameshort'))]["name"];
            }
          };
        } 
        $newdata[]=$d;
      }
      pdodb::disconnect();
      echo json_encode($newdata,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE); exit;
    } else {
      pdodb::disconnect();
      echo json_encode(array(),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE); exit;
      }
  }
  public static function ProjTemplFinal($d1){
    global $stypes;
    $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
    if(!empty($data->templid)){
    $sql="select id,appcode,templcode,templname,".(DBTYPE=='oracle'?"to_char(templinfo) as templinfo":"templinfo").",totalcost,".(DBTYPE=='oracle'?"to_char(servinfo) as servinfo":"servinfo").",serviceid, formid, owner from config_projtempl where templcode=? ";
    $q = $pdo->prepare($sql);
    $q->execute(array($data->templid));
    if($val = $q->fetch(PDO::FETCH_ASSOC)){
      $d['id']=$val['id'];
      $d['templcode']=$val['templcode']; 
      $d['appcode']=$val['appcode']; 
      $d['templname']=$val['templname'];
      $d['templinfo']=$val['templinfo'];
      $d['formid']=!empty($val['formid'])?(count(json_decode($val['formid'],true))>0?true:false):false;
      $d['totalcost']=$val['totalcost'];
      $d['servinfo']=$val['servinfo'];
      $d['serviceid']=$val['serviceid'];
      $d['formids']=$val['formid'];
      $d['projinfo']=array();
      if($val['servinfo']){
        foreach(json_decode($val['servinfo'],true) as $keyin=>$valin){
          if(!empty($valin)){ 
            $d['projinfo'][]=$keyin;
          }
        };
      }
      $d['projinfo']=json_encode($d['projinfo'],true);
      $sql="select avatar,fullname,utitle,email,user_online,user_online_show from users where mainuser=?";
      $qin = $pdo->prepare($sql); 
      $qin->execute(array($val["owner"]));
      if($zobjin = $qin->fetch(PDO::FETCH_ASSOC)){
         $d['owner']["avatar"]=!empty($zobjin["avatar"])?$zobjin["avatar"] : '/assets/images/avatar.svg';
         $d['owner']["user"]=$val["owner"];
         $d['owner']["fullname"]=$zobjin["fullname"];
         $d['owner']["utitle"]=$zobjin["utitle"];
         $d['owner']["email"]=$zobjin["email"];
         $d['owner']["user_online"]=$zobjin["user_online_show"]==0?"secondary":($zobjin["user_online"]==1?"success":"danger");
      }
      pdodb::disconnect();
      echo json_encode($d,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE); exit;
    } else {
      pdodb::disconnect();
      echo json_encode(array(),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE); exit;
      }
    }
  }
  public static function ServiceList(){
    if(isset($_POST['search'])){
      $pdo = pdodb::connect();
      $data=array();
      $sql="select wid,wname,wtype,wfcost,wfcurcost,formid from config_workflows where wname like '%".htmlspecialchars($_POST['search'])."%' or winfo like '%".htmlspecialchars($_POST['search'])."%' ";
      $q = $pdo->prepare($sql);
      $q->execute(); 
      if($zobj = $q->fetchAll()){  
        foreach($zobj as $val) {  
         $data[]=array("name"=>$val['wname'],"nameid"=>$val['wid'],"type"=>$val['wtype'],"formid"=>$val['formid'],"cost"=>$val['wfcost'],"curcost"=>$val['wfcurcost']);
       }
      }
      echo json_encode($data,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
      pdodb::disconnect();
      clearSESS::template_end();
      exit;
   }
  }
}