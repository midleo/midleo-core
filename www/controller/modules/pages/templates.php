<?php 
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
    include $website['corebase']."public/modules/css.php";
    echo '</head><body class="card-no-border"><div id="main-wrapper">';
    include $website['corebase']."public/modules/headcontent.php";
    echo '<div class="page-wrapper"><div class="container-fluid">';
    echo '<div class="row pt-3"><div class="col-lg-2">';
    include "public/modules/sidebar.php";
    echo '</div><div class="col-lg-8">';


    
    echo '<div class="col-md-2">';
    include $website['corebase']."public/modules/breadcrumbin.php";
    echo '</div></div></div>';
    include $website['corebase']."public/modules/footer.php";
	echo '</div></div>';
    include $website['corebase']."public/modules/js.php"; 
	echo '</body></html>';
    include $website['corebase']."public/modules/template_end.php";
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
    include $website['corebase']."public/modules/css.php";
    echo '</head><body class="card-no-border" '.($page=="reqchat"?"onload=\"setInterval('chat.update()', 20000)\"":"").'> <div id="main-wrapper">';
    include $website['corebase']."public/modules/headcontent.php";
	echo '<div class="page-wrapper"><div class="container-fluid">';
    include $website['corebase']."public/modules/breadcrumb.php";
    echo '<div class="row"><div class="col-12">';
    
	
	
    
    echo '</div></div>';
    include $website['corebase']."public/modules/footer.php";
	echo '</div></div>';
    include $website['corebase']."public/modules/js.php"; 
	echo '</body></html>';
    include $website['corebase']."public/modules/template_end.php";
  }
}