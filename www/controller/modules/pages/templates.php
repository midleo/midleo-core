<?php 
class Class_test{
  public static function getPage(){



  }
}
class Class_template{
  public static function getPage($thisarray){
    global $installedapp;
    global $website;
    global $maindir;
    global $page;
    global $modulelist;
    if($installedapp!="yes"){ header("Location: /install"); }
    session_start();
    $err = array();
    $msg = array();
    $pdo = pdodb::connect();
    include "public/modules/css.php";
    echo '</head><body class="card-no-border"><div id="main-wrapper">';
    include "public/modules/headcontentmain.php";
    echo '<div class="page-wrapper"><div class="container-fluid">';
    include "public/modules/breadcrumbinfo.php";
    echo '<div class="row"><div class="col-12">';
	
	
	
    echo '</div></div>';
    include "public/modules/footer.php";
	echo '</div></div>';
    include "public/modules/js.php"; 
	echo '</body></html>';
    include "public/modules/template_end.php";
  }
}
class Class_templatecp{
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
    echo '</head><body class="card-no-border" '.($page=="reqchat"?"onload=\"setInterval('chat.update()', 20000)\"":"").'> <div id="main-wrapper">';
    include "public/modules/headcontent.php";
	echo '<div class="page-wrapper"><div class="container-fluid">';
    include "public/modules/breadcrumb.php";
    echo '<div class="row"><div class="col-12">';
    
	
	
    
    echo '</div></div>';
    include "public/modules/footer.php";
	echo '</div></div>';
    include "public/modules/js.php"; 
	echo '</body></html>';
    include "public/modules/template_end.php";
  }
}
class Class_templatecpreq{
  public static function getPage($thisarray){
    global $installedapp;
    global $website;
    global $maindir;
    global $page;
    global $typeq;
    global $typereq;
    global $modulelist;
    sessionClassreq::page_protectreq(base64_encode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
    $pdo = pdodb::connect();
    $data=sessionClassreq::getSessUserData(); foreach($data as $key=>$val){  ${$key}=$val;  } 
    include "public/modules/css.php";
    echo '</head><body class="card-no-border"><div id="main-wrapper">';
    include "public/modules/headcontentreq.php";
    echo '<div class="page-wrapper"><div class="container-fluid">';
    include "public/modules/breadcrumbreq.php";
    echo '<div class="row"><div class="col-12">';
	
    
    
    echo '</div></div>';
    include "public/modules/footer.php";
	echo '</div></div>';
    include "public/modules/js.php"; 
	echo '</body></html>';
    include "public/modules/template_end.php";
  }
}