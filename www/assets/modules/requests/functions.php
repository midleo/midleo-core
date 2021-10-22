<?php
class requestFunctions{
   public static function addchtask($reqid,$usrfullname){
    $msg=array();
    $err=array();
    global $website;
    $pdo = pdodb::connect();
    if(!empty($_POST['chtask'])){
      $sql="insert into requests_comments (reqid,commuser,commfullname,commtext) values(?,?,?,?)";
      $q = $pdo->prepare($sql);
      $q->execute(array($reqid,$_SESSION['user'],$usrfullname,"created change task:".$_POST["chtask"].".<br>Additional info:".$_POST["chinfo"]));
      $sql="insert into requests_deployments (reqid,projid,reqapp,consuser,prodnum,prodinfo) values(?,?,?,?,?,?)";
      $q = $pdo->prepare($sql);
      $q->execute(array(
        $reqid,
        !empty(htmlspecialchars($_POST['projname']))?htmlspecialchars($_POST['projname']):"run",
        htmlspecialchars($_POST['reqapp']),
        $_SESSION['user'],
        htmlspecialchars($_POST['chtask']),
        $_POST['chinfo']
      ));
      $msg="Information added";
    } else {
      $err="empty change task";
    }
    return array("err"=>$err,"msg"=>$msg);
    pdodb::disconnect();
   }
   public static function addcomm($wfstep,$updinfo,$reqid,$usrfullname){
    $msg=array();
    $err=array();
    global $website;
    $pdo = pdodb::connect();
    if(!empty($updinfo)){
      $sql="insert into requests_comments (reqid,commuser,commfullname,commtext) values(?,?,?,?)";
      $q = $pdo->prepare($sql);
      $q->execute(array($reqid,$_SESSION['user'],$usrfullname,$updinfo));
      textClass::replaceMentions($updinfo,$_SERVER["HTTP_HOST"]."/reqinfo/".$reqid);
      $msg="Comment added";
    } else {
      $err="empty comment";
    }
    return array("err"=>$err,"msg"=>$msg);
    pdodb::disconnect();
   }
   public static function donereq($bstep,$uid,$wfdata){
     $msg=array();
     $err=array();
     global $website;
     $pdo = pdodb::connect();
     $nowtime = new DateTime();
     $now=$nowtime->format('Y-m-d H:i').":00";
     $sql="update requests set assigned=?,wfstep=?,modified='".$now."',deployed='1',deployed_by=? where sname=?";
    $q = $pdo->prepare($sql);
    $q->execute(array("done",htmlspecialchars($_POST['wfstep']),$_SESSION['user'],$uid));
      gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("reqid"=>$uid,"appid"=>"system"), "Request " . $uid. " finished by ".$_SESSION["user"]);
     $sql="select email from users where mainuser=?";
      $q = $pdo->prepare($sql);
      $q->execute(array(htmlspecialchars($_POST['requser'])));
      if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
        send_mailfinal(
          $website["system_mail"],
          $zobj['email'],
          "[MidlEO] ".$uid.":".htmlspecialchars($_POST['reqname']),
          "Hello,<br>Request was completed in - ".$website["env_name"],
          "<br><br>You can see the resolution "."<a href=\"https://".$_SERVER['HTTP_HOST']."/console\" target=\"_blank\">here</a>",
          $body=array(
            "Requestor"=>htmlspecialchars($_POST['requser']),
            "Assigned Group"=>"Returned to customer",
            "Request"=>"<a href=\"https://".$_SERVER['HTTP_HOST']."/browse/req/$uid\" target=\"_blank\">".$uid."</a>",
            "Request info"=>$_POST['reqinfo'],
            "Update info"=>$_POST['updateinfo']
          ),
          "full"
        );
      }
      header("Location: /reqinfo/".$uid);
     $msg[]="Request finished";  
     return array("err"=>$err,"msg"=>$msg);
     pdodb::disconnect();
   }
  public static function updreq($usname,$uid,$wfdata){
     $msg=array();
     $err=array();
     global $website;
     $pdo = pdodb::connect();
     
      $sql="update requests set tags=?,info=? where sname=?";
      $q = $pdo->prepare($sql);
      $q->execute(array(htmlspecialchars($_POST['tags']),$_POST['reqinfo'],$uid));
      gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("reqid"=>$uid,"appid"=>"system"), "Updated request <a href='/browse/req/".$uid."'>".htmlspecialchars($_POST['reqname'])."</a>");
      if(!empty(htmlspecialchars($_POST['tags']))){
        gTable::dbsearch($uid,"https://".$_SERVER['HTTP_HOST']."/browse/req/".$uid,htmlspecialchars($_POST['tags']));
      }
      $sql="select email from users where mainuser=?";
      $q = $pdo->prepare($sql);
      $q->execute(array(htmlspecialchars($_POST['requser'])));
      if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
        send_mailfinal(
          $website["system_mail"],
          $zobj['email'],
          "[MidlEO] ".$uid.":".htmlspecialchars($_POST['reqname']),
          "Hello,<br>Request was updated in - ".$website['env_name'],
          "<br><br>Please check it "."<a href=\"https://".$_SERVER['HTTP_HOST']."/console\" target=\"_blank\">here</a>",
           $body=array(
            "Requestor"=>htmlspecialchars($_POST['requser']),
            "Assigned Group"=>!empty($wfdata["nodes"][htmlspecialchars($_POST['wfstep'])][0]["elusrname"])?$wfdata["nodes"][htmlspecialchars($_POST['wfstep'])][0]["elusrname"]:"",
            "Request"=>"<a href=\"https://".$_SERVER['HTTP_HOST']."/browse/req/$uid\" target=\"_blank\">".$uid."</a>",
            "Request info"=>$_POST['reqinfo']
           ),
          "full"
        );
      }
   
    if(!empty(htmlspecialchars($_POST['effchanged']))){ 
      foreach($_POST["effgr"] as $key=>$val){
        $sql="select count(id) from requests_efforts where reqid=? and effuser=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($uid,str_replace("eff_","",$val)));
        if($q->fetchColumn()==0){
          $sql="insert into requests_efforts (reqid,effuser,efffullname,effdays,reqapp) values(?,?,?,?,?)";
          $q = $pdo->prepare($sql);
          $q->execute(array($uid,str_replace("eff_","",$val),$usname,htmlspecialchars($_POST[$val]),htmlspecialchars($_POST["reqapp"])));
        } else {
          $sql="update requests_efforts set effdays=? ,effuser=?, efffullname=? where reqid=?";
          $q = $pdo->prepare($sql);
          $q->execute(array(htmlspecialchars($_POST[$val]),str_replace("eff_","",$val),$usname,$uid));
        }
      }
      $temparr=array();
      foreach($_POST as $key=>$val) {
       if (strncmp($key, "eff_", 4) === 0 && $val!=0){
         $temparr[$key]=htmlspecialchars($val);
       }
      } 
      $sql="select count(id) from requests_efforts_all where reqid=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($uid)); 
       if($q->fetchColumn()==0){
           $sql="insert into requests_efforts_all (reqid,effreq,effdata) values(?,?,?)";
           $q = $pdo->prepare($sql);
           $q->execute(array($uid,htmlspecialchars($_POST['efforts']),json_encode($temparr,true)));
       } else {
           $sql="update requests_efforts_all set effreq=?, effdata=? where reqid=?";
           $q = $pdo->prepare($sql);
           $q->execute(array(htmlspecialchars($_POST['efforts']),json_encode($temparr,true),$uid));
       }
     } 
  //   $msg[]="The request has been updated.";
   //  return array("err"=>$err,"msg"=>$msg);
     pdodb::disconnect();
     header("Location: /reqinfo/".$uid);
   }
   public static function assign($bstep,$reqid){
     $msg=array();
     $err=array();
     global $website;
     $pdo = pdodb::connect();
     $nowtime = new DateTime();
     $now=$nowtime->format('Y-m-d H:i').":00";
     $sql="update requests set assigned=?,modified = '".$now."' where sname=?";
     $q = $pdo->prepare($sql);
     $q->execute(array($_SESSION['user'],$reqid)); 
     gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("projid"=>htmlspecialchars($_POST['projname']),"reqid"=>$reqid,"appid"=>"system"), "Request have been assigned <a href='/browse/req/".$reqid."'>by ".$reqid."</a>");
     $msg[]="Request assigned"; 
     return array("err"=>$err,"msg"=>$msg);
     pdodb::disconnect();
   }
   public static function sendback($reqid,$requser){
    $msg=array();
    $err=array();
    global $website;
    $pdo = pdodb::connect();
    $nowtime = new DateTime();
    $now=$nowtime->format('Y-m-d H:i').":00";
    $sql="update requests set assigned=?,modified = '".$now."' where sname=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($requser,$reqid));
    gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("reqid"=>$reqid,"appid"=>"system"), "Request have been send back to customer <a href='/browse/req/".$reqid."'>".$requser."</a>");
    $msg[]="Request sent back"; 
    return array("err"=>$err,"msg"=>$msg);
    pdodb::disconnect();
   }
   public static function sendbackcl($reqid){
    $msg=array();
    $err=array();
    global $website;
    $pdo = pdodb::connect();
    $nowtime = new DateTime();
    $now=$nowtime->format('Y-m-d H:i').":00";
    $sql="update requests set assigned='',modified = '".$now."' where sname=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($reqid));
    gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>"system","reqid"=>htmlspecialchars($_POST["reqid"])), "Request have been sent back to the team");
    $msg[]="Request sent back"; 
    return array("err"=>$err,"msg"=>$msg);
    pdodb::disconnect();
   }
   public static function addreq($wfdata,$wfdatagroups,$usname,$uemail,$wfdataha,$wfdatahc){
     $msg=array();
     $err=array();
     global $website;
     $pdo = pdodb::connect();
     
    $reqlatname = textClass::cyr2lat(htmlspecialchars($_POST['reqname']));
    $reqlatname = textClass::strreplace($reqlatname);
    $hash = textClass::getRandomStr(12);

      $img = $_FILES['dfile']; 
      if(!empty($img['tmp_name'][0]))
      {
        $img_desc = documentClass::FilesArange($img);
        $files="";
        foreach($img_desc as $val)
                {
                  $files.=$val['name'].",";
                }
        $files=rtrim($files,",");
      }
      $sql="insert into requests (sname,wid,projnum,reqapp,reqname,reqlatname,info,reqtype,reqfile,deadline,deadlinedeployed,requser,wfstep,wfutype,wfunit,wfbstep,projapproved,projconfirmed) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
      $q = $pdo->prepare($sql);
      $q->execute(array(
        $hash,
        htmlspecialchars($_POST['reqwid']),
        !empty(htmlspecialchars($_POST['projname']))?htmlspecialchars($_POST['projname']):"run",
        htmlspecialchars($_POST['appname']),
        htmlspecialchars($_POST['reqname']),
        $reqlatname,
        $_POST['reqinfo'],
        htmlspecialchars($_POST['reqtype']),
        (!empty($files)?$files:""),
        htmlspecialchars($_POST['deadline']),
        htmlspecialchars($_POST['deadlinedeployed']),
        $_SESSION['user'],
        array_keys($wfdata["nodes"])[0],
        !empty($wfdata["nodes"][array_keys($wfdata["nodes"])[0]][0]["elusrtype"])?$wfdata["nodes"][array_keys($wfdata["nodes"])[0]][0]["elusrtype"]:"",
        !empty($wfdata["nodes"][array_keys($wfdata["nodes"])[0]][0]["elusr"])?$wfdata["nodes"][array_keys($wfdata["nodes"])[0]][0]["elusr"]:"",
        !empty($wfdata["nodes"][array_keys($wfdata["nodes"])[0]][0]["elusrbstep"])?$wfdata["nodes"][array_keys($wfdata["nodes"])[0]][0]["elusrbstep"]:"",
        !$wfdataha?1:0,
        !$wfdatahc?1:0
      ));
 
      if(!empty($img['tmp_name'][0]))
      {
        $img_desc = documentClass::FilesArange($img);
        if (!is_dir('data/requests/'.$hash)) { if (!mkdir('data/requests/'.$hash,0755)) { echo "Cannot create request dir data/requests/".$hash."<br>";}}
        foreach($img_desc as $val)
                {
                  $msg[]=documentClass::uploaddocument($val,"data/requests/".$hash."/")."<br>";
                }
      }
      if(!empty($_POST['reqtype'])){
        $datatype=array();
        foreach($_POST as $key => $value) {
           if (strpos($key, $_POST['reqtype']) === 0 && !empty($value)) {
             $datatype[explode("_",$key)[2]][explode("_",$key)[1]]=$value;
          }
        }
       if(!empty($datatype)){
            $sql="insert into requests_data (reqid,reqtype,reqdata) values (?,?,?)";
            $q = $pdo->prepare($sql);
            $q->execute(array($hash,$_POST['reqtype'],json_encode($datatype,true)));
         }
      }
      gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("reqid"=>$hash,"appid"=>"system"), "Opened new request <a href='/browse/req/".$hash."'>".htmlspecialchars($_POST['reqname'])."</a>");
      send_mailfinal(
        $website['system_mail'],
        $wfdatagroups[$wfdata["nodes"][array_keys($wfdata["nodes"])[0]][0]["elusr"]]["uemail"],
        "[MidlEO] ".$hash.":".htmlspecialchars($_POST['reqname']),
        "Hello,<br>You have received new request in - ".$website['env_name'],
        "<br><br>Please check it "."<a href=\"https://".$_SERVER["HTTP_HOST"]."/requests\" target=\"_blank\">here</a>",
        $body=array(
          "Requestor"=>$usname,
          "Assigned Group"=>!empty($wfdata["nodes"][array_keys($wfdata["nodes"])[0]][0]["elusrname"])?$wfdata["nodes"][array_keys($wfdata["nodes"])[0]][0]["elusrname"]:"",
          "Request"=>"<a href=\"https://".$_SERVER["HTTP_HOST"]."/browse/req/".$hash."\" target=\"_blank\">".$hash."</a>",
          "Request info"=>$_POST['reqinfo']
        ),
        "full"
      );
      if(!empty($uemail)){
        send_mailfinal(
          $website['system_mail'],
          $uemail,
          "[MidlEO] ".$hash.":".htmlspecialchars($_POST['reqname']),
          "Hello,<br>You have created new request in - ".$website['env_name'],
          "<br><br>Please check it "."<a href=\"https://".$_SERVER["HTTP_HOST"]."/console\" target=\"_blank\">here</a>",
          $body=array(
            "Requestor"=>$usname,
            "Assigned Group"=>!empty($wfdata["nodes"][array_keys($wfdata["nodes"])[0]][0]["elusrname"])?$wfdata["nodes"][array_keys($wfdata["nodes"])[0]][0]["elusrname"]:"",
            "Request"=>"<a href=\"https://".$_SERVER["HTTP_HOST"]."/browse/req/".$hash."\" target=\"_blank\">".$hash."</a>",
            "Request info"=>$_POST['reqinfo']
          ),
          "full"
        );
      }
      if($wfdataha==1){
        $sql="insert into requests_approval(reqid,projid,reqapp) values(?,?,?)";
        $q = $pdo->prepare($sql);
        $q->execute(array($hash,!empty(htmlspecialchars($_POST['projname']))?htmlspecialchars($_POST['projname']):"run",htmlspecialchars($_POST['appname'])));
      }
      if($wfdatahc==1){
        $sql="insert into requests_confirmation(reqid,projid) values(?,?)";
        $q = $pdo->prepare($sql);
        $q->execute(array($hash,!empty(htmlspecialchars($_POST['projname']))?htmlspecialchars($_POST['projname']):"run"));
      }
      $msg[]="Thank you for the new request.<br>In case of questions, you will be contacted.";

      
     return array("err"=>$err,"msg"=>$msg);
     pdodb::disconnect();
   }
  public static function savereqdata($thirdsubpage,$secsubpage,$user,$bstep){
    $msg=array();
     $err=array();
     global $website;
     $pdo = pdodb::connect();
     $datatype=array();
        foreach($_POST as $key => $value) {
           if (strpos($key, $_POST['reqtype']) === 0 && !empty($value)) {
             $datatype[explode("_",$key)[2]][explode("_",$key)[1]]=$value;
          }
        }
      if(!empty($datatype)){
            $sql="update requests_data set reqdata=? where reqid=? and reqtype=?";
            $q = $pdo->prepare($sql);
            $q->execute(array(json_encode($datatype,true),$thirdsubpage,$secsubpage));
    }
    gTable::track($_SESSION["userdata"]["usname"], $user, array("reqid"=>$thirdsubpage,"appid"=>"system"), "Updated request <a href='/browse/req/".$thirdsubpage."'>".$_POST["updinfo"]."</a>");
    $msg[]="Request updated";  
     return array("err"=>$err,"msg"=>$msg);
     pdodb::disconnect();
  }
  public static function updreqcl($wfdata){
     $msg=array();
     $err=array();
     global $website;
     $pdo = pdodb::connect();
    
      $sql="update requests set info=?, deadline=?, deadlinedeployed=? ".(isset($_POST["apprtype"])?",efforts='".htmlspecialchars($_POST['effappr'])."'":"")." where sname=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($_POST['reqinfo'],htmlspecialchars($_POST['deadline']),htmlspecialchars($_POST['deadlinedeployed']),htmlspecialchars($_POST['reqid'])));

      if(isset($_POST["apprtype"])){
       $sql="update requests_efforts_all set effappr=? where reqid=?";
       $q = $pdo->prepare($sql);
       $q->execute(array(htmlspecialchars($_POST['effappr']),htmlspecialchars($_POST['reqid'])));
      }
      gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("reqid"=>htmlspecialchars($_POST['reqid']),"appid"=>"system"), "Updated request <a href='/browse/req/".htmlspecialchars($_POST['reqid'])."'>".htmlspecialchars($_POST['reqname'])."</a>");
    /*
      send_mailfinal(
       $website['system_mail'],
       $wfdatagroups[$wfdata["nodes"][array_keys($wfdata["nodes"])[0]][0]["elusr"]]["uemail"],
       "[MidlEO] ".htmlspecialchars($_POST['reqid']).":".htmlspecialchars($_POST['reqname']),
       "Hello,<br>request was updated - ".$website['env_name'],
       "<br><br>Please check it "."<a href=\"https://".$_SERVER['HTTP_HOST']."/requests\" target=\"_blank\">here</a>",
       $body=array(
         "Requestor"=>htmlspecialchars($_POST['requser']),
         "Assigned Group"=>!empty($wfdata["nodes"][array_keys($wfdata["nodes"])[0]][0]["elusrname"])?$wfdata["nodes"][array_keys($wfdata["nodes"])[0]][0]["elusrname"]:"",
         "Request"=>"<a href=\"https://".$_SERVER['HTTP_HOST']."/browse/req/".htmlspecialchars($_POST['reqid'])."\" target=\"_blank\">".htmlspecialchars($_POST['reqid'])."</a>",
         "Request info"=>$_POST['reqinfo'],
         "Update info"=>$_POST['updateinfo']
       ),
       "full"
     );  */
     $msg[]="The request has been updated.";
     return array("err"=>$err,"msg"=>$msg);
     pdodb::disconnect();
     header("Location: /reqinfocl/".htmlspecialchars($_POST['reqid']));
   }
   public static function sendnext($wfstep,$nextstep,$usr,$reqid,$usname,$wfdata,$wfdatagroups){
    $msg=array();
    $err=array();
    global $website;
    $pdo = pdodb::connect();
    $nowtime = new DateTime();
    $now=$nowtime->format('Y-m-d H:i').":00";
    $sql="update requests set assigned='',wfstep=?,wfutype=?,wfunit=?,wfbstep=?,modified = '".$now."' where sname=?";
    $q = $pdo->prepare($sql);
    $q->execute(array(
      $nextstep,
      !empty($wfdata["nodes"][$nextstep][0]["elusrtype"])?$wfdata["nodes"][$nextstep][0]["elusrtype"]:"",
      !empty($wfdata["nodes"][$nextstep][0]["elusr"])?$wfdata["nodes"][$nextstep][0]["elusr"]:"",
      !empty($wfdata["nodes"][$nextstep][0]["elusrbstep"])?$wfdata["nodes"][$nextstep][0]["elusrbstep"]:"",
      $reqid
      )
    );
    gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("reqid"=>$reqid,"appid"=>"system"), "Request have been sent to the next step <a href='/browse/req/".$reqid."'>".$nextstep." by ".$_SESSION['user']."</a>");
    send_mailfinal(
      $website['system_mail'],
      $wfdatagroups[$wfdata["nodes"][$nextstep][0]["elusr"]]["uemail"],
      "[MidlEO] ".$reqid.":".htmlspecialchars($_POST['reqname']),
      "Hello,<br>request was updated - ".$website['env_name'],
      "<br><br>Please check it "."<a href=\"https://".$_SERVER["HTTP_HOST"]."/requests\" target=\"_blank\">here</a>",
      $body=array(
        "Requestor"=>htmlspecialchars($_POST['requser']),
        "Assigned Group"=>!empty($wfdata["nodes"][$nextstep][0]["elusrname"])?$wfdata["nodes"][$nextstep][0]["elusrname"]:"",
        "Request"=>"<a href=\"https://".$_SERVER["HTTP_HOST"]."/browse/req/$reqid\" target=\"_blank\">".$reqid."</a>",
        "Request info"=>$_POST['reqinfo']
      ),
      "full"
    );
    $msg[]="The request has been updated.";
    return array("err"=>$err,"msg"=>$msg);
    pdodb::disconnect();
    header("Location: /requests");
   }
}