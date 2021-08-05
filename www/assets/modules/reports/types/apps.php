<?php
$sql="select id,tags,appcode,appname,".(DBTYPE=='oracle'?"to_char(appinfo) as appinfo":"appinfo").",owner,".(DBTYPE=='oracle'?"to_char(appusers) as appusers":"appusers").",appcreated from config_app_codes where appcode=? and owner='".$_SESSION["user"]."'";
$q = $pdo->prepare($sql); 
  $q->execute(array(htmlspecialchars($_GET["app"]))); 
  if($zobj = $q->fetch(PDO::FETCH_ASSOC)){ ?>

<div class="page">
<h4><?php echo $zobj["appname"];?></h4>
Created on: <?php echo $zobj["appcreated"];?>
<hr class="mt-0 mb-1">
<br>
<table style="border:0px;width:100%;" cellspacing="0" cellpadding="0">
<tr><td width="50%" style="width:50%;vertical-align:top;">
<div class="form-group-sm">
<h5>Application identifier:</h5>
<?php echo $_GET["app"];?>
</div><br><br>
<div class="form-group-sm">
<h5>Tags&nbsp;&nbsp;&nbsp;</h5>
<?php if(!empty($zobj["tags"])){ foreach(explode(",", $zobj["tags"]) as $key){ echo '<a class="btn btn-light" href="/searchall/?sa=y&st=tag&fd='.$key.'" title="'.$key.'">'.$key.'</a>&nbsp;'; }}?>
</div><br><br>
<div class="form-group-sm">
<h5>Application Details</h5>
<?php echo $zobj["appinfo"];?>
</div><br>
</td>
<td width="50%" style="width:50%;vertical-align:top;">
<div class="form-group-sm">
<h5>Application owner</h5>
<?php 
$sql="select avatar,fullname,utitle,user_online,user_online_show from users where mainuser=?";
$qin = $pdo->prepare($sql); 
$qin->execute(array($zobj["owner"])); 
if($zobjin = $qin->fetch(PDO::FETCH_ASSOC)){ ?>
<div class="contact-widget position-relative">
<a href="/browse/user/<?php echo $zobj["owner"];?>" target="_blank" class="py-3 px-2 text-decoration-none">
 <div class="user-img position-relative d-inline-block mr-2"> 
 <img src="<?php echo !empty($zobjin["avatar"])?$zobjin["avatar"] : '/assets/images/avatar.svg' ;?>"
 width="40" alt="<?php echo $zobjin["fullname"];?>" data-bs-toggle="tooltip"
 data-bs-placement="top" title="<?php echo $zobjin["fullname"];?>" class="rounded-circle">
 <span class="profile-status pull-right d-inline-block position-absolute bg-<?php echo $zobjin["user_online_show"]==0?"secondary":($zobjin["user_online"]==1?"success":"danger");?> rounded-circle"></span>
</div>
 <div class="mail-contnet d-inline-block align-middle">
 <h5 class="my-1"><?php echo $zobjin["fullname"];?></h5> <span class="mail-desc font-12 text-truncate overflow-hidden btn btn-light"><?php echo !empty($zobjin["utitle"])?$zobjin["utitle"]:"No title";?></span>
</div>
</a>
</div>
<?php } ?>
</div>
<br><br>
<div class="form-group-sm">
<h5>Team members</h5>
<?php if($zobj["appusers"]){ ?>
<div class="contact-widget position-relative">
<?php
   foreach(json_decode($zobj["appusers"],true) as $key=>$val){?>
<a href="/browse/user/<?php echo $key;?>" target="_blank" class="py-3 px-2  d-block text-decoration-none">
 <div class="user-img position-relative d-inline-block mr-2"> 
 <img src="<?php echo !empty($val["uavatar"])?'/assets/images/users/'.$val["uavatar"] : '/assets/images/avatar.svg' ;?>"
width="40" alt="<?php echo $val["uname"];?>" data-bs-toggle="tooltip"
data-bs-placement="top" title="<?php echo $val["uname"];?>" class="rounded-circle">
</div>
 <div class="mail-contnet d-inline-block align-middle">
 <h5 class="my-1"><?php echo $val["uname"];?></h5> <span class="mail-desc font-12 text-truncate overflow-hidden btn btn-light"><?php echo $val["utitle"];?></span>
</div>
</a>
<?php } ?>
</div>
<?php } else { echo "<div class='alert'>No members yet</div>"; } ?>
</div>
</td>
</tr>
</table>
<br><br>
<div class="form-group-sm">
<h5>Monthly requests info</h5><br>
<?php $sql ="select sname,projnum,reqname,reqtype from requests where reqapp=?";
$q = $pdo->prepare($sql);
$q->execute(array($_GET["app"]));
if($zobj = $q->fetchAll()){ ?>
<table style="border:0px;width:100%;" cellspacing="0" cellpadding="0">
<thead><tr><td>Request</td><td>About</td><td>Project</td><td>Type</td></tr></thead>
<?php foreach ($zobj as $val) { ?>
<tr><td><?php echo $val["sname"];?></td><td><a href="/browse/req/<?php echo $val["sname"];?>"><?php echo $val["reqname"];?></a></td><td><?php echo $val["projnum"];?></td><td><?php echo $typereq[$val["reqtype"]];?></td></tr>
<?php } ?>
</table>
<?php } else {
 echo "No requests till now.";
}
?>
</div><br><br>
<div class="form-group-sm">
<h5>Monthly requests summary</h5><br>
<canvas id="bar-chart-eff" height="150"></canvas>
</div><br><br>
<div class="form-group-sm">
<h5>Generated packages</h5><br>
<?php $sql ="select packname,deployedin,packuid,created_time,created_by from env_packages where proj=?";
$q = $pdo->prepare($sql);
$q->execute(array($_GET["app"]));
if($zobj = $q->fetchAll()){ ?>
<table style="border:0px;width:100%;" cellspacing="0" cellpadding="0">
<thead><tr><td>Package</td><td>Identifier</td><td>Deployed in</td><td>Created by</td></tr></thead>
<?php foreach ($zobj as $val) { ?>
<tr><td><?php echo $val["packname"];?></td><td><?php echo $val["packuid"];?></td><td><?php if(!empty($val["deployedin"])){foreach(json_decode($val["deployedin"],true) as $keyin=>$valin){ echo $menudataenv[$keyin]['name']; } }?></td><td><?php echo $val["created_by"];?></td></tr>
<?php } ?>
</table>
<?php } else {
 echo "No requests till now.";
}
?>
</div><br><br>


<?php } else {
    echo "<div class='alert alert-light'>Only owner of the application can generate report</div>";
  }