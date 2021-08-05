<?php
header("X-Frame-Options: sameorigin");
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("Strict-Transport-Security: max-age=31536000");
include "controller/config.main.php";
$thewholelink=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$url = new url('/');
$page=urldecode($url->part(1));
$subpage = urldecode($url->part(2));
$secsubpage = urldecode($url->part(3));
$thirdsubpage = urldecode($url->part(4));
$fourthsubpage = urldecode($url->part(5));
$fifthsubpage = urldecode($url->part(6)); 
$lastsubpage = substr($thewholelink, strrpos($thewholelink, '/') + 1); 
$pageclass="Class_".$page; 
if(empty($page) || $page=="?" || $page=="index" || $page=="index.php"){ 
  Class_main::getPage(); 
} else {
  if(method_exists($pageclass, "getPage") && is_callable(array($pageclass, "getPage"))){ 
    $pageclass::getPage(array("p0"=>$page,"p1"=>$subpage,"p2"=>$secsubpage,"p3"=>$thirdsubpage,"p4"=>$fourthsubpage,"p5"=>$fifthsubpage, "last"=>$lastsubpage)); 
  } else { 
    Class_error::getPage("404");
  }
 } 
?>