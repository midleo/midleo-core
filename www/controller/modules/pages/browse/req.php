<?php
$sql="select * from requests where sname=?";
$q = $pdo->prepare($sql);
if($q->execute(array($thisarray['p2']))){
      $zobj = $q->fetch(PDO::FETCH_ASSOC); 
if(!is_array($zobj) and empty($zobj)){ textClass::PageNotFound(); } else { 
  
  if(!empty($zobj["wid"])){
    $sql="select ".(DBTYPE=='oracle'?"to_char(wdata) as wdata":"wdata")." from config_workflows where wid=?";
    $qin = $pdo->prepare($sql); 
    $qin->execute(array($zobj["wid"]));
    if($zobjin = $qin->fetch(PDO::FETCH_ASSOC)){ 
     $menudata=!empty($zobjin["wdata"])?json_decode($zobjin["wdata"],true):json_decode("[{}]",true);    
     $menudatalaststep=end(array_keys($menudata));
    } 
    } else {
     $menudata=json_decode("[{}]",true); 
    }
  ?>

 <div class="card">
 <div class="card-header"><h2><?php echo $zobj['reqname'];?></h2></div>
   <div class="card-body card-padding" >
     <br>
    <div class="row">
      <div class="col-md-8 form-horizontal">
         <div class="form-group">
                  <label class="col-md-3 control-label">Name</label>
                  <div class="col-md-9"><div class="fg-line"><input name="reqname" type="text" class="form-control" value="<?php echo $zobj['reqname'];?>"></div></div>
                </div> 
                <?php if(!empty($zobj['reqapp'])){ 
  $sql="select appinfo from config_app_codes where appcode=?";
 $q = $pdo->prepare($sql);
if($q->execute(array($zobj['reqapp']))){ 
  $zobjin = $q->fetch(PDO::FETCH_ASSOC); ?> <div class="form-group">
                  <label class="col-md-3 control-label" style="padding-top:0;">Application</label>
                  <div class="col-md-9"><div class="fg-line"><input type="text" class="form-control" value="<?php echo $zobjin['appinfo'];?>"></div></div>
                </div><?php }} ?>
                 <div class="form-group">
                  <label class="col-md-3 control-label">Request Info</label>
                  <div class="col-md-9"><div class="fg-line"><textarea rows="5" name="reqinfo" class="form-control textarea"><?php echo $zobj['info'];?></textarea></div></div>
                </div>
                <div class="form-group" id="requser"><input type="hidden" name="requser" value="<?php echo $zobj['requser'];?>">
                  <label class="col-md-3 control-label" style="padding-top:0;">Requested by</label>
                  <div class="col-md-9"><div class="fg-line"><a href="/browse/user/<?php echo $zobj['requser'];?>"><?php echo $zobj['requser'];?></a></div></div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label <?php if(strtotime(date('Y-m-d', strtotime(date('Y-m-d') . " +3 days")))>=strtotime(date("Y-m-d",strtotime($zobj['deadline'])))){?>text-red text-blink<?php } ?>">Deadline ready</label>
                    <div class="col-md-3"><div class="fg-line"><input name="deadline" class="form-control" type="text" value="<?php echo $zobj['deadline'];?>" disabled="disabled"></div></div>
                    <div class="col-md-6 control-label" style="text-align:left;">Ready until</div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label <?php if(strtotime(date('Y-m-d', strtotime(date('Y-m-d') . " +3 days")))>=strtotime(date("Y-m-d",strtotime($zobj['deadlinedeployed'])))){?>text-red text-blink<?php } ?>">Deadline prod</label>
                    <div class="col-md-3"><div class="fg-line"><input name="deadlinedeployed" class="form-control" type="text"  value="<?php echo $zobj['deadlinedeployed'];?>" disabled="disabled"></div></div>
                    <div class="col-md-6 control-label" style="text-align:left;">Production date</div>
                  </div>
      </div>
      <div class="col-md-4">
        <div class="d-grid btnbox" role="group" >
  <?php if(!empty($_SESSION['user'])){?><a href="/reqinfo/<?php echo $thisarray['p2'];?>" target="_parent" class="btn btn-primary waves-effect"><i class="mdi mdi-sitemap"></i>&nbsp;Open the request</a><?php } ?>
        </div>
        <br>
        
       <?php if($zobj['bstep']!="9999"){ ?>
         <a class="list-group-item media" href="" style="margin-bottom:15px!important;">
        <div class="pull-left">
          <i class="mdi mdi-account-outline mdi-24px" style="padding-top:5px;"></i>
         </div>
        <div class="media-body">
         <div class="lgi-heading">Assignment group</div>
          <small class="lgi-text"><?php if($zobj['requser']==$zobj['assigned']){ echo "Customer"; } else{ echo $menudata[$zobj['bstep']]["nameen"]; } ?></small>
          </div>
         </a>
        <?php   }?>
        <?php if($zobj['deployed']!=1){ 
           if($zobj['assigned']=="canceled"){ echo ' <div class="alert alert-warning" style="color:#000;"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-warning" xlink:href="/assets/images/icon/midleoicons.svg#i-warning" /></svg>&nbsp;status: Canceled</div>'; }
           elseif(!empty($zobj['assigned'])){ echo ' <div class="alert alert-info"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-warning" xlink:href="/assets/images/icon/midleoicons.svg#i-warning" /></svg>&nbsp;status: In progress</div>'; }
          else { echo ' <div class="alert alert-secondary"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-warning" xlink:href="/assets/images/icon/midleoicons.svg#i-warning" /></svg>&nbsp;status: Not assigned</div>'; }
        ?>
        <?php } else { ?>
        <div class="alert alert-success"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-warning" xlink:href="/assets/images/icon/midleoicons.svg#i-warning" /></svg>&nbsp;status: Completed</div>
        <?php } ?>
      </div>
     </div>
   </div>
   
   </div>

<?php }} ?>