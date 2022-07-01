<?php
$modulelist["session"]["name"]="Session module";
$modulelist["session"]["system"]=true;
class sessionClass{
  public static function checkAcc($accarr=array(),$rule=null){
    $rule = explode(",", $rule);
    if($_SESSION["user_level"]>=3 || count(array_intersect_key(array_flip($rule), $accarr))>0){
      return true; 
    } else {
      return false;
    }    
  }
  public static function getSessUserData(){
    if(!isset($_SESSION["userdata"]) || isset($_POST["addfile"]) || isset($_POST["delavatar"])){
      $q=gTable::read("users","*"," where mainuser='".$_SESSION['user']."'");
      $zobj = $q->fetch(PDO::FETCH_ASSOC);
      $tmparr=array("user_online_show"=>$zobj['user_online_show'],"user_activity_show"=>$zobj['user_activity_show'],"ugroups"=>json_decode($zobj['ugroups'],true),"effgroup"=>$zobj['effgroup'],"userphone"=>$zobj['phone'],"usertitle"=>$zobj['utitle'],"usemail"=>$zobj['email'],"uavatar"=>$zobj['avatar'],"usname"=>$zobj['fullname'],"uavatar"=>$zobj['avatar']);
      if(!empty($zobj['wid'])){
        $tmp=json_decode($zobj['wid'],true);
      }
      if(!empty($zobj['pjid'])){
        $tmppj=json_decode($zobj['pjid'],true);
      }
      if(!empty($zobj['appid'])){
        $tmpapp=json_decode($zobj['appid'],true);
      }
      if(!is_array($tmp)){ $tmp=array(); }
      if(!is_array($tmppj)){ $tmppj=array(); }
      if(!is_array($tmpapp)){ $tmpapp=array(); }
      $ugrarr = array();
      $ugrarr[] = $_SESSION["user"];
      $ugr="";
      $tmpacc=array();
      if(!empty($zobj['ugroups'])){
        foreach(json_decode($zobj['ugroups'],true) as $key=>$val){
          if($key){
            $ugr.=$key.",";
            $ugrarr[] = $key;
            $q=gTable::read("user_groups","appid,pjid,wid,acclist"," where group_latname='".$key."'");
            if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
              if(!empty($zobj["wid"])){
                foreach(json_decode($zobj["wid"],true) as $keyin=>$valin){
                  $tmp[$keyin]="1";
                }
              }
              if(!empty($zobj["pjid"])){
                foreach(json_decode($zobj["pjid"],true) as $keyin=>$valin){
                  $tmppj[$keyin]="1";
                }
              }
              if(!empty($zobj["appid"])){
                foreach(json_decode($zobj["appid"],true) as $keyin=>$valin){
                  $tmpapp[$keyin]="1";
                }
              }
              if($zobj["acclist"]){
                foreach(json_decode($zobj["acclist"],true) as $keyin=>$valin){ 
                  $tmpacc[$valin]=true; 
                }
              }
            }
          }
       }
      } 
      $wid="";
      $widarr=array();
      foreach($tmp as $key=>$val){
        $wid.=$key.",";
        $widarr[] = $key;
      } 
      $pjarr=array();
      foreach($tmppj as $key=>$val){
        $pjarr[] = $key;
      }
      $apparr=array();
      foreach($tmpapp as $key=>$val){
        $apparr[] = $key;
      }
      $wid=rtrim($wid, ",");
      $ugr=rtrim($ugr, ",");
      $tmparr["wid"]=$wid;
      $tmparr["acclist"]=$tmpacc;
      $tmparr["widarrkeys"]=$tmp;
      $tmparr["pjarrkeys"]=$tmppj;
      $tmparr["apparrkeys"]=$tmpapp;
      $tmparr["ugr"]=$ugr;
      $tmparr["widarr"]=$widarr;
      $tmparr["pjarr"]=$pjarr;
      $tmparr["apparr"]=$apparr;
      $tmparr["ugrarr"]=$ugrarr;
      $_SESSION["userdata"]=$tmparr;
      return $_SESSION["userdata"];
    } else {
      return $_SESSION["userdata"];
    }
  }
  public static function checkvisitor($thissessuser,$newvisit=0){
    $thismonth=date('Y-m');
    if(empty($_COOKIE['visitor']) && $newvisit==0){
      $pdo = pdodb::connect();
      $sql = "SELECT views FROM user_visits where DATE_FORMAT(month,'%Y-%m')=? and mainuser=? limit 1";  
      $q = $pdo->prepare($sql);
      $q->execute(array($thismonth,$thissessuser));
      if($q->rowCount()>0){
        $sql = "update user_visits set views=views+1 where DATE_FORMAT(month,'%Y-%m')=? and mainuser=?";  
        $q = $pdo->prepare($sql);
        $q->execute(array($thismonth,$thissessuser));
      } else {
        $sql = "insert into user_visits (month,views,mainuser) values (now(),'1',?)";  
        $q = $pdo->prepare($sql);
        $q->execute(array($thissessuser));
      }
      pdodb::disconnect();
      $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
      setcookie("visitor",$_SERVER['REMOTE_ADDR'], time()+3600, "/", ".".$domain);
    };     
  }
  public static function page_protect($next=null) {
    session_start();
    if (isset($_SESSION['HTTP_USER_AGENT']))
    {
      if ($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT']))
      {
        sessionClass::logout();
        exit;
      }
    }
    if (!isset($_SESSION['user_id']) && !isset($_SESSION['user']) )
    {
      if(isset($_COOKIE['user_id']) && isset($_COOKIE['user_key'])){
        $cookie_user_id  = inputClass::filter($_COOKIE['user_id']);
        $pdo = pdodb::connect();
        $sql = "select user_level,ckey,ctime from users where id =?";  
        $q = $pdo->prepare($sql);
        $q->execute(array($cookie_user_id));
        if($q->rowCount()>0){
          $zobj = $q->fetch(PDO::FETCH_ASSOC);
          $thisckey=$zobj['ckey']; $thisctime=$zobj['ctime'];  $thisuser_level=$zobj['user_level'];
          if( (time() - $thisctime) > 1*60*24*COOKIE_TIME_OUT) {
            sessionClass::logout();
          }
          if( !empty($thisckey) && is_numeric($_COOKIE['user_id']) && inputClass::isUserID($_COOKIE['user']) && $_COOKIE['user_key'] == sha1($thisckey)  ) {
            session_regenerate_id();   
            $_SESSION['user_id'] = $_COOKIE['user_id'];
            $_SESSION['user'] = $_COOKIE['user'];
            $_SESSION['user_level'] = $thisuser_level;
            $_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
          } else {
            sessionClass::logout();
          }
        }
        pdodb::disconnect();
        unset($zobj); unset($sql); unset($q);
      } else {
        header("Location: /mlogin");
        exit();
      }
    }
  }
  public static function logout()
  {
    session_start();
    if(isset($_SESSION['user_id']) || isset($_COOKIE['user_id'])) {
      $nowtime = new DateTime();
      $now=$nowtime->format('Y-m-d H:i').":00";
      $pdo = pdodb::connect();
      $user_ip = addslashes(htmlspecialchars($_SERVER['REMOTE_ADDR']));
      $sql = "update users set ckey= '', ctime= '' , user_online= '0', users_ip = ? , online_time = '".$now."' where id=?  OR id = ?";  
      $q = $pdo->prepare($sql);
      $q->execute(array($user_ip,$_SESSION['user_id'],$_COOKIE['user_id']));
      pdodb::disconnect();
    }      
    unset($_SESSION['user_id']);
    unset($_SESSION['user']);
    unset($_SESSION['user_level']);
    unset($_SESSION['usrpwd']);
    unset($_SESSION['HTTP_USER_AGENT']);
    unset($_SESSION["userdata"]);
    session_unset();
    session_destroy();
    $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
    setcookie("user_id", '', time()-60*60*24*COOKIE_TIME_OUT, "/", ".".$domain);
    setcookie("user", '', time()-60*60*24*COOKIE_TIME_OUT, "/", ".".$domain);
    setcookie("user_key", '', time()-60*60*24*COOKIE_TIME_OUT, "/", ".".$domain);
    header("Location: /?");
  }
  public static function getviews($thistable,$thisuser){ 
    $pdo = pdodb::connect();
    $sql = "select month,views from $thistable where mainuser=? and month>DATE_SUB(now(), INTERVAL 4 MONTH) order by id desc";
    $q = $pdo->prepare($sql);
    $q->execute(array($thisuser));
    $in==0;
    $zobj = $q->fetchAll();
    foreach($zobj as $val) {
      $in++; $content[] = $val;
    }
    return json_encode($content);
    pdodb::disconnect();
    unset($q,$sql,$val,$zobj,$content);
  }
}
$modulelist[]="Ldap controller";
  class ldap extends sessionClass{
    public static function ldapgrinfo($ds,$ldapgtree,$ldapgroup){
      $info=array();
      if (!empty($ds)) {
        $result = ldap_search($ds,$ldapgtree, "(CN=$ldapgroup*)");
        $data = ldap_get_entries($ds, $result);
        if(!empty($data[0]['description'][0])){
          return $data[0]['description'][0];
        }
      } else { return false; }
    }
    public static function ldapconn($host,$user,$pass,$ldaputree,$ldapgtree=null,$search=null,$port=389){
      $info=array();
      $ds = ldap_connect($host, $port);
      ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
      ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
      if ($ds) {
        $binddn = "uid=$user,".$ldaputree;
        $ldapbind = @ldap_bind($ds, $binddn, $pass);
        if ($ldapbind) {
          $info['success']=true;
          if(!empty($search)){
            $result = ldap_search($ds,$ldaputree, "(uid=$user*)") or die ("Error in search query: ".ldap_error($ds));
            $data = ldap_get_entries($ds, $result);
            $info['user']['uid']=$user;
            if(!empty($data[0]['displayname'][0])){ $info['user']['displayname']=$data[0]['displayname'][0]; }
            if(!empty($data[0]['mail'][0])){ $info['user']['email']=$data[0]['mail'][0]; }
            if(!empty($data[0]['street'][0])){ $info['user']['street']=$data[0]['street'][0]; }
            if(!empty($data[0]['uscitycode'][0])){ $info['user']['city']=$data[0]['uscitycode'][0]; }
            if(!empty($data[0]['telephonenumber'][0])){ $info['user']['phone']=$data[0]['telephonenumber'][0]; }
            if(!empty($ldapgtree) && !empty($data[0]['memberof'])){
              foreach($data[0]['memberof'] as $key=>$val) {
                unset($info['user']['groups']['count']);
                $group=str_replace(array(",".$ldapgtree,"cn="),"",$val);
                $info['user']['groups'][$key]=array("name"=>$group,"info"=>ldapgroupcheck($ds,$ldapgtree,$group));
              }
            }
          }
        }
        else {
          $info['error']="Cannot bind user to:".$host."<br>".ldap_error($ds);
        }
      } else { $info['error']="Cannot connect to:".$host; }
      return json_encode($info,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }
    public static function ldapsearch($host,?string $user,$ldaputree,$ldapgtree,$search,$port=389){
      $info=array();
      $ds = ldap_connect($host, $port);
      ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
      ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
      if ($ds) {
        $result = ldap_search($ds,$ldaputree, "(uid=$search*)") or die ("Error in search query: ".ldap_error($ds));
        $data = ldap_get_entries($ds, $result);
        $info['user']['uid']=$search;
        if(!empty($data[0]['displayname'][0])){ $info['user']['displayname']=$data[0]['displayname'][0]; }
        if(!empty($data[0]['mail'][0])){ $info['user']['email']=$data[0]['mail'][0]; }
        if(!empty($data[0]['street'][0])){ $info['user']['street']=$data[0]['street'][0]; }
        if(!empty($data[0]['uscitycode'][0])){ $info['user']['city']=$data[0]['uscitycode'][0]; }
        if(!empty($data[0]['telephonenumber'][0])){ $info['user']['phone']=$data[0]['telephonenumber'][0]; }
        if(!empty($ldapgtree) && !empty($data[0]['memberof'])){
          foreach($data[0]['memberof'] as $key=>$val) {
            $info['user']['groups'][$key]=str_replace(array(",".$ldapgtree,"cn="),"",$val);
          }
        }
      } else { $info['error']="Cannot connect to:".$host; }
      return json_encode($info,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    } 
    public static function ldapgroupcheck($host,?string $user,$ldaputree,$ldapgtree,$ldapgroup,$port=389){
      $info=array();
	  $filter = "(&(uid=$user*)(memberof=cn=".$ldapgroup.",".$ldapgtree."))";
      $ds = ldap_connect($host, $port);
      ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
      ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
      if ($ds) {
        $result = ldap_search($ds,$ldaputree, $filter) or die ("Error in search query: ".ldap_error($ds));
        $data = ldap_get_entries($ds, $result); 
        if(!empty($ldapgtree) && !empty($data[0]['memberof'])){
          return true;
        }
      } else { return false; }
    }
  }