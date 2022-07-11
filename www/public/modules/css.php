<!DOCTYPE html>
<html lang="en-US">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=1">
<meta name="description" content="midleo.CORE - Knowledge Base, automation and tracking of your Middleware Environment">
<meta name="author" content="Vasilev.link">
<meta property="description" content='midleo.CORE - Knowledge Base, automation and tracking of your Middleware Environment'/>
<meta name="keywords" content="Middleware, Source Control, Request system, midleo.CORE">
<meta name="application-name" content="MidlEO">
<link rel="shortcut icon" href="/<?php echo $website['corebase'];?>favicon.ico">
<title><?php if($blogtitle){ echo $blogtitle." :: midleo.CORE"; } elseif($website["env_name"]){ echo $website["env_name"]." :: midleo.CORE"; } else { ?>midleo.CORE - Knowledge Base, automation and tracking of your Middleware Environment<?php } ?></title>
<link href="/<?php echo $website['corebase'];?>assets/css/materialdesignicons.min.css" rel="stylesheet" type="text/css" />
<link href="/<?php echo $website['corebase'];?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="/<?php echo $website['corebase'];?>assets/css/tagsinput.css" />
<link rel="stylesheet" type="text/css" href="/<?php echo $website['corebase'];?>assets/css/sweet-alert.css" />
<link href="/<?php echo $website['corebase'];?>assets/css/google_ubuntu_fonts.css" rel="stylesheet" />
<link href="/<?php echo $website['corebase'];?>assets/css/animate.css" rel="stylesheet" type="text/css" />
<link href="/<?php echo $website['corebase'];?>assets/css/spinners.css" rel="stylesheet" type="text/css" />
<link href="/<?php echo $website['corebase'];?>assets/css/midleostyle.php?<?php echo !empty($website['color'])?"c=".$website['color']."&":"";?>v=<?php echo filemtime('./'.$website['corebase'].'assets/css/midleostyle.php');?>" rel="stylesheet" type="text/css" />
<?php if(isset($_SESSION["user"])){?>
<link href="/<?php echo $website['corebase'];?>assets/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
<?php } ?>
<!--[if lt IE 9]>
<script src="/<?php echo $website['corebase'];?>assets/js/html5shiv.js"></script>
<script src="/<?php echo $website['corebase'];?>assets/js/respond.min.js"></script>
<![endif]-->