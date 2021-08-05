<?php
if (!empty($thisarray['p2'])){
    $sql="select * from env_servers where serverid=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($thisarray['p2']));
    if($zobj = $q->fetchAll()){
          foreach($zobj as $key=>$val) { ?>
    <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <div class=" text-center"> <svg class="midico midico-outline" style="height:55px;width:55px;"><use href="/assets/images/icon/midleoicons.svg#i-server" xlink:href="/assets/images/icon/midleoicons.svg#i-server"/></svg>
                                    <h4 class="card-title mt-2"><?php echo $val["serverdns"];?></h4>
                                    <h6 class="card-subtitle"><?php echo $val["info"];?></h6>
                                </div>
                            </div>
                            <div>
                                <hr>
                                
                            </div>
                            <div class="card-body"> 
                                <small class="text-muted">Server IP</small>
                                <h6><?php echo $val["serverip"];?></h6>
                                <small class="text-muted">Server OS</small>
                                <h6><?php echo $val["servertype"];?></h6>
                                <?php if(!empty($val["pluid"])){
                                    $sql="select placename from env_places where pluid=?";
                                    $q = $pdo->prepare($sql); 
                                    $q->execute(array($val["pluid"])); 
                                    ?>
                                <small class="text-muted">Place</small>
                                <h6><a href="/browse/place/<?php echo $val["pluid"];?>" target="_blank"><?php echo $q->fetchColumn();?></a></h6>
                                <?php } ?>
                                <?php if(!empty($val["tags"])){?>
                                <small class="text-muted">Tags</small>
                                <h6>
<?php   
  $kt=explode(",",$val["tags"]);
  foreach($kt as $keyin=>$valin){ if(strlen($valin) > 0){
    $valin=ltrim($valin, ' ');   $valin=rtrim($valin, ' ');
    echo '<a class="btn btn-light btn-sm waves-effect" href="/searchall/?sa=y&st=tag&fd='.$valin.'" title="'.$valin.'"><i class="mdi mdi-tag"></i>&nbsp;'.$valin.'</a>&nbsp;&nbsp;&nbsp;';
  }} ?>
</h6><?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="card">
                            <ul class="nav nav-tabs customtab" >
                            <li class="nav-item"> <a class="nav-link waves-effect<?php echo $thisarray['p3']=="appsrv"?" active":"";?>" href="/browse/server/<?php echo $thisarray['p2'];?>/appsrv"
                                        role="tab">App servers</a> </li>
                                <li class="nav-item"> <a class="nav-link waves-effect<?php echo $thisarray['p3']==""?" active":"";?>" href="/browse/server/<?php echo $thisarray['p2'];?>"
                                        role="tab">Disks</a> </li>
                                <li class="nav-item"> <a class="nav-link waves-effect<?php echo $thisarray['p3']=="prog"?" active":"";?>" href="/browse/server/<?php echo $thisarray['p2'];?>/prog"
                                        role="tab">Programs</a> </li>
                                <li class="nav-item"> <a class="nav-link waves-effect<?php echo $thisarray['p3']=="net"?" active":"";?>" href="/browse/server/<?php echo $thisarray['p2'];?>/net"
                                        role="tab">Network</a> </li>
                                <li class="nav-item"> <a class="nav-link waves-effect<?php echo $thisarray['p3']=="diagrams"?" active":"";?>" href="/browse/server/<?php echo $thisarray['p2'];?>/diagrams"
                                        role="tab">Diagrams</a> </li>
                                <li class="nav-item"> <a class="nav-link waves-effect<?php echo $thisarray['p3']=="track"?" active":"";?>" href="/browse/server/<?php echo $thisarray['p2'];?>/track"
                                        role="tab">Tracking</a> </li>
                            </ul>
</div>
<div class="row">
          <div class="col-md-3 position-relative">
              <input type="text" ng-model="search" class="form-control topsearch dtfilter" placeholder="Filter">
              <span class="searchicon"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-search" xlink:href="/assets/images/icon/midleoicons.svg#i-search"/></svg>
          </div>
  </div><br>
<div class="card">
                           
<?php if($thisarray['p3']==""){?>
                                    <div class="card-body p-0">

                                    <div class="table-responsive">
                                    <table class="datainfo display nowrap table row-border"  cellspacing="0" width="100%" style="margin-top:0px!important;">
                                        <thead>
                                            <tr>
                                                <th>Device</th>
                                                <th>Mount Point</th>
                                                <th>Type</th>
                                                <th>Options</th>
                                                <th>Disc total</th>
                                                <th>Disc free</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                      <?php foreach(json_decode($val["serverdisc"],true) as $keyin=>$valin){ ?>
                                    <tr>
                                    <td><?php echo $valin["device"];?></td>
                                    <td><?php echo $valin["mountpoint"];?></td>
                                    <td><?php echo $valin["fstype"];?></td>
                                    <td><?php echo $valin["opts"];?></td>
                                    <td><?php echo serverClass::fsConvert($valin["disk_usage"]["total"]);?></td>
                                    <td><?php echo serverClass::fsConvert($valin["disk_usage"]["free"]);?></td>


                                    </tr>
                                     <?php }?>
                                      

                                      </tbody>
                                   </table>
                                </div>

                                    </div>
                                      <?php } ?>
                                <?php if($thisarray['p3']=="prog"){?>
                                    <div class="card-body p-0">
                                    
                                    <div class="table-responsive">
                                    <table class="datainfo display nowrap table row-border"  cellspacing="0" width="100%" style="margin-top:0px!important;">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Version</th>
                                                <th>Publisher</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                      <?php foreach(json_decode($val["serverprog"],true) as $keyin=>$valin){ ?>
                                    <tr>
                                    <td><?php echo $valin["name"];?></td>
                                    <td><?php echo $valin["version"];?></td>
                                    <td><?php echo $valin["publisher"];?></td>

                                    </tr>
                                     <?php }?>
                                      

                                      </tbody>
                                   </table>
                                </div>


                                    </div>
                                    <?php }  if($thisarray['p3']=="net"){?>
                                    <div class="card-body p-0">
                                    
                                    <div class="table-responsive">
                                    <table class="datainfo display nowrap table row-border"  cellspacing="0" width="100%" style="margin-top:0px!important;">
                                        <thead>
                                            <tr>
                                                <th>Adapter</th>
                                                <th>Interface</th>
                                                <th>Address</th>
                                                <th>Netmask</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                      <?php if($val["servernet"]){ 
                                          foreach(json_decode($val["servernet"],true) as $keyin=>$valin){ 
if(is_array($valin["MAC"]) && $valin["MAC"]){   ?>
<tr><td><?php echo $valin["name"];?></td><td>MAC</td><td><?php echo $valin["MAC"]["addr"];?></td><td><?php echo $valin["MAC"]["netmask"];?></td></tr>
<?php }
if(is_array($valin["IPv4"]) && $valin["IPv4"]){   ?>
<tr><td><?php echo $valin["name"];?></td><td>IPv4</td><td><?php echo $valin["IPv4"]["addr"];?></td><td><?php echo $valin["IPv4"]["netmask"];?></td></tr>
<?php } 
if(is_array($valin["IPv6"]) && $valin["IPv6"]){   ?>
<tr><td><?php echo $valin["name"];?></td><td>IPv6</td><td><?php echo $valin["IPv6"]["addr"];?></td><td><?php echo $valin["IPv6"]["netmask"];?></td></tr>
<?php }?>
                                     <?php }} ?>
                                      

                                      </tbody>
                                   </table>
                                </div>


                                    </div>
                                    <?php } ?>
                                    <?php if($thisarray['p3']=="appsrv"){?>
                                        <div class="card-body p-0">
                                       
                                       <div class="table-responsive">
                                       <table class="datainfo display nowrap table row-border"  cellspacing="0" width="100%" style="margin-top:0px!important;">
                                           <thead>
                                               <tr>
                                                   <th>Application server</th>
                                                   <th>Type</th>
                                                   <th>Listener port</th>
                                                   <th>Tags</th>
                                               </tr>
                                           </thead>
                                           <tbody>
                                           <?php  $sqlin="select id,tags,serv_type,appsrvname,port from env_appservers where serverid=?";
    $qin = $pdo->prepare($sqlin); 
    if($qin->execute(array($thisarray['p2']))){
          $zobjin = $qin->fetchAll();
          foreach($zobjin as $valin) {           ?>
<tr>
               <td><a href="/browse/appserver/<?php echo $valin["id"];?>" target="_self"><?php echo $valin["appsrvname"];?></a></td>
               <td><?php echo $typesrv[$valin["serv_type"]];?></td>
               <td><?php echo $valin["port"];?></td>
               <td><?php if (!empty($valin['tags'])) {
            $clientkeyws = $valin['tags'];
            $kt = explode(",", $clientkeyws);
            foreach ($kt as $key => $val) {if ($val != " " and strlen($val) < 70 and strlen($val) > 0) {
                $val = ltrim($val, ' ');
                $val = rtrim($val, ' ');
                echo '<a class="badge badge-secondary waves-effect" style="margin-right:5px;margin-top:5px;" href="/searchall/?sa=y&st=tag&fd=' . $val . '">' . $val . '</a>';
            }}
        }?></td>



               </tr>

<?php }} ?>
                                           </tbody>
                                   </table>
                                </div>
                                    </div>
                                    <?php } ?>
                                    <?php if($thisarray['p3']=="diagrams"){?>
                                    <div class="card-body p-0">
                                       
                                    <div class="table-responsive">
                                    <table class="datainfo display nowrap table row-border"  cellspacing="0" width="100%" style="margin-top:0px!important;">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Released date</th>
                                                <th>Released by</th>
                                                <th>Tags</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                    <?php  $sqlin="select tags,desid,desname,desdate,desuser from config_diagrams where srvlist like ?";
    $qin = $pdo->prepare($sqlin); 
    if($qin->execute(array("%".$thisarray['p2']."%"))){
          $zobjin = $qin->fetchAll();
          foreach($zobjin as $valin) {           ?>
          <tr>
               <td><a href="/draw/<?php echo $valin["desid"];?>" target="_blank"><?php echo $valin["desname"];?></a></td>
               <td><?php echo textClass::ago($valin["desdate"]);?></td>
               <td><?php echo $valin["desuser"];?></td>
               <td><?php if (!empty($valin['tags'])) {
            $clientkeyws = $valin['tags'];
            $kt = explode(",", $clientkeyws);
            foreach ($kt as $key => $val) {if ($val != " " and strlen($val) < 70 and strlen($val) > 0) {
                $val = ltrim($val, ' ');
                $val = rtrim($val, ' ');
                echo '<a class="badge badge-secondary waves-effect" style="margin-right:5px;margin-top:5px;" href="/searchall/?sa=y&st=tag&fd=' . $val . '">' . $val . '</a>';
            }}
        }?></td>      
               </tr>          
      <?php }} ?>
      </tbody>
                                   </table>
                                </div>
                                    </div>
                                    <?php } ?>
                                    <?php if($thisarray['p3']=="track"){?>
                                    <div class="card-body p-0">
                                       
                                    <div class="table-responsive">
                                    <table class="datainfo display nowrap table row-border"  cellspacing="0" width="100%" style="margin-top:0px!important;">
                                        <thead>
                                            <tr>
                                                <th>Who</th>
                                                <th>Tracking info</th>
                                                <th>When</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                    <?php  
                                    $sql = "select * from tracking where srvid like ? or srvid like ? ".($dbtype=="oracle"?" and ROWNUM <= 6 order by id desc":" order by id desc limit 6");
                                    $q = $pdo->prepare($sql); 
                                    $q->execute(array("%".$thisarray['p2']."%","%".$val["serverdns"]."%"));
                                
                        if($zobj = $q->fetchAll()){
                          foreach($zobj as $val) {
                            ?><tr><td class="text-start"><a href="/browse/user/<?php echo $val['whoid'];?>" target="_blank"><?php echo $val['who'];?></a></td>
                            <td class="text-start"><?php echo $val['what'];?></td>
                            <td class="text-end"><?php echo textClass::getTheDay($val['trackdate']);?></td>
                                       </tr>
                                       <?php }}
                                    ?>
      </tbody>
                                   </table>
                                </div>
                                    </div>
                                    <?php } ?>
                        </div>
                    </div>
                </div>
     <?php  } } else { 
            textClass::PageNotFound();
        }
} else {
    textClass::PageNotFound();
}

