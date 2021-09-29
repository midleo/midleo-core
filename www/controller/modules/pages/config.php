<?php
$modulelist["mail"]["name"]="Pages controller";
$modulelist["mail"]["system"]=true;
include_once "control_panel.php";
include_once "api.php";
include_once "pubapi.php";
include_once "env.php";
include_once "app.php";
include_once "browse.php";
include_once "templates.php";
class Class_main{
  public static function getPage(){
    global $installedapp;
    global $website;
    if($installedapp!="yes"){ header("Location: /install"); } 
    session_start();
    if($_GET["p"]="welcome"){ 
      Class_welcome::getPage();
    } elseif (isset($_SESSION['user_id']) && isset($_SESSION['user'])) { 
      header("Location: /cp/?"); 
    } elseif(isset($_SESSION['requser_id']) && isset($_SESSION['requser'])){ 
      header("Location: /console/?"); 
    }
   // else {  header("Location: /info/?");  } 
    else { Class_welcome::getPage(); }
  }
}
class Class_error{
  public static function getPage(){
    global $installedapp;
    global $website;
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0"><title>Error 404 Page not found - http://'.$_SERVER['HTTP_HOST'].'</title><meta name="robots" content="noindex,follow"/><style type="text/css">html,body{height:100%;padding:0;margin:0}body{color:#000;background-repeat:no-repeat;background:#fff;}a,a:hover,a:active{color: rgb(0, 0, 0);font-size: 22px;text-decoration: none;text-transform: uppercase;}</style></head><body><table style="width:100%;height:100%;"><tr><td style="vertical-align:middle;text-align:center;font-size:30px;font-family:Tahoma;">Page not found<br/><br/><img style="width:400px;" src="/assets/images/404-not-found.svg"><br/><br/><a href="http://'.$_SERVER['HTTP_HOST'].'" title="back to http://'.$_SERVER['HTTP_HOST'].'">back to main page</a></td></tr></table></body></html>';
  }
}
class Class_banned{
  public static function getPage(){
    global $installedapp;
    global $website;
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0"><title>Error 403 No access</title><meta name="robots" content="noindex,follow"/><style type="text/css">html,body{height:100%;padding:0;margin:0}body{color:#000;background-repeat:no-repeat;background:#fff;}a,a:hover,a:active{color: rgb(0, 0, 0);font-size: 22px;text-decoration: none;text-transform: uppercase;}</style></head><body><table style="width:100%;height:100%;"><tr><td style="vertical-align:middle;text-align:center;font-size:30px;font-family:Tahoma;">You are banned!<br/><br/><img style="width:400px;" src="/assets/images/404-not-found.svg"><br/><br/><a href="http://'.$_SERVER['HTTP_HOST'].'" title="back to http://'.$_SERVER['HTTP_HOST'].'">Please try again later</a></td></tr></table></body></html>';
  }
}
class Class_logout{
  public static function getPage($thisarray){
    sessionClass::logout();  
  }
}
class Class_reqlogout{
  public static function getPage($thisarray){
    sessionClassreq::logoutreq();
  }
}
class Class_info{
   public static function getDiagramDataById($diagramId) {
     $pdo = pdodb::connect(); 
     $sqlin="SELECT imgdata FROM config_diagrams where desid=?";
     $qin = $pdo->prepare($sqlin);
     $qin->execute(array($diagramId));
     if ($results = $qin->fetch(PDO::FETCH_ASSOC)) {
        $imageData = $results['imgdata'];
        if (!empty($imageData)) {
            return $imageData;
        }
     }
     return null;
     pdodb::disconnect();
   }
   public static  function replaceDiagramsWithImage($text) {
    return preg_replace_callback("#\[diagram=([a-z0-9]+)\]#i", function ($matches) {
      $imageData = Class_info::getDiagramDataById($matches[1]);
      if ($imageData === null) {
         return null;
      }
      return "<img style='max-width:100%;' src='{$imageData}'>";
    }, $text);
  }
  public static function getPage($thisarray){
   global $installedapp;
   global $website;
   global $maindir;
   global $page;
   global $modulelist;
   global $dbtype;
   if($installedapp!="yes"){ header("Location: /install"); } 
    $pdo = pdodb::connect(); 
    sessionClass::page_protect(base64_encode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
    $tmp["likesearch"]="";
    $data=sessionClass::getSessUserData(); foreach($data as $key=>$val){  ${$key}=$val; } 
    if(!empty($ugroups)){
        foreach($ugroups as $keyin=>$valin){
            $tmp["likesearch"].=" or accgroups like '%".$keyin."%'";
        }
    }
    $sactive=" author='".$_SESSION["user"]."'";
    $sactive.=" or public='1'".$tmp["likesearch"];
    $sactcat=" public='1'".$tmp["likesearch"];
$sql="select count(id) from knowledge_info".(!empty($sactive)?" where".$sactive:"");
$q = $pdo->prepare($sql);
$q->execute();
$blognum=$q->fetchColumn();
$forumcase=!empty($thisarray["p1"])?$thisarray["p1"]:"";
$keyws=!empty($thisarray["p2"])?$thisarray["p2"]:"";
$page = (isset($thisarray["p1"]) && $thisarray["p1"]=="pn" && !empty($keyws))? $keyws:1;
$prev = ($page - 1);
$next = ($page + 1);
$max_results = 4;
$from = abs(($page * $max_results) - $max_results);
$to = abs(($page * $max_results));
if ($forumcase=="posts") {
    $sql="SELECT t.cat_name,t.category,i.catname FROM knowledge_info t, knowledge_categories i where t.cat_latname=? and t.category=i.category";
    $q = $pdo->prepare($sql);
    $q->execute(array($keyws));
    if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
      $blogtitle=$zobj['cat_name']; $blogcategory=$zobj['category']; $blogcategoryname=$zobj['catname'];
    } else { $noresult=1;}
  }
  if($forumcase=="category"){ if(!empty($keyws)){
    $sql="SELECT catname, category FROM knowledge_categories where category=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($keyws));
    if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
        $blogcatname=$zobj['catname']; $blogcat=$zobj['category'];
    } else { $blogcatname="wrong category!"; }
  } else { $blogcatname="Category is empty!"; }}
  include "public/modules/css.php";   
  echo '</head><body class="fix-header card-no-border"><div id="main-wrapper">';
  include "public/modules/headcontentinfo.php";   
  echo '<div class="page-wrapper">'; ?>
        <div class="container-fluid">
            <?php include "public/modules/breadcrumbinfo.php"; ?>

            <?php     echo '<div class="row"><div class="col-md-10">';
      if ($forumcase=="posts") { 
  $sql="SELECT id,cat_latname,cat_name,category,cattext,catdate,views,catlikes,author,tags FROM knowledge_info where cat_latname=?".(!empty($sactive)?" and (".$sactive.")":""); 
  $q = $pdo->prepare($sql);
  $q->execute(array($keyws));
  if($zobj = $q->fetch(PDO::FETCH_ASSOC)){ ?>
            <div class="card" style="box-shadow:none;">
                <div class="card-body">
                    <h4 class="card-title"><?php echo $zobj['cat_name'];?></h4>
                    <h6 class="card-subtitle" style="margin-bottom: 0px;">Posted
                        <?php echo textClass::ago($zobj['catdate']);?></h6>
                    <br>
                    <p><?php 
        if (strpos($zobj['cattext'], 'diagram') !== false) {
          echo Class_info::replaceDiagramsWithImage($zobj['cattext']);
        } else {
           echo $zobj['cattext'];
        }
        ?></p>
                </div>
                <ul class="wall-attrs clearfix list-inline list-unstyled" style="margin-left: 10px;">
                    <li class="wa-stats"><span><i class="mdi mdi-eye"></i> <?php echo $zobj['views'];?>
                            views</span><span style="padding:0;"><a href=""
                                style="color:red;display:block;width:100%;height:100%;padding: 7px 12px;"><i
                                    class="mdi mdi-heart"></i> <?php echo $zobj['catlikes'];?> likes</a></span><span><i
                                class="mdi mdi-open-in-new"></i> <?php echo $zobj['category'];?></span></li>
                </ul>
            </div>

            <?php if(!empty($zobj['tags'])){?><br>
            <div class="blogTags">
                <?php
  $clientkeyws=$zobj['tags'];
  $kt=explode(",",$clientkeyws);
  foreach($kt as $key=>$val){ if($val<>" " and strlen($val) < 70 and strlen($val) > 0){
    $val=ltrim($val, ' ');
    $val=rtrim($val, ' ');
    echo '<a class="btn btn-light btn-sm waves-effect" style="margin-right:5px;margin-top:5px;" href="/info/tags/'.$val.'"><i class="mdi mdi-tag"></i>&nbsp;'.$val.'</a>';
  }}  ?></div><?php } ?>
            <?php
$sql="update knowledge_info set views=views+1 where id=?"; 
$q = $pdo->prepare($sql);
$q->execute(array($zobj['id']));
  } else { echo "<div class='alert alert-light'>No result found</div>";} $disnav="1"; 
} else {                                                                                                                                                                                                                                                 
  if(isset($_POST['searchkey'])){
    $sql="SELECT * FROM knowledge_info where (lower(cat_name) like ? or cattext like ?) ".(!empty($sactive)?" and".$sactive:"")." order by id desc";
    $q = $pdo->prepare($sql);
    $q->execute(array("%".strtolower(htmlspecialchars($_POST['searchkey']))."%","%".htmlspecialchars($_POST['searchkey'])."%"));
  } elseif($forumcase=="tags"){
    if(!empty($keyws)){
      $clientkeyws=ltrim($keyws);
      $clientkeyws=rtrim($clientkeyws);
      $kt=explode(" ",$clientkeyws);
      foreach($kt as $key=>$val){ if($val<>" " and strlen($val) > 0){$qt .= " tags like '%$val%' or ";}}
      $qt=substr($qt,0,(strLen($qt)-3));
      $sql="SELECT id,cat_latname,cat_name,category,cattext,catdate,views,catlikes,author,tags FROM knowledge_info where $qt ".(!empty($sactive)?" and".$sactive:"")." order by id desc";
    } else { 
     $sql="SELECT id,cat_latname,cat_name,category,cattext,catdate,views,catlikes,author,tags FROM knowledge_info ".(!empty($sactive)?" where".$sactive:"")." order by id desc";
    }
    $q = $pdo->prepare($sql);
    $q->execute(array());  
  } elseif($forumcase=="category"){
    $sql="SELECT id,cat_latname,cat_name,category,cattext,catdate,views,catlikes,author,tags FROM knowledge_info where category=? ".(!empty($sactcat)?" and ".$sactcat:"")." order by id desc";
    $q = $pdo->prepare($sql);
    $q->execute(array($keyws));
  } else {
    $sql="SELECT count(id) FROM knowledge_info".(!empty($sactive)?" where".$sactive:"");
    $q = $pdo->prepare($sql);
    $q->execute();                              
    $total_results = $q->fetchColumn();
    $total_pages = ceil($total_results / $max_results);
    if($dbtype=='postgresql'){
        $sql="SELECT * FROM knowledge_info ".(!empty($sactive)?" where".$sactive:"")." order by id desc offset ".$from." LIMIT ".$max_results;
    } else {
        $sql="SELECT * FROM knowledge_info ".(!empty($sactive)?" where".$sactive:"")." order by id desc LIMIT ".$from.",".$max_results;
    }
    $q = $pdo->prepare($sql);
    $q->execute();
  }
  if($zobj = $q->fetchAll()){
    foreach($zobj as $val) {
      ?>
            <div class="card waves-effect" style="display:block;box-shadow:none;"
                onclick="location.href='/info/posts/<?php echo $val['cat_latname'];?>'">
                <div class="card-body">
                    <h4 class="card-title"><?php echo $val['cat_name'];?></h4>
                    <h6 class="card-subtitle" style="margin-bottom: 0px;">Posted
                        <?php echo textClass::ago($val['catdate']);?></h6>
                    <br>
                    <p><?php echo strip_tags(textClass::word_limiter($val['cattext'],120,400));?></p>
                </div>


                <ul class="wall-attrs clearfix list-inline list-unstyled blogstat" style="margin-left: 10px;">
                    <li class="wa-stats">
                        <span><i class="mdi mdi-eye"></i> <?php echo $val['views'];?> views</span>
                        <span style="color:red;"><i class="mdi mdi-heart"></i> <?php echo $val['catlikes'];?>
                            likes</span>
                        <span><i class="mdi mdi-open-in-new"></i> <?php echo $val['category'];?></span>
                    </li>
                </ul>
            </div>
            <?php }} else { ?> <div class="card" style="box-shadow:none;">
                <div class="card-body card-padding">
                    <div class="text-center">
                        <p class="lead">There are no posts in this category</p>
                        <p class="lead"><a href="/cpinfo">Create one</a></p>
                    </div>
                </div>
            </div><?php } ?>
            <?php if($blognum>0){?>
            <nav style="padding-top:10px">
                <ul class="pagination">
                    <?php if($page > 1)
{ ?>
                    <li class="page-item"><a class="page-link" href="/info/pn/<?php echo $prev;?>">Previous</a></li>
                    <?php }
for($i = 1; $i <= $total_pages; $i++)
    {
      if(($page) == $i)
      { ?>
                    <li class="page-item active"><a class="page-link"
                            href="/info/pn/<?php echo $i;?>"><?php echo $i;?></a></li>
                    <?php  }
      else
      { ?>
                    <li class="page-item"><a class="page-link" href="/info/pn/<?php echo $i;?>"><?php echo $i;?></a>
                    </li>
                    <?php }
    }
if($page < $total_pages)
{ ?>
                    <li class="page-item"><a class="page-link" href="/info/pn/<?php echo $next;?>">Next</a></li>
                    <?php } ?>
                </ul>
            </nav>
            <?php } 
      }    


   echo '</div><div class="col-md-2">'; 



   include "blogsidebar.php";




   echo '<div class="stickyside h2menu"><div class="list-group h2-menu" id="top-menu"></div>   </div></div></div></div></div></div></div>';
   
   
   
   include "public/modules/footer.php";
   include "public/modules/js.php";
   include "public/modules/template_end.php";
   if(!empty($text)){unset($text);}
    echo '</body></html>';
   //end function
  }
}
class Class_mregister{
  public static function getPage(){
    global $installedapp;
    global $website;
    global $page;
    global $modulelist;
    global $appver;
    $nowtime = new DateTime();
    $now=$nowtime->format('Y-m-d H:i').":00";
    if($website["registration"]!=1){ header("Location: /mlogin"); }
    if($installedapp!="yes"){ header("Location: /install"); }
  session_start();
  $err = array();
  $msg = array();
  $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
  $pdo = pdodb::connect();
  if($_SESSION['bannnow']>2 || $_COOKIE['bannnow']>2){
    setcookie("bannnow",$_SESSION['bannnow'], time()+1*60*24*COOKIE_TIME_OUT, "/", ".".$domain);
    header("Location: /banned");
  }
  foreach($_GET as $key => $value) {
    $get[$key] = inputClass::filter($value);
  }
  if(isset($_POST["doRegister"])){
    foreach($_POST as $key => $value) {
      $data[$key] = inputClass::filter($value);
    }
 if($_SESSION['rand_code']!=$data["captcha"]){
   $err[]="Wrong number in captcha!";
 } else {
  $sql="select count(id) from users where email=?";
  $q = $pdo->prepare($sql);
  $q->execute(array(strtolower($data["usr_email"])));
  if($q->fetchColumn()>0){
    $err[]="User already exist!";
  } else {
    $temparr=array();
    $temparr["uid"]=md5(uniqid(microtime()).$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);
    $temparr["pwd"]= inputClass::PwdHash($data["pwdnew"]);
    $temparr["mainuser"] = textClass::cyr2lat($data["usr_name"]);
    $temparr["mainuser"] = textClass::strreplace($temparr["mainuser"],"_").".".textClass::getRandomStr(4);
    $sql="insert into users (uuid,mainuser,email,pwd,fullname,ugroups) values (?,?,?,?,?,?)";
    $q = $pdo->prepare($sql);
    if($q->execute(array($temparr["uid"],$temparr["mainuser"],strtolower($data["usr_email"]),$temparr["pwd"],$data["usr_name"],"[]"))){
      $msg[]="Success";

      if(!empty($data['authtype'])){





      } else {
        $sql="SELECT id,pwd,mainuser,active,user_level FROM users WHERE email=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($data["usr_email"]));
        $zobj = $q->fetch(PDO::FETCH_ASSOC);
        session_start();
        session_regenerate_id (true);
          $_SESSION['user_id']= $zobj['id'];
          $_SESSION['user_level']= $zobj['user_level'];  
          $_SESSION['user'] = $zobj['mainuser'];
          $_SESSION['usrpwd']=documentClass::encryptIt($data['pwd']);
          $_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
          $stamp = time();
          $ckey = inputClass::GenKey();
          $sql="update users set ctime=?, ckey = ?, user_online = '1', online_time = '".$now."' where id=?";
          $q = $pdo->prepare($sql);
          $q->execute(array($stamp,$ckey,$zobj['id']));
          setcookie("user_id", $_SESSION['user_id'], time()+1*60*24*COOKIE_TIME_OUT, "/", ".".$domain);
          setcookie("user",$_SESSION['user'], time()+1*60*24*COOKIE_TIME_OUT, "/", ".".$domain);
          if(!empty($_GET['next'])){ header("Location: ".base64_decode(htmlspecialchars($_GET["next"]))); } else { header("Location: /cp/?"); }
      }
    } else {
      $err[]="Problem occur. Please try again";
    }
    

  }
  
 }


  }
  include "public/modules/css.php";    ?>
            </head>

            <body style="background-image: url('/assets/images/login_bg.jpg');background-repeat: no-repeat;background-size: cover;">
                <section id="wrapper">
                    <div class="login-register">
                        <div class="row justify-content-center">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <form class="form-material form-horizontal" id="loginform"
                                            action="" method="post">

                                            <div style="position: absolute;right: 10px;top: 10px;">
                                                <span class="text-muted">v. <?php echo $appver;?></span>
                                            </div>
                                            <div class="text-center">
                                                <img data-bs-toggle="tooltip" src="/assets/images/midleo-logo-sq-dark.svg"
                                                    alt="Midleo CORE"
                                                    title="Midleo CORE" class="light-logo"
                                                    style="width:100px;" />
                                                <h3 class="p-2 rounded-title mb-3"></h3>
                                            </div>
                                            <?php $sql="select ldapserver,ldapinfo from ldap_config";
                        $q = $pdo->prepare($sql);              
                       $q->execute();      
                       if($zobj = $q->fetchAll()){?>
                                            <div class="form-group mb-3">
                                                <select name="authtype" id="authtype" class="form-control">
                                                    <option value="">Authentication provider</option>
                                                    <?php foreach($zobj as $val) { ?><option
                                                        value="<?php echo $val['ldapserver'];?>">
                                                        <?php echo $val['ldapinfo'];?></option><?php } ?>
                                                    <option value="">Default</option>
                                                </select>
                                                
                                            </div>
                                            <?php } ?>
                                            <div class="form-group mb-1">
                                                <input name="usr_name" id="usr_name" class="form-control" type="text"
                                                    required="" placeholder="Your name">
                                            </div>
                                            <div class="form-group mb-1">
                                                <input name="usr_email" id="username" class="form-control" type="text"
                                                    required="" placeholder="Email address">
                                            </div>
                                            <div class="form-group mb-1">
                                                <input type="password" id="pwdnew" name="pwdnew" class="form-control " placeholder="Password">
                                            </div>
                                            <div class="form-group mb-1">
                                                <input class="form-control" type="password" id="pwdnew2" name="pwdnew2"
                                                    equalto="#pwdnew" placeholder="Confirm password">
                                            </div>
                                            <div class="form-group mb-1">
                                                <div class="col-xs-12">
                                                    <div style="display: block;" id="pass-strength-result"><i
                                                            class='mdi mdi-lock-open-outline'></i>&nbsp;Password
                                                        Strenght</div>
                                                </div>
                                            </div>
                                           
                                            <div class="form-group row">
                                                <div class="col-md-6 text-end"><img src="/pubapi/captcha" /></div>
                                                <div class="col-md-6">
                                                    <input name="captcha" id="captcha" class="form-control" type="text"
                                                        required="" placeholder="write the number">
                                                                                                    </div>
                                            </div>
                                            <div class="form-group text-center mt-3">
                                                <div class="col-xs-12">
                                                    <button name="doRegister"
                                                        class="btn m-btn-theme text-uppercase waves-effect waves-light"
                                                        type="submit"><svg class="midico midico-outline">
                                                            <use href="/assets/images/icon/midleoicons.svg#i-logout"
                                                                xlink:href="/assets/images/icon/midleoicons.svg#i-logout" />
                                                        </svg>&nbsp;Register</button>
                                                </div>
                                            </div>
                                            <div class="form-group mb-0">
                                                <div class="col-sm-12 text-center">
                                                    You have an account? <a href="/mlogin"
                                                        class="text-info ml-1"><b>Sign In</b></a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>


                <!-- Older IE warning message -->
                <!--[if lt IE 9]>
  <div class="ie-warning">
  <h1 class="c-red">Warning!!</h1>
  <p>You are using an outdated version of Internet Explorer, please upgrade <br/>to any of the following web browsers to access this website.</p>
  <div class="iew-container">
  <ul class="iew-download">
  <li><a href="http://www.google.com/chrome/"><div>Chrome</div></a></li>
  <li><a href="https://www.mozilla.org/en-US/firefox/new/"><div>Firefox</div></a></li>
  <li><a href="http://www.opera.com"><div>Opera</div></a></li>
  <li><a href="https://www.apple.com/safari/"><div>Safari</div></a></li>
  <li><a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie"><div>IE (New)</div></a></li>
</ul>
</div><p>Sorry for the inconvenience!</p></div>
  <![endif]--><?php
include "public/modules/footer.php";
    include "public/modules/js.php"; ?>
                <script type='text/javascript'>
                (function(a) {
                    function b() {
                        var e = a("#pwdnew").val(),
                            d = a("#username").val(),
                            c = a("#pwdnew2").val(),
                            f;
                        a("#pass-strength-result").removeClass("short bad good strong");
                        if (!e) {
                            a("#pass-strength-result").html(pwsL10n.empty);
                            return
                        }
                        f = passwordStrength(e, d, c);
                        switch (f) {
                            case 2:
                                a("#pass-strength-result").addClass("bad").html(pwsL10n.bad);
                                break;
                            case 3:
                                a("#pass-strength-result").addClass("good").html(pwsL10n.good);
                                break;
                            case 4:
                                a("#pass-strength-result").addClass("strong").html(pwsL10n.strong);
                                break;
                            case 5:
                                a("#pass-strength-result").addClass("short").html(pwsL10n.mismatch);
                                break;
                            default:
                                a("#pass-strength-result").addClass("short").html(pwsL10n["short"])
                        }
                    }
                    a(document).ready(function() {
                        a("#pwdnew").val("").keyup(b);
                        a("#pwdnew2").val("").keyup(b);
                        a("#pass-strength-result").show();
                        a(".color-palette").click(function() {
                            a(this).siblings('input[name="admin_color"]').prop("checked", true)
                        });
                        a("#first_name, #last_name, #nickname").blur(function() {
                            var c = a("#display_name"),
                                e = c.find("option:selected").attr("id"),
                                f = [],
                                d = {
                                    display_nickname: a("#nickname").val(),
                                    display_username: a("#username").val(),
                                    display_firstname: a("#first_name").val(),
                                    display_lastname: a("#last_name").val()
                                };
                            if (d.display_firstname && d.display_lastname) {
                                d.display_firstlast = d.display_firstname + " " + d
                                .display_lastname;
                                d.display_lastfirst = d.display_lastname + " " + d.display_firstname
                            }
                            a("option", c).remove();
                            a.each(d, function(i, g) {
                                var h = g.replace(/<\/?[a-z][^>]*>/gi, "");
                                if (d[i].length && a.inArray(h, f) == -1) {
                                    f.push(h);
                                    a("<option />", {
                                        id: i,
                                        text: h,
                                        selected: (i == e)
                                    }).appendTo(c)
                                }
                            })
                        })
                    })
                })(jQuery);
                var pwsL10n = {
                    empty: "<i class='mdi mdi-lock-open-outline'></i>&nbsp;Password Strenght",
                    short: "<i class='mdi mdi-lock-open-outline'></i>&nbsp;Too Short",
                    bad: "<i class='mdi mdi-lock-open'></i>&nbsp;Bad",
                    good: "<i class='mdi mdi-lock'></i>&nbsp;Good",
                    strong: "<i class='mdi mdi-shield-lock-outline'></i>&nbsp;Strong",
                    mismatch: "<i class='mdi mdi-lock-question'></i>&nbsp;Mismatch"
                };
                try {
                    convertEntities(pwsL10n);
                } catch (e) {};
                </script>
                <script src="/assets/js/password-strength-meter.js" type="text/javascript"></script> <?php
    include "public/modules/template_end.php";
    echo '</body></html>';


  }
}
class Class_mlogin{
  public static function getPage(){
    global $installedapp;
    global $website;
    global $page;
    global $modulelist;
    global $appver;
    global $dbtype;
    if($installedapp!="yes"){ header("Location: /install"); }
  session_start();
  $err = array();
  $msg = array();
  $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
  $pdo = pdodb::connect();
  $nowtime = new DateTime();
  $now=$nowtime->format('Y-m-d H:i').":00";
 // if($_SESSION['bannnow']>2 || $_COOKIE['bannnow']>2){
 //   setcookie("bannnow",$_SESSION['bannnow'], time()+1*60*24*COOKIE_TIME_OUT, "/", ".".$domain);
 //   header("Location: /banned");
 // }
  foreach($_GET as $key => $value) {
    $get[$key] = inputClass::filter($value);
  }
  if(isset($_POST['doLogin']))
  {
    foreach($_POST as $key => $value) {
      $data[$key] = inputClass::filter($value);
    }
    if(!empty($data['authtype'])){
      $sql="select * from ldap_config where ldapserver=?";
        $q = $pdo->prepare($sql);
        $q->execute(array(strtolower($data['authtype'])));
        $zobj = $q->fetch(PDO::FETCH_ASSOC);      
      if($ldap=ldap::ldapconn($zobj['ldapserver'],$data['usr_email'],$data['pwd'],$zobj['ldaptree'],"",strtolower($data['usr_email']),$zobj['ldapport'])){
        $_SESSION['ldapuser']=$ldap;
        $ldap=json_decode($ldap,true);
        if(!empty($ldap['error'])){
          $err[]=$ldap['error'];
        } else {
          if(!empty($ldap['user']['email'])){
            $sql="SELECT id,pwd,mainuser,active,user_level FROM users WHERE mainuser=? and ldapserver=?";
            $q = $pdo->prepare($sql);
            $q->execute(array(strtolower($data['usr_email']),$data['authtype']));
            session_regenerate_id (true);
            if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
              $_SESSION['user_id']= $zobj['id'];
              $_SESSION['user_level']= $zobj['user_level'];  
              $_SESSION['user'] = $zobj['mainuser'];
              $_SESSION['usrpwd']=documentClass::encryptIt($data['pwd']);
              $_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
              $stamp = time();
              $ckey = inputClass::GenKey();
              $sql="update users set ctime=?, ckey = ?, user_online = '1', online_time = '".$now."' where id=?";
              $q = $pdo->prepare($sql);
              $q->execute(array($stamp,$ckey,$zobj['id']));
              setcookie("user_id", $_SESSION['user_id'], time()+1*60*24*COOKIE_TIME_OUT, "/", ".".$domain);
              setcookie("user",$_SESSION['user'], time()+1*60*24*COOKIE_TIME_OUT, "/", ".".$domain);
              if(!empty($_GET['next'])){ header("Location: ".base64_decode(htmlspecialchars($_GET["next"]))); } else { header("Location: /?"); }
            } else {
                $err[] = "No such user";
                if($website["registration"]==1){
                  header("Location: /mregister");
                }
            }
          } else {
            $err[] = "Error taking data for the user from ldap";
          }
        }
      } else {
        $err[] = "Cannot authenticate with ldap server";
      }
    } else {
      $email = strtolower(htmlspecialchars($_POST['usr_email']));
      $pass = $data['pwd'];
      if (strpos($email,'@') === false) {
        $sql="SELECT id,pwd,mainuser,active,user_level FROM users WHERE mainuser=?";
      } else {
        $sql="SELECT id,pwd,mainuser,active,user_level FROM users WHERE email=?";
      }
      $q = $pdo->prepare($sql);
      $q->execute(array($email));
      if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
        if (password_verify($pass, $zobj['pwd'])) {  
          session_regenerate_id (true);
          $_SESSION['user_id']= $zobj['id'];
          $_SESSION['user_level']= $zobj['user_level'];  
          $_SESSION['user'] = $zobj['mainuser'];
          $_SESSION['usrpwd']=documentClass::encryptIt($data['pwd']);
          $_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
          $stamp = time();
          $ckey = inputClass::GenKey();
          $sql="update users set ctime=?, ckey = ?, user_online = '1', online_time = '".$now."' where id=?";
          $q = $pdo->prepare($sql);
          $q->execute(array($stamp,$ckey,$zobj['id']));
          setcookie("user_id", $_SESSION['user_id'], time()+1*60*24*COOKIE_TIME_OUT, "/", ".".$domain);
          setcookie("user",$_SESSION['user'], time()+1*60*24*COOKIE_TIME_OUT, "/", ".".$domain);
          if(!empty($_GET['next'])){ header("Location: ".base64_decode(htmlspecialchars($_GET["next"]))); } else { header("Location: /cp/?"); }
        } else {
          $err[] = "Wrong password";  
          $sql="insert into user_failure(fail_type,mainuser,what,ip) values('loginpass',?,?,?)";
          $q = $pdo->prepare($sql);
          $q->execute(array($email,$pass,$_SERVER['REMOTE_ADDR']));
          $_SESSION['bannnow']=$_SESSION['bannnow']+1;
        }
      } else {
          $err="No such user";
          if($website["registration"]==1){
            header("Location: /mregister");
          }
      }
    }
  }
  if(isset($_POST['doForgot'])){  
    foreach($_POST as $key => $value) {
      $data[$key] = inputClass::filter($value);
    }
    if(!inputClass::isEmail($data['usr_email'])) {
      $err[] = "This mail is not valid!&nbsp;";
    }
    $usr_email = $data['usr_email'];
    if(!empty($usr_email)){
      $sql="select id, mainuser, fullname from users where email=?";
      $q = $pdo->prepare($sql);
      $q->execute(array(strtolower($usr_email)));
      if ($zobj = $q->fetch(PDO::FETCH_ASSOC)){
        $new_pwd = inputClass::GenPwd();
        $pwd_reset = inputClass::PwdHash($new_pwd);
        $sql="update users set pwd=? WHERE id=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($pwd_reset,$zobj['id']));
        send_mailfinal("noreply@".$_SERVER['HTTP_HOST'],$usr_email,"password reset for Midleo","Hello,<br>You requested to change your password","<br><br>If this request was not done by you, please contact our support team!",$body=array("email"=>$usr_email,"password"=>$new_pwd,"username"=>$zobj['mainuser'],"fullname"=>$zobj['fullname']),"full");
        $msg[] = "Your new password was sent.&nbsp;<i class=\'mdi mdi-loading iconspin\'></i>";             
      } else {
          $err[]="No such user";
      }
    } 
  }
include "public/modules/css.php";    ?>
                </head>

                <body style="background-image: url('/assets/images/login_bg.jpg');background-repeat: no-repeat;background-size: cover;">
                    <section id="wrapper">
                        <div class="login-register">
                            <div class="row justify-content-center">
                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <form class="form-horizontal" id="loginform" action=""
                                                method="post">
                                                <div style="position: absolute;right: 10px;top: 10px;">
                                                    <span class="text-muted">v.
                                                        <?php echo !empty($appver)?$appver:"initial";?></span>
                                                </div>
                                                <div class="text-center">
                                                    <img data-bs-toggle="tooltip"
                                                        src="/assets/images/midleo-logo-sq-dark.svg"
                                                        alt="Midleo CORE"
                                                        title="Midleo CORE" class="light-logo"
                                                        style="width:120px;" />
                                                    <h3 class="p-2 rounded-title mb-3"></h3>
                                                </div>
                                                <?php $sql="select ldapserver,ldapinfo from ldap_config";
                        $q = $pdo->prepare($sql);              
                       $q->execute();      
                       if($zobj = $q->fetchAll()){?>
                                                <div class="form-group mb-2">
                                                <label for="authtype">Authentication provider</label>
                                                    <select name="authtype" id="authtype" class="form-control">
                                                        <?php foreach($zobj as $val) { ?><option
                                                            value="<?php echo $val['ldapserver'];?>">
                                                            <?php echo $val['ldapinfo'];?></option><?php } ?>
                                                        <option value="">Default</option>
                                                    </select>
                                                </div>
                                                <?php } ?>
                                                <div class="form-group mb-2">
                                                    <input name="usr_email" class="form-control" type="text" required=""
                                                        id="usr_email" placeholder="Username / Email address">
                                                  
                                                </div>
                                                <div class="form-group mb-2">
                                                    <input name="pwd" class="form-control" type="password" id="pwd"
                                                        required="" placeholder="Password">
                                                    
                                                </div>
                                                <div class="form-group">
                                                    <div class="d-flex no-block align-items-center">
                                                        <div class="ml-auto">
                                                            <a href="javascript:void(0)" id="to-recover"
                                                                class="text-muted"><i
                                                                    class="mdi mdi-lock-alert mr-1"></i> Forgot pwd?</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group text-center mt-3">
                                                    <button name="doLogin"
                                                        class="btn m-btn-theme text-uppercase waves-effect btn-block waves-light"
                                                        type="submit"><svg class="midico midico-outline">
                                                            <use href="/assets/images/icon/midleoicons.svg#i-login"
                                                                xlink:href="/assets/images/icon/midleoicons.svg#i-login" />
                                                        </svg>&nbsp;Log In</button>
                                                </div>
                                                <?php if($website["registration"] && $website["registration"]==1){?>
                                                <div class="form-group mb-0">
                                                    <div class="col-sm-12 text-center">
                                                        Don't have an account? <a href="/mregister"
                                                            class="text-info ml-1"><b>Sign Up</b></a>
                                                    </div>
                                                </div>
                                                <?php } ?>
                                            </form>
                                            <form class="form-horizontal " id="recoverform" action=""
                                                method="post">
                                                <div style="position: absolute;right: 10px;top: 10px;">
                                                    <span class="text-muted">v.
                                                        <?php echo !empty($appver)?$appver:"initial";?></span>
                                                </div>
                                                <div class="text-center">
                                                    <img data-bs-toggle="tooltip"
                                                        src="/assets/images/midleo-logo-sq-dark.svg"
                                                        alt="Midleo CORE"
                                                        title="Midleo CORE" class="light-logo"
                                                        style="width:120px;" />
                                                    <h3 class="p-2 rounded-title mb-3"></h3>
                                                </div>
                                                <div class="form-group ">
                                                    <div class="col-xs-12">
                                                        <p class="text-muted" style="line-height:initial;">Enter your Email and instructions will be
                                                            sent to you! </p>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-2">
                                                <label for="email_reset">Email</label>

                                                    <input class="form-control" name="usr_email" id="email_reset"
                                                        type="text" required="">
                                                </div><br>
                                                <div class="form-group text-center mt-3">
                                                    <div class="col-xs-12">
                                                        <button
                                                            class="btn m-btn-theme text-uppercase waves-effect btn-block waves-light"
                                                            name="doForgot" type="submit">Reset</button>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-0">
                                                    <div class="col-sm-12 text-center">
                                                        <a href="/mlogin" class="text-info ml-1"><b>Log In</b></a>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>


                    <!-- Older IE warning message -->
                    <!--[if lt IE 9]>
  <div class="ie-warning">
  <h1 class="c-red">Warning!!</h1>
  <p>You are using an outdated version of Internet Explorer, please upgrade <br/>to any of the following web browsers to access this website.</p>
  <div class="iew-container">
  <ul class="iew-download">
  <li><a href="http://www.google.com/chrome/"><div>Chrome</div></a></li>
  <li><a href="https://www.mozilla.org/en-US/firefox/new/"><div>Firefox</div></a></li>
  <li><a href="http://www.opera.com"><div>Opera</div></a></li>
  <li><a href="https://www.apple.com/safari/"><div>Safari</div></a></li>
  <li><a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie"><div>IE (New)</div></a></li>
</ul>
</div><p>Sorry for the inconvenience!</p></div>
  <![endif]--><?php
    include "public/modules/footer.php";
    include "public/modules/js.php"; 
    echo '<script type="text/javascript">localStorage.clear();</script>';
    include "public/modules/template_end.php";
    echo '</body></html>';
    
    
  }
}
class Class_profile{
  public static function getPage($thisarray){
    global $installedapp;
    global $website;
	  global $page;
    global $modulelist;
    global $gracclist;
    global $accrights;
    global $maindir;
    if($installedapp!="yes"){ header("Location: /install"); }
    sessionClass::page_protect(base64_encode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
    $err = array();
    $msg = array();
    $pdo = pdodb::connect();
    if(isset($_POST['edinf'])){
    $sql="update users set fullname=?,email=?, phone=?,utitle=?,user_online_show=?,user_activity_show=? where mainuser=?";
    $q = $pdo->prepare($sql);
    $q->execute(array(htmlspecialchars($_POST['usname']),htmlspecialchars($_POST['usemail']),htmlspecialchars($_POST['usphone']),htmlspecialchars($_POST['ustitle']),htmlspecialchars($_POST['user_online_show']),htmlspecialchars($_POST['user_activity_show']),$_SESSION['user']));
    $msg[]='user info updated successfully';
  }
if(isset($_POST['addfile'])){
    if(!empty(htmlspecialchars($_POST["avatarid"]))){
      $usersessavatar=htmlspecialchars($_POST["avatarid"]);
      if (file_exists($usersessavatar)){ unlink($usersessavatar);};
      if (file_exists("../".$usersessavatar)){ unlink("../".$usersessavatar);};
    }
    $msg[]=imageClass::resizeavatar("ufile");  
  }
  if(isset($_POST['delavatar'])){
    $sql="update users set avatar='' where mainuser=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($_SESSION['user']));
    $usersessavatar=htmlspecialchars($_POST["avatarid"]);
    if (file_exists($maindir.$usersessavatar)){ unlink($maindir.$usersessavatar);};
    if (file_exists("../".$usersessavatar)){ unlink("../".$usersessavatar);};
    $err[]='Avatar deleted.';
  }
$data=sessionClass::getSessUserData(); foreach($data as $key=>$val){  ${$key}=$val;  }
if(isset($_POST['changepass'])){
    $q=gTable::read("users","pwd"," where id='".$_SESSION['user_id']."'");
    $zobj = $q->fetch(PDO::FETCH_ASSOC);
    if(password_verify($_POST['pwd'], $zobj["pwd"]))
    { $newsha1 = inputClass::PwdHash($_POST['pwdnew']);  
      $sql="update users set pwd='$newsha1' where id=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($_SESSION['user_id']));
      $msg[]='Password was changed';
     } else {
       $err[]='Old password is wrong'; }
  }
    include "public/modules/css.php";
   echo '</head><body class="card-no-border"> <div id="main-wrapper">';
   $breadcrumb["text"]="Profile settings";
    include "public/modules/headcontent.php";
     echo '<div class="page-wrapper"><div class="container-fluid">';
    
    include "public/modules/breadcrumb.php"; ?>


                    <div class="modal" id="chgpic" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog" style="width:380px;">
                            <div class="modal-content">
                                <form enctype="multipart/form-data" method="post" action="">
                                    <div class="modal-header">Upload avatar</div>
                                    <div class="modal-body form-horizontal form-material">
                                        <div class="image-editor text-center">
                                            <button type="button" id="fileupload" onClick="getFile('tufile')"
                                                class="btn btn-light btn-sm">upload</button>
                                            <div style='height: 0px;width: 0px; overflow:hidden;'><input type="file"
                                                    class="cropit-image-input" name="tufile" id="tufile"
                                                    onChange="sub(this,'fileupload')"
                                                    accept=".bmp, .gif, .jpeg, .jpg, .png, .tif, .tiff, image/jpeg"
                                                    size="1" /></div>
                                            <div class="cropit-preview profileprev" style="margin: 10px auto;">
                                            </div>
                                            <div class="image-size-label">Resize image</div>
                                            <input type="range" class="cropit-image-zoom-input" id="input-slider">
                                            <input type="hidden" name="ufile" class="ufile" />
                                            <div class="row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-6">
                                                    <button type="button"
                                                        class="btn btn-light btn-sm prevbutton">View</button>
                                                </div>
                                                <div class="col-md-3"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" name="avatarid" value="<?php echo $uavatar;?>">
                                        <button type="submit" class="btn btn-info btn-sm uploaddata"
                                            name="addfile">Save</button>&nbsp;&nbsp;&nbsp;
                                        <button type="button" class="btn btn-danger btn-sm"
                                            data-bs-dismiss="modal">Cancel</button>
                                    </div>
                            </div>
                            </form>
                        </div>
                    </div>

                    <div class="row"> 
                        <div class="col-lg-4 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Avatar</h4>
                                </div>
                                <div class="card-body">
                                    <div class="mt-4 text-center">
                                        <img class="img img-fluid rounded-circle"
                                            src="<?php echo !empty($uavatar)?$uavatar:"/assets/images/avatar.svg";?>"
                                            alt="" width="150">
                                        <h4 class="card-title mt-2"><?php echo $usname;?></h4>
                                        <h6 class="card-subtitle"><?php echo $usemail;?></h6>
                                        <div class="row text-center justify-content-md-center">
                                            <?php if(!empty($uavatar)){?> <form enctype="multipart/form-data"
                                                method="post" action=""><input type="hidden" name="avatarid"
                                                    value="<?php echo $uavatar;?>"><?php } ?>
                                                <div class="btn-group">
                                                    <button type="button" data-bs-toggle="modal" data-bs-target="#chgpic"
                                                        class="btn btn-info waves-effect waves-dark btn-sm">
                                                        <i class="mdi mdi-camera"></i> <span
                                                            class="hidden-xs">Upload</span>
                                                    </button>
                                                    <?php if(!empty($uavatar)){?><button type="submit"
                                                        class="btn btn-danger btn-sm waves-effect waves-dark"
                                                        name="delavatar"><svg class="midico midico-outline">
                                                            <use href="/assets/images/icon/midleoicons.svg#i-x"
                                                                xlink:href="/assets/images/icon/midleoicons.svg#i-x" />
                                                        </svg>&nbsp;Delete</button><?php } ?>
                                                </div>
                                                <?php if(!empty($uavatar)){?>
                                            </form><?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Profile info</h4>
                                </div>
                                <ul class="nav nav-tabs profile-tab" role="tablist">
                                    <li class="nav-item"> <a class="nav-link active waves-effect waves-dark"
                                            data-bs-toggle="tab" href="#profile" role="tab">Profile</a> </li>
                                    <li class="nav-item"> <a class="nav-link waves-effect waves-dark" data-bs-toggle="tab"
                                            href="#security" role="tab">Security</a> </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="profile" role="tabpanel">
                                        <div class="card-body">
                                            <form class="form-material " method="post" action="">
                                                <div class="form-group mt-3">
                                                <label>Name</label>
                                                    <input type="text" name="usname" value="<?php echo $usname;?>"
                                                        class="form-control">
                                                </div>
                                                <div class="form-group mt-3">
                                                <label>Email</label>
                                                    <input type="text" class="form-control" name="usemail"
                                                        value="<?php echo $usemail;?>">
                                                </div>
                                                <div class="form-group mt-3">
                                                <label>Phone</label>
                                                    <input type="text" name="usphone" value="<?php echo $userphone;?>"
                                                        class="form-control">
                                                </div>
                                                <div class="form-group mt-3">
                                                <label>Title</label>
                                                    <input type="text" name="ustitle" value="<?php echo $usertitle;?>"
                                                        class="form-control">
                                                    
                                                </div>
                                                <div class="form-group mt-3">
                                                <label>User Groups</label>
                                                    <input type="text"
                                                        value="<?php if($ugroups){ foreach($ugroups as $key=>$val){ echo $val.", "; }} echo $accrights[$_SESSION["user_level"]]; ?>"
                                                        class="form-control" disabled>
                                                    
                                                </div>
                                                <div class="form-group mt-3">
                                                <label>Access groups</label>
                                                    <textarea class="form-control"
                                                        disabled><?php if($acclist){?><?php foreach($acclist as $key=>$val){ echo $gracclist[$key].", "; } } else { echo "Not access yet"; } ?></textarea>
                                                    
                                                </div>

                                                <div class="form-group mt-3">
                                                    <input type='hidden' value='0' name='user_online_show'>
                                                    <input type="checkbox" value="1" name="user_online_show" id="onlsh"
                                                        class="material-inputs filled-in chk-col-blue"
                                                        <?php if($user_online_show==1){?>checked="checked" <?php } ?> />
                                                    <label class="chbl" for="onlsh">Show Online status</label>
                                                </div>
                                                <div class="form-group mt-1">
                                                    <input type='hidden' value='0' name='user_activity_show'>
                                                    <input type="checkbox" value="1" name="user_activity_show"
                                                        id="actsh" class="material-inputs filled-in chk-col-blue"
                                                        <?php if($user_activity_show==1){?>checked="checked"
                                                        <?php } ?> />
                                                    <label class="chbl" for="actsh">Show My activity</label>
                                                </div>
                                                <div class="form-group ">
                                                    <br><br>
                                                    <button type="submit" name="edinf"
                                                        class="btn btn-info waves-effect waves-dark"><svg
                                                            class="midico midico-outline">
                                                            <use href="/assets/images/icon/midleoicons.svg#i-save"
                                                                xlink:href="/assets/images/icon/midleoicons.svg#i-save" />
                                                        </svg>&nbsp;Save</button>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="security" role="tabpanel">
                                        <div class="card-body">
                                            <form class="form-material " method="post" action="">

                                                <div class="form-group mt-3">
                                                <label>Old password</label>
                                                    <input id="pwd" type="password" name="pwd" class="form-control"
                                                        required>
                                                   
                                                </div>
                                                <div class="form-group mt-3">
                                                <label>New password</label>
                                                    <input type="password" id="pwdnew" name="pwdnew"
                                                        class="form-control" required>
                                                   
                                                </div>
                                                <div class="form-group mt-3">
                                                <label>repeat new password</label>
                                                    <input type="password" id="pwdnew2" name="pwdnew2" equalto="#pwdnew"
                                                        class="form-control" required>
                                                    
                                                </div>
                                                <div class="form-group mt-3">
                                                    <input type="hidden" id="username"
                                                        value="<?php echo $_SESSION['user'];?>">
                                                    <div style="display: block;" id="pass-strength-result"><i
                                                            class='mdi mdi-lock-open-outline'></i>&nbsp;Password
                                                        Strenght</div>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <button type="submit" name="changepass"
                                                        class="btn btn-info waves-effect waves-dark"><svg
                                                            class="midico midico-outline">
                                                            <use href="/assets/images/icon/midleoicons.svg#i-save"
                                                                xlink:href="/assets/images/icon/midleoicons.svg#i-save" />
                                                        </svg>&nbsp;Change</button>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>




        </div>
        </div>

        <?php include "public/modules/footer.php";
    echo "</div></div>";
    include "public/modules/js.php";  ?>
        <script src="/assets/js/jquery/jquery.cropit.js" type="text/javascript"></script>
        <script type='text/javascript'>
        (function(a) {
            function b() {
                var e = a("#pwdnew").val(),
                    d = a("#username").val(),
                    c = a("#pwdnew2").val(),
                    f;
                a("#pass-strength-result").removeClass("short bad good strong");
                if (!e) {
                    a("#pass-strength-result").html(pwsL10n.empty);
                    return
                }
                f = passwordStrength(e, d, c);
                switch (f) {
                    case 2:
                        a("#pass-strength-result").addClass("bad").html(pwsL10n.bad);
                        break;
                    case 3:
                        a("#pass-strength-result").addClass("good").html(pwsL10n.good);
                        break;
                    case 4:
                        a("#pass-strength-result").addClass("strong").html(pwsL10n.strong);
                        break;
                    case 5:
                        a("#pass-strength-result").addClass("short").html(pwsL10n.mismatch);
                        break;
                    default:
                        a("#pass-strength-result").addClass("short").html(pwsL10n["short"])
                }
            }
            a(document).ready(function() {
                a("#pwdnew").val("").keyup(b);
                a("#pwdnew2").val("").keyup(b);
                a("#pass-strength-result").show();
                a(".color-palette").click(function() {
                    a(this).siblings('input[name="admin_color"]').prop("checked", true)
                });
                a("#first_name, #last_name, #nickname").blur(function() {
                    var c = a("#display_name"),
                        e = c.find("option:selected").attr("id"),
                        f = [],
                        d = {
                            display_nickname: a("#nickname").val(),
                            display_username: a("#username").val(),
                            display_firstname: a("#first_name").val(),
                            display_lastname: a("#last_name").val()
                        };
                    if (d.display_firstname && d.display_lastname) {
                        d.display_firstlast = d.display_firstname + " " + d.display_lastname;
                        d.display_lastfirst = d.display_lastname + " " + d.display_firstname
                    }
                    a("option", c).remove();
                    a.each(d, function(i, g) {
                        var h = g.replace(/<\/?[a-z][^>]*>/gi, "");
                        if (d[i].length && a.inArray(h, f) == -1) {
                            f.push(h);
                            a("<option />", {
                                id: i,
                                text: h,
                                selected: (i == e)
                            }).appendTo(c)
                        }
                    })
                })
            })
        })(jQuery);
        var pwsL10n = {
            empty: "<i class='mdi mdi-lock-open-outline'></i>&nbsp;Password Strenght",
            short: "<i class='mdi mdi-lock-open-outline'></i>&nbsp;Too Short",
            bad: "<i class='mdi mdi-lock-open'></i>&nbsp;Bad",
            good: "<i class='mdi mdi-lock'></i>&nbsp;Good",
            strong: "<i class='mdi mdi-shield-lock-outline'></i>&nbsp;Strong",
            mismatch: "<i class='mdi mdi-lock-question'></i>&nbsp;Mismatch"
        };
        try {
            convertEntities(pwsL10n);
        } catch (e) {};
        </script>
        <script src="/assets/js/password-strength-meter.js" type="text/javascript"></script> <?php
    include "public/modules/template_end.php";
    echo '</body></html>';
  }
}
class Class_searchall{
  public static function getPage($thisarray){
    global $installedapp;
    global $website;
	global $page;
	global $modulelist;
    global $maindir;
    if($installedapp!="yes"){ header("Location: /install"); }
    sessionClass::page_protect(base64_encode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
    $err = array();
    $msg = array();
    $pdo = pdodb::connect();
    $data=sessionClass::getSessUserData(); foreach($data as $key=>$val){  ${$key}=$val; } 
    include "public/modules/css.php";
    echo '</head><body class="card-no-border"> <div id="main-wrapper">';
    $breadcrumb["text"]="Search results";
    include "public/modules/headcontent.php";
  echo '<div class="page-wrapper"><div class="container-fluid">';
  
    include "public/modules/breadcrumb.php";
    echo '<div class="row"><div class="col-12">'; 
    if(isset($_REQUEST["sa"])){ 
      $data=array();
      if(!empty($_REQUEST["fd"])){
        if(empty($_REQUEST["st"])){
        $sql="SELECT cat_latname,cat_name FROM knowledge_info where (lower(cat_name) like ? or cattext like ?) order by id desc";
        $q = $pdo->prepare($sql);
        $q->execute(array("%".strtolower(htmlspecialchars($_POST['fd']))."%","%".htmlspecialchars($_POST['fd'])."%"));
       if($zobj = $q->fetchAll()){ 
          foreach($zobj as $val) {
            $data["/info/posts/".$val["cat_latname"]]=$val["cat_name"];
          }
         }
        } elseif($_POST["st"]=="requests"){
      $sql="SELECT sname,reqname FROM requests where (lower(reqname) like ? or info like ?) order by id desc";
      $q = $pdo->prepare($sql);
      $q->execute(array("%".strtolower(htmlspecialchars($_POST['fd']))."%","%".htmlspecialchars($_POST['fd'])."%"));
         if($zobj = $q->fetchAll()){ 
          foreach($zobj as $val) {
            $data["/reqinfo/".$val["sname"]]=$val["reqname"];
          }
         }
        } elseif($_POST["st"]=="diagram"){
          $sql="select desid,desname from config_diagrams where xmldata like ?";
          $q = $pdo->prepare($sql);
          $q->execute(array("%".htmlspecialchars($_POST['fd'])."%"));
           if($zobj = $q->fetchAll()){ 
          foreach($zobj as $val) {
            $data["/diagrams/v/".$val["desid"]]=$val["desname"];
          }
         }
        } elseif($_POST["st"]=="file"){
          $sql="select what, swhere, tags from search where what like ?";
          $q = $pdo->prepare($sql);
          $q->execute(array("%".htmlspecialchars($_POST['fd'])."%"));
          if($zobj = $q->fetchAll()){ 
            foreach($zobj as $val) {
              $data[str_replace("console","requests",$val["swhere"])]=$val["what"]." with tags:".$val["what"];
            }
           }
        } elseif($_REQUEST["st"]=="tag"){ 
          $sql="select what, swhere, tags from search where tags like ?";
          $q = $pdo->prepare($sql);
          $q->execute(array("%".htmlspecialchars($_REQUEST['fd'])."%"));
          if($zobj = $q->fetchAll()){ 
            foreach($zobj as $val) {
              $data[str_replace("console","requests",$val["swhere"])]=$val["tags"]." in:".$val["what"];
            }
          }
        }
      }
    } else { $data=array(); }
    ?>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Search Result For "<?php echo htmlspecialchars($_REQUEST['fd']);?>"</h4>
                <h6 class="card-subtitle"></h6>
                <ul class="search-listing">
                    <?php if(is_array($data) && !empty($data)){ foreach($data as $key=>$val) {?>
                    <li>
                        <h3><a href="<?php echo $key;?>"><?php echo $val;?></a></h3>
                    </li>
                    <?php } } else { ?>
                    <li>No data found</li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <?php echo '</div></div>';
    include "public/modules/footer.php";
	echo '</div></div>';
    include "public/modules/js.php"; 
	echo '</body></html>';
    include "public/modules/template_end.php";
  }
}
class Class_welcome{
  public static function getPage(){
    global $installedapp;
    global $website;
    global $maindir;
    global $page;
    global $modulelist;
    if($installedapp!="yes"){ header("Location: /install"); } 
    session_start();
 $pdo = pdodb::connect(); 
 include "public/modules/css.php";   
   echo '<style type="text/css">.card-header + .card-body{padding-top:15px;}</style></head>';
   echo '<body class="fix-header card-no-border no-sidebar"><div id="main-wrapper">';
   include "public/modules/headcontentmain.php";   
   echo '<div class="page-wrapper">'; ?>
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <h4 class="mb-0">Browse resources</h4>
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card maincard">
                                <div class="row">
                                    <div class="col-md-3 text-end">
                                        <img src="/assets/images/icon/documentation.svg"
                                            style="padding-top: 20px;width:80px;display: block;float: right;">
                                    </div>
                                    <div class="col-md-9">
                                        <div class="card-body">
                                            <h4 class="card-title">Documentation</h4>
                                            <p class="card-text">Get help using and administering MidlEO</p><br>
                                            <a href="/info/" class="btn btn-light">Docs</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card maincard">
                                <div class="row">
                                    <div class="col-md-3 text-end">
                                        <img src="/assets/images/icon/support.svg"
                                            style="padding-top: 20px;width:80px;display: block;float: right;">
                                    </div>
                                    <div class="col-md-9">
                                        <div class="card-body">
                                            <h4 class="card-title">Contact support</h4>
                                            <p class="card-text">Open a service request or browse the middleware
                                                environment</p>
                                            <a href="/requests" class="btn btn-light">Open request</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card maincard">
                                <div class="row">
                                    <div class="col-md-3 text-end">
                                        <img src="/assets/images/icon/status.svg"
                                            style="padding-top: 20px;width:80px;display: block;float: right;">
                                    </div>
                                    <div class="col-md-9">
                                        <div class="card-body">
                                            <h4 class="card-title">Middleware status</h4>
                                            <p class="card-text">Check the health of your servers and applications</p>
                                            <br>
                                            <a href="/browse/serverlist" class="btn btn-light">Browse</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card maincard">
                                <div class="row">
                                    <div class="col-md-3 text-end">
                                        <img src="/assets/images/icon/idea.svg"
                                            style="padding-top: 20px;width:80px;display: block;float: right;">
                                    </div>
                                    <div class="col-md-9">
                                        <div class="card-body">
                                            <h4 class="card-title">Suggestions and bug reports</h4>
                                            <p class="card-text">Find existing feature suggestions and bug reports</p>
                                            <br>
                                            <a href="/info/category/bugs" class="btn btn-light">Browse</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>

            <?php include "public/modules/license.php";
    echo '</div></div>';
    include "public/modules/footer.php";
    include "public/modules/js.php";
    include "public/modules/template_end.php";
    if(!empty($text)){unset($text);}
     echo '</body></html>';

  }
}