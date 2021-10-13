<?php 
   sessionClass::page_protect(base64_encode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
   $pdo = pdodb::connect();
   $msg=array();
   $data=sessionClass::getSessUserData(); foreach($data as $key=>$val){  ${$key}=$val;  }
   include "public/modules/css.php"; ?>
</head>

<body class="fix-header card-no-border">
    <div id="main-wrapper">
    <?php if(!empty($thisarray["p1"])){
         $breadcrumb["link"]="/requests"; $breadcrumb["text"]="Requests"; 
         if($thisarray["p1"]=="type"){
          $breadcrumb["text2"]=$thisarray["p3"];
         } else {
          $breadcrumb["link2"]="/reqinfo/".$thisarray["p1"];
          $breadcrumb["text2"]=$thisarray["p1"];
         }
         } else {
          $breadcrumb["text"]="Requests"; 
         }
          ?>
        <?php include "public/modules/headcontent.php";?>
        <div class="page-wrapper">
            <div class="container-fluid">
              
                <?php include "public/modules/breadcrumb.php";
         echo '<div class="row"><div class="col-12">'; ?>
                <?php $q=gTable::countAll("requests"," where sname='".$thisarray["p1"]."'");
  if($q>0){ ?>
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header"><h4>Request information</h4></div>
                            <div class="card-body">
                                
                                    <?php
$sql= "SELECT t.id,t.commuser,t.commfullname,t.commdate,".(DBTYPE=='oracle'?"to_char(t.commtext) as commtext":"t.commtext")." FROM requests_comments t where t.reqid=? order by t.id desc";
$stmt = $pdo->prepare($sql);
                        $stmt->execute(array($thisarray["p1"]));
                        if($zobjin = $stmt->fetchAll()){
                          ?>
                          <div class="profiletimeline position-relative">
                                    <?php foreach($zobjin as $val) {  
                                            $sql="select u.avatar,u.user_online,u.user_online_show from users u where u.mainuser=?";
                                            $stmt = $pdo->prepare($sql); 
                                            $stmt->execute(array($val["commuser"]));
                                            $valin = $stmt->fetch(PDO::FETCH_ASSOC); 
                                         ?>
                                    <div class="sl-item mt-2 mb-3">
                                        <div class="sl-left user-img float-start me-3 position-relative"
                                            data-bs-toggle="tooltip"
                                            title="<?php echo $valin["user_online_show"]==0?"Unknown":($valin["user_online"]==1?"Online":"Offline");?>">
                                            <?php if(!empty($valin["avatar"])){?><img src="<?php echo $valin["avatar"];?>"
                                                alt="user" width="40"
                                                class="img-fluid rounded-circle" /><?php } else { ?><img
                                                src="/assets/images/avatar.svg" alt="user" width="40"
                                                class="img-fluid rounded-circle" /><?php } ?>
                                            <span
                                                class="profile-status pull-right d-inline-block position-absolute bg-<?php echo $valin["user_online_show"]==0?"secondary":($valin["user_online"]==1?"success":"danger");?> rounded-circle"></span>
                                        </div>
                                        <div class="sl-right">
                                            <div>
                                                <div class="d-flex">
                                                    <h5 class="mb-0 font-weight-light"><a href="#"
                                                            class="link"><?php echo $val["commfullname"];?></a></h5>
                                                    <span class="sl-date text-muted ml-1">
                                                        <?php echo date("H:i d.M.Y",strtotime($val["commdate"]));?></span>
                                                </div>
                                                <p class="mt-2">
                                                    <?php echo $val["commtext"];?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <?php } ?>
                                    </div>
                                    <?php } else { ?>
                                    <div class="alert">There are no comments about this request.</div>
                                    <?php } ?>
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header"><h4>Navigation</h4></div>
                            <div class="card-body  p-0">
                                <?php include "reqnav.php";?>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <?php  } else { textClass::PageNotFound(); } ?>
            <?php include "public/modules/footer.php";?>
            <?php include "public/modules/js.php";?>
            <?php include "public/modules/template_end.php";?>
</body>
</html>