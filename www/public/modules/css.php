<!DOCTYPE html>
<html lang="en-US">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=1">
<meta name="description" content="MidlEO Core - Organize your Middleware platform | Request Sytem, IT service desk and tracking of your Middleware Environment">
<meta name="author" content="Vasilev.link">
<meta property="description" content='MidlEO Core - Organize your Middleware platform | Request Sytem, IT service desk and tracking of your Middleware Environment'/>
<meta name="keywords" content="Middleware, Source Control, Request system">
<meta name="application-name" content="MidlEO">
<link rel="shortcut icon" href="/favicon.ico">
<title><?php if($blogtitle){ echo $blogtitle." :: MIDLEO"; } elseif($website["env_name"]){ echo $website["env_name"]." :: MIDLEO"; } else { ?>Middleware Enteprise Organizer - Organize your Middleware platform<?php } ?></title>
<link href="/assets/css/materialdesignicons.min.css" rel="stylesheet" type="text/css" />
<link href="/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="/assets/css/tagsinput.css" />
<link rel="stylesheet" type="text/css" href="/assets/css/sweet-alert.css" />
<link href="/assets/css/google_ubuntu_fonts.css" rel="stylesheet" />
<link href="/assets/css/animate.css" rel="stylesheet" type="text/css" />
<link href="/assets/css/spinners.css" rel="stylesheet" type="text/css" />
<link href="/assets/css/midleostyle.php?&v=<?php echo filemtime('./assets/css/midleostyle.php');?>" rel="stylesheet" type="text/css" />
<?php  if(!empty($_SESSION['requser']) || $page=="requests" || $page=="reqsearch" || $page=="reqinfo" || $page=="reqtasks" || $page=="calendar" || $page=="env" || $page=="projects" || $page=="smanagement" ||  $page=="business"){ ?>
<link href="/assets/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
<?php } ?>
<script src="/assets/js/angular.min.js"></script>
<script type="text/javascript" src="/assets/js/sys-controller.js"></script>
<!--[if lt IE 9]>
<script src="assets/js/html5shiv.js"></script>
<script src="assets/js/respond.min.js"></script>
<![endif]-->
<?php if(!empty($_SESSION['appnewver']) && $_SESSION['appnewver']!="no" && $thisarray["last"]!="update"){ 
$alert["/appconfig/update"]["head"]="New Update available"; 
$alert["/appconfig/update"]["line1"]="New v.".$_SESSION['appnewver']; 
$alert["/appconfig/update"]["line2"]="last update:".$_SESSION['lastupdate']; 
}?>