<div class="row">
          <div class="col-md-3 position-relative">
              <input type="text" ng-model="search" class="form-control topsearch dtfilter" placeholder="Filter">
              <span class="searchicon"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-search" xlink:href="/assets/images/icon/midleoicons.svg#i-search"/></svg>
          </div>
  </div><br>
  <div class="card">
<div class="card-body p-0">
<div class="table-responsive">
<table class="datainfo display nowrap table row-border"  cellspacing="0" width="100%" style="padding-top:0px !important;">
<thead>
<tr>
<th>Status</th>
<th data-priority="1">Server</th>
<th>OS</th>
<th data-priority="2">Server IP</th>
<th>Last update</th>
<th><i class="mdi mdi-tag"></i>&nbsp;Tags</th>
</tr>
</thead>
<tbody>
<?php
if(isset($_SESSION["user"])){
    $ugr="";
    if(!empty($ugroups)){ 
      foreach($ugroups as $key=>$val){ if($key){ $ugr.=$key.","; }}
      $ugr=rtrim($ugr, ",");
    } 
    $partsgrid = explode(",", $ugr);
    $sqlin = "select * from env_servers where srvpublic='1' or groupid in (" . str_repeat('?,', count($partsgrid) - 1) . '?' . ")";
    $qin = $pdo->prepare($sqlin);
    $qin->execute($partsgrid);
} else {
    $sqlin = "select * from env_servers where srvpublic='1'";
    $qin = $pdo->prepare($sqlin);
    $qin->execute();
}
    if ($zobjin = $qin->fetchAll()) {
    foreach ($zobjin as $valin) {
        ?>
 <tr>
  <td><span class="badge badge-<?php if (round(abs(strtotime(date('Y-m-d H:i:s')) - strtotime($valin['servupdated'])) / 60) <= $valin["updperiod"]) {echo "success";} else {echo "danger";}?>"><?php if (round(abs(strtotime(date('Y-m-d H:i:s')) - strtotime($valin['servupdated'])) / 60) <= $valin["updperiod"]) {echo "Online";} else {echo "No update";}?></span></td>
  <td><a href="/?" class="font-medium link"><?php echo $valin["serverdns"]; ?></a></td>
  <td><a href="/?" class="font-bold link"><?php echo $valin["servertype"]; ?></a></td>
  <td><?php echo $valin["serverip"]; ?></td>
  <td><?php echo $valin["servupdated"]; ?></td>
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
<?php }}?>
</tbody>
</table>
</div>
</div>
</div>