<?php
class Class_deplapi{
  public static function getPage($thisarray){
    global $website;
    global $maindir;
    session_start();
    if(!empty($thisarray["p1"]) && !empty($_SESSION['user'])) {
        header('Content-type:application/json;charset=utf-8'); 
    switch($thisarray["p1"]) {
        case 'deployall':  Class_deplapi::deployAll();  break;
        case 'mqpreview': Class_deplapi::mqPreview();  break;
        case 'packprepare': Class_deplapi::preparePack(); break;
        case 'dellpack':  Class_deplapi::dellPackage();  break;
        case 'deployselected': Class_deplapi::deploySelected();  break;
        case 'deployfte': Class_deplapi::deployFte();  break;
        default: echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"please use the API correctly."));exit;
                    }
   } else { echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"please use the API correctly."));exit;  }
  }

  public static function deployAll(){
    global $typesrv;
    global $env;
    global $website;
    $env=json_decode($env,true);
    $data = json_decode(file_get_contents("php://input"));
    $err = false;
    if(!empty($data->deplinfo->env)){   
      $pdo = pdodb::connect();  
      if(!empty($website['gitreposurl'])){
        $return=vc::gitTreelist("packages/".$data->deplinfo->pkgid);
        if($website['gittype']=="gitlab"){
          $response=json_decode($return,true);
        }
        if($website['gittype']=="bitbucket"){
          $response=json_decode($return,true)["values"];
        }
        foreach($response as $keyin=>$valin){
          if(preg_match('/^(?=.*'.$data->deplinfo->env.')(?=.*txt)/s', $valin["path"])){
            $appsrv=str_replace(array("packages/".$data->deplinfo->pkgid."/".$data->deplinfo->env."_",".txt"),"",$valin["path"]);
            $str=vc::gitGetRaw($valin["path"]);
            $tmp["gitupload"]=$valin["path"];
            if(explode("#", $data->deplinfo->srvid, 2)[0]=="qm"){
               $sql="select * from env_appservers where proj=? and qmname=?";
            } else if(explode("#", $data->deplinfo->srvid, 2)[0]=="fte"){
              $sql="select * from env_appservers where proj=? and agentname=?";
            } else if(explode("#", $data->deplinfo->srvid, 2)[0]=="tibems"){
              $sql="select * from env_appservers where proj=? and qmname=?";
            }
            $q = $pdo->prepare($sql);
            $q->execute(array($data->appid,$appsrv));
            if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
              if(explode("#", $data->deplinfo->srvid, 2)[0]=="qm"){
                $connd='{"type":"WRITE","hostname":"'.$zobj['serverdns'].'","qmanager":"'.$zobj['qmname'].'","port":"'.$zobj['port'].'","channel":"'.$zobj['qmchannel'].'","sslcipher":"'.$zobj['sslcipher'].'","sslkey":"'.$zobj['sslkey'].'","sslpass":"'.$zobj['sslpass'].'","ssl":"'.($zobj['sslenabled']=="1"?"yes":"no").'"}';
                $output=IBMMQ::execJava("READ",$connd,$str); 
                if(!empty($output)){
                  echo $output[0];
                 } else {
                   echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"Error occured: No output, please check if you have installed java and configured everything correctly. <br> Read carefully section => Important information for deployment process"));
                }        

              } else if(explode("#", $data->deplinfo->srvid, 2)[0]=="fte"){
               
                




              } else if(explode("#", $data->deplinfo->srvid, 2)[0]=="tibems"){






              }
              $i=explode(';' , $str);
              $i=count($i);

              $i=!empty($i)?$i:0;
              $depltype=!empty($output['err'])?1:0;
              $sql="insert into env_deployments(proj,packuid,deplobjects,deplby,depltype,deplenv,deplin) values(?,?,?,?,?,?,?)";
              $stmt = $pdo->prepare($sql);
              $stmt->execute(array($data->appid,$data->deplinfo->pkgid,$i,$_SESSION['user'],$depltype,$typesrv[$zobj["serv_type"]],$data->deplinfo->env));
              if($tmp["gitupload"]){
                $resp=vc::GetCommitID($tmp["gitupload"]);
                $lastcommit=json_decode($resp,true)[0]["id"];
                if($lastcommit){
                  $sql="insert into env_gituploads (gittype,commitid,packuid,fileplace,steptype,stepuser) values (?,?,?,?,?,?)";
                  $q = $pdo->prepare($sql);
                  $q->execute(array($website['gittype'],$lastcommit,$data->deplinfo->pkgid,"packages/".$data->deplinfo->pkgid,"deploy",$_SESSION["user"]));
                }
              }
              
            } 
          }
        }


      } else {
        if (is_dir("data/packages/".$data->deplinfo->pkgid)) {

        } else {
          $err=true;
        }
      }
      if($i>0){
        $sqlin="select id,deployedin from env_packages where packuid=?";
        $qin = $pdo->prepare($sqlin);
         if($qin->execute(array(htmlspecialchars($data->deplinfo->pkgid)))){
          $zobjin = $qin->fetch(PDO::FETCH_ASSOC);
          $datain=!empty($zobjin['deployedin'])?json_decode($zobjin['deployedin'],true):array();
          if (!in_array($data->deplinfo->env,$datain)){
            $datain[]=$data->deplinfo->env;
          }
          $datain=json_encode($datain,true);
          $sql="update env_packages set deployedin=? where id=?";
          $q = $pdo->prepare($sql);
          $q->execute(array($datain,$zobjin['id']));
         }
         if($data->deplinfo->reqid){
          $sqlin="select id,deployedin from requests_deployments where reqid=?";
          $qin = $pdo->prepare($sqlin);
          if($qin->execute(array(htmlspecialchars($data->deplinfo->reqid)))){
            $penv=array_search($data->deplinfo->env, array_column($env, 'nameshort'));
            $zobjin = $qin->fetch(PDO::FETCH_ASSOC);
            $datain=!empty($zobjin['deployedin'])?json_decode($zobjin['deployedin'],true):array();
            $datain[$penv]["name"]=$data->deplinfo->env;
            $datain[$penv]["package"]=$data->deplinfo->pkgid;
            $datain[$penv]["results"]="";
            $datain=json_encode($datain,true);
            $sql="update requests_deployments set deployedin=? where id=?";
            $q = $pdo->prepare($sql);
            $q->execute(array($datain,$zobjin['id']));
          }
         }
      }
      if($err){
        gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>$data->appid), "Deployed new package with id:<a href='/env/deploy/".$data->appid."'>".$data->deplinfo->pkgid."</a>");
      }
      pdodb::disconnect();
    } else {
      echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"Please specify Deployment environment"));
    }
    exit;
  }
  public static function deploySelected($inpdata=null){
    if(!$inpdata){
      $data = json_decode(file_get_contents("php://input"));
    } else {
      $data = json_decode($inpdata);
    }
    $err = ""; 
    if(!empty($data->type)){   
      $pdo = pdodb::connect();  
      $arrayvars=json_decode("[{}]",true);
      $sql="select varname,varvalue,isarray from mqenv_vars";
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
      $srv=explode("#", $data->deplinfo->qm);
      $connd='{"type":"WRITE","hostname":"'.$srv[0].'","qmanager":"'.textClass::stage_array($srv[2],$arrayvars,$data->deplinfo->deplenv).'","port":"'.$srv[1].'","channel":"'.$srv[3].'","sslcipher":"'.$srv[7].'","sslkey":"'.$srv[5].'","sslpass":"'.$srv[6].'","ssl":"'.($srv[4]=="1"?"yes":"no").'"}';
      $str=""; $i=0;
      foreach($data->deplinfo->selectedobj as $key=>$val){
        $return=mqConf::mqsc($data->appl,$srv[2],$data->type,$val);
        if(!empty($return['str'][$data->deplinfo->deplenv])){ $i++ ; }
        $str.=$return['str'][$data->deplinfo->deplenv];
        $err.=$return['err'];
      }
      if(!empty($err)){
        if($data->job){
          return json_encode(array('error'=>true,'type'=>"error",'errorlog'=>$err));
        } else {
          echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>$err));
        }
      }
      if(!empty($str) && !empty($connd)){
      //  $str=textClass::stage_array($str,$arrayvars,$data->deplinfo->deplenv);
        $output=IBMMQ::execJava("READ",$connd,$str); 
         if(!empty($output)){
          if($data->job){
            return $output[0];
          } else {
            echo $output[0];
          }
         } else {
          if($data->job){
           return json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"Error occured: No output, please check if you have installed java and configured everything correctly. \n Read carefully section => Important information for deployment process"));
          } else {
            echo  json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"Error occured: No output, please check if you have installed java and configured everything correctly. \n Read carefully section => Important information for deployment process"));
          }
        }
     }
      $depltype=!empty($output['err'])?1:0;
      $sql="insert into env_deployments(proj,packuid,deplobjects,deplby,depltype,deplenv,deplin) values(?,?,?,?,?,?,?)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(htmlspecialchars($data->appl),htmlspecialchars($srv[2]),$i,$_SESSION['user'],$depltype,"IBM MQ",$data->deplinfo->deplenv));
      pdodb::disconnect();
    } else {
      if($data->job){
        return json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"Please specify MQ object type"));
      } else {
        echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"Please specify MQ object type"));
      }
    }
    gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("srvid"=>$srv[0],"reqid"=>$data->deplinfo->reqid,"appid"=>$data->appl), "Deployed new IBM MQ ".$data->type." :<a href='/env/".$data->type."/".$data->appl."'>ID:".json_encode($data->deplinfo->selectedobj,true)."</a>");
    exit;
  }
  public static function deployFte($inpdata=null){
    if(!$inpdata){
      $data = json_decode(file_get_contents("php://input"));
    } else {
      $data = json_decode($inpdata);
    }
    if(!$data->fteids){
      if($data->job){
        return json_encode(array('success'=>false,'type'=>"error",'resp'=>"No FTE input!" ),JSON_UNESCAPED_UNICODE);
      } else {
        echo json_encode(array('success'=>false,'type'=>"error",'resp'=>"No FTE input!" ),JSON_UNESCAPED_UNICODE);
      }
      exit;
    }
    $pdo = pdodb::connect();
    $arrayvars=json_decode("[{}]",true);
    $sql="select varname,varvalue,isarray from mqenv_vars";
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
    foreach($data->fteids as $key=>$val){
      $sql= "SELECT * FROM mqenv_mqfte where id=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($val));  
      if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
        $str=Class_api::generateFteXML($zobj['mqftetype'],$zobj['mqftename'],$zobj['batchsize'],$zobj['sourceagt'],$zobj['sourcequeue'],$zobj['sourcedir'],$zobj['regex'],$zobj['sourcefile'],$zobj['sourceagtqmgr'],$zobj['destagtqmgr'],$zobj['destagt'],$zobj['destqueue'],$zobj['postsourcecmd'],$zobj['postsourcecmdarg'],$zobj['postdestcmd'],$zobj['postdestcmdarg'],$zobj['sourcedisp'],$zobj['sourceccsid'],$zobj['destccsid'],$zobj['destdir'],$zobj['destfile'],$zobj['textorbinary']);
        $str=textClass::stage_array($str,$arrayvars,$data->env);
        $sql="select * from env_appservers where proj=? and agentname=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(htmlspecialchars($data->appcode),htmlspecialchars($zobj['sourceagt'])));
        if($zobjin = $stmt->fetch(PDO::FETCH_ASSOC)){ 
          $connd='{"type":"WRITEFILE","queue":"SYSTEM.MIDLEO.IOT.QUEUE","hostname":"'.$zobjin['serverdns'].'","qmanager":"'.$zobjin['qmname'].'","port":"'.$zobjin['port'].'","channel":"'.$zobjin['qmchannel'].'","sslcipher":"'.$zobjin['sslcipher'].'","sslkey":"'.$zobjin['sslkey'].'","sslpass":"'.$zobjin['sslpass'].'","ssl":"'.($zobjin['sslenabled']=="1"?"yes":"no").'","file":"'.(base64_encode(dirname(__FILE__)."/resources/".strtoupper($zobj['mqftename'].".".$data->env).".xml")).'"}';
          $conndreply='{"type":"READ","function":"QBROWSEANDCLEAR","queue":"SYSTEM.MIDLEO.IOT.QUEUE.REPLY","hostname":"'.$zobjin['serverdns'].'","qmanager":"'.$zobjin['qmname'].'","port":"'.$zobjin['port'].'","channel":"'.$zobjin['qmchannel'].'","sslcipher":"'.$zobjin['sslcipher'].'","sslkey":"'.$zobjin['sslkey'].'","sslpass":"'.$zobjin['sslpass'].'","ssl":"'.($zobjin['sslenabled']=="1"?"yes":"no").'"}';
        } else {
          if($data->job){
            return json_encode(array('success'=>false,'type'=>"error",'resp'=>"Cannot find agent:".$zobj['sourceagt']." in appservers section!" ),JSON_UNESCAPED_UNICODE);
          } else {
            echo json_encode(array('success'=>false,'type'=>"error",'resp'=>"Cannot find agent:".$zobj['sourceagt']." in appservers section!" ),JSON_UNESCAPED_UNICODE);
          }
          pdodb::disconnect();
          exit;
        }
        file_put_contents(dirname(__FILE__)."/resources/".strtoupper($zobj['mqftename'].".".$data->env).".xml",$str);
        $output=IBMMQ::execJava("WRITE",$connd,"midleo");
        sleep(3);
        $outputreply=IBMMQ::execJava("READ",$conndreply,"midleo");
      }
    }
    if($data->job){
      return json_encode(array('success'=>true,'type'=>"success",'resp'=>$output[0],'respreply'=>$outputreply[0]/*,'replyconnd'=>base64_encode($connd)*/),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    } else {
      echo json_encode(array('success'=>true,'type'=>"success",'resp'=>$output[0],'respreply'=>$outputreply[0]/*,'replyconnd'=>base64_encode($connd)*/),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    }
    pdodb::disconnect();
    exit;
  }
  public static function preparePack(){
    global $website;
    header("Content-Type: text/html; charset=UTF-8");
    $data = json_decode(file_get_contents("php://input"));
    if (!is_dir("data/packages")) { mkdir("data/packages",0755); }
    if($data->packid){
      $pdo = pdodb::connect();
      $sql="select t.packuid,t.proj,t.gitprepared,t.srvtype,".(DBTYPE=='oracle'?"to_char(t.pkgobjects) as pkgobjects":"t.pkgobjects")." from env_packages t where t.id=?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array($data->packid)); 
      if($zobj = $stmt->fetch(PDO::FETCH_ASSOC)){
       if (is_dir("data/packages/".$zobj['packuid'])) {
         documentClass::rRD("data/packages/".$zobj['packuid']);
       }
       if(explode("#", $zobj['srvtype'], 2)[0]=="qm"){ 
        if (!is_dir("data/packages/".$zobj['packuid'])) { mkdir("data/packages/".$zobj['packuid'],0755); }
        foreach(json_decode($zobj['pkgobjects'],true) as $key=>$val){ 
          foreach($val as $keyin=>$valin){ 
            foreach($valin as $k=>$v){
              if($k=="authrec"){
                foreach($v as $kobj=>$vobj){
                $pkgarr=array();
                $pkgarr["job"]=true;
                $pkgarr["what"]=$k;
                $pkgarr["mq"]["qmid"]=$vobj;
                $return=Class_api::createAuth("one",$key,"yes",json_encode($pkgarr));
                if(json_decode($return,true)["resp"]){
                  foreach(json_decode($return,true)["resp"] as $kresp=>$vresp){
                    file_put_contents("data/packages/".$zobj['packuid']."/".$kresp."_".$keyin."-auth.sh",$vresp,FILE_APPEND);
                  }
                }
              }
              } else if($k=="dlqh"){
                foreach($v as $kobj=>$vobj){
                $pkgarr=array();
                $pkgarr["job"]=true;
                $pkgarr["what"]=$k;
                $pkgarr["mq"]["qmid"]=$vobj;
                $return=Class_api::createDlqh("","yes",json_encode($pkgarr));
                if(json_decode($return,true)["resp"]){
                  foreach(json_decode($return,true)["resp"] as $kresp=>$vresp){
                    file_put_contents("data/packages/".$zobj['packuid']."/".$kresp."_".$keyin.".dlq",$vresp,FILE_APPEND);
                  }
                }
              }
              } else {
                foreach($v as $kobj=>$vobj){
                  $return=mqConf::mqsc($key,$keyin,$k,$vobj);
                  if(!empty($return)){
                    foreach($return["str"] as $kresp=>$vresp){
                      file_put_contents("data/packages/".$zobj['packuid']."/".$kresp."_".$keyin.".txt",$vresp,FILE_APPEND);
                    }
                  }
                  $pkgarr=array();
                  $pkgarr["job"]=true;
                  $pkgarr["what"]=$k;
                  $pkgarr["mq"]["qmid"]=$vobj;
                  $return=Class_api::createMqsc("one",$key,"no",json_encode($pkgarr));
                  foreach(json_decode($return,true)["resp"] as $kresp=>$vresp){
                    file_put_contents("data/packages/".$zobj['packuid']."/".$kresp."_".$keyin.".mqsc",$vresp,FILE_APPEND);
                  }
                }
              }
            }
          }
        }
        
      } else if(explode("#", $zobj['srvtype'], 2)[0]=="fte"){ 
        foreach(json_decode($zobj['pkgobjects'],true) as $key=>$val){ 
          foreach($val as $keyin=>$valin){ 
            foreach($valin as $k=>$v){ 
              foreach($v as $kobj=>$vobj){
                $pkgarr=array();
                $pkgarr["job"]=true;
                $pkgarr["mqfte"]["fteid"]=$vobj;
                $return=Class_api::createFte("one",$zobj['proj'],"yes",json_encode($pkgarr));
                if(!empty($return)){
                  if (!is_dir("data/packages/".$zobj['packuid'])) { mkdir("data/packages/".$zobj['packuid'],0755); }
                  foreach(json_decode($return,true)["resp"] as $kresp=>$vresp){
                    file_put_contents("data/packages/".$zobj['packuid']."/".$kresp."_".$keyin.".xml",$vresp,FILE_APPEND);
                  }
                }
              }
            }
          }
        }
      } else if(explode("#", $zobj['srvtype'], 2)[0]=="tibems"){ 
        foreach(json_decode($zobj['pkgobjects'],true) as $key=>$val){ 
          foreach($val as $keyin=>$valin){ 
            foreach($valin as $k=>$v){ 
              foreach($v as $kobj=>$vobj){




              }
            }
          }
        }
      }
      if(!empty($website['gittype'])){
        foreach(glob("data/packages/".$zobj['packuid']."/*") as $file ) { 
          $shagit="";
          if($website['gittype']=="github" && $zobj["gitprepared"]==1){
            $resp=vc::gitTreelist("packages/".$zobj['packuid']."/".basename($file));
            $shagit=json_decode($resp,true)["sha"];
          }
          $return=vc::gitAdd("text",$file,"packages/".$zobj['packuid']."/".basename($file),($zobj["gitprepared"]==1?true:false),$shagit);
          if(empty($return["err"])){
            unlink($file);
            $tmp["gitupload"]="packages/".$zobj['packuid']."/".basename($file);
          } else {
            echo $return["err"];
            $tmp["gitupload"]=false;
          }
        }
        if(count(glob("data/packages/".$zobj['packuid']."/*")) === 0){ 
          rmdir("data/packages/".$zobj['packuid']);
        }
      }
      if($tmp["gitupload"]){
        $resp=vc::GetCommitID($tmp["gitupload"]); 
        $lastcommit=json_decode($resp,true)[0]["id"];
        if($lastcommit){
          $sql="insert into env_gituploads (gittype,commitid,packuid,fileplace,steptype,stepuser) values (?,?,?,?,?,?)";
          $q = $pdo->prepare($sql);
          $q->execute(array($website['gittype'],$lastcommit,$zobj['packuid'],"packages/".$zobj['packuid'],"prepare",$_SESSION["user"]));
        }
      }
      gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>$zobj["proj"]), "Prepared new package with id:<a href='/env/packages/".$zobj["proj"]."'>".$zobj['packuid']."</a>");
      $sql="update env_packages set gitprepared=1 where id=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($data->packid));
      echo "Package prepared";
    } 
      pdodb::disconnect();
    }
    exit;
  }
  public static function dellPackage(){
    global $website;
    header("Content-Type: text/html; charset=UTF-8");
    $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
    $sql="delete from env_packages where id=?";
    $q = $pdo->prepare($sql);
    if($q->execute(array(htmlspecialchars($data->packid)))){
      gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>htmlspecialchars($data->proj)), "Deleted package:<a href='/env/packages/".htmlspecialchars($data->proj)."'>".$data->packuid."</a>");
        if(!empty($website['gittype'])){
            $return=vc::gitTreelist("packages/".$data->packuid);
            if($website['gittype']=="gitlab"){
             foreach(json_decode($return,true) as $keyin=>$valin){
              if($valin["type"]=="blob"){
                $return=vc::gitDelete($valin["path"]);
              }
             }
            }
            if($website['gittype']=="bitbucket"){
              foreach(json_decode($return,true)["values"] as $keyin=>$valin){
                $return=vc::gitDelete($valin["path"]);
              }
            }
            if($website['gittype']=="github"){
              foreach(json_decode($return,true) as $keyin=>$valin){
                $resp=vc::gitTreelist($valin["path"]);
                $shagit=json_decode($resp,true)["sha"];
                $return=vc::gitDelete($valin["path"],$shagit);
              }
            }
          }
          if (is_dir("data/packages/".$data->packuid)) {
            documentClass::rRD("data/packages/".$data->packuid);
          }
        echo "Package was deleted";
      } else {
        echo "Error deleting package";
      }
      pdodb::disconnect();
      exit;
  }
  public static function mqPreview(){
    $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
    $arrayvars=json_decode("[{}]",true);
    $sql="select varname,varvalue,isarray from mqenv_vars where proj=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($data->thisapp));
    if($zobj = $q->fetchAll()){
      foreach($zobj as $val) {
       if($val['isarray']==1){
         $arrayvars["{".$val['varname']."}"]=array("isarray"=>$val['isarray'],"val"=>json_decode($val['varvalue'],true));
       } else {
         $arrayvars["{".$val['varname']."}"]=array("isarray"=>$val['isarray'],"val"=>$val['varvalue']);
       }
      }
    }
    $sql="select ".(DBTYPE=='oracle'?"to_char(qminv) as qminv":"qminv")." from env_jobs_mq where proj=? and qmgr=?";
    $q = $pdo->prepare($sql); 
    $q->execute(array($data->thisapp,textClass::stage_array($data->qm,$arrayvars,$data->env)));
    if($zobj = $q->fetch(PDO::FETCH_ASSOC)){ 
      $qmex=json_decode($zobj["qminv"],true);
    } else {
      $qmex=array();
    }
    $qmnew=array();
    $sql="select ".(DBTYPE=='oracle'?"to_char(pkgobjects) as pkgobjects":"pkgobjects")." from env_packages where packuid=? and proj=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($data->thispkg,$data->thisapp));
    if($zobjin = $q->fetch(PDO::FETCH_ASSOC)){
      foreach(json_decode($zobjin['pkgobjects'],true)[$data->thisapp] as $key=>$val){ 
        foreach($val as $keyin=>$valin){ 
            if($keyin=="authrec"){
    
            } elseif($keyin=="dlqh"){
    
            } else {
                $qmnew[strtoupper($keyin)]=array();
                foreach($valin as $kobj=>$vobj){
                    $sql= "SELECT qmgr,objname,objtype,".(DBTYPE=='oracle'?"to_char(objdata) as objdata":"objdata")." FROM mqenv_mq_".$keyin." where qmgr=? and proj=? and id=?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(array($data->qm,$data->thisapp,$vobj));
                    if($zobjin = $stmt->fetch(PDO::FETCH_ASSOC)){
                        $objects=json_decode($zobjin['objdata'],true); 
                        unset($objects['qm']);
                        unset($objects['env']);
                        unset($objects['tags']); 
                        unset($objects['proj']); 
                        unset($objects['jobid']); 
                        unset($objects['active']); 
                        if(!empty($objects['maxmsgl'])){
                           $objects['maxmsgl']=intval(str_replace("?","",mb_convert_encoding($objects['maxmsgl'], 'ASCII','UTF-8')));
                        }
                        if(!empty($objects['maxdepth'])){
                            $objects['maxdepth']=intval($objects['maxdepth']);
                        }
                        foreach($objects as $keyoin=>$valoin){
                            if(!empty($valoin)){
                              if($keyoin=="trigger"){
                                $objects[$keyoin]=strtoupper($valoin);
                              } else {
                                $objects[$keyoin]=str_replace("'","",(!preg_match('/[^A-Za-z0-9]/', $valoin)?($keyoin=="mcauser"?"'".$valoin."'":strtoupper($valoin)):"'".$valoin."'"));
                              }
                            }
                          }
                        $objects=array_change_key_case($objects,CASE_UPPER);
                        $objects=textClass::stage_array($objects,$arrayvars,$data->env);
                        $qst=textClass::stage_array(strtoupper($zobjin["objname"]),$arrayvars,$data->env);
                        $qmnew[strtoupper($keyin)][$qst]=$objects;
                    }
    
                }
            }
    
        }
    } 
    }
    if(is_array($qmex) && !empty($qmex) && is_array($qmnew) && !empty($qmnew)){
      $new=textClass::compare_multi_Arrays($qmex,$qmnew);
      echo json_encode($new,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
    } else {
      echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"No MQ objects found"));
    }
    pdodb::disconnect();
    exit;
  }
}