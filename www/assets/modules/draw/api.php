<?php
class Class_drawapi{
  public static function getPage($thisarray){
    global $website;
    global $maindir;
    session_start();
    $err = array();
    $msg = array();
    if(!empty($thisarray["p1"]) && !empty($_SESSION['user'])) {
    switch($thisarray["p1"]) {
      case 'editgraph': Class_drawapi::editGraph();  break;
      case 'readdes':  Class_drawapi::readDesign();  break;
      case 'deldesign': Class_drawapi::deleteDesign(); break;
      default: echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"please use the API correctly."));exit;
      }
  } else { 
    echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"please use the API correctly."));exit;  
  }
  }
  public static function editGraph(){
    $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
    if(!empty($data->xml)){
      $nowtime = new DateTime();
      $now=$nowtime->format('Y-m-d H:i').":00";
        $xml=simplexml_load_string(stripslashes($data->xml));
        $sql="update config_diagrams set xmldata=?, desdate=?, imgdata=?  where desid=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(urldecode(gzinflate(base64_decode($xml->diagram))),$now, $data->data, $data->desid));
        echo "done";
    } else {
        echo "empty data";
    }
    pdodb::disconnect();
    exit;
  }
  public static function readDesign(){
    $pdo = pdodb::connect();
    //$data = json_decode(file_get_contents("php://input"));
    $tmp=array();
    $tmp["likesearch"]=" desuser='".$_SESSION["user"]."'";
    if(!empty($_SESSION["userdata"]["ugroups"])){
      foreach($_SESSION["userdata"]["ugroups"] as $keyin=>$valin){
          $tmp["likesearch"].=" or accgroups like '%".$keyin."%'";
      }
    }
    $sql="select id,reqid,desid,desdate,desuser,imgdata,desname,tags from config_diagrams where (".$tmp["likesearch"].")"; 
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data=array();
    $data = $stmt->fetchAll(PDO::FETCH_CLASS);
    pdodb::disconnect();
    echo json_encode($data,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE); exit;
  }
  public static function deleteDesign(){
    $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));    
      $sql="delete from config_diagrams where id=?";
      $stmt = $pdo->prepare($sql);
      if($stmt->execute(array($data->id))){
        gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>"system"), "Deleted design with name:".htmlspecialchars($data->desid));
        echo "Design was deleted.";
      } else {
        echo "Unable to delete design.";
      }
    pdodb::disconnect();
  }
}