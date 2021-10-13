<?php 
  sessionClass::page_protect(base64_encode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
  $pdo = pdodb::connect(); 
  $msg=array();
  $data=sessionClass::getSessUserData(); foreach($data as $key=>$val){  ${$key}=$val;  }

  if(isset($_POST['assign'])){  $q=requestFunctions::assign(htmlspecialchars($_POST['wfstep']),$thisarray["p1"]); $err=$q["err"];  $msg=$q["msg"];  }
  if(isset($_POST['addchtask'])){ $q=requestFunctions::addchtask($thisarray["p1"],$usname); $err=$q["err"];  $msg=$q["msg"]; }
  if(isset($_POST['addcomm'])){  $q=requestFunctions::addcomm(htmlspecialchars($_POST['wfstep']),$_POST['requpdinfo'],$thisarray["p1"],$usname);  $err=$q["err"];  $msg=$q["msg"]; }
  if(isset($_POST['sendback'])){ $q=requestFunctions::sendback($thisarray["p1"],htmlspecialchars($_POST['requser']));  $err=$q["err"];  $msg=$q["msg"]; }

  $q=gTable::read("requests","*"," where sname='".$thisarray["p1"]."'");
  $zobj = $q->fetch(PDO::FETCH_ASSOC);
  $q=gTable::read("requests_deployments","deployedin,reqid"," where reqid='".$thisarray["p1"]."'");
  $zobjin = $q->fetch(PDO::FETCH_ASSOC);
  $deployedin=$zobjin['deployedin'];
  $deplinreq=$zobjin['deployedin'];
  
  if(!empty($zobj["wid"])){
    $sql="select haveappr,haveconf,".(DBTYPE=='oracle'?"to_char(wdata) as wdata":"wdata").",".(DBTYPE=='oracle'?"to_char(wgroups) as wgroups":"wgroups")." from config_workflows where wid=?";
    $qin = $pdo->prepare($sql); 
    $qin->execute(array($zobj["wid"]));
    if($zobjin = $qin->fetch(PDO::FETCH_ASSOC)){ 
     $wfdata=!empty($zobjin["wdata"])?json_decode($zobjin["wdata"],true):json_decode("[{}]",true);    
     $wfdatalaststep=end(array_keys($wfdata["nodes"])); 
     $wfdatagroups=!empty($zobjin["wgroups"])?json_decode($zobjin["wgroups"],true):json_decode("[{}]",true);
     $wfdataha=$zobjin["haveappr"];
     $wfdatahc=$zobjin["haveconf"];
    } 
    } else {
     $wfdata=json_decode("[{}]",true); 
    }
  
  if($wfdataha!=1){ $zobj['projapproved']=1; }
  if($wfdatahc!=1){ $zobj['projconfirmed']=1; }
  
  if (!empty($env)) {  $menudataenv = json_decode($env, true); } else {  $menudataenv = array(); }

  if(isset($_POST['sendnext'])){ $q=requestFunctions::sendnext(htmlspecialchars($_POST['wfstep']),htmlspecialchars($_POST['nextstep']),$_SESSION['user'],$thisarray["p1"],$usname,$wfdata,$wfdatagroups); $err=$q["err"];  $msg=$q["msg"]; }
  if(isset($_POST['updreq'])){  $q=requestFunctions::updreq($usname,$thisarray["p1"],$wfdata);  }
  if(isset($_POST['donereq'])){   $q=requestFunctions::donereq(htmlspecialchars($_POST['wfstep']),$thisarray["p1"],$wfdata);   $err=$q["err"];  $msg=$q["msg"];  }
 
 include "public/modules/css.php"; ?>
     <link href="/assets/css/css-chart.css" rel="stylesheet">
</head>

<body class="fix-header card-no-border">
    <div id="main-wrapper">
    <?php if(!empty($thisarray["p1"])){ $breadcrumb["text"]="Requests"; $breadcrumb["link"]="/requests"; 
$breadcrumb["text2"]=($thisarray["p1"]=="type"?$thisarray["p3"]:$thisarray["p1"]);
$breadcrumb["link2"]=($thisarray["p1"]=="type"?"":"/reqinfo/".$thisarray["p1"]);
	} else { $breadcrumb["text"]="Requests"; $breadcrumb["link"]=""; } ?>
        <?php include "public/modules/headcontent.php";?>
        <div class="page-wrapper">
            <div class="container-fluid">
               
                <?php  include "public/modules/breadcrumb.php";?>
                


                <?php  if(!is_array($zobj) and empty($zobj)){?>
                <html>

                <head>
                    <script language="JavaScript">
                    function redirect() {
                        parent.location.href = "/requests"
                    }
                    </script>
                </head>

                <body onLoad="redirect()"></body>

                </html>
                <?php } ?>
                <div class="row" id="ngApp" ng-app="ngApp" ng-controller="ngCtrl">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header"><h4><?php echo $zobj['reqname'];?></h4>
                        
                                    <?php if($zobj['deployed']!=1){ 
               if($zobj['assigned']=="canceled"){ echo ' <div class="ribbon ribbon-warning ribbon-right" style="font-size:small;"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-warning" xlink:href="/assets/images/icon/midleoicons.svg#i-warning" /></svg>&nbsp;Canceled</div>'; }
               elseif(!empty($zobj['assigned'])){ echo ' <div class="ribbon ribbon-info ribbon-right" style="font-size:small;"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-warning" xlink:href="/assets/images/icon/midleoicons.svg#i-warning" /></svg>&nbsp;In progress</div>'; }
              else { echo ' <div class="ribbon ribbon-default ribbon-right" style="font-size:small;"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-warning" xlink:href="/assets/images/icon/midleoicons.svg#i-warning" /></svg>&nbsp;Not assigned</div>'; }
            ?>
                                    <?php } else { ?>
                                        <div class="ribbon ribbon-success ribbon-right" style="font-size:small;"><i
                                            class="mdi mdi-information-outline"></i>&nbsp;Completed
                                            </div>
                                    <?php } ?>
                            </div>
                            <div class="card-body">

                                <form name="form" action="" enctype="multipart/form-data" method="post"
                                    class="form-material form-horizontal">
                                    <?php 
            if(!empty($ugrarr)){ $checkgr=in_array($zobj["wfunit"],$ugrarr); $effunit=$zobj["wfunit"]; } else { $checkgr=1; }
            if(($checkgr || $zobj["wfunit"]==$_SESSION["user"]) && array_key_exists($zobj["wid"],$widarrkeys)){?>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <div class="btn-group  mb-2 mb-md-0 reqbtngroup" role="group"
                                                aria-label="Request buttons">
                                                <button type="button" data-bs-toggle="modal" href="#updmodal"
                                                    class="btn btn-light btn-sm waves-effect"><i
                                                        class="mdi mdi-comment-multiple-outline"></i>&nbsp;Comment</button>
                                                <?php if (empty($deplinreq) && $wfdata["nodes"][$zobj["wfstep"]][0]["elusrchtask"]==1 && $zobj['assigned']==$_SESSION['user']) { ?><button
                                                    type="button" data-bs-toggle="modal" href="#chmodal"
                                                    class="btn btn-light btn-sm waves-effect"><i
                                                        class="mdi mdi-account-switch"></i>&nbsp;Change
                                                    task</button><?php } ?>
                                                <?php if(empty($zobj['assigned'])){?><button
                                                    class="btn btn-primary btn-sm waves-effect" name="assign"
                                                    type="submit"><i
                                                        class="mdi mdi-account-check-outline"></i>&nbsp;Assign</button><?php } ?>

                                                <?php if($wfdatalaststep==$zobj["wfstep"] && $zobj['assigned']==$_SESSION['user'] && $zobj['deployed']!=1){?><button
                                                    name="donereq" type="submit"
                                                    class="btn btn-primary btn-sm waves-effect"><i
                                                        class="mdi mdi-check"></i>&nbsp;Finish</button><?php } else { ?>
                                                <?php if(!empty($zobj["wfstep"]) && $zobj['assigned']==$_SESSION['user']){
                     foreach($wfdata["connections"][$zobj["wfstep"]] as $keyin=>$valin){ ?>
                                                <button type="button"
                                                    ng-click="sendnext('<?php echo $valin["targetId"];?>')"
                                                    class="btn btn-light btn-sm waves-effect"><i
                                                        class="mdi mdi-send"></i>&nbsp;Next:
                                                    <?php echo $wfdata["nodes"][$valin["targetId"]][0]["label"];?></button>
                                                <?php  }
                   }?>
                                                <?php } ?>
                                                <?php if($wfdata["nodes"][$zobj["wfstep"]][0]["elusrconf"]==1 && $zobj['projconfirmed']!=1 && $zobj['assigned']==$_SESSION['user']){ $tmp["confirmreq"]=true; ?><button
                                                    class="btn btn-light btn-sm confirmreq<?php echo $zobj['sname'];?>"
                                                    type="button"
                                                    ng-click="confirmreq('<?php echo $zobj['sname'];?>','<?php echo $_SESSION['user'];?>','<?php echo $zobj["wfstep"];?>','<?php echo $usname;?>','<?php echo $zobj['projnum'];?>')"><i
                                                        class="mdi mdi-check-decagram"></i>&nbsp;Confirm</button><?php } ?>
                                                <?php if($wfdata["nodes"][$zobj["wfstep"]][0]["elusrappr"]==1 && $zobj['projapproved']!=1 && $zobj['assigned']==$_SESSION['user']){ $tmp["approvereq"]=true; ?><button
                                                    class="btn btn-light btn-sm approvereq<?php echo $zobj['sname'];?>"
                                                    type="button"
                                                    ng-click="approvereq('<?php echo $zobj['sname'];?>','<?php echo $_SESSION['user'];?>','<?php echo $zobj["wfstep"];?>','<?php echo $usname;?>','<?php echo $zobj['projnum'];?>')"><i
                                                        class="mdi mdi-check-decagram"></i>&nbsp;Approve</button><?php } ?>
                                                <?php if($zobj['assigned']==$_SESSION['user']){?><button
                                                    class="btn btn-light btn-sm" name="updreq" type="submit"><i
                                                        class="mdi mdi-content-save"></i>&nbsp;Save</button><?php } ?>
                                                <input type="text" style="display:none;" name="nextstep"
                                                    ng-model="proj.nextstep">
                                                <button type="submit" id="sendnext" name="sendnext"
                                                    style="display:none;"></button>
                                            </div>
                                            <div class="btn-group  mb-2 mb-md-0 reqbtngroup" role="group"
                                                aria-label="Request buttons">
                                                <?php if($zobj['assigned']==$_SESSION['user']){?><button
                                                    class="btn btn-light btn-sm" name="sendback" type="submit"><i
                                                        class="mdi mdi-account-arrow-left-outline"></i>&nbsp;Sent back
                                                    to requestor</button><?php } ?>
                                            </div>
                                        </div>
                                    </div><br>
                                    <?php } ?>
                           
                                        <input type="hidden" name="reqname" value="<?php echo $zobj['reqname'];?>">
                                    <div class="form-group row">
                                        <label class="form-control-label text-lg-left col-md-3">Project</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static text-uppercase"><?php echo $zobj['projnum'];?>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                        </div>
                                    </div>
                                    <?php if ($wfdata["nodes"][$zobj["wfstep"]][0]["elusreff"]==1 && $zobj['assigned']==$_SESSION['user']) {  ?>
                                    <div class="form-group row">
                                        <label class="form-control-label text-lg-left col-md-3">Efforts</label>
                                        <div class="col-md-4"><?php
              $q=gTable::read("requests_efforts_all","effreq,effappr,".(DBTYPE=='oracle'?"to_char(effdata) as effdata":"effdata")," where reqid='".$thisarray["p1"]."'");
              $zobjin = $q->fetch(PDO::FETCH_ASSOC); 
              if($zobjin["effreq"]!=$zobjin["effappr"] || empty($zobjin["effreq"])){
                $temparr=json_decode($zobjin["effdata"],true);
                        ?>
                                            <button type="button" data-bs-toggle="modal"
                                                class="waves-effect waves-light btn btn-light btn-sm"
                                                href="#eff-list">View/Change</button>

                                            <div class="modal" id="eff-list" tabindex="-1" role="dialog"
                                                aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-body">
                                                            <table class="table table-striped table-hovered">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="text-end" style="width:200px;">Team
                                                                        </th>
                                                                        <th class="text-center">Number (in
                                                                            <?php echo $website["effort_unit"];?>)</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php foreach($wfdatagroups as $key=>$val){ ?>
                                                                    <tr
                                                                        style="<?php echo (($key==$_SESSION["user"] || in_array($key,$ugrarr))?"":"display:none;");?>">
                                                                        <td class="text-end" style="padding:5px">
                                                                            <?php echo $val["uname"];?></td>
                                                                        <td class="text-center"><input type="text"
                                                                                onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"
                                                                                class="form-control effgroup"
                                                                                name="eff_<?php echo $key;?>"
                                                                                value="<?php echo !empty($temparr["eff_".$key])?$temparr["eff_".$key]:"0";?>">
                                                                        <input type="text" style="display:none;" name="effgr[]" value="eff_<?php echo $key;?>">
                                                                        </td>
                                                                    </tr>

                                                                    <?php } ?>
                                                                    <tr>
                                                                        <td class="text-end" style="padding:5px">
                                                                            <b>Total</b></td>
                                                                        <td class="text-center"><input type="number"
                                                                                class="form-control" id="budg_total"
                                                                                name="efforts"></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <input type="text" name="effunit"
                                                                        value="<?php echo !empty($effunit)?$effunit:$_SESSION["user"];?>"
                                                                        style="display:none;">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light btn-sm"
                                                                data-bs-dismiss="modal"><i
                                                                    class="mdi mdi-check"></i>&nbsp;OK</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php } else { echo $zobjin["effappr"]; } ?>
                                        </div>
                                        <div class="col-md-6 control-label" style="text-align:left;">
                                            <?php echo $effort_unit;?></div>
                                    </div><input name="effchanged" value="" type="text" id="effchanged"
                                        style="display:none;">
                                    <?php } ?>

                                    <?php if(!empty($zobj['reqapp'])){ 
      $q=gTable::read("config_app_codes","appinfo"," where appcode='".$zobj['reqapp']."'");
      if($zobjin = $q->fetch(PDO::FETCH_ASSOC)){  ?> <div class="form-group row">
                                        <label class="form-control-label text-lg-left col-md-3">Application</label>
                                        <div class="col-md-9">
                                            <p class="form-control-static text-uppercase">
                                                <?php echo $zobjin['appinfo'];?></p>
                                            <input type="text" style="display:none;" name="reqapp"
                                                value="<?php echo $zobj['reqapp'];?>">
                                        </div>
                                    </div><?php }} ?>
                                    <div class="form-group row">
                                        <label class="form-control-label text-lg-left col-md-3">Tags</label>
                                        <div class="col-md-8"><input id="tags" data-role="tagsinput" name="tags"
                                                type="text" class="form-control" value="<?php echo $zobj['tags'];?>">
                                        </div>
                                        <div class="col-md-1" style="padding-left:0px;"><button type="button"
                                                class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="You can search this project with tags"><i
                                                    class="mdi mdi-information-variant mdi-18px"></i></button></div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="form-control-label text-lg-left col-md-3">Request Info</label>
                                        <div class="col-md-9"><textarea rows="5" name="reqinfo"
                                                class="form-control textarea"><?php echo $zobj['info'];?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row" id="requser"><input type="hidden" name="requser"
                                            value="<?php echo $zobj['requser'];?>">
                                        <label class="form-control-label text-lg-left col-md-3">Requested by</label>
                                        <div class="col-md-9"><a
                                                href="/browse/user/<?php echo $zobj['requser'];?>"><?php echo $zobj['requser'];?></a>
                                        </div>
                                    </div>
                                    <div class="form-group row" id="requser">
                                        <label class="form-control-label text-lg-left col-md-3">Assignment group</label>
                                        <div class="col-md-9"><p class="form-control-static text-uppercase"><?php if($zobj['requser']==$zobj['assigned']){ echo "Customer"; } else{ 
                $sqlin="select ".($zobj["wfutype"]=="group"?"group_name":"fullname")." from ".($zobj["wfutype"]=="group"?"user_groups":"users")." where ".($zobj["wfutype"]=="group"?"group_latname":"mainuser")."=?";
                $qin = $pdo->prepare($sqlin); 
                $qin->execute(array($zobj["wfunit"]));
                if($zobjin = $qin->fetch(PDO::FETCH_ASSOC)){ 
                 echo $zobj["wfutype"]=="group"?$zobjin["group_name"]:$zobjin["fullname"]; 
                }
                } ?></p>
                                        </div>
                                    </div>
                                    <?php if(!empty($zobj['assigned'])){?>
                                    <div class="form-group row" id="requser">
                                        <label class="form-control-label text-lg-left col-md-3">Assigned by</label>
                                        <div class="col-md-9"><a
                                                href="/browse/user/<?php echo $zobj['assigned'];?>"><?php echo $zobj['assigned'];?></a>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="form-group row">
                                        <label class="form-control-label text-lg-left col-md-3">Dates</label>
                                        <div class="col-md-4">
                                            <label
                                                class="form-control-label <?php if($zobj['deployed']!=1 && (strtotime(date('Y-m-d', strtotime(date('Y-m-d') . " +3 days")))>=strtotime(date("Y-m-d",strtotime($zobj['deadline']))))){?>text-red text-blink<?php } ?>">Ready until</label>
                                            <input name="deadline" class="form-control" type="text"
                                                value="<?php echo $zobj['deadline'];?>" disabled="disabled">

                                        </div>
                                        <div class="col-md-4">
                                            <label
                                                class="form-control-label <?php if($zobj['deployed']!=1 && (strtotime(date('Y-m-d', strtotime(date('Y-m-d') . " +3 days")))>=strtotime(date("Y-m-d",strtotime($zobj['deadlinedeployed']))))){?>text-red text-blink<?php } ?>">Production date</label>
                                            <input name="deadlinedeployed" class="form-control" type="text"
                                                value="<?php echo $zobj['deadlinedeployed'];?>" disabled="disabled">
                                        </div>
                                    </div>
                                    <?php if ($wfdata["nodes"][$zobj["wfstep"]][0]["elusrdepl"]==1 && $zobj['projapproved']==1 && $zobj['projconfirmed']==1 && $zobj['deployed']!=1) { ?>
                                    
                                    <hr>
                                    <div class="form-group row">
                                        <label class="form-control-label text-lg-left col-md-3">Environment</label>
                                        <div class="col-md-3">
                                            <select class="form-control" ng-model="deployin" name="deployin"
                                                onchange="$('.deplbut').show();">
                                                <option value="">Please select</option>
                                                <?php foreach($menudataenv as $thiskey=>$thisval){?><option
                                                    value="<?php echo $thiskey."#".$thisval['nameshort'];?>"><?php echo $thisval['name'];?>
                                                </option><?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 deplbut">
                                        <input name="pkgname" ng-model="pkgname" class="form-control" type="text"
                                                value="" placeholder="Package name" >
                                        </div>
                                        <div class="col-md-3 deplbut">
                                            <button class="btn btn-light" type="button"
                                                ng-click="deploybut('<?php echo $zobj['sname'];?>','<?php echo $_SESSION['user'];?>','<?php echo $usname;?>','<?php echo $zobj['projnum'];?>')"><i
                                                    class="mdi mdi-upload"></i>&nbsp; Deploy</button>
                                        </div>
                                    </div>
                                    <?php } ?>


                                    <?php 
              if(!empty($deployedin)){ ?>
              <div class="form-group row">
                                        <label class="form-control-label text-lg-left col-md-3">Deployment process</label>
                                        <div class="col-md-9">
                                        <ul class="list-group">
                                            <?php $deplin=json_decode($deployedin,true);
              foreach($deplin as $key=>$val){ ?>
                                            <li class="list-group-item"><?php if(!empty($val)){?><span
                                                    style="float:right;color:#009688"><i
                                                        class="mdi mdi-check"></i></span><?php } else { ?><span
                                                    style="float:right;color:#d50000"><i
                                                        class="mdi mdi-close"></i></span><?php } ?><?php echo $menudataenv[$key]['name'];?>
                                            </li>
                                            <?php } ?>
                                        </ul>
</div>
</div>

                               
                                <?php } ?>

                                    <input type="hidden" name="reqid" value="<?php echo $thisarray["p1"];?>">
                                    <input type="hidden" name="wfstep" value="<?php echo $zobj["wfstep"];?>">
                                    <div class="modal" id="updmodal" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-body">

                                                    <div class="form-group">
                                                        <textarea rows="5" name="requpdinfo"
                                                            class="form-control textarea"></textarea>
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-light btn-sm" name="addcomm" type="submit"><i
                                                            class="mdi mdi-content-save"></i>&nbsp;Save</button>&nbsp;
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        data-bs-dismiss="modal"><i
                                                            class="mdi mdi-check"></i>&nbsp;Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if (empty($deplinreq) && $wfdata["nodes"][$zobj["wfstep"]][0]["elusrchtask"]==1 && $zobj['assigned']==$_SESSION['user']) { ?>
                                    <div class="modal" id="chmodal" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-body">

                                                    <div class="form-group row">
                                                        <label class="form-control-label text-lg-left col-md-3">Change
                                                            task</label>
                                                        <div class="col-md-9"><input name="chtask" value=""
                                                                class="form-control" type="text"></div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label
                                                            class="form-control-label text-lg-left col-md-3">Info</label>
                                                        <div class="col-md-9"><textarea name="chinfo"
                                                                class="form-control textarea" type="text"
                                                                rows="2"></textarea></div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-light btn-sm" name="addchtask"
                                                        type="submit"><i
                                                            class="mdi mdi-content-save"></i>&nbsp;Save</button>&nbsp;
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        data-bs-dismiss="modal"><i
                                                            class="mdi mdi-check"></i>&nbsp;Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php     } ?>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header"><h4>Additional information</h4></div>
                            <div class="card-body  p-2">

                                <ul class="assignedto list-style-none pb-2 border-bottom">
                                <?php 
               $q=gTable::read("config_workflows","wgroups"," where wid='".$zobj["wid"]."'");
               $zobjin = $q->fetch(PDO::FETCH_ASSOC); 
               $tmp["wgroups"]=array();
               $tmp["wusers"]=array();
               $tmp["requsers"]=array();
               foreach(json_decode($zobjin["wgroups"],true) as $keyin=>$valin){
                 if($valin["type"]=="group"){ $tmp["wgroups"][]=$keyin; }
                 if($valin["type"]=="user"){  $tmp["wusers"][]=$keyin;  }
               };
               if(count($tmp["wgroups"])>0){
                $sqlin="select users from user_groups where group_latname in (".str_repeat('?,', count($tmp["wgroups"]) - 1) . '?)';
                $qin = $pdo->prepare($sqlin);
                $qin->execute($tmp["wgroups"]);
                $zobjin = $qin->fetchAll();
                foreach($zobjin as $val){
                  foreach(json_decode($val["users"],true) as $keyin=>$valin){
                    $tmp["wusers"][]=$keyin;
                  }
                }
               }
               if(count($tmp["wusers"])>0){
                 $sqlin="select mainuser, avatar, fullname from users where mainuser in (".str_repeat('?,', count($tmp["wusers"]) - 1) . '?)';
                 $qin = $pdo->prepare($sqlin);
                 $qin->execute($tmp["wusers"]);
                 $zobjin = $qin->fetchAll();
                 foreach($zobjin as $valin){
                  $tmp["requsers"][$valin["mainuser"]]["avatar"]=$valin["avatar"];
                  $tmp["requsers"][$valin["mainuser"]]["fullname"]=$valin["fullname"];
                 }
               }
               if(count($tmp["requsers"])>0){
                foreach($tmp["requsers"] as $valin){
                  echo '<li class="d-inline-block border-0 me-1"><img src="'.(!empty($valin["avatar"])?$valin["avatar"]:"/assets/images/avatar.svg").'"
                  width="40" alt="user" data-bs-toggle="tooltip" data-bs-placement="top" title=""
                  data-original-title="'.$valin["fullname"].'" class="rounded-circle"></li>';
                }
               }
            ?>

                                   
                                </ul>
<br>                               
                                <div class="alert alert-<?php echo $priorityarr[$zobj['priority']]["butcolor"];?>">
                                    <i class="mdi mdi-flag"></i>&nbsp;priority:
                                    <?php echo $priorityarr[$zobj['priority']]["name"];?><br><small
                                       ><?php echo $priorityarr[$zobj['priority']]["info"];?></small>
                                </div>
                                <?php include "reqnav.php";?>
                                <br><br>
                                <?php if($wfdatahc==1){?>
                                <?php if($zobj['projconfirmed']==1){
            $q=gTable::read("requests_confirmation","conffullname,confuser,confdate"," where reqid='".$thisarray["p1"]."'");
            $zobjin = $q->fetch(PDO::FETCH_ASSOC);
            ?>
            <div class="alert alert-success"> <i class="mdi mdi-account-circle-outline"></i>
            <b><a href="/browse/user/<?php echo $zobjin['confuser'];?>" target="_blank"><?php echo $zobjin['conffullname'];?></a></b> confirmed the project on
            <?php echo  date("d.m.Y",strtotime($zobjin['confdate']));?>
                                        </div>
                                <?php } else { ?>
                                <div class="alert alert-warning">Project is still not confirmed</div>
                                <?php } ?>
                                <?php } ?>
                                <?php if($wfdataha==1){?>
                                <?php if($zobj['projapproved']==1){
             $q=gTable::read("requests_approval","apprdate,appruser,apprfullname"," where reqid='".$thisarray["p1"]."'");
            $zobjin = $q->fetch(PDO::FETCH_ASSOC);
            ?>
            <div class="alert alert-success"> <i class="mdi mdi-account-circle-outline"></i>
            <b><a href="/browse/user/<?php echo $zobjin['appruser'];?>" target="_blank"><?php echo $zobjin['apprfullname'];?></a></b> approved the project on
            <?php echo  date("d.m.Y",strtotime($zobjin['apprdate']));?> </div>
                                <?php } else { ?>
                                    <div class="alert alert-warning">Project is still not approved</div>
                                <?php } ?>
                                <?php } ?>      
                                
                            </div>
                        </div>
                        <?php 
if ($wfdata["nodes"][$zobj["wfstep"]][0]["elusreff"]==1 && $zobj['assigned']==$_SESSION['user']) {
$q=gTable::read("requests_efforts","effdays"," where reqid='".$thisarray["p1"]."' and effuser='".$_SESSION["user"]."'");
$zobjin = $q->fetch(PDO::FETCH_ASSOC);
if($zobjin["effdays"]){ 
  $tempvar=$zobjin["effdays"];
  $q=gTable::read("calendar","sum(time_period) as timeperiod"," where subj_id='".$thisarray["p1"]."' and mainuser='".$_SESSION["user"]."'");
  $zobjin = $q->fetch(PDO::FETCH_ASSOC); 
  $temparr["percent"]=0;
  $temparr["timeperiod"]=0;
  if($zobjin["timeperiod"]){ 
    $temparr["timeperiod"]=round($zobjin["timeperiod"]/8, 2);
    $temparr["percent"]=round($temparr["timeperiod"]/$tempvar*100,2);
  } 
?>
<div class="card card-body">
                            <div class="row">
                                <div class="col pe-0 align-self-center">
                                    <h2 class="font-weight-light mb-0">Efforts</h2>
                                    <h6 class="text-muted"><?php echo $temparr["timeperiod"];?> of <?php echo $tempvar;?>
                                            <?php echo $website["effort_unit"];?></h6>
                                </div>
                                <div class="col text-end align-self-center">
                                    <div data-label="<?php echo $temparr["percent"];?>%" class="css-bar mb-0 css-bar-info css-bar-<?php echo $temparr["percent"];?>"></div>
                                </div>
                            </div>
                        </div>
                                <?php } ?>
                                <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php include "public/modules/footer.php";?>
        <?php include "public/modules/js.php";?>
        <script type="text/javascript" src="/assets/modules/requests/assets/js/ng-controller.js"></script>
        <script type="text/javascript">
        $(document).ready(function() {
            var sum = 0;
            $('.effgroup').each(function() {
                sum += parseFloat(this.value);
            });
            $("#budg_total").val(sum);
            if (sum > 0) {
                $('#effchanged').val('yes');
            };
            $(".effgroup").change(function() {
                $('#effchanged').val('yes');
            });
            $(".effgroup").keyup(function() {
                var sum = 0;
                $('.effgroup').each(function() {
                    sum += parseFloat(this.value);
                });
                $("#budg_total").val(sum);
            });
        });
        </script>
       <!-- <script type="text/javascript">
        var app = angular.module('ngApp', []);

        app.config(['$compileProvider',
            function($compileProvider) {
                $compileProvider.aHrefSanitizationWhitelist(/^\s*(https?|ftp|mailto|tel|file|blob):/);
            }
        ]);
        app.controller('ngCtrl', function($scope, $http) {
            $('.updinfo,.deplbut').hide();
            var efforts = $("#efforts").val();
            $scope.proj = {};
            $scope.proj.efforts = efforts;
            <?php if($tmp["confirmreq"]){?>
            $scope.confirmreq = function(reqid, user, wfstep, usfullname, projid) {
                $http({
                    method: 'POST',
                    data: {
                        'reqid': reqid,
                        'user': user,
                        'wfstep': wfstep,
                        'fullname': usfullname,
                        'project': projid
                    },
                    url: '/reqapi/confirmreq'
                }).then(function successCallback(response) {
                    notify(response.data, 'success');
                    $(".confirmreq" + reqid).hide();
                });
            };
        <?php } ?>
        <?php if($tmp["approvereq"]){?>
            $scope.approvereq = function(reqid, user, wfstep, usfullname, projid) {
                $http({
                    method: 'POST',
                    data: {
                        'reqid': reqid,
                        'user': user,
                        'wfstep': wfstep,
                        'fullname': usfullname,
                        'project': projid
                    },
                    url: '/reqapi/approvereq'
                }).then(function successCallback(response) {
                    notify(response.data, 'success');
                    $(".approvereq" + reqid).hide();
                });
            };
            <?php } ?>
            $scope.deploybut = function(reqid, user, usfullname, projid) {
                var env = $scope.deployin;
                var pkgname = $scope.pkgname;
                $http({
                    method: 'POST',
                    data: {
                        'reqid': reqid,
                        'user': user,
                        'fullname': usfullname,
                        'project': projid,
                        'pkgname': pkgname,
                        'env': env
                    },
                    url: '/reqapi/deployreq'
                }).then(function successCallback(response) {
                    notify(response.data, 'success');
                });
            };
            $scope.sendnext = function(nextid) {
                $scope.proj.nextstep = nextid;
                setTimeout(function() {
                    $("#sendnext").click();
                }, 1);
            } 
        });
        angular.bootstrap(document.getElementById("ngApp"), ['ngApp']);
        </script>-->
        <script src="/assets/js/tagsinput.min.js" type="text/javascript"></script>
        <?php include "public/modules/template_end.php";?>
</body>
</html>