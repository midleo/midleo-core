<div class="row">
          <div class="col-md-3 position-relative">
              <input type="text" ng-model="search" class="form-control topsearch dtfilter" placeholder="Filter">
              <span class="searchicon"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-search" xlink:href="/assets/images/icon/midleoicons.svg#i-search"/></svg>
          </div>
  </div><br>
  <div class="card" id="ngApp" ng-app="ngApp" ng-controller="ngCtrl">
        <div class="card-body p-0"> 
     <table id="data-table" class="table table-hover stylish-table" aria-busy="false" style="margin-top:0px!important;">
        <thead>
          <tr>
            <th  data-align="left" data-header-align="left">ErrorCode</th>
            <th data-align="left" data-header-align="left"  >Loglevel</th>
             <th  data-align="left" data-header-align="left" >Message</th>
             <th  data-align="left" data-header-align="left" >Qmanager</th>
             <th  data-align="left" data-header-align="left" >Object</th>
             <th  data-align="left" data-header-align="left" >Log</th>
             <th  data-align="left" data-header-align="left" >Alert time</th>
          </tr>
        </thead>
        <tbody><?php
        $sql="select * from mon_alerts where srv=?";
        $q = $pdo->prepare($sql); 
        $q->execute(array($zobj["srv"])); 
        if($zobjin = $q->fetchAll()){
            foreach($zobjin as $valin) { ?>
<tr>
<td><?php echo $valin["errorcode"];?></td>
<td><?php echo $valin["loglevel"];?></td>
<td><?php echo $valin["errormessage"];?></td>
<td><?php echo $valin["appsrv"];?></td>
<td><?php echo $valin["appobject"];?></td>
<td><?php echo $valin["errorplace"];?></td>
<td><?php echo $valin["alerttime"];?></td>
</tr>

           <?php }
        } else {

        } ?>


        </tbody>
      </table>
      </div>
      </div>