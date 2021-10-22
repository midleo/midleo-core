<?php
$modulelist["smanagement"]["name"]="Service Workflow designer";
include_once "smanagement.php";
class Class_reqworkflow{
  public static function getPage($thisarray){
    global $website;
    global $maindir;
    session_start();
    $err = array();
    $msg = array();
    if(!empty($thisarray["p1"]) && !empty($_SESSION['user'])) {
    switch($thisarray["p1"]) {
      case 'read': Class_reqworkflow::readWfl();  break;
      case 'save': Class_reqworkflow::saveWfl();  break;
      case 'delete': Class_reqworkflow::delWfl();  break;
      case 'servicelist': Class_reqworkflow::serviceList();  break;
      case 'groups': Class_reqworkflow::getGroups($thisarray["p2"]);  break;
      default: echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"please use the API correctly."));exit;
                    }
  } else { echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"please use the API correctly."));exit;  }
  }
  public static function saveWfl(){
    $pdo = pdodb::connect();
    if(!empty($_POST["data"])){
      $nowtime = new DateTime();
      $now=$nowtime->format('Y-m-d H:i').":00";
        $sql="update config_workflows set wdata=?, winfo=?, wname=?, wtype=?, wfcost=?,wfcurcost=?, haveconf=?, formid=?, haveappr=?, wuser_updated=?, modified='".$now."' where wid=?"; 
        $stmt = $pdo->prepare($sql); 
        $stmt->execute(array($_POST['data'],htmlspecialchars($_POST['winfo']),htmlspecialchars($_POST['wname']),htmlspecialchars($_POST['wtype']),htmlspecialchars($_POST['wfcost']),htmlspecialchars($_POST['wfcurcost']),htmlspecialchars($_POST['haveconf']),htmlspecialchars($_POST['formid']),htmlspecialchars($_POST['haveappr']),$_SESSION["user"],$_POST['wid']));
        echo "Workflow updated successfully";
    } else {
        echo "empty data";
    }
    pdodb::disconnect();
    exit;
  }
  public static function delWfl(){
    $pdo = pdodb::connect();
    if(!empty($_POST["wid"])){
        $sql="delete from config_workflows where wid=?"; 
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($_POST['wid']));
        echo "Workflow deleted successfully";
    } else {
        echo "empty wid";
    }
    pdodb::disconnect();
    exit;
  }
  public static function readWfl(){
    header('Content-Type: application/json');
    $pdo = pdodb::connect();
    if(!empty($_POST["wid"])){
      $sql="select ".(DBTYPE=='oracle'?"to_char(wdata) as wdata":"wdata")." ,".(DBTYPE=='oracle'?"to_char(wgroups) as wgroups":"wgroups")."  from config_workflows where wid=?";
      $q = $pdo->prepare($sql); 
      if($q->execute(array(htmlspecialchars($_POST["wid"])))){
        $zobj = $q->fetch(PDO::FETCH_ASSOC);
      //  echo $zobj['wdata'];
        echo json_encode(array_merge((!empty($zobj['wdata'])?json_decode($zobj['wdata'],true):array()),array("ulist"=>(!empty($zobj['wgroups'])?json_decode($zobj['wgroups'],true):array()))),true);
      } else {
        echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"No such ID"));
      }
    } else {
        echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"Empty ID"));
    }
    pdodb::disconnect();
    exit;
  }
  public static function getGroups($d1){
    if($d1=="readone"){
      header('Content-Type: application/json');
      $pdo = pdodb::connect();
      $data = json_decode(file_get_contents("php://input"));
      $sql="select ".(DBTYPE=='oracle'?"to_char(wgroups) as wgroups":"wgroups")." from config_workflows where wid=?";
      $q = $pdo->prepare($sql);
      $q->execute(array(htmlspecialchars($data->wid)));
      if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
        echo $zobj['wgroups'];
      }
      pdodb::disconnect();
      exit;
    } elseif($d1=="delusr"){
      $pdo = pdodb::connect();
      $data = json_decode(file_get_contents("php://input"));
      $sql="select id, ".(DBTYPE=='oracle'?"to_char(wgroups) as wgroups":"wgroups")." from config_workflows where wid=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($data->wid));
	    if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
         if(!empty($zobj['wgroups'])){ $tmp=json_decode($zobj['wgroups'],true); } else { $tmp=array(); }
         if(!is_array($tmp)){ $tmp=array(); }
         unset($tmp[htmlspecialchars($data->userid)]);	
		     $sql="update config_workflows set wgroups=? where id=?";
		     $q = $pdo->prepare($sql);
         $q->execute(array(json_encode($tmp,true),$zobj["id"]));
         $sql="select id,wid from ".(htmlspecialchars($data->utype)=="group"?"user_groups":"users")." where ".(htmlspecialchars($data->utype)=="group"?"group_latname":"mainuser")."=?";
          $q = $pdo->prepare($sql);
          $q->execute(array(htmlspecialchars($data->userid)));
          if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
            if(!empty($zobj['wid'])){ $tmp=json_decode($zobj['wid'],true); } else { $tmp=array(); }
            if(!is_array($tmp)){ $tmp=array(); }
            unset($tmp[$data->wid]);	
            $sql="update ".(htmlspecialchars($data->utype)=="group"?"user_groups":"users")." set wid=? where id=?";
            $q = $pdo->prepare($sql);
            $q->execute(array(json_encode($tmp,true),$zobj["id"]));
          }
       }
       gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>"system","wfid"=>$data->wid), "Removed user <b>".htmlspecialchars($data->userid)."</b> from service <a href='/smanagement/".$data->wid."'>".$data->wid."</a>");
       echo "User deleted successfully";
       pdodb::disconnect();
       exit;
      } elseif($d1=="addusr"){
        $pdo = pdodb::connect();
        $data = json_decode(file_get_contents("php://input"));
        $sql="select id, ".(DBTYPE=='oracle'?"to_char(wgroups) as wgroups":"wgroups")." from config_workflows where wid=?";
        $q = $pdo->prepare($sql);
        $q->execute(array(htmlspecialchars($data->wid)));
        if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
          if(!empty($zobj['wgroups'])){ $tmp=json_decode($zobj['wgroups'],true); } else { $tmp=array(); }
          if(!is_array($tmp)){ $tmp=array(); }
          $tmp[htmlspecialchars($data->uid)]=array("type"=>htmlspecialchars($data->utype),"uname"=>htmlspecialchars($data->uname),"uemail"=>htmlspecialchars($data->uemail));
          //array_push($tmpusers, array("type"=>htmlspecialchars($data->utype),"uid"=>htmlspecialchars($data->uid),"uname"=>htmlspecialchars($data->uname)));
          $sql="update config_workflows set wgroups=? where id=?";
          $q = $pdo->prepare($sql);
          $q->execute(array(json_encode($tmp,true),$zobj["id"]));
          $sql="select id,wid from ".(htmlspecialchars($data->utype)=="group"?"user_groups":"users")." where ".(htmlspecialchars($data->utype)=="group"?"group_latname":"mainuser")."=?";
          $q = $pdo->prepare($sql);
          $q->execute(array(htmlspecialchars($data->uid)));
          if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
            if(!empty($zobj['wid'])){ $tmp=json_decode($zobj['wid'],true); } else { $tmp=array(); }
            if(!is_array($tmp)){ $tmp=array(); }
            $tmp[$data->wid]="1";
            $sql="update ".(htmlspecialchars($data->utype)=="group"?"user_groups":"users")." set wid=? where id=?";
            $q = $pdo->prepare($sql);
            $q->execute(array(json_encode($tmp,true),$zobj["id"]));
          }
        }
        gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>"system","wfid"=>$data->wid), "Added user <b>".htmlspecialchars($data->uname)."</b> to service <a href='/smanagement/".$data->wid."'>".$data->wid."</a>");
        echo "User added successfully";
        pdodb::disconnect();
        exit;
      }

  }
  public static function serviceList(){
    global $stypes;
    if (!empty($stypes)) { $stypes = json_decode($stypes, true);} else { $stypes = array();}
    header('Content-Type: application/json');
    $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
    if(isset($_SESSION["user"])){
      $partswid = explode(",", $data->wid);
      $sql="select wid,wname,winfo, modified,wtype,wfcost from config_workflows where wowner='".$_SESSION["user"]."' or wid in (" . str_repeat('?,', count($partswid) - 1) . '?' . ")";
      $q = $pdo->prepare($sql); 
      $q->execute($partswid); 
     } else {
      $sql="select wid,wname,winfo, modified,wtype,wfcost from config_workflows";
      $q = $pdo->prepare($sql);
      $q->execute();
    }
      if($zobj = $q->fetchAll()){
        $data = array();
        foreach($zobj as $val) {
          $data['wid'] = $val['wid'];
          $data['wname'] = $val['wname'];
          $data['wfcost'] = $val['wfcost'];
          $data['winfo'] = $val['winfo'];
          $data['modified'] = date("d M Y",strtotime($val["modified"]));
          $data['wicon'] = !empty($val["wtype"])?$stypes[array_search($val["wtype"], array_column($stypes, 'nameshort'))]["icon"]:"application";
          $newdata[] = $data;
        }
      }
    pdodb::disconnect();
    echo json_encode($newdata, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    gTable::closeAll();
  }
}
class Class_wfview{
  public static function getPage($thisarray){
    global $website;
    global $maindir;
    session_start();
    $err = array();
    $msg = array(); 
    if(isset($_SESSION["user"])){
      $data=sessionClass::getSessUserData(); foreach($data as $key=>$val){  ${$key}=$val; } 
    } elseif(isset($_SESSION["requser"])) {
      $data=sessionClassreq::getSessUserData(); foreach($data as $key=>$val){  ${$key}=$val;  } 
    } else { exit; };
    if(!empty($thisarray["p1"])){ 
      $q=gTable::read("requests","wid,wfstep"," where sname='".$thisarray["p1"]."'");
      $zobj = $q->fetch(PDO::FETCH_ASSOC);
      if(!is_array($zobj) || empty($zobj)){ echo "missing or wrong ID"; exit; }

      include "public/modules/css.php"; ?>
      <link rel="stylesheet" type="text/css" href="/assets/css/jquery-ui.min.css">
      <link rel="stylesheet" type="text/css" href="/assets/modules/smanagement/assets/css/midleo-workflow.css"><?php
       echo '</head><body class="fix-header card-no-border"><div id="main-wrapper">'; ?>
       <div id="dragwf">
       <div class="jtk-canvas canvas-wide flowchart jtk-surface" id="canvas"></div>
       </div>
<input id="wid" name="wid" value="<?php echo $zobj["wid"];?>" type="text" style="display:none;">
<input id="currentST" style="display:none;" value="<?php echo $zobj["wfstep"];?>">


      </div>
     <?php  include "public/modules/js.php"; ?>
     <script type="text/javascript" src="/assets/js/underscore-min.js"></script>
     <script type="text/javascript" src="/assets/js/jquery/jquery.panzoom.min.js"></script>
     <script type="text/javascript" src="/assets/modules/smanagement/assets/js/jsplumb.min.js"></script>
<script type="text/javascript" src="/assets/modules/smanagement/assets/js/midleo-workflow-view.js"></script>
<?php include "public/modules/template_end.php";
    echo '</body></html>';
    } else { echo "missing or wrong ID"; }
  }
}