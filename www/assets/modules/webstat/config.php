<?php
$modulelist["webstat"]["name"] = "Statistics";
include_once "api.php";
class Class_webstat
{
    public static function getPage($thisarray)
    {
        global $installedapp;
        global $website;
        global $page;
        global $bsteps;
        global $modulelist;
        global $maindir;
        if ($installedapp != "yes") {header("Location: /install");}
        sessionClass::page_protect(base64_encode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
        $err = array();
        $msg = array();
        $pdo = pdodb::connect();
        $data=sessionClass::getSessUserData(); foreach($data as $key=>$val){  ${$key}=$val; } 
        include $website['corebase']."public/modules/css.php";
        echo '<link rel="stylesheet" type="text/css" href="/'.$website['corebase'].'assets/css/jquery-ui.min.css">';
        echo '</head><body class="fix-header card-no-border"><div id="main-wrapper">';
        $breadcrumb["text"] = "Statistics";
        include $website['corebase']."public/modules/headcontent.php"; ?>
<div class="page-wrapper">
    <div class="container-fluid">
        <?php      
      $brarr=array(
        array(
          "title"=>"Request statistics",
          "link"=>"/webstat/charts",
          "icon"=>"mdi-chart-bell-curve-cumulative",
          "active"=>($thisarray['p1']=="charts")?"active":"",
        ),
        array(
            "title"=>"IBM MQ statistics",
            "link"=>"/webstat/ibmmq",
            "icon"=>"mdi-tray-full",
            "active"=>($thisarray['p1']=="ibmmq")?"active":"",
          )
      );
        if(!empty($thisarray['p1'])){
    
        if (file_exists(__DIR__ . "/views/" . $thisarray['p1'] . ".php")) { include "views/" . $thisarray['p1'] . ".php"; }
        else { 
            include $website['corebase']."public/modules/footer.php";
            include $website['corebase']."public/modules/js.php"; 
            textClass::PageNotFound(); 
        }} else {

        }
        include $website['corebase']."public/modules/template_end.php";
        echo '</body></html>';
    }
}