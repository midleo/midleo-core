<?php
class Class_api{
  public static function getPage($thisarray){
    global $website;
    global $maindir;
    global $typesrv;
    session_start();
    $err = array();
    $msg = array();
    if(!empty($thisarray["p1"]) && !empty($_SESSION['user'])) {
    switch($thisarray["p1"]) {
      case 'add':  Class_api::add($thisarray["p2"]);  break;
      case 'addvar':  Class_api::addVar();  break;
      case 'addfte':  Class_api::addfte();  break;
      case 'addappsrv':  Class_api::addServer();  break;
      case 'dell':  Class_api::dell($thisarray["p2"]);  break;
      case 'duplicate':  Class_api::duplicate($thisarray["p2"]);  break;
      case 'tasks':  Class_api::tasks($thisarray["p2"]);  break;
      case 'calendar':  Class_api::getCal($thisarray["p2"],$thisarray["p3"]);  break;
      case 'delvar':  Class_api::delVar();  break; 
      case 'delldap':  Class_api::delLdap();  break; 
      case 'applications': Class_api::Applications($thisarray["p2"]);  break;
      case 'modules': Class_api::Modules($thisarray["p2"]);  break;
      case 'groups': Class_api::getGroups($thisarray["p2"]);  break;
      case 'dellfte':  Class_api::delfte();  break;
      case 'dellappsrv':  Class_api::dellAppsrv();  break;
      case 'dellflows':  Class_api::dellFlows();  break;  
      case 'dellflow':  Class_api::dellFlow();  break;
      case 'update':  Class_api::update($thisarray["p2"]);  break;
      case 'updatevar':  Class_api::updateVar();  break;  
      case 'updatefte':  Class_api::updatefte();  break;
      case 'updappsrv':  Class_api::updAppsrv();  break;
      case 'updsrv':  Class_api::updSrv();  break;
      case 'read':  Class_api::read($thisarray["p2"],$thisarray["p3"]);  break;
      case 'readfte':  Class_api::readfte($thisarray["p2"]);  break;
      case 'readflows':  Class_api::readFlows();  break;
      case 'updatesystemnest': Class_api::updateSystemnest(); break;
      case 'readflow':  Class_api::readFlow();  break;
      case 'users':  Class_api::getUsers($thisarray["p2"]);  break;
      case 'addflow':  Class_api::addFlow();  break;
      case 'readappsrv':  Class_api::readAppsrv($thisarray["p2"]);  break;
      case 'readsrv':  Class_api::readSrv($thisarray["p2"]);  break;
      case 'firewall':  Class_api::Firewalls($thisarray["p2"]);  break;
      case 'dns':  Class_api::DNS($thisarray["p2"]);  break;
      case 'readpack':  Class_api::readPackage();  break;
      case 'readplaces':  Class_api::readPlaces($thisarray["p2"]);  break;
      case 'readimp':  Class_api::readImported();  break;
      case 'readdepl':  Class_api::readDeployments();  break;
      case 'gitlist': Class_api::readGitInfo(); break;
      case 'getallusrgr':  Class_api::readAllusrGr($thisarray["p2"]);  break;
      case 'mqsc':  Class_api::createMqsc($thisarray["p2"],$thisarray["p3"]);  break;
      case 'auth':  Class_api::createAuth($thisarray["p2"],$thisarray["p3"]);  break;
      case 'dlqh':  Class_api::createDlqh($thisarray["p2"]);  break;
      case 'fte':  Class_api::createFte($thisarray["p2"],$thisarray["p3"]);  break;  
      case 'appgroups': Class_api::appGroups($thisarray["p2"]);  break;  
      case 'test': Class_api::test(); break;
      default: echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"please use the API correctly."));exit;
                    }
  } else { echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"please use the API correctly."));exit;  }
  }
   public static function test(){
    require_once 'controller/vendor/autoload.php';
    $parser = new \Smalot\PdfParser\Parser();
    $pdf    = $parser->parseFile('public/temp/test.pdf');
    $text = $pdf->getText();
    echo $text;
    $details  = $pdf->getDetails();
 
    foreach ($details as $property => $value) {
    if (is_array($value)) {
        $value = implode(', ', $value);
    }
    echo $property . ' => ' . $value . "\n";
   }
  /*  $mpdf = new \Mpdf\Mpdf([
      'format' => 'A4',
      'margin_left' => 5,
      'margin_right' => 5,
      'margin_top' => 5,
      'margin_bottom' => 10,
      'margin_header' => 5,
      'margin_footer' => 5,
    ]);
    $mpdf->SetProtection(array('print'));  
        $mpdf->SetAuthor("MidlEO");
        $mpdf->showWatermarkText = false;
        $mpdf->SetDisplayMode('fullpage');  
        $mpdf->SetTitle("Midleo core");
        $html="test";
        $pagecount = $mpdf->SetSourceFile('2021.07_vasil_vasilev_FITS_4500054819.pdf');
        $tplId = $mpdf->importPage($pagecount);
        $mpdf->useTemplate($tplId);
        $mpdf->WriteHTML($html);
        $mpdf->Output();
*/
   }
    public static function update($d1){
    $pdo = pdodb::connect();
    $nowtime = new DateTime();
    $now=$nowtime->format('Y-m-d H:i').":00";
    $data = json_decode(file_get_contents("php://input"));
    $newobjects=array();
    foreach($data->mq as $key=>$val){
      if(!empty($val)){ 
        $newobjects[$key]=$val;
      }
   //   if(empty($val)){unset($newobjects[$key]);}
    }
    if($data->mq->type=="auth"){
      $newobjects["authrec"]=array();
      foreach($data->mq->authrec as $valauth){
        if(!empty($valauth)){ 
          if(!in_array($valauth, $newobjects["authrec"])){
            $newobjects["authrec"][]=htmlspecialchars($valauth);
        }
        }
      }
    }
      unset($newobjects['qid']);
      unset($newobjects['qmid']); 
      $sql= "update mqenv_mq_".$d1." set qmgr=?,objname=?,objdata=?,proj=?,changed='".$now."' where id=?";
      $stmt = $pdo->prepare($sql);
      if($stmt->execute(array($data->mq->qm,$data->mq->name,json_encode($newobjects,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE),$data->mq->proj,$data->mq->qmid))){
        gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>$data->mq->proj), "Updated ".$d1." with name:<a href='/env/".$d1."/".$data->mq->proj."'>".htmlspecialchars($data->mq->name)."</a>");
        if(!empty(htmlspecialchars($data->mq->tags))){
          gTable::dbsearch(htmlspecialchars(!empty($data->mq->name)?$data->mq->name:$data->mq->qm),$_SERVER["HTTP_REFERER"],htmlspecialchars($data->mq->tags));
        }
        echo "Object was changed.";
      } else {
        echo "Unable to update object.";
      }
    pdodb::disconnect();
  }
  public static function dell($d1){
    $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));    
      $sql="delete from mqenv_mq_".$d1." where id=?";
      $stmt = $pdo->prepare($sql);
      if($stmt->execute(array($data->qmid))){
        gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>$data->projid), "Deleted ".$d1." with name:<a href='/env/".$d1."/".$data->projid."'>".htmlspecialchars($data->qid)."</a>");
        echo "Object was deleted.";
      } else {
        echo "Unable to delete object.";
      }
    pdodb::disconnect();
  }
  public static function duplicate($d1){
    $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));    
    if($d1=="tibco"){
      $sql="insert into tibco_obj (proj,srv,objname,objtype,objdata,projinfo) select proj,srv,concat(objname,'.COPY'),objtype,objdata,projinfo from tibco_obj where id=?";
    } else if($d1=="appsrv"){
      $sql="insert into env_appservers (proj,serv_type,tags,serverdns,serverip,serverid,port,qmname,qmchannel,agentname,brokername,execgname,info,sslenabled,sslkey,sslpass,sslcipher,srvuser,srvpass) select REPLACE(proj,proj,'".$data->newappid."'),serv_type,tags,serverdns,serverip,serverid,port,qmname,qmchannel,agentname,brokername,execgname,info,sslenabled,sslkey,sslpass,sslcipher,srvuser,srvpass from env_appservers where id=?";
    } else {
      $sql="insert into mqenv_mq_".$d1." (proj,qmgr,objname,objtype,objdata,lockedby,projinfo) select proj,qmgr,concat(objname,'.COPY'),objtype,objdata,lockedby,projinfo from mqenv_mq_".$d1." where id=?";
    }
    $stmt = $pdo->prepare($sql);
    if($stmt->execute(array($data->qmid))){
      echo "Object was copied.";
    } else {
      echo "Unable to copy object.";
    }
    pdodb::disconnect();
  }
  public static function add($d1){
    $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
    $newobjects=array();
    foreach($data->mq as $key=>$val){
      $newobjects[$key]=$val;
    }
    if($data->mq->type=="auth"){
      $newobjects["authrec"]=array();
      foreach($data->mq->authrec as $valauth){
        if($valauth){
          $newobjects["authrec"][]=htmlspecialchars($valauth);
        }
      }
    }
     $sql="insert into mqenv_mq_".$d1." (proj,qmgr,objname,objtype,objdata,projinfo) values(?,?,?,?,?,'system')";
      $q = $pdo->prepare($sql);
      $q->execute(array(htmlspecialchars($data->projid),htmlspecialchars($data->mq->qm),htmlspecialchars($data->mq->name),htmlspecialchars($data->mq->type),json_encode($newobjects,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE)));
      gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>$data->projid), "Created new ".$d1." with name:<a href='/env/".$d1."/".$data->projid."'>".htmlspecialchars($data->mq->name)."</a>");
      if(!empty(htmlspecialchars($data->mq->tags))){
        gTable::dbsearch(htmlspecialchars(!empty($data->mq->name)?$data->mq->name:$data->mq->qm),$_SERVER["HTTP_REFERER"],htmlspecialchars($data->mq->tags));
      }
    echo $d1." was created.";
    pdodb::disconnect();
  }
  public static function read($d1,$d2) {
    if($d2=="one"){
      $pdo = pdodb::connect();
      $data = json_decode(file_get_contents("php://input"));
        if($d1=="vars"){
          $sql="select * from mqenv_vars where id=?";
          $stmt = $pdo->prepare($sql);
          $stmt->execute(array($data->qmid));
           if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
             $objects['name']=$row['varname'];
             $objects['varname']=$row['varname'];  
             $objects['varid']=$row['id'];
             $objects['proj']=$row['proj'];
             $objects['tags']=$row['tags'];
             $objects['vartype']=$row['isarray']==1?"envrelated":"envsame";
             $objects['varvaluesame']=$row['isarray']==1?"":$row['varvalue'];
             if($row['isarray']==1){
               $tempobj=json_decode($row['varvalue'],true);
               foreach($tempobj as $key=>$val){
                 $objects['env'][$key]=$val;
               }
             }
          }
        } else {
        $sql= "SELECT id,qmgr,jobrun,jobid,proj,objname,objtype,".(DBTYPE=='oracle'?"to_char(objdata) as objdata":"objdata")." FROM mqenv_mq_".$d1." where id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($data->qmid));
        if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
          $objects=json_decode($row['objdata'],true);
          $objects['name']=$row['objname'];
          $objects['qid']=$row['objname'];
          $objects['qmid']=$row['id'];
          $objects['jobrun']=$row['jobrun'];
          $objects['jobid']=$row['jobid'];
          $objects['proj']=$row['proj'];
          $objects['qm']=$row['qmgr'];
          $objects['type']=strtolower($row['objtype']);
          if(!empty($objects["authrec"])){ 
            foreach($objects["authrec"] as $key=>$val){ 
              unset($objects["authrec"][$key]);
              $objects["authrec"][str_replace(array("+","-"),"",$val)]=$val;
            }
          }
        }
        }
        pdodb::disconnect();
        echo json_encode($objects,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
    } else {
      $pdo = pdodb::connect();
      $data = json_decode(file_get_contents("php://input"));
        if($d1=="vars"){
          $sql="select * from mqenv_vars".(!empty($data->projid)?" where proj='".$data->projid."'":"");
          $stmt = $pdo->prepare($sql);
          $stmt->execute();
          $zobj = $stmt->fetchAll();
          $data=array();
          foreach($zobj as $val) { 
            $data['name']=$val['varname'];
            $data['varname']=$val['varname'];  
            $data['varvalue']=$val['varvalue'];    
            $data['varid']=$val['id'];
            $data['proj']=$val['proj'];
            $newdata[]=$data;
         }
        }
        else {
        $sql= "SELECT id,jobrun,jobid,qmgr,proj,objname,objtype,".(DBTYPE=='oracle'?"to_char(objdata) as objdata":"objdata")." FROM mqenv_mq_".$d1." ".(!empty($data->projid)?" where proj='".$data->projid."'":"");
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $zobj = $stmt->fetchAll();
        $data=array();
        foreach($zobj as $val) { 
          $data['objects'][$val['qmgr']][$val['objname']]=json_decode($val['objdata'],true);
          $data['objects'][$val['qmgr']][$val['objname']]['name']=$val['objname'];
          $data['objects'][$val['qmgr']][$val['objname']]['qid']=$val['objname'];
          $data['objects'][$val['qmgr']][$val['objname']]['qm']=$val['qmgr'];
          $data['objects'][$val['qmgr']][$val['objname']]['qmid']=$val['id'];
          $data['objects'][$val['qmgr']][$val['objname']]['id']=$val['id'];
          $data['objects'][$val['qmgr']][$val['objname']]['appsrv']=$val['qmgr'];
          $data['objects'][$val['qmgr']][$val['objname']]['jobrun']=$val['jobrun'];
          $data['objects'][$val['qmgr']][$val['objname']]['jobid']=$val['jobid'];
          $data['objects'][$val['qmgr']][$val['objname']]['type']=$val['objtype'];
          $data['objects'][$val['qmgr']][$val['objname']]['proj']=$val['proj'];
          $newdata[]=$data['objects'][$val['qmgr']][$val['objname']];
          $keys[]=array_keys($data['objects'][$val['qmgr']][$val['objname']]);
        }
        if(is_array($keys)){
         foreach(textClass::getL2Keys($keys) as $key){
         $d[$key]=""; 
         }
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
  public static function readFlows(){
    $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
    if($data->type=="env"){
      $sql="select id,flowid,flowname,info,".(DBTYPE=='oracle'?"to_char(reqinfo) as reqinfo":"reqinfo").",modified,lockedby from iibenv_flows where projid=?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(htmlspecialchars($data->projid))); 
    } else {
      $sql="select * from requests_data where reqid=? and reqtype='fte'";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(htmlspecialchars($data->projid))); 
    }
    $data=array();
    $data = $stmt->fetchAll(PDO::FETCH_CLASS);
    echo json_encode($data,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
	pdodb::disconnect();
  }
  public static function readFlow(){
    $data = json_decode(file_get_contents("php://input"));
    $files = scandir("data/flows/".$data->type."/".$data->flowid);
    $newdata=array();
    foreach ($files as $key => $value) {
      if (!in_array($value,array(".",".."))){
        $d['file']=$value;
        if (method_exists("serverClass", "fsConvert") && is_callable(array("serverClass", "fsConvert"))){ 
           $d['size']=filesize("data/flows/".$data->type."/".$data->flowid."/".$value)==0?filesize("data/flows/".$data->type."/".$data->flowid."/".$value):serverClass::fsConvert(filesize("data/flows/".$data->type."/".$data->flowid."/".$value));
        } else {
          $d['size']=filesize("data/flows/".$data->type."/".$data->flowid."/".$value);
        }
        $d['changed']=date("d.m.Y H:i:s",filemtime("data/flows/".$data->type."/".$data->flowid."/".$value));
        $newdata[]=$d;
      }
    }
    echo json_encode($newdata,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
  }
  public static function addFlow(){
    $pdo = pdodb::connect();
    $uid=md5(uniqid(microtime()).$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);
    $data = json_decode(file_get_contents("php://input"));
      $sql="insert into iibenv_flows (projid,flowid,flowname,info) values(?,?,?,?)";
      $q = $pdo->prepare($sql);
      if($q->execute(array(htmlspecialchars($data->projid),$uid,htmlspecialchars($data->flow->name),htmlspecialchars($data->flow->info)))){
        if (!is_dir('data/flows/env/'.$uid)) { if (!mkdir('data/flows/env/'.$uid,0755)) { echo "Cannot create flow dir<br>";}}
        gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>htmlspecialchars($data->projid)), "Created new message flow project:<a href='/env/flows/".htmlspecialchars($data->projid)."'>".htmlspecialchars($data->flow->name)."</a>");
        if(!empty(htmlspecialchars($data->flow->tags))){
          gTable::dbsearch(htmlspecialchars($data->flow->name),$_SERVER["HTTP_REFERER"],htmlspecialchars($data->flow->tags));
        }
        echo "Flow project was created.";
      }
      else{
        echo "Unable to create flow project.";
      }
    pdodb::disconnect();
  }
  public static function createMqsc($d1,$d2,$d3="no",$inpdata=null){
    global $maindir;
    global $env;
    $pdo = pdodb::connect();
    if($d1=="one"){
      if(!$inpdata){
        $data = json_decode(file_get_contents("php://input"));
      } else {
        $data = json_decode($inpdata);
      }
      $arrayvars=json_decode("[{}]",true);
      $sql="select varname,varvalue,isarray from mqenv_vars where proj=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($d2));
      if($zobj = $q->fetchAll()){
       foreach($zobj as $val) {
        if($val['isarray']==1){
          $arrayvars["{".$val['varname']."}"]=array("isarray"=>$val['isarray'],"val"=>json_decode($val['varvalue'],true));
         } else {
          $arrayvars["{".$val['varname']."}"]=array("isarray"=>$val['isarray'],"val"=>$val['varvalue']);
         }
       }
      } 
      if (!empty($env)) {  $menudataenv = json_decode($env, true); } else {  $menudataenv = array(); }
        $sql= "SELECT id,proj,qmgr,objname,objtype,".(DBTYPE=='oracle'?"to_char(objdata) as objdata":"objdata")." FROM mqenv_mq_".$data->what." where id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($data->mq->qmid));
        if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
          $objects=json_decode($row['objdata'],true);
          $str="";
          unset($objects['tags']);
          unset($objects['qm']);
          unset($objects['env']);
          unset($objects['jobrun']);
          unset($objects['jobid']);
          if($d3=="no"){
            header("Access-Control-Allow-Origin: *");
            header("Content-Type: text/html; charset=UTF-8");
          }
          if($objects['type']=="sub"){
            $str.="DELETE ".wordwrap("SUB('".strtoupper($row['objname'])."') \n",63,"- \n ");
          }
          if($data->what=="qm"){
            $str.="ALTER QMGR +\n";
          } else {
            $str.="DEFINE ".wordwrap(strtoupper($row['objtype'])."('".strtoupper($row['objname'])."') + \n",63,"- \n ");
          }
          if(!empty($objects['chltype'])){
            $str.="   CHLTYPE(".strtoupper($objects['chltype']).") +\n";
            unset($objects['chltype']);
          }
		      if(!empty($objects['maxmsgl'])){
            $str.="   MAXMSGL(".str_replace("?","",mb_convert_encoding($objects['maxmsgl'], 'ASCII','UTF-8')).") +\n";
            unset($objects['maxmsgl']);
          }
          unset($objects['name'],$objects['type'],$objects['active'],$objects['proj'],$objects['qmgr']);
          foreach($objects as $key=>$val){
            if(!empty($val)){
              if($key=="trigger"){
                $str.="   ".strtoupper($val)." +\n";
              } else {
                $str.=wordwrap("   ".strtoupper($key)."(".(!preg_match('/[^A-Za-z0-9]/', $val)?($key=="mcauser"?"'".$val."'":strtoupper($val)):"'".$val."'").") +\n",70,"- \n ");
              }
            }
          }
          if($data->what=="qm"){
            $str.=";\n";
          } else {
            $str.="   REPLACE;\n";
          }
          $str=str_replace(array("- \n +","+ \n -")," + ",$str);
          if(is_array($menudataenv)){ foreach($menudataenv as $keyenv=>$valenv){
            $strarr[$valenv['nameshort']]=textClass::stage_array($str,$arrayvars,$valenv['nameshort']);
          }
          if($data->job){
            return json_encode(array('success'=>true,'type'=>"success",'resp'=>$strarr),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
          } else {
            echo json_encode(array('success'=>true,'type'=>"success",'resp'=>$strarr),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
           }
          } else {
           $str=textClass::stage_array($str,$arrayvars,"");
           if($data->job){
            return json_encode(array('success'=>true,'type'=>"success",'resp'=>$str),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
           } else {
            echo json_encode(array('success'=>true,'type'=>"success",'resp'=>$str),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
           }
          } 
        } else {
          if($data->job){
            return json_encode(array('success'=>false,'type'=>"error",'resp'=>"No such data"),JSON_UNESCAPED_UNICODE);
          } else {
            echo json_encode(array('success'=>false,'type'=>"error",'resp'=>"No such data"),JSON_UNESCAPED_UNICODE);
          }
        }
    } /*else {
      header("Access-Control-Allow-Origin: *");
      header("Content-Type: text/plain; charset=UTF-8");
      $data = json_decode(file_get_contents("php://input"));
      if(!empty($data->what)){
      } else {
        Class_api::createMQSCALL($d2,$d3,htmlspecialchars($data->type));
      }
    }*/
    pdodb::disconnect();
  }
  public static function readPackage(){
    global $typesrv;
    global $env;
    $env=json_decode($env,true);
    $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
    $sql="select packuid,proj,packname,packuid,srvtype,gitprepared,deployedin,created_by,created_time,id from env_packages where proj=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($data->proj));
    $data=array();
    $zobj = $stmt->fetchAll();
    foreach($zobj as $val) {
      if(!empty($val['deployedin'])){ 
        $data['deployedin']=array();
        foreach(json_decode($val['deployedin'],true) as $keyin=>$valin){ 
          if(!empty($valin)){
            $data['deployedin'][]=$env[array_search($valin, array_column($env, 'nameshort'))]["name"];
          }
        }

      } else {
        $data['deployedin']=array("Not yet");
      }
      $data['packuid']=$val['packuid'];
      $data['proj']=$val['proj'];
      $data['typesrv']=$typesrv[explode("#", $val['srvtype'], 2)[0]];
      $data['srvtype']=explode("#", $val['srvtype'], 2)[0];
      $data['id']=$val['id'];
      $data['name']=$val['packname'];
      $data['isdeployed']=!empty($val['deployedin'])?true:false;
      $data['isgitprepared']=$val['gitprepared']==0?false:true;
      $data['gitprepared']=$val['gitprepared']==0?'Not prepared':'Prepared';
      $data['gitpreparedspan']=$val['gitprepared']==0?'secondary':'info';
      $data['uid']=$val['packuid'];
      $data['user']=$val['created_by'];
      $data['released']=date("d F/Y",strtotime($val['created_time']));
      $newdata[]=$data;
    }
    pdodb::disconnect();
    echo json_encode($newdata,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
  }
  public static function readfte($d1) {
    if($d1=="one"){
      $pdo = pdodb::connect();
      $data = json_decode(file_get_contents("php://input"));
        $sql="select t.*,".(DBTYPE=='oracle'?"to_char(t.info) as info":"t.info")." from mqenv_mqfte t where t.fteid=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(htmlspecialchars($data->fteid)));
      $data=array();
      if($zobj = $stmt->fetch(PDO::FETCH_ASSOC)){
        echo json_encode($zobj,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
      }
      pdodb::disconnect();
    } else {
      $pdo = pdodb::connect();
      $data = json_decode(file_get_contents("php://input"));
      $sql="select t.*, t.mqftename as name,t.sourceagt as appsrv, ".(DBTYPE=='oracle'?"to_char(t.info) as info":"t.info")." from mqenv_mqfte t";
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
      $data=array();
      $data = $stmt->fetchAll(PDO::FETCH_CLASS);
      pdodb::disconnect();
      echo json_encode($data,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
    }
  }
  public static function readAppsrv($d1) {
    global $typesrv;
    if($d1=="one"){
      $pdo = pdodb::connect();
      $data = json_decode(file_get_contents("php://input"));
      $sql="select * from env_appservers where id=? and proj=?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(htmlspecialchars($data->server),htmlspecialchars($data->appcode)));
      $data=array();
      if($zobj = $stmt->fetch(PDO::FETCH_ASSOC)){
        echo json_encode($zobj,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
      }
      pdodb::disconnect();
    } else {
      $pdo = pdodb::connect();
      $data = json_decode(file_get_contents("php://input"));
      $sql="select serverdns,appsrvname,port,id,serv_type,qmname from env_appservers where proj=?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(htmlspecialchars($data->appcode)));
      $data=array();
      if($zobj = $stmt->fetchAll()){
        foreach($zobj as $val) {
          $d=array();
          $d['server']=$val['serverdns'];
          $d['appsrvname']=$val['appsrvname'];
          $d['port']=$val['port'];
          $d['id']=$val['id'];
          $d['serv_type']=$typesrv[$val['serv_type']];
          $d['qmname']=$val['qmname'];
          $data[]=$d;
        }
      } 
      pdodb::disconnect();
      echo json_encode($data,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
    }
  }
  public static function Firewalls($d1) {
    if($d1=="one"){
      $pdo = pdodb::connect();
      $data = json_decode(file_get_contents("php://input"));
      $sql="select * from env_firewall where id=?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(htmlspecialchars($data->id)));
      $data=array();
      if($zobj = $stmt->fetch(PDO::FETCH_ASSOC)){
        echo json_encode($zobj,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
      }
      pdodb::disconnect();
    } elseif($d1=="update"){
     $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
    $keys="";
    foreach($data->fw as $key=>$val){
      $keys.=htmlspecialchars($key)."='".str_replace("'","\"",htmlspecialchars($val))."',";
      }
    $sql="update env_firewall set ".rtrim($keys,',')." where id=?";
    $q = $pdo->prepare($sql); 
    if($q->execute(array(htmlspecialchars($data->fw->id)))){
      gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>htmlspecialchars($data->projid)), "Updated firewall configuration with DNS:<a href='/env/firewall/".htmlspecialchars($data->projid)."'>".htmlspecialchars($data->fw->srcdns)."</a>");
      if(!empty(htmlspecialchars($data->fw->tags))){
        gTable::dbsearch(htmlspecialchars($data->fw->srcdns),$_SERVER["HTTP_REFERER"],htmlspecialchars($data->fw->tags));
      }
      echo "Firewall configuration was updated";
    } else {
      echo "Error updating Firewall configuration";
    }
    pdodb::disconnect();
   } elseif($d1=="add"){
     $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
    $keys=""; $vals="";
    foreach($data->fw as $key=>$val){
        $keys.=htmlspecialchars($key).",";
        $vals.="'".str_replace("'","\"",htmlspecialchars($val))."',";
      }
    $sql="insert into env_firewall (proj,".rtrim($keys,',').") values(?,".rtrim($vals,',').")";
    $q = $pdo->prepare($sql);
    if($q->execute(array(htmlspecialchars($data->projid)))){
      echo "Firewall rules were created";
      gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>htmlspecialchars($data->projid)), "Defined new firewall configuration with DNS:<a href='/env/firewall/".htmlspecialchars($data->projid)."'>".htmlspecialchars($data->fw->srcdns)."</a>");
      if(!empty(htmlspecialchars($data->fw->tags))){
        gTable::dbsearch(htmlspecialchars($data->fw->srcdns),$_SERVER["HTTP_REFERER"],htmlspecialchars($data->fw->tags));
      }
    } else {
      echo "Error creating Server configuration";
    }
    pdodb::disconnect();
   } elseif($d1=="delete"){
     $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
    $sql="delete from env_firewall where id=?";
    $q = $pdo->prepare($sql);
    if($q->execute(array(htmlspecialchars($data->id)))){
      gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>htmlspecialchars($data->projid)), "Deleted firewall configuration for DNS:<a href='/env/firewall/".htmlspecialchars($data->projid)."'>".htmlspecialchars($data->dns)."</a>");
      echo "Firewall configuration was deleted";
    } else {
      echo "Error deleting Firewall configuration";
    }
    pdodb::disconnect();
   } else {
      $pdo = pdodb::connect();
      $data = json_decode(file_get_contents("php://input"));
      $sql="select * from env_firewall".(!empty($data->appcode)?" where proj='".htmlspecialchars($data->appcode)."'":"");
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
      $data=array();
      $data = $stmt->fetchAll(PDO::FETCH_CLASS);
      pdodb::disconnect();
      echo json_encode($data,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
    }
  }
  public static function delfte(){
    $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
      $sql="delete from mqenv_mqfte where fteid=?";
      $q = $pdo->prepare($sql);
      if($q->execute(array(htmlspecialchars($data->fteid)))){
        gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>htmlspecialchars($data->projid)), "Deleted file transfer configuration with ID:".htmlspecialchars($data->fteid));
        echo "FTE configuration was deleted";
      } else {
        echo "Error deleting FTE configuration";
      }
    pdodb::disconnect();
    exit;
  }
  public static function dellAppsrv(){
    $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
    $sql="delete from env_appservers where id=?";
    $q = $pdo->prepare($sql);
    if($q->execute(array(htmlspecialchars($data->server)))){
      gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>htmlspecialchars($data->appcode)), "Deleted server configuration for server:<a href='/env/appservers/".htmlspecialchars($data->appcode)."'>".htmlspecialchars($data->server)."</a>");
      echo "Server configuration was deleted";
    } else {
      echo "Error deleting Server configuration";
    }
    pdodb::disconnect();
  }
  public static function dellFlows(){
    $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
      $sql="delete from iibenv_firewall where flowid=?";
      $q = $pdo->prepare($sql);
      if($q->execute(array(htmlspecialchars($data->flowid)))){
        documentClass::rRD("data/flows/env/".htmlspecialchars($data->flowid));
        gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>htmlspecialchars($data->projid)), "Deleted flow configuration:<a href='/env/flows/".htmlspecialchars($data->projid)."'>".htmlspecialchars($data->flowname)."</a>");
        echo "Flow project was deleted";
      } else {
        echo "Error deleting flow project";
      }
    pdodb::disconnect();
  }
  public static function addfte(){
    $randomid=md5(uniqid(microtime()).$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);
    $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
    $keys=""; $vals="";
    foreach($data->mqfte as $key=>$val){
        $keys.=htmlspecialchars($key).",";
        $vals.="'".str_replace("'","\"",htmlspecialchars($val))."',";
      }
      $sql="insert into mqenv_mqfte (fteid,proj,".rtrim($keys,',').") values(?,?,".rtrim($vals,',').")";
      $q = $pdo->prepare($sql);
      if($q->execute(array($randomid,htmlspecialchars($data->projid)))){
        echo "FTE configuration was created";
        gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>htmlspecialchars($data->projid)), "Created new IBM MQ FTE configuration:<a href='/env/fte/".htmlspecialchars($data->projid)."'>".htmlspecialchars($data->mqfte->mqftename)."</a>");
        if(!empty(htmlspecialchars($data->mqfte->tags))){
          gTable::dbsearch(htmlspecialchars($data->mqfte->mqftename),$_SERVER["HTTP_REFERER"],htmlspecialchars($data->mqfte->tags));
        }
      } else {
        echo "Error creating FTE configuration";
      }
    pdodb::disconnect();
  }
  public static function addServer(){
    $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
    $keys=""; $vals="";
    foreach($data->serv as $key=>$val){
        if($key=="srvpass"){
          $keys.=htmlspecialchars($key).",";
          $vals.="'".documentClass::encryptIt(str_replace("'","\"",htmlspecialchars($val)))."',";
        } else {
          $keys.=htmlspecialchars($key).",";
          $vals.="'".str_replace("'","\"",htmlspecialchars($val))."',";
        }
      }
    $sql="insert into env_appservers (proj,".rtrim($keys,',').") values(?,".rtrim($vals,',').")";
    $q = $pdo->prepare($sql); 
    if($q->execute(array(htmlspecialchars($data->appcode)))){
      echo "Server configuration was created";
      gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>htmlspecialchars($data->appcode)), "Created new server configuration with name:<a href='/env/appservers/".$data->appcode."'>".htmlspecialchars($data->serv->serverdns)."</a>");
      if(!empty(htmlspecialchars($data->serv->tags))){
        gTable::dbsearch(htmlspecialchars($data->serv->serverdns),$_SERVER["HTTP_REFERER"],htmlspecialchars($data->serv->tags));
      }
    } else {
      echo "Error creating Server configuration";
    }
    pdodb::disconnect();
  }
  public static function updatefte(){
    $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
    $keys="";
    foreach($data->mqfte as $key=>$val){
      $keys.=htmlspecialchars($key)."='".str_replace("'","\"",htmlspecialchars($val))."',";
    }
      $sql="update mqenv_mqfte set ".rtrim($keys,',')." where fteid=?";
      $q = $pdo->prepare($sql);
      if($q->execute(array(htmlspecialchars($data->mqfte->fteid)))){
        gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>htmlspecialchars($data->projid)), "Updated IBM MQ FTE configuration:<a href='/env/fte/".htmlspecialchars($data->projid)."'>".htmlspecialchars($data->mqfte->mqftename)."</a>");
        if(!empty(htmlspecialchars($data->mqfte->tags))){
          gTable::dbsearch(htmlspecialchars($data->mqfte->mqftename),$_SERVER["HTTP_REFERER"],htmlspecialchars($data->mqfte->tags));
        }
        echo "FTE configuration was updated";
      } else {
        echo "Error updating FTE configuration";
      }
   pdodb::disconnect();
  }
  public static function updAppsrv(){
    $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
    $keys="";
    $servid=$data->serv->id;
    unset($data->serv->id);
    foreach($data->serv as $key=>$val){
      if($key=="srvpass" && !empty($key)){
        $keys.=htmlspecialchars($key)."='".documentClass::encryptIt(str_replace("'","\"",htmlspecialchars($val)))."',";
      } else {
        $keys.=htmlspecialchars($key)."='".str_replace("'","\"",htmlspecialchars($val))."',";
       }
      }
    $sql="update env_appservers set ".rtrim($keys,',')." where id=?"; 
    $q = $pdo->prepare($sql);
    if($q->execute(array(htmlspecialchars($servid)))){
      gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>htmlspecialchars($data->appcode)), "Updated Application server configuration for server:<a href='/env/appservers/".htmlspecialchars($data->appcode)."'>".htmlspecialchars($data->serv->server)."</a>");
      if(!empty(htmlspecialchars($data->serv->tags))){
        gTable::dbsearch(htmlspecialchars($data->serv->server),$_SERVER["HTTP_REFERER"],htmlspecialchars($data->serv->tags));
      }
      echo "Server configuration was updated";
    } else {
      echo "Error updating Server configuration";
    }
    pdodb::disconnect();
  }
  public static function createAuth($d1,$d2,$d3="no",$inpdata=null){
    global $maindir;
    global $env;
    $pdo = pdodb::connect();
    if($d1=="one"){
      if(!$inpdata){
        $data = json_decode(file_get_contents("php://input"));
      } else {
        $data = json_decode($inpdata);
      }
      $arrayvars=json_decode("[{}]",true);
      $sql="select varname,varvalue,isarray from mqenv_vars where proj=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($d2));
      if($zobj = $q->fetchAll()){
       foreach($zobj as $val) {
        if($val['isarray']==1){
          $arrayvars["{".$val['varname']."}"]=array("isarray"=>$val['isarray'],"val"=>json_decode($val['varvalue'],true));
         } else {
          $arrayvars["{".$val['varname']."}"]=array("isarray"=>$val['isarray'],"val"=>$val['varvalue']);
         }
       }
      } 
      if (!empty($env)) {  $menudataenv = json_decode($env, true); } else {  $menudataenv = array(); }
      if($d3=="no"){
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: text/html; charset=UTF-8");
      }
        $sql= "SELECT qmgr,objname,objtype,".(DBTYPE=='oracle'?"to_char(objdata) as objdata":"objdata")." FROM mqenv_mq_authrec where id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($data->mq->qmid)); 
        if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
          $objects=json_decode($row['objdata'],true);
          $str="";
          $str.="#authority records for qmanager:".$row['qmgr']."\n";
          $str.="setmqaut -m ".$row['qmgr']." -n \"".$row['objname']."\" -t ".$row['objtype']." -g \"".$objects['group']."\"";
          foreach($objects['authrec'] as $val){
            $str.=" ".$val;
          }
          $str.="\n";
         if(is_array($menudataenv)){ 
           foreach($menudataenv as $keyenv=>$valenv){
            $strarr[$valenv['nameshort']]=textClass::stage_array($str,$arrayvars,$valenv['nameshort']);
           }
           if($data->job){
             return json_encode(array('success'=>true,'type'=>"success",'resp'=>$strarr),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
           } else {
            echo json_encode(array('success'=>true,'type'=>"success",'resp'=>$strarr),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
           }
         } else {
          $str=textClass::stage_array($str,$arrayvars,"");
          if($data->job){
            return json_encode(array('success'=>true,'type'=>"success",'resp'=>$str),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
          } else {
            echo json_encode(array('success'=>true,'type'=>"success",'resp'=>$str),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
          }
         } 
        } else {
          if($data->job){
            return json_encode(array('success'=>false,'type'=>"error",'resp'=>"No such data" ),JSON_UNESCAPED_UNICODE);
          } else {
            echo json_encode(array('success'=>false,'type'=>"error",'resp'=>"No such data" ),JSON_UNESCAPED_UNICODE);
          }
        }
    } else {
      header("Access-Control-Allow-Origin: *");
      header("Content-Type: text/plain; charset=UTF-8");
      $data = json_decode(file_get_contents("php://input"));
      Class_api::createAuthALL($d2,$d3,htmlspecialchars($data->type));
    }
    pdodb::disconnect();
  }
  public static function createDlqh($d1,$d2="no",$inpdata=null){
    global $env;
    $pdo = pdodb::connect();
    $now=date('Y-m-d H:i:s');
    if(!$inpdata){
      $data = json_decode(file_get_contents("php://input"));
    } else {
      $data = json_decode($inpdata);
    }
    if($d2=="no"){
      header("Access-Control-Allow-Origin: *");
      header("Content-Type: text/html; charset=UTF-8");
    }
      $sql= "SELECT qmgr,proj,objname,objtype,".(DBTYPE=='oracle'?"to_char(objdata) as objdata":"objdata")." FROM mqenv_mq_dlqh where id=?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array($data->mq->qmid));
      if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      $arrayvars=json_decode("[{}]",true);
      $sql="select varname,varvalue,isarray from mqenv_vars where proj=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($row["proj"])); 
      if($zobj = $q->fetchAll()){
       foreach($zobj as $val) {
        if($val['isarray']==1){
          $arrayvars["{".$val['varname']."}"]=array("isarray"=>$val['isarray'],"val"=>json_decode($val['varvalue'],true));
         } else {
          $arrayvars["{".$val['varname']."}"]=array("isarray"=>$val['isarray'],"val"=>$val['varvalue']);
         }
       }
      } 
      if (!empty($env)) {  $menudataenv = json_decode($env, true); } else {  $menudataenv = array(); }
        $str="";
        $str.="\n*For automated triggering of DLQH, please define MQ process.\n*For manual - /usr/bin/runmqdlq SYSTEM.DEAD.LETTER.QUEUE ".$row['qmgr']." < /var/mqm/Rules.dlq\n\n";
        $str.="*MQ rules\n";
        $str.="*DLQH rules for qmanager:".$row['qmgr']."\n";
        $str.="*Released by:".$_SESSION["user"]."\n";
        $str.="*Released on:".$now."\n\n";
        $str.=$row['objname']."\n";
        //echo $str;
         if(is_array($menudataenv)){ 
           foreach($menudataenv as $keyenv=>$valenv){
            $strarr[$valenv['nameshort']]=textClass::stage_array($str,$arrayvars,$valenv['nameshort']);
           }
           if($data->job){
            return json_encode(array('success'=>true,'type'=>"success",'resp'=>$strarr),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
           } else {
            echo json_encode(array('success'=>true,'type'=>"success",'resp'=>$strarr),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
           }
          } else {
           $str=textClass::stage_array($str,$arrayvars,"");
           if($data->job){
             return json_encode(array('success'=>true,'type'=>"success",'resp'=>$str),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
           } else {
            echo json_encode(array('success'=>true,'type'=>"success",'resp'=>$str),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
           }
          } 
      } else {
        if($data->job){
          return json_encode(array('success'=>false,'type'=>"error",'resp'=>"No such data" ),JSON_UNESCAPED_UNICODE);
        } else {
          echo json_encode(array('success'=>false,'type'=>"error",'resp'=>"No such data" ),JSON_UNESCAPED_UNICODE);
        }
      }

    pdodb::disconnect();
  }
  public static function createAuthALL($d2,$d3,$d4){
    $pdo = pdodb::connect();
      $sql= "SELECT qmgr,objname,objtype,".(DBTYPE=='oracle'?"to_char(objdata) as objdata":"objdata")." FROM mqenv_mq_authrec";
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
      if($zobj = $stmt->fetchAll()){
        $str="";
        foreach($zobj as $row) {
          $objects=json_decode($row['objdata'],true);
          $str.="#authority records for qmanager:".$row['qmgr']."\n";
          $str.="setmqaut -m ".$row['qmgr']." -n \"".$row['objname']."\" -t ".$row['objtype']." -g \"".$objects['group']."\"";
          foreach($objects['authrec'] as $val){
            $str.=" ".$val;
          }
          $str.="\n";
        }
      }
    if($d3!="no"){
      if(file_exists($d3)){ unlink($d3); }
      file_put_contents($d3,$str);
    } else {
      echo $str;
    }
    pdodb::disconnect();
  }
  public static function createFte($d1,$d2,$d3="no",$inpdata=null){
    global $env;
    $pdo = pdodb::connect();
    if($d1=="one"){
      if(!$inpdata){
        $data = json_decode(file_get_contents("php://input"));
      } else {
        $data = json_decode($inpdata);
      }
      if($data->job){
        $sql= "SELECT * FROM mqenv_mqfte where id=?";
      } else {
        $sql= "SELECT * FROM mqenv_mqfte where fteid=?";
      }
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array($data->mqfte->fteid));     
      if($zobj = $stmt->fetch(PDO::FETCH_ASSOC)){ 
        $arrayvars=json_decode("[{}]",true);
        $sql="select varname,varvalue,isarray from mqenv_vars".(!empty($d2)?" where proj='".$d2."'":"");
        $q = $pdo->prepare($sql);
        $q->execute();
        if($zobjin = $q->fetchAll()){
         foreach($zobjin as $val) {
          if($val['isarray']==1){
            $arrayvars["{".$val['varname']."}"]=array("isarray"=>$val['isarray'],"val"=>json_decode($val['varvalue'],true));
           } else {
            $arrayvars["{".$val['varname']."}"]=array("isarray"=>$val['isarray'],"val"=>$val['varvalue']);
           }
         }
        } 
      if (!empty($env)) {  $menudataenv = json_decode($env, true); } else {  $menudataenv = array(); }
       if($d3=="no"){
          header("Access-Control-Allow-Origin: *");
          header("Content-Type: text/html; charset=UTF-8");
        }
        //generateFteXML($zobj['mqftename'],$zobj['batchsize'],$zobj['sourceagt'],$zobj['sourcequeue'],$zobj['sourcedir'],$zobj['regex'],$zobj['sourcefile'],$zobj['sourceagtqmgr'],$zobj['destagtqmgr'],$zobj['destagt'],$zobj['destqueue'],$zobj['postsourcecmd'],$zobj['postsourcecmdarg'],$zobj['postdestcmd'],$zobj['postdestcmdarg'],$zobj['sourcedisp'],$zobj['sourceccsid'],$zobj['destccsid'],$zobj['destdir'],$zobj['textorbinary']);

        $str=Class_api::generateFteXML($zobj['mqftetype'],$zobj['mqftename'],$zobj['batchsize'],$zobj['sourceagt'],$zobj['sourcequeue'],$zobj['sourcedir'],$zobj['regex'],$zobj['sourcefile'],$zobj['sourceagtqmgr'],$zobj['destagtqmgr'],$zobj['destagt'],$zobj['destqueue'],$zobj['postsourcecmd'],$zobj['postsourcecmdarg'],$zobj['postdestcmd'],$zobj['postdestcmdarg'],$zobj['sourcedisp'],$zobj['sourceccsid'],$zobj['destccsid'],$zobj['destdir'],$zobj['destfile'],$zobj['textorbinary']);
         if(is_array($menudataenv)){ foreach($menudataenv as $keyenv=>$valenv){
            $strarr[$valenv['nameshort']]=textClass::stage_array($str,$arrayvars,$valenv['nameshort']);
          }
          if($data->job){
            return json_encode(array('success'=>true,'type'=>"success",'resp'=>$strarr),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
          } else {
            echo json_encode(array('success'=>true,'type'=>"success",'resp'=>$strarr),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
           }
          } else {
            $str=textClass::stage_array($str,$arrayvars,"");
            if($data->job){
              return json_encode(array('success'=>true,'type'=>"success",'resp'=>$str),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
            } else {
              echo json_encode(array('success'=>true,'type'=>"success",'resp'=>$str),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
            }
          } 
      } else {
        if($data->job){
          return json_encode(array('success'=>false,'type'=>"error",'resp'=>"No such data" ),JSON_UNESCAPED_UNICODE);
        } else {
          echo json_encode(array('success'=>false,'type'=>"error",'resp'=>"No such data" ),JSON_UNESCAPED_UNICODE);
        }
      }
    } else {
      header("Access-Control-Allow-Origin: *");
      header("Content-Type: text/plain; charset=UTF-8");
      $data = json_decode(file_get_contents("php://input"));
      //Class_api::createFteALL($d2,$d3);
    }
    pdodb::disconnect();
  }
  public static function getUsers($d1){
	global $installedapp;
    global $website;
    global $maindir;
    global $page;
    global $modulelist;
    global $accrights;
    if($d1=="recent"){
      $pdo = pdodb::connect();
      $data = json_decode(file_get_contents("php://input")); 
      $sql="select count(id) from users_recent where uuid=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($_SESSION["user_id"]));
      if($q->fetchColumn()>0){
        $sql="select ".(DBTYPE=='oracle'?"to_char(recentdata) as recentdata":"recentdata")." from users_recent where uuid=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($_SESSION["user_id"]));
        if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
          $tmparr=json_decode($zobj["recentdata"],true);
          if(count($tmparr)>=5){
            array_pop($tmparr);
          }
          $tmparrn=array(
            "name"=>$data->appname,
            "link"=>$data->link,
            "id"=>$data->appid
          );
          array_unshift($tmparr , $tmparrn);
          $sql="update users_recent set recentdata=? where uuid=?";
          $q = $pdo->prepare($sql);
          $q->execute(array(json_encode($tmparr,true),$_SESSION["user_id"]));
        } 
      } else {
        $tmparr=array();
        $tmparrn=array(
          "name"=>$data->appname,
          "link"=>$data->link,
          "id"=>$data->appid
        );
        array_unshift($tmparr , $tmparrn);
        $sql="insert into users_recent (uuid,recentdata) values (?,?)";
        $q = $pdo->prepare($sql);
        $q->execute(array($_SESSION["user_id"],json_encode($tmparr,true)));
      }
      pdodb::disconnect();
      $_SESSION["userdata"]["lastappid"]=$data->appid;
    } else if($d1=="add"){
      $pdo = pdodb::connect();
      $data = json_decode(file_get_contents("php://input")); 
      $sql="select count(id) from users where mainuser=?";
      $q = $pdo->prepare($sql);
      $q->execute(array(strtolower(htmlspecialchars($data->users->name))));
      if($q->fetchColumn()>0){
        echo "User already exist!";
      } else {
        $uid=md5(uniqid(microtime()).$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);
        $pwd = inputClass::PwdHash(htmlspecialchars($data->users->pass));
        $sql="insert into users (uuid,mainuser,email,pwd,fullname,user_level,utitle) values (?,?,?,?,?,?,?)";
        $q = $pdo->prepare($sql);
        $q->execute(array($uid,strtolower(htmlspecialchars($data->users->name)),htmlspecialchars($data->users->email),$pwd,htmlspecialchars($data->users->fullname),!empty($data->users->rights)?htmlspecialchars($data->users->rights):"1",htmlspecialchars($data->users->title)));
        send_mailfinal(
          $website["system_mail"],
          htmlspecialchars($data->users->email),
          "[MidlEO] user registration",
          "Hello,<br>Welcome to Midleo!",
          "<br><br>You can login "."<a href=\"https://".$_SERVER['HTTP_HOST']."/mlogin\" target=\"_blank\">here</a>",
          $body=array(
            "Username"=>htmlspecialchars($data->users->name),
            "Email"=>htmlspecialchars($data->users->email),
            "Password"=>htmlspecialchars($data->users->pass),
            "Title"=>htmlspecialchars($data->users->utitle),
            "Fullname"=>htmlspecialchars($data->users->fullname),
			      "Group"=>htmlspecialchars($data->users->effgroup)
          ),
          "full"
        );
 	   echo "User created successfully";
      }
      pdodb::disconnect();
    } else if($d1=="addldap"){
      $pdo = pdodb::connect();
      $data = json_decode(file_get_contents("php://input"));
      $sql="select count(id) from users where mainuser=?";
      $q = $pdo->prepare($sql);
      $q->execute(array(strtolower(htmlspecialchars($data->users->name))));
      if($q->fetchColumn()>0){
        echo "User already exist!";
      } else {
        $sql="select * from ldap_config where ldapserver=?";
        $q = $pdo->prepare($sql);
        $q->execute(array(strtolower(htmlspecialchars($data->users->authtype))));
        $zobj = $q->fetch(PDO::FETCH_ASSOC);
        $ldap=ldap::ldapsearch($zobj['ldapserver'],"",$zobj['ldaptree'],$zobj['ldaptree'],strtolower(htmlspecialchars($data->users->name)),$zobj['ldapport']);
        $ldap=json_decode($ldap,true);
        if(!empty($ldap['error'])){
          echo $ldap['error'];
        } else {
          if(!empty($ldap['user']['email'])){
            $uid=md5(uniqid(microtime()).$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'].htmlspecialchars($data->users->name));
            $new_pwd = inputClass::GenPwd();
            $pwd = inputClass::PwdHash($new_pwd);
            $sql="insert into users (uuid,mainuser,email,pwd,fullname,user_level,ldap,ldapserver,utitle) values (?,?,?,?,?,'1',?,?,?)";
            $q = $pdo->prepare($sql);
            $q->execute(array($uid,strtolower(htmlspecialchars($data->users->name)),$ldap['user']['email'],$pwd,(!empty($ldap['user']['displayname'])?$ldap['user']['displayname']:"Display Name"),htmlspecialchars($data->users->rights),$zobj['ldapserver'],htmlspecialchars($data->users->title)));
            echo "User added successfully";
          } else {
            echo "Error taking data for the user from ldap";
          }
        }
      }
      pdodb::disconnect();
    } else if($d1=="update"){
      $pdo = pdodb::connect();
      $data = json_decode(file_get_contents("php://input"));
      if(!empty(htmlspecialchars($data->users->pass))){
        $sql="update users set user_level=?, fullname=?, email=?, utitle=?, pwd=?, wid=? where mainuser=?";
        $q = $pdo->prepare($sql);
        $texec=$q->execute(array(htmlspecialchars($data->users->rights),htmlspecialchars($data->users->fullname),htmlspecialchars($data->users->email),htmlspecialchars($data->users->title),inputClass::PwdHash(htmlspecialchars($data->users->pass)),htmlspecialchars($data->users->wid),htmlspecialchars($data->users->name)));
      } else {
        $sql="update users set user_level=?, fullname=?, email=?, utitle=?, wid=? where mainuser=?";
        $q = $pdo->prepare($sql);
        $texec=$q->execute(array(htmlspecialchars($data->users->rights),htmlspecialchars($data->users->fullname),htmlspecialchars($data->users->email),htmlspecialchars($data->users->title),htmlspecialchars($data->users->wid),htmlspecialchars($data->users->name)));
      }
      if($texec){ 
        echo "User ".htmlspecialchars($data->users->name)." updated";
      } else { 
        echo "User cannot be updated";
      }
      pdodb::disconnect();
      exit;
    } else if($d1=="readone"){
      $pdo = pdodb::connect();
      $data = json_decode(file_get_contents("php://input"));
      $sql="select * from users where mainuser=?";
      $q = $pdo->prepare($sql);
      $q->execute(array(htmlspecialchars($data->user)));
      if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
        $d['rights']=!empty($zobj['user_level'])?$zobj['user_level']:"";
        $d['fullname']=$zobj['fullname'];
        $d['name']=$zobj['mainuser'];
        $d['title']=$zobj['utitle'];
        $d['email']=$zobj['email'];
        $d['wid']=$zobj['wid'];
        //$d['uacolor']=sprintf('#%06X', mt_rand(0, 0x222222));
        $d['uacolor']="var(--usercolor)";
        $d['shortname']=substr(textClass::initials($zobj['fullname']),0,2);
      }
      echo json_encode($d,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
      pdodb::disconnect();
    } else if($d1=="del"){
      $pdo = pdodb::connect();
      $data = json_decode(file_get_contents("php://input"));
      $sql="delete from users where mainuser=?";
      $q = $pdo->prepare($sql);
      if($q->execute(array(htmlspecialchars($data->user)))){
        echo "User ".htmlspecialchars($data->user)." deleted";
      } else {
        echo "There was a problem while deleting this user";
      }
      pdodb::disconnect();
    } else{
      $pdo = pdodb::connect();
      $data = json_decode(file_get_contents("php://input"));
      $sql="select * from users where not mainuser=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($_SESSION["user"]));
      $zobj = $q->fetchAll();
      foreach($zobj as $val) {
        $d['rights']=$val['user_level'];
        $d['fullname']=$val['fullname'];
        $d['acc']=$accrights[$val['user_level']];
        $d['name']=$val['mainuser'];
       // $d['uacolor']=sprintf('#%06X', mt_rand(0, 0xffffff));
        $d['uacolor']="var(--usercolor)";
        $d['shortname']=substr(textClass::initials($val['fullname']),0,2);
        $newdata[]=$d;
      }
      echo json_encode($newdata,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
      pdodb::disconnect();
    }
  }
  public static function generateFteXML($ftetype,$name,$batchsize,$sourceagt,$sourcequeue,$sourcedir,$regex,$sourcefile,$sourceagtqmgr,$destagtqmgr,$destagt,$destqueue,$postsourcecmd,$postsourcecmdarg,$postdestcmd,$postdestcmdarg,$sourcedisp,$sourceccsid,$destccsid,$destdir,$destfile,$textorbinary,$isfile="no"){
    $str="";
    $str.='<?xml version="1.0" encoding="UTF-8"?>
      <monitor:monitor xmlns:monitor="http://www.ibm.com/xmlns/wmqfte/7.0.1/MonitorDefinition" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" version="5.00" xsi:schemaLocation="http://www.ibm.com/xmlns/wmqfte/7.0.1/MonitorDefinition ./Monitor.xsd">';
    $str.='<name>'.$name.'</name>';
    $str.='<pollInterval units="minutes">10</pollInterval>';
    $str.='<batch maxSize="'.$batchsize.'"/>';
    $str.='<agent>'.$sourceagt.'</agent>';
    if(!empty($sourcequeue)){
      $str.='<resources><queue>'.$sourcequeue.'</queue></resources>';
      $str.='<triggerMatch><conditions><allOf><condition><queueNotEmpty/></condition></allOf></conditions></triggerMatch>';
    } else {
      $str.='<resources><directory recursionLevel="0">'.$sourcedir.'</directory></resources>';
      $str.='<triggerMatch><conditions><allOf><condition><fileMatch><pattern'.($regex==1?' type="regex"':'').'>'.$sourcefile.'</pattern></fileMatch></condition></allOf></conditions></triggerMatch>';
    }
    $str.='<tasks><task><name/><transfer><request version="6.00" xsi:noNamespaceSchemaLocation="FileTransfer.xsd"><managedTransfer>';
    $str.='<originator><hostName>'.(!empty($_SERVER['SERVER_NAME'])?$_SERVER['SERVER_NAME']:"example.com").'</hostName><userID>mqm</userID></originator>';
    $str.='<sourceAgent QMgr="'.$sourceagtqmgr.'" agent="'.$sourceagt.'"/>';
    $str.='<destinationAgent QMgr="'.$destagtqmgr.'" agent="'.$destagt.'"/>';
    $str.='<transferSet priority="1">';
    $str.='<metaDataSet><metaData key="com.ibm.wmqfte.JobName">'.$name.'</metaData><metaData key="dataType">'.$name.'</metaData>';
    if(!empty($destqueue)){ $str.='<metaData key="destinationQueue">'.$destqueue.'</metaData>';}
    $str.='</metaDataSet>';
    if(!empty($postsourcecmd)){
      $str.='<postSourceCall><command retryCount="1" retryWait="30" successRC="0" type="executable" name="'.$postsourcecmd.'">';
      if(!empty($postsourcecmdarg)){
        $kt=explode(" ",$postsourcecmdarg);
        while(list($key,$val)=each($kt)){
          if($val<>" " and strlen($val) > 0){
            $str.='<argument>'.$val.'</argument>';
          }
        }
      }
      $str.='</command></postSourceCall>';
    }
    if(!empty($postdestcmd)){
      $str.='<postDestinationCall><command retryWait="30" successRC="0" type="executable" name="'.$postdestcmd.'">';
      if(!empty($postdestcmdarg)){
        $kt=explode(" ",$postdestcmdarg);
        while(list($key,$val)=each($kt)){
          if($val<>" " and strlen($val) > 0){
            $str.='<argument>'.$val.'</argument>';
          }
        }
      }
      $str.='</command></postDestinationCall>';
    }
    $str.='<item checksumMethod="MD5" mode="'.$textorbinary.'">';
    if(!empty($sourcequeue)){
      $str.='<source disposition="leave" recursive="false" type="queue"><queue useGroups="false">${QueueName}@'.$sourceagtqmgr.'</queue></source>';
    } else {
      $str.='<source disposition="'.$sourcedisp.'" recursive="false" type="file"><file '.(!empty($sourceccsid)?'encoding="'.$sourceccsid.'"':"").'>${FilePath}</file></source>';
    }
    if($ftetype=="f2f" || $ftetype=="q2f"){
      if(!empty($destfile)){
       $str.='<destination exist="overwrite" type="file"><file '.(!empty($destccsid)?'encoding="'.$destccsid.'"':"").'>'.$destfile.'</file></destination>';
      } else {
       $str.='<destination exist="overwrite" type="directory"><file '.(!empty($destccsid)?'encoding="'.$destccsid.'"':"").'>'.$destdir.'</file></destination>';
      }
    } 
    if($ftetype=="f2q"){
      $str.='<destination exist="overwrite" type="queue"><queue encoding="UTF-8" persistent="true">'.$destqueue.'</queue></destination>';
    }
    $str.='</item>';
    $str.='</transferSet></managedTransfer></request></transfer></task></tasks><originator><hostName>'.(!empty($_SERVER['SERVER_NAME'])?$_SERVER['SERVER_NAME']:"example.com").'</hostName><userID>mqm</userID></originator><job><name/></job></monitor:monitor>';
    $dom = new DOMDocument;
    $dom->preserveWhiteSpace = FALSE; 
    $dom->loadXML($str);
    $dom->formatOutput = TRUE;
    if($isfile=="no"){
      $txt=$dom->saveXml();
  /*    $txt.='<!--command for creating the transfer
fteCreateMonitor.cmd/sh -ix '.$name.'.xml
-->'; */
      $txt.='';
      return $txt;
    } else {
      $dom->save($isfile);
    /*  $txt = '<!--command for creating the transfer
fteCreateMonitor.cmd/sh -ix '.$name.'.xml
-->'; */
      $txt='';
      $myfile = file_put_contents($isfile, $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
    }
  }
  public static function dellFlow() {
    $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
    gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>htmlspecialchars($data->projid)), "Deleted file ".htmlspecialchars($data->file)." in flow:<a href='/env/flows/".htmlspecialchars($data->projid)."'>".htmlspecialchars($data->flowname)."</a>");
    $thisfile="data/flows/".$data->type."/".htmlspecialchars($data->flowid)."/".htmlspecialchars($data->file);
    if (file_exists($thisfile)){ unlink($thisfile); echo "File deleted";}
    else { echo "File not found"; }
    pdodb::disconnect();
    exit;
  }
  public static function readImported(){
    $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
    $sql="select * from mqenv_imported_files where proj=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($data->appid));
    $data=array();
    $data = $stmt->fetchAll(PDO::FETCH_CLASS);
    pdodb::disconnect();
    echo json_encode($data,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE); exit;
  }
  public static function readDeployments(){
    global $env;
    $env=json_decode($env,true);
    $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
    $sql="select * from env_deployments where proj=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($data->appid));
    $zobj = $stmt->fetchAll();
    foreach($zobj as $val) {
      $data=array();
      if(!empty($val['deplin'])){ 
        $data['deployedin']=$env[array_search($val['deplin'], array_column($env, 'nameshort'))]["name"];
      } else {
        $data['deployedin']="";
      }
      $data['depltype']=$val['depltype'];
      $data['deplenv']=$val['deplenv'];
      $data['depldate']=$val['depldate'];
      $data['packuid']=$val['packuid'];
      $data['deplobjects']=$val['deplobjects'];
      $data['deplby']=$val['deplby'];
      $data['packuid']=$val['packuid'];
      $newdata[]=$data;
    }
    pdodb::disconnect();
    echo json_encode($newdata,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE); exit;
  }
  public static function readGitInfo(){
    $data = json_decode(file_get_contents("php://input"));
    if(!$data->pkg){
      echo json_encode(array(),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE); exit;
    }
    $pdo = pdodb::connect();
    $sql="select * from env_gituploads where packuid=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($data->pkg));
    $zobj = $stmt->fetchAll();
    foreach($zobj as $val) {
      $data=array();
      $data['gittype']=$val['gittype'];
      $data['commitid']=$val['commitid'];
      $data['packuid']=$val['packuid'];
      $data['fileplace']=$val['fileplace'];
      $data['steptime']=$val['steptime'];
      $data['steptype']=$val['steptype'];
      $data['stepuser']=$val['stepuser'];
      $newdata[]=$data;
    }
    pdodb::disconnect();
    echo json_encode($newdata,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE); exit;
  }
  public static function addVar(){
    $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
    foreach($data->mq->env as $key=>$val){
      $varval[$key]=$val;
    }
    $vartype=$data->mq->vartype=="envrelated"?1:0;
    $varvalue=json_encode($varval,true);
    if($data->mq->vartype=="envsame"){ $varvalue=""; $varvalue=$data->mq->varvaluesame;}
    $sql="insert into mqenv_vars (proj,varname,varvalue,isarray) values (?,?,?,?)";
    $q = $pdo->prepare($sql);
      if($q->execute(array(htmlspecialchars($data->projid),htmlspecialchars($data->mq->name),$varvalue,$vartype))){
        gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>htmlspecialchars($data->projid)), "Defined new variable:<a href='/env/vars/".htmlspecialchars($data->projid)."'>".htmlspecialchars($data->mq->name)."</a>");
        if(!empty(htmlspecialchars($data->mq->tags))){
          gTable::dbsearch(htmlspecialchars($data->mq->name),$_SERVER["HTTP_REFERER"],htmlspecialchars($data->mq->tags));
        }
        echo "The variable is saved";
      } else {
        echo "Problem saving the variable";
      }
    pdodb::disconnect(); exit;
  }
  public static function updateVar(){
    $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
    foreach($data->mq->env as $key=>$val){
      $varval[$key]=$val;
    }
    $vartype=$data->mq->vartype=="envrelated"?1:0;
    $varvalue=json_encode($varval,true);
    if($data->mq->vartype=="envsame"){ $varvalue=""; $varvalue=$data->mq->varvaluesame;}
    $sql="update mqenv_vars set varname=?, varvalue=?, isarray=?, tags=? where id=?";
    $q = $pdo->prepare($sql);
      if($q->execute(array(htmlspecialchars($data->mq->name),$varvalue,$vartype,htmlspecialchars($data->mq->tags),htmlspecialchars($data->mq->varid)))){
        gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>htmlspecialchars($data->projid)), "Updated variable:<a href='/env/vars/".htmlspecialchars($data->projid)."'>".htmlspecialchars($data->mq->name)."</a>");
        if(!empty(htmlspecialchars($data->mq->tags))){
          gTable::dbsearch(htmlspecialchars($data->mq->name),$_SERVER["HTTP_REFERER"],htmlspecialchars($data->mq->tags));
        }
        echo "The variable is updated";
      } else {
        echo "Problem updating the variable";
      }
     pdodb::disconnect(); exit;
  }
  public static function delVar(){
    $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
    $sql="delete from mqenv_vars where id=?";
    $q = $pdo->prepare($sql);
    if($q->execute(array(htmlspecialchars($data->varid)))){
      gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>htmlspecialchars($data->projid)), "Deleted variable:<a href='/env/vars/".htmlspecialchars($data->projid)."'>".htmlspecialchars($data->varname)."</a>");
      echo "Variable deleted successfully";
    }
    pdodb::disconnect(); exit;
  }
  public static function delLdap(){
    if(isset($_POST['ldapsrv'])){
      $pdo = pdodb::connect();
      $sql = "delete from ldap_config where id=? and ldapserver=?";
      $q = $pdo->prepare($sql);
      if($q->execute(array(htmlspecialchars($_POST['srvid']),htmlspecialchars($_POST['ldapsrv'])))){
        gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>"system"), "Deleted ldap server configuration:".htmlspecialchars($_POST['ldapsrv']));
        echo json_encode(array('success'=>true ));
      } else {
        echo json_encode(array('success'=>false ));
      }
      pdodb::disconnect(); 
      exit;
    } else {
      echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"Please specify blog ID"));exit;
    }
  }
  public static function Applications($d1){
    if($d1=="qmlist"){
       $pdo = pdodb::connect();
       $data = json_decode(file_get_contents("php://input"));

       if(!empty(htmlspecialchars($data->pkg))){
        $sql="select ".(DBTYPE=='oracle'?"to_char(pkgobjects) as pkgobjects":"pkgobjects")." from env_packages where packuid=? and proj=?";
        $q = $pdo->prepare($sql);
        $q->execute(array(htmlspecialchars($data->pkg),htmlspecialchars($data->appl)));
        if($zobjin = $q->fetch(PDO::FETCH_ASSOC)){
          $tmp=array();
          foreach(json_decode($zobjin["pkgobjects"],true)[htmlspecialchars($data->appl)] as $keyin=>$valin) {
            $tmp[]=$keyin;

          }
        }
      }
      echo json_encode($tmp,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
      pdodb::disconnect();
      exit;
    } elseif($d1=="delete"){
      $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
    $sql="delete from config_app_codes where id=?";
    $q = $pdo->prepare($sql);
    if($q->execute(array(htmlspecialchars($data->id)))){
      gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>htmlspecialchars($data->appcode)), "Deleted application:".htmlspecialchars($data->appcode));
      echo "Application was deleted";
     } else {
      echo "Error deleting application";
     }
     pdodb::disconnect();
   } else {
    $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
    $sql="select id,tags,appcode,appcreated,appname,owner from config_app_codes";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    if($zobj = $stmt->fetchAll()){
      foreach($zobj as $val) {
        $d['appcode']=$val['appcode'];
        $d['owner']=$val['owner'];
        $d['id']=$val['id'];
        $d['appcreated']=$val['appcreated'];
        $d['tags']=!empty($val['tags'])?explode(',', $val['tags']):"";
        $d['appname']=strip_tags(textClass::word_limiter($val['appname'],20,80));
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
  public static function getCal($d1,$d2){
   if($d1==$_SESSION['user']){
    if($d2=="delete"){
     $pdo = pdodb::connect();
      $sql="delete from calendar where mainuser=? and id=?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(htmlspecialchars($d1),htmlspecialchars($_POST["event_id"])));
      pdodb::disconnect();
      echo "Event deleted!";
      exit;
    } else {
    header('Content-type:application/json;charset=utf-8'); 
      $pdo = pdodb::connect();
      $sql="select * from calendar where mainuser=?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(htmlspecialchars($d1)));
      $arr_content=array();
      if($zobj = $stmt->fetchAll()){
        foreach($zobj as $val) {
       //  $arr_line['allDay']   =  $val["allDay"]==1?true:false ;
          $arr_line['editable']   = true;
          $arr_line['start']=date('Y-m-d\TH:i:s',strtotime($val["date_start"]));
          $arr_line['end']=date('Y-m-d\TH:i:s',strtotime($val["date_end"]));
          $arr_line['title']=$val["subject"] ;
         $arr_line['color']="#".$val["color"] ;     
         $arr_line['id']=$val["id"] ;     
      //    $arr_line['url']="/calendar";
          
         $arr_content[] = $arr_line;
        }
        echo json_encode($arr_content);
      }
      pdodb::disconnect();
      exit;
    }
     } else {
      echo json_encode(array("No session!"),true); 
    }
  }
  public static function tasks($d1){
    if($d1=="update"){
      $pdo = pdodb::connect();
       $sql="update tasks set taskstate='1' where mainuser=? and id=?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array($_SESSION["user"],htmlspecialchars($_POST['id'])));
       pdodb::disconnect();
      exit;
    } elseif($d1=="delete"){
      $pdo = pdodb::connect();
      $sql="delete from tasks where mainuser=? and id=?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array($_SESSION["user"],htmlspecialchars($_POST['id'])));
       pdodb::disconnect();
      exit;
    } else {
      echo json_encode(array("Unknown method"),true); 
    }
    
  }
  public static function getGroups($d1){
    if($d1=="addusr"){
      $pdo = pdodb::connect();
      $data = json_decode(file_get_contents("php://input"));
      $sql="select users from user_groups where group_latname=?";
      $q = $pdo->prepare($sql);
      $q->execute(array(htmlspecialchars($data->grid)));
	    if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
         if(!empty($zobj['users'])){ $tmpusers=json_decode($zobj['users'],true); } else { $tmpusers=json_decode("{}",true); }
         if(!is_array($tmpusers)){ $tmpusers=array(); }
         $tmpusers[htmlspecialchars($data->uid)]=htmlspecialchars($data->uname);	 
		     $sql="update user_groups set users=? where group_latname=?";
		     $q = $pdo->prepare($sql);
		     $q->execute(array(json_encode($tmpusers,true),htmlspecialchars($data->grid)));
	    }
      if($data->utype=="user"){
      $sql="select ugroups from users where mainuser=?";
      $q = $pdo->prepare($sql);
      $q->execute(array(htmlspecialchars($data->uid)));
	    if($zobj = $q->fetch(PDO::FETCH_ASSOC)){  
         if(!empty($zobj['ugroups'])){ $tmpgroups=json_decode($zobj['ugroups'],true); } else { $tmpgroups=json_decode("{}",true); }
         if(!is_array($tmpgroups)){ $tmpgroups=array(); }
         $tmpgroups[htmlspecialchars($data->grid)]=htmlspecialchars($data->group);	 
		     $sql="update users set ugroups=? where mainuser=?";
		     $q = $pdo->prepare($sql);
		     $q->execute(array(json_encode($tmpgroups,true),htmlspecialchars($data->uid)));
	     }
      }
      gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>"system"), "Added user <b>".htmlspecialchars($data->uname)."</b> to group <b>".htmlspecialchars($data->group)."</b>");
      echo "User added successfully";
      pdodb::disconnect();
      exit;
    } elseif($d1=="update"){
      $pdo = pdodb::connect();
      $data = json_decode(file_get_contents("php://input"));
      $sql="update user_groups set group_email=?, acclist=? where group_latname=?";
      $q = $pdo->prepare($sql);
      $q->execute(array(htmlspecialchars($data->group->email),$data->modules,htmlspecialchars($data->group->id)));
      gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>"system"), "Updated group <b>".htmlspecialchars($data->group->name)."</b>");
      echo "Group updated successfully";
      pdodb::disconnect();
      exit;
    } elseif($d1=="add"){
      $pdo = pdodb::connect();
      $data = json_decode(file_get_contents("php://input"));
      $sql="select count(id) from user_groups where group_name=?";
      $q = $pdo->prepare($sql);
      $q->execute(array(htmlspecialchars($data->group->name)));
      if($q->fetchColumn()>0){
        echo "Group already exist!";
      } else {
        $latname = textClass::getRandomStr();
        $sql="insert into user_groups(group_latname,group_name,group_email,acclist) values(?,?,?,?)";
        $q = $pdo->prepare($sql);
        $q->execute(array($latname,htmlspecialchars($data->group->name),htmlspecialchars($data->group->email),$data->modules));
        echo "Group created";
        gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>"system"), "Created new group <b>".htmlspecialchars($data->group->name)."</b>");
      }
      pdodb::disconnect();
      exit;
    } elseif($d1=="readone"){
      $pdo = pdodb::connect();
      $data = json_decode(file_get_contents("php://input"));
      $sql="select * from user_groups where group_latname=?";
      $q = $pdo->prepare($sql);
      $q->execute(array(htmlspecialchars($data->group)));
      if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
        if($zobj['acclist']){
          $modules=json_decode($zobj['acclist'],true);
          foreach($modules as $key=>$val){ $modules[$val]=$val; } 
          $d['modules']=$modules;
          $d['selectedid']=!empty($d['modules'])?json_decode($zobj['acclist'],true):array();
        } else {
          $d['selectedid']=array();
        }
        $d['name']=$zobj['group_name'];
        $d['latname']=$zobj['group_latname'];
        $d['email']=$zobj['group_email'];
        $d['users']=json_decode($zobj['users'],true);
        $d["id"]=$zobj["group_latname"];
      }
      echo json_encode($d,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
      pdodb::disconnect();
      exit;
    } elseif($d1=="del"){
      $pdo = pdodb::connect();
      $data = json_decode(file_get_contents("php://input"));
      $sql="select id,group_latname,group_name,users from user_groups where group_latname=?";
      $q = $pdo->prepare($sql);
      $q->execute(array(htmlspecialchars($data->groupid)));
      $zobj = $q->fetch(PDO::FETCH_ASSOC);
      $groupname=$zobj["group_name"];
      if(!empty($zobj["group_latname"])){  
       $users=json_decode($zobj['users'],true);
       foreach($users as $key=>$val){ 
         $sql="select count(id) from users where mainuser=?";
         $q = $pdo->prepare($sql);
         $q->execute(array($key));
         if($q->fetchColumn()>0){ 
          $sql="select ugroups from users where mainuser=?";
          $q = $pdo->prepare($sql);
          $q->execute(array($key));
	       if($zobj = $q->fetch(PDO::FETCH_ASSOC)){  
           if(!empty($zobj['ugroups'])){ $tmpgroups=json_decode($zobj['ugroups'],true); } else { $tmpgroups=json_decode("{}",true); }
           if(!is_array($tmpgroups)){ $tmpgroups=array(); }
           unset($tmpgroups[htmlspecialchars($data->groupid)]);	 
		       $sql="update users set ugroups=? where mainuser=?";
		       $q = $pdo->prepare($sql);
		       $q->execute(array(json_encode($tmpgroups,true),$key));
	        } 
         }
       }
      $sql="delete from user_groups where group_latname=?"; 
      $q = $pdo->prepare($sql);
      $q->execute(array(htmlspecialchars($data->groupid)));
      gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>"system"), "Deleted group <b>".$groupname."</b>");
      echo "Group deleted successfully";
      }
      pdodb::disconnect();
      exit;
    } elseif($d1=="delusr"){
      $pdo = pdodb::connect();
      $data = json_decode(file_get_contents("php://input"));
      $sql="select group_name,users from user_groups where group_latname=?";
      $q = $pdo->prepare($sql);
      $q->execute(array(htmlspecialchars($data->groupid)));
	  if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
        $groupname=$zobj["group_name"];
         if(!empty($zobj['users'])){ $tmpusers=json_decode($zobj['users'],true); } else { $tmpusers=json_decode("{}",true); }
         if(!is_array($tmpusers)){ $tmpusers=array(); }
         unset($tmpusers[htmlspecialchars($data->userid)]);	
		     $sql="update user_groups set users=? where group_latname=?";
		     $q = $pdo->prepare($sql);
		     $q->execute(array(json_encode($tmpusers,true),htmlspecialchars($data->groupid)));
	     }
         $sql="select count(id) from users where mainuser=?";
         $q = $pdo->prepare($sql);
         $q->execute(array(htmlspecialchars($data->userid)));
         if($q->fetchColumn()>0){ 
          $sql="select ugroups from users where mainuser=?";
          $q = $pdo->prepare($sql);
          $q->execute(array(htmlspecialchars($data->userid)));
	       if($zobj = $q->fetch(PDO::FETCH_ASSOC)){  
           if(!empty($zobj['ugroups'])){ $tmpgroups=json_decode($zobj['ugroups'],true); } else { $tmpgroups=json_decode("{}",true); }
           if(!is_array($tmpgroups)){ $tmpgroups=array(); }
           unset($tmpgroups[htmlspecialchars($data->groupid)]);	 
		       $sql="update users set ugroups=? where mainuser=?";
		       $q = $pdo->prepare($sql);
		       $q->execute(array(json_encode($tmpgroups,true),htmlspecialchars($data->userid)));
	        } 
         }
         gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>"system"), "Removed user <b>".htmlspecialchars($data->userid)."</b> from group <b>".$groupname."</b>");
      echo "User deleted successfully";
      pdodb::disconnect();
      exit;
    } else{
      $pdo = pdodb::connect();
      $data = json_decode(file_get_contents("php://input"));
      $sql="select * from user_groups";
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
        if($zobj = $stmt->fetchAll()){
        foreach($zobj as $val) {
        $d['id']=$val['group_latname']; 
        $d['group_name']=$val['group_name'];
        $d['group_avatar']=$val['group_avatar'];
        $d['users']=json_decode($val['users'],true);
        // $d['uacolor']=sprintf('#%06X', mt_rand(0, 0xffffff));
        $d['uacolor']="var(--usercolor)";
        $d['shortname']=substr(textClass::initials($val['group_name']),0,2);
        $newdata[]=$d;
      }
     }
       echo json_encode($newdata,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
      pdodb::disconnect();
	  exit;
    }
  }
  public static function Modules($d1){
   if($d1=="delete"){
     $moddir="assets/modules/";
     $thisid=htmlspecialchars($_POST['thisid']); 
     if(file_exists($moddir.$thisid."/config.php") && !empty($thisid)){
      //    documentClass::rRD($moddir.$thisid);
          echo json_encode(array("text"=>"Module ".$thisid." deleted"),true);
        }
     exit;
   }
  }
  public static function readAllusrGr($d1){
    if(isset($_POST['search'])){
    $pdo = pdodb::connect();
    $data=array();
    $sql="select mainuser,fullname,email, avatar, utitle from users WHERE fullname like'%".htmlspecialchars($_POST['search'])."%' or mainuser like'%".htmlspecialchars($_POST['search'])."%' limit 10";
    $q = $pdo->prepare($sql);
    $q->execute();
      if($zobj = $q->fetchAll()){  
        foreach($zobj as $val) {  
         $data[]=array("name"=>$val['fullname'],"nameid"=>$val['mainuser'],"email"=>$val['email'],"avatar"=>str_replace("/assets/images/users/","",$val['avatar']),"utitle"=>!empty($val['utitle'])?$val['utitle']:"No title","type"=>"user");
      }
    }
    if($d1=="all"){
      $sql="select group_latname,group_name,group_email from user_groups WHERE group_name like'%".htmlspecialchars($_POST['search'])."%' limit 10";
      $q = $pdo->prepare($sql);
      $q->execute();
      if($zobj = $q->fetchAll()){  
        foreach($zobj as $val) {  
         $data[]=array("name"=>$val['group_name'],"nameid"=>$val['group_latname'],"utitle"=>"group","avatar"=>"","email"=>$val['group_email'],"type"=>"group");
      }
     }
    }
    $data[]=array("name"=>"Return to Customer","nameid"=>"custret","email"=>"info@system","avatar"=>"","utitle"=>"customer","type"=>"user");
    echo json_encode($data,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
    pdodb::disconnect();
    exit;
   }
  }
  public static function DNS($d1) {
    if($d1=="one"){
      $pdo = pdodb::connect();
      $data = json_decode(file_get_contents("php://input"));
      $sql="select id,proj,tags,dnsname,dnsserv,ttl,dnsclass,dnstype,".(DBTYPE=='oracle'?"to_char(dnsrecord) as dnsrecord":"dnsrecord")." from env_dns where id=?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(htmlspecialchars($data->id)));
      $data=array();
      if($zobj = $stmt->fetch(PDO::FETCH_ASSOC)){
        echo json_encode($zobj,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
      }
      pdodb::disconnect();
    } elseif($d1=="update"){
     $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
    $keys="";
    foreach($data->dns as $key=>$val){
      $keys.=htmlspecialchars($key)."='".str_replace("'","\"",htmlspecialchars($val))."',";
      }
    $sql="update env_dns set ".rtrim($keys,',')." where id=?";
    $q = $pdo->prepare($sql); 
    if($q->execute(array(htmlspecialchars($data->dns->id)))){
      gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>htmlspecialchars($data->projid)), "Updated DNS configuration:<a href='/env/dns/".htmlspecialchars($data->projid)."'>".htmlspecialchars($data->dns->dnsname)."</a>");
      if(!empty(htmlspecialchars($data->dns->tags))){
        gTable::dbsearch(htmlspecialchars($data->dns->dnsname),$_SERVER["HTTP_REFERER"],htmlspecialchars($data->dns->tags));
      }
      echo "DNS record was updated";
    } else {
      echo "Error updating DNS record";
    }
    pdodb::disconnect();
   } elseif($d1=="add"){
     $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
    $keys=""; $vals="";
    foreach($data->dns as $key=>$val){
        $keys.=htmlspecialchars($key).",";
        $vals.="'".str_replace("'","\"",htmlspecialchars($val))."',";
      }
    $sql="insert into env_dns (proj,".rtrim($keys,',').") values(?,".rtrim($vals,',').")";
    $q = $pdo->prepare($sql);
    if($q->execute(array(htmlspecialchars($data->projid)))){
      echo "DNS record was created";
      gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>htmlspecialchars($data->projid)), "Defined DNS configuration:<a href='/env/dns/".htmlspecialchars($data->projid)."'>".htmlspecialchars($data->dns->dnsname)."</a>");
      if(!empty(htmlspecialchars($data->dns->tags))){
        gTable::dbsearch(htmlspecialchars($data->dns->dnsname),$_SERVER["HTTP_REFERER"],htmlspecialchars($data->dns->tags));
      }
    } else {
      echo "Error creating DNS records";
    }
    pdodb::disconnect();
   } elseif($d1=="delete"){
     $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
    $sql="delete from env_dns where id=?";
    $q = $pdo->prepare($sql);
    if($q->execute(array(htmlspecialchars($data->id)))){
      gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>htmlspecialchars($data->projid)), "Deleted DNS configuration:<a href='/env/dns/".htmlspecialchars($data->projid)."'>".htmlspecialchars($data->dns)."</a>");
      echo "DNS configuration was deleted";
    } else {
      echo "Error deleting DNS configuration";
    }
    pdodb::disconnect();
   } else {
      $pdo = pdodb::connect();
      $data = json_decode(file_get_contents("php://input"));
      $sql="select id,proj,tags,dnsname,dnsserv,ttl,dnsclass,dnstype,".(DBTYPE=='oracle'?"to_char(dnsrecord) as dnsrecord":"dnsrecord")."  from env_dns".(!empty($data->appcode)?" where proj='".htmlspecialchars($data->appcode)."'":"");
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
      $data=array();
      $data = $stmt->fetchAll(PDO::FETCH_CLASS);
      pdodb::disconnect();
      echo json_encode($data,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
    }
  }
  public static function readSrv($d1) {
    global $typesrv;
    if($d1=="one"){
      $pdo = pdodb::connect();
      $data = json_decode(file_get_contents("php://input"));
      $sql="select id,tags,serverid,serverdns,servertype,serverip,servupdated,srvpublic,updperiod from env_servers where id=?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(htmlspecialchars($data->server)));
      $data=array();
      if($zobj = $stmt->fetch(PDO::FETCH_ASSOC)){
        echo json_encode($zobj,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
      }
      pdodb::disconnect();
    } else {
      $pdo = pdodb::connect();
      $data = json_decode(file_get_contents("php://input"));
      $partsgrid = explode(",", $data->grid);
      $sql="select id,tags,serverid,serverdns,servertype,serverip,servupdated from env_servers where groupid in (" . str_repeat('?,', count($partsgrid) - 1) . '?' . ")";
      $stmt = $pdo->prepare($sql);
      $stmt->execute($partsgrid);
      $data=array();
      if($zobj = $stmt->fetchAll()){
        foreach($zobj as $val) {
          $d=array();
          $d['tags']=$val['tags'];
          $d['id']=$val['id'];
          $d['serverid']=$val['serverid'];
          $d['server']=$val['serverdns'];
          $d['servertype']=$val['servertype'];
          $d['serverip']=$val['serverip'];
          $d['servupdated']=$val['servupdated'];
          $data[]=$d;
        }
      } 
      pdodb::disconnect();
      echo json_encode($data,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
    }
  }
  public static function updSrv(){
    $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
    $sql="update env_servers set tags=?, pluid=?, srvpublic=? where id=?";
    $q = $pdo->prepare($sql);
    if($q->execute(array(htmlspecialchars($data->serv->tags),htmlspecialchars($data->serv->place),htmlspecialchars($data->serv->srvpublic),htmlspecialchars($data->serv->id)))){
      gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>htmlspecialchars($data->projid)), "Updated server configuration:<a href='/env/servers/".htmlspecialchars($data->projid)."'>".htmlspecialchars($data->serv->serverdns)."</a>");
      if(!empty(htmlspecialchars($data->serv->tags))){
        gTable::dbsearch(htmlspecialchars($data->serv->serverdns),$_SERVER["HTTP_REFERER"],htmlspecialchars($data->serv->tags));
      }
      if($data->serv->place){
        $sql="update env_places set plused=plused+1 where pluid=?";
        $q = $pdo->prepare($sql);
        $q->execute(array(htmlspecialchars($data->serv->place)));
      }
      echo "Server configuration was updated";
    } else {
      echo "Error updating Server configuration";
    }
    pdodb::disconnect();
  }
  public static function readPlaces($d1){
    if($d1=="delete"){
      $pdo = pdodb::connect();
      $data = json_decode(file_get_contents("php://input"));
      $sql="delete from env_places where pluid=?";
      $stmt = $pdo->prepare($sql);
      if($stmt->execute(array(htmlspecialchars($data->id)))){
        gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>htmlspecialchars($data->projid)), "Deleted Place:".htmlspecialchars($data->name));
        echo "Place was deleted";
      } else {
        echo "Error deleting Place configuration";
      }
      pdodb::disconnect();
    } else {
      $pdo = pdodb::connect();
      $data = json_decode(file_get_contents("php://input"));
      $sql="select * from env_places";
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
      $data=array();
      $zobj = $stmt->fetchAll();
      foreach($zobj as $val) {
        $data['used']=!empty($val['plused'])?"Yes":"No";
        $data['name']=$val['placename'];
        $data['region']=$val['plregion'];
        $data['city']=$val['plcity'];
        $data['user']=explode("#",$val['plcontact'])[2];
        $data['id']=$val['pluid'];
        $newdata[]=$data;
      }
      pdodb::disconnect();
      echo json_encode($newdata,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
    }
  }
  public static function appGroups($d1){
    if($d1=="readone"){
      header('Content-Type: application/json');
      $pdo = pdodb::connect();
      $data = json_decode(file_get_contents("php://input"));
      $sql="select ".(DBTYPE=='oracle'?"to_char(appusers) as appusers":"appusers")." from config_app_codes where appcode=?";
      $q = $pdo->prepare($sql);
      $q->execute(array(htmlspecialchars($data->appcode)));
      if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
        echo $zobj['appusers'];
      }
      pdodb::disconnect();
      exit;
    } elseif($d1=="delusr"){
      $pdo = pdodb::connect();
      $data = json_decode(file_get_contents("php://input"));
      $sql="select id, ".(DBTYPE=='oracle'?"to_char(appusers) as appusers":"appusers")." from config_app_codes where appcode=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($data->appcode));
	    if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
         if(!empty($zobj['appusers'])){ $tmp=json_decode($zobj['appusers'],true); } else { $tmp=array(); }
         if(!is_array($tmp)){ $tmp=array(); }
         unset($tmp[htmlspecialchars($data->userid)]);	
		     $sql="update config_app_codes set appusers=? where id=?";
		     $q = $pdo->prepare($sql);
         $q->execute(array(json_encode($tmp,true),$zobj["id"]));
         $sql="select id,appid from ".(htmlspecialchars($data->utype)=="group"?"user_groups":"users")." where ".(htmlspecialchars($data->utype)=="group"?"group_latname":"mainuser")."=?";
          $q = $pdo->prepare($sql);
          $q->execute(array(htmlspecialchars($data->userid)));
          if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
            if(!empty($zobj['appid'])){ $tmp=json_decode($zobj['appid'],true); } else { $tmp=array(); }
            if(!is_array($tmp)){ $tmp=array(); }
            unset($tmp[$data->appcode]);	
            $sql="update ".(htmlspecialchars($data->utype)=="group"?"user_groups":"users")." set appid=? where id=?";
            $q = $pdo->prepare($sql);
            $q->execute(array(json_encode($tmp,true),$zobj["id"]));
          }
       }
       gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>htmlspecialchars($data->appcode)), "Removed user <b>".htmlspecialchars($data->userid)."</b> from application <a href='/env/apps/?app=".$data->appcode.">".$data->appcode."</a>");
       echo "User deleted successfully";
       pdodb::disconnect();
       exit;
      } elseif($d1=="addusr"){
        $pdo = pdodb::connect();
        $data = json_decode(file_get_contents("php://input"));
        $sql="select id, ".(DBTYPE=='oracle'?"to_char(appusers) as appusers":"appusers")." from config_app_codes where appcode=?";
        $q = $pdo->prepare($sql);
        $q->execute(array(htmlspecialchars($data->appcode)));
        if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
          if(!empty($zobj['appusers'])){ $tmp=json_decode($zobj['appusers'],true); } else { $tmp=array(); }
          if(!is_array($tmp)){ $tmp=array(); }
          $tmp[htmlspecialchars($data->uid)]=array("type"=>htmlspecialchars($data->utype),"uname"=>htmlspecialchars($data->uname),"uemail"=>htmlspecialchars($data->uemail),"uavatar"=>htmlspecialchars($data->avatar),"utitle"=>htmlspecialchars($data->utitle));
          $sql="update config_app_codes set appusers=? where id=?";
          $q = $pdo->prepare($sql);
          $q->execute(array(json_encode($tmp,true),$zobj["id"]));

          $sql="select id,appid from ".(htmlspecialchars($data->utype)=="group"?"user_groups":"users")." where ".(htmlspecialchars($data->utype)=="group"?"group_latname":"mainuser")."=?";
          $q = $pdo->prepare($sql);
          $q->execute(array(htmlspecialchars($data->uid)));
          if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
            if(!empty($zobj['appid'])){ $tmp=json_decode($zobj['appid'],true); } else { $tmp=array(); }
            if(!is_array($tmp)){ $tmp=array(); }
            $tmp[$data->appcode]="1";
            $sql="update ".(htmlspecialchars($data->utype)=="group"?"user_groups":"users")." set appid=? where id=?";
            $q = $pdo->prepare($sql);
            $q->execute(array(json_encode($tmp,true),$zobj["id"]));
          }

        }
        gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>htmlspecialchars($data->appcode)), "Added user <b>".htmlspecialchars($data->uname)."</b> to application <a href='/env/apps/?app=".$data->appcode.">".$data->appcode."</a>");
        echo "User added successfully";
        pdodb::disconnect();
        exit;
      }

  }
  public static function updateSystemnest(){
    if(isset($_SESSION["user"]) && !empty($_POST['thistype'])){
      textClass::change_config('controller/config.vars.php', array($_POST['thistype'] => $_POST['data']));
    } else  {
      echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"Wrong data"));exit;
    }
  }
}