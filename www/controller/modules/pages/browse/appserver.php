<?php
if (!empty($thisarray['p2'])){
    $sql="select * from env_appservers where id=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($thisarray['p2']));
    if($val = $q->fetch(PDO::FETCH_ASSOC)){ ?>
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header border-bottom">Service information</div>
            <div class="card-body p-0">
                <table class="table table-hover small mb-0">
                    <tr>
                        <td>Description</td>
                        <td><?php echo $val["info"];?></td>
                    </tr>
                    <tr>
                        <td>Server DNS</td>
                        <td><?php echo $val["serverdns"];?></td>
                    </tr>
                    <tr>
                        <td>Server IP Address</td>
                        <td><?php echo $val["serverip"];?></td>
                    </tr>
                    <?php if(!empty($val["serverid"])){?>
                    <tr>
                        <td>Server Information</td>
                        <td><a class="btn btn-light btn-sm waves-effect" target="_blank"
                                href="/browse/server/<?php echo $val["serverid"];?>"><i
                                    class="mdi mdi-server-security"></i>&nbsp;Check</a></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td>Service Type</td>
                        <td><?php echo $typesrv[$val["serv_type"]];?></td>
                    </tr>
                    <?php if(!empty($val["tags"])){?>
                    <tr>
                        <td>Tags</td>
                        <td>
                            <?php   
  $kt=explode(",",$val["tags"]);
  foreach($kt as $keyin=>$valin){ if(strlen($valin) > 0){
    $valin=ltrim($valin, ' ');   $valin=rtrim($valin, ' ');
    echo '<a class="btn btn-light btn-sm waves-effect" href="/searchall/?sa=y&st=tag&fd='.$valin.'" title="'.$valin.'"><i class="mdi mdi-tag"></i>&nbsp;'.$valin.'</a>&nbsp;&nbsp;&nbsp;';
  }} ?>
                        </td>
                    </tr><?php } ?>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <ul class="nav nav-tabs customtab">

                <li class="nav-item"> <a class="nav-link waves-effect<?php echo $thisarray['p3']==""?" active":"";?>"
                        href="/browse/appserver/<?php echo $thisarray['p2'];?>" role="tab">Firewall</a> </li>
                <li class="nav-item"> <a class="nav-link waves-effect<?php echo $thisarray['p3']=="req"?" active":"";?>"
                        href="/browse/appserver/<?php echo $thisarray['p2'];?>/req" role="tab">Requests</a> </li>
                <li class="nav-item"> <a class="nav-link waves-effect<?php echo $thisarray['p3']=="obj"?" active":"";?>"
                        href="/browse/appserver/<?php echo $thisarray['p2'];?>/obj" role="tab">Objects</a> </li>
                <li class="nav-item"> <a
                        class="nav-link waves-effect<?php echo $thisarray['p3']=="diagrams"?" active":"";?>"
                        href="/browse/appserver/<?php echo $thisarray['p2'];?>/diagrams" role="tab">Diagrams</a> </li>
                <li class="nav-item"> <a
                        class="nav-link waves-effect<?php echo $thisarray['p3']=="track"?" active":"";?>"
                        href="/browse/appserver/<?php echo $thisarray['p2'];?>/track" role="tab">Tracking</a> </li>
            </ul>
        </div>
        <div class="card">

            <?php if($thisarray['p3']==""){?>
            <div class="card-body p-0">


                <div class="table-responsive">
                    <table class="datainfo display nowrap table row-border" cellspacing="0" width="100%"
                        style="margin-top:0px!important;">
                        <thead>
                            <tr>
                                <th>Source IP</th>
                                <th data-priority="1">Destination IP</th>
                                <th>Source DNS</th>
                                <th data-priority="2">Destination DNS</th>
                                <th>Port</th>
                                <th>Comment</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  $sqlin="select * from env_firewall where srcip=? or ( destip=? and port='".$val["port"]."')";
    $qin = $pdo->prepare($sqlin);
    if($qin->execute(array($val["serverip"],$val["serverip"]))){
          $zobjin = $qin->fetchAll();
          foreach($zobjin as $valin) { ?>
                            <tr>
                                <td <?php if($valin["srcip"] == $val["serverip"]){?>class="text-red" <?php } ?>>
                                    <?php echo $valin["srcip"];?></td>
                                <td <?php if($valin["destip"] == $val["serverip"]){?>class="text-red" <?php } ?>>
                                    <?php echo $valin["destip"];?></td>
                                <td><?php echo $valin["srcdns"];?></td>
                                <td><?php echo $valin["destdns"];?></td>
                                <td <?php if($valin["destip"] == $val["serverip"] && $valin["port"] == $val["port"]){?>class="text-red"
                                    <?php } ?>><?php echo $valin["port"];?></td>
                                <td><?php echo $valin["info"];?></td>
                            </tr>
                            <?php }} ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php } ?>
            <?php if($thisarray['p3']=="req"){?>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="datainfo display nowrap table row-border" cellspacing="0" width="100%"
                        style="margin-top:0px!important;">
                        <thead>
                            <tr>
                                <th>Request</th>
                                <th>Application</th>
                                <th>Request name</th>
                                <th>Date created</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  $sqlin="select t.sname,t.reqapp,t.reqname,t.created,i.reqid from requests t, requests_data i where t.sname=i.reqid and i.reqdata like ?";
    $qin = $pdo->prepare($sqlin); 
    if($qin->execute(array("%".$val["serverdns"]."%"))){
          $zobjin = $qin->fetchAll();
          foreach($zobjin as $valin) {           ?>
                            <tr>
                                <td><a href="/reqinfo/<?php echo $valin["sname"];?>"
                                        target="_blank"><?php echo $valin["sname"];?></a></td>
                                <td><?php echo $valin["reqapp"];?></td>
                                <td><?php echo $valin["reqname"];?></td>
                                <td><?php echo $valin["created"];?></td>
                            </tr>
                            <?php }} ?>
                        </tbody>
                    </table>
                </div>

            </div>
            <?php } ?>

            <?php if($thisarray['p3']=="obj"){?>
            <div class="card-body" id="myobj" style="height: calc(50vw);">
            </div>
            <?php } ?>
            <?php if($thisarray['p3']=="track"){?>
            <div class="card-body p-0">

                <div class="table-responsive">
                    <table class="datainfo display nowrap table row-border" cellspacing="0" width="100%"
                        style="margin-top:0px!important;">
                        <thead>
                            <tr>
                                <th>Who</th>
                                <th>Tracking info</th>
                                <th>When</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  
                                    $sql = "select * from tracking where appsrvid=? ".($dbtype=="oracle"?" and ROWNUM <= 6 order by id desc":" order by id desc limit 6");
                                    $q = $pdo->prepare($sql); 
                                    $q->execute(array($thisarray['p2']));
                                
                        if($zobj = $q->fetchAll()){
                          foreach($zobj as $val) {
                            ?><tr>
                                <td class="text-start"><a href="/browse/user/<?php echo $val['whoid'];?>"
                                        target="_blank"><?php echo $val['who'];?></a></td>
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
            <?php if($thisarray['p3']=="diagrams"){?>
            <div class="card-body p-0">

                <div class="table-responsive">
                    <table class="datainfo display nowrap table row-border" cellspacing="0" width="100%"
                        style="margin-top:0px!important;">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Released date</th>
                                <th>Released by</th>
                                <th>Tags</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  $sqlin="select tags,desid,desname,desdate,desuser from config_diagrams where appsrvlist like ?";
       $qin = $pdo->prepare($sqlin); 
       if($qin->execute(array("%".$thisarray['p2']."%"))){
             $zobjin = $qin->fetchAll();
             foreach($zobjin as $valin) {           ?>
                            <tr>
                                <td><a href="/draw/<?php echo $valin["desid"];?>"
                                        target="_blank"><?php echo $valin["desname"];?></a></td>
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
        </div>
    </div>
</div>
<?php  } else {
            textClass::PageNotFound();
        }
} else {
    textClass::PageNotFound();
}