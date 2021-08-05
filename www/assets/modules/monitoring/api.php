<?php
class Class_monapi{
  public static function getPage($thisarray){
    global $website;
    global $maindir; 
    global $typesrv;
    session_start();
    $err = array();
    $msg = array();
    header('Content-type:application/json;charset=utf-8'); 
    if(!empty($thisarray["p1"])) {
    switch($thisarray["p1"]) {
      case 'getall':  Class_monapi::readMonitors();  break;
      case 'alert':  Class_monapi::writeAlert();  break;
      default: echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"please use the API correctly."));exit;
                    }
  } else { echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"please use the API correctly."));exit;  }
  }
public static function readMonitors(){
    global $monjobprovider;
    global $monjobtype;
    global $monjobsrv;
    global $monaltype;
    global $jobstatus;
    $pdo = pdodb::connect();
    if(!empty($_SESSION["userdata"]["apparr"])){
    $sql="select * from mon_jobs where appid in (" . str_repeat('?,', count($_SESSION["userdata"]["apparr"]) - 1) . '?' . ") ";
    $stmt = $pdo->prepare($sql);
    if($stmt->execute($_SESSION["userdata"]["apparr"])){
      $zobj = $stmt->fetchAll();
      $data=array();
      foreach($zobj as $val) {
        $data['monname']=$val['monname'];
        $data['monid']=$val['monid'];
        $data['id']=$val['id'];
        $data['monprovider']=$monjobprovider[$val['monprovider']];
        $data['montype']=$monjobtype[$val['montype']];
        $data['monsoft']=$monjobsrv[$val['monsoft']];
        $data['alerttype']=$monaltype[$val['monaltype']];
        $data['srv']=$val['srv'];
        $data['statusinfo']=$jobstatus[$val['jobstatus']]["statcolor"];
        $data['statusinfotxt']=$jobstatus[$val['jobstatus']]["name"];
        
        $newdata[]=$data;
      }
    }
    }
    pdodb::disconnect();
    echo json_encode($newdata,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
    exit;
  }
  public static function writeAlert(){
    global $website;
    $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"),true);
    if(!empty($_GET["monid"])){
      $tmp=array();
      $tmp["err"]="";
      foreach($data["APPOBJ"] as $keyin=>$valin){
        $tmp["err"].="Queue:".$valin["NAME"].";Depth:".$valin["DEPTH"].";Max:".$valin["MAX"].";Current:".$valin["CURRENT"]."<br>";
        $tpm["alerttype"]=$valin["TYPE"];
      }
      $tpm["alerttime"]=$data["TIME"];
      $tpm["appsrv"]=$data["APPSRV"];

      $sql="insert into mon_alerts(srv,appsrvid,alerttype,loglevel,errorcode,errorplace,errormessage,appsrv,appobject,alerttime) values(?,?,?,?,?,?,?,?,?,?)";
      $q = $pdo->prepare($sql);
      $q->execute(array(
        htmlspecialchars($_GET["srv"]),
        htmlspecialchars($_GET["monid"]),
        "midleomon",
        "WARNING",
        "MIDLEOAPP",
        $tpm["appsrv"],
        $tmp["err"],
        $tpm["appsrv"],
        $tpm["alerttype"],
        $tpm["alerttime"]
      ));
      pdodb::disconnect();
      exit;
    }
    $sql="select monsoft,monaltype,monaemail from mon_jobs where srv=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($data[0]["host"]["name"]));
    $tmp=array();
    if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
      if($zobj["monsoft"]=="ibmmq"){
        $d = new DateTime($data[0]["message"]["ibm_datetime"]);
        $sql="insert into mon_alerts(srv,appsrvid,alerttype,loglevel,errorcode,errorplace,errormessage,appsrv,appobject,alerttime) values(?,?,?,?,?,?,?,?,?,?)";
        $q = $pdo->prepare($sql);
        $q->execute(array(
          $data[0]["host"]["name"],
          $data[0]["message"]["ibm_qmgrId"],
          $data[0]["service"]["type"],
          $data[0]["message"]["loglevel"],
          $data[0]["message"]["ibm_messageId"],
          $data[0]["log"]["file"]["path"],
          $data[0]["message"]["message"],
          $data[0]["message"]["ibm_serverName"],
          (!empty($data[0]["message"]["ibm_commentInsert1"])?$data[0]["message"]["ibm_commentInsert1"]:$data[0]["message"]["ibm_serverName"]),
          $d->format('Y-m-d H:i:s')
        ));
        $tmp["severity"]=$data[0]["message"]["loglevel"];
        $tmp["notif"]=array(
          "Host"=>$data[0]["host"]["name"],
          "Qmanager"=>$data[0]["message"]["ibm_qmgrId"],
          "Error code"=>$data[0]["message"]["ibm_messageId"],
          "Log path"=>$data[0]["log"]["file"]["path"],
          "Message"=>$data[0]["message"]["message"],
          "Alert time"=>$d->format('Y-m-d H:i:s')
        );
      } elseif($zobj["monsoft"]=="kafka"){

      }
      if($zobj["monaltype"]=="email"){
        send_mailfinal(
          $website['system_mail'],
          $zobj["monaemail"],
          "[MidlEO] ".$zobj["monsoft"].": Monitoring alert",
          "Severity ".$tmp["severity"]." alert.",
          "<br>Please act acordingly!",
          $tmp["notif"],
          "full"
        );

      }
    }
    pdodb::disconnect();
    exit;
  }

}