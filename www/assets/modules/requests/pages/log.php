<?php if (!empty($thisarray["p2"])) {
  $q=gTable::countAll("requests"," where sname='".$thisarray["p2"]."'");
  if($q>0){
   ?>

    <?php 
    $q=gTable::read("tracking",($dbtype=="oracle"?"to_char(what) as what,who,trackdate":"*"),(DBTYPE=="oracle"?" where reqid='".$thisarray["p2"]."' and ROWNUM <= 60":" where  reqid='".$thisarray["p2"]."' limit 60"));
    if($zobj = $q->fetchAll()){ ?>
    <div class="row">
          <div class="col-md-3 position-relative">
              <input type="text" ng-model="search" class="form-control topsearch dtfilter" placeholder="Search in logs">
              <span class="searchicon"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-search" xlink:href="/assets/images/icon/midleoicons.svg#i-search"/></svg>
          </div>
  </div><br>
    <div class="row"><div class="col-md-8">
    <div class="card">
  <div class="card-body p-0">
      <table id="data-table" class="table table-hover stylish-table mb-0" aria-busy="false">
        <thead>
          <tr>
            <th data-column-id="date" data-order="desc"  data-width="150px">Date</th>
             <th data-column-id="who"  data-width="120px">User</th>
            <th data-column-id="log">Log</th>
          </tr>
        </thead>
        <tbody><?php
  foreach($zobj as $val) {
    ?><tr><td><?php echo $val['trackdate'];?></td><td><?php echo $val['who'];?></td><td><?php echo $val['what'];?></td></tr>
          <?php } ?>
        </tbody>
      </table></div></div></div>
      <div class="col-md-4">
      <div class="card"  >
       <div class="card-body p-0" >
                <?php include "reqnav.php";?>
                </div>
        </div>
        </div>
      </div>
    <?php } else { ?>
    <div class="row"><div class="col-md-8">
      <div class="alert alert-light">No information about this Request till now</div>
      </div></div>
    <?php } ?>
  </div>
</div>  
<?php } else { textClass::PageNotFound(); }
} else { textClass::PageNotFound(); } ?>
