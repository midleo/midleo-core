<div class="row pt-3">
    <div class="col-lg-2">
        <?php include "public/modules/sidebar.php"; ?>
    </div>
    <div class="col-lg-10">
        <div class="row" id="ngApp" ng-app="ngApp" ng-controller="ngCtrl">
            <div class="col-lg-9">
                <div class="card p-0">
                    <table id="data-table" class="table table-hover stylish-table mb-0" aria-busy="false"
                        style="margin-top:0px!important;">
                        <thead>
                            <tr>
                                <th data-align="left" data-header-align="left">ErrorCode</th>
                                <th data-align="left" data-header-align="left">Loglevel</th>
                                <th data-align="left" data-header-align="left">Message</th>
                                <th data-align="left" data-header-align="left">Qmanager</th>
                                <th data-align="left" data-header-align="left">Object</th>
                                <th data-align="left" data-header-align="left">Log</th>
                                <th data-align="left" data-header-align="left">Alert time</th>
                            </tr>
                        </thead>
                        <tbody><?php
        $sql="select * from mon_alerts where appsrvid=?";
        $q = $pdo->prepare($sql); 
        $q->execute(array($thisarray['p1'])); 
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
            <div class="col-md-3">
                <?php include "public/modules/filterbar.php"; ?>
                <?php include "public/modules/breadcrumbin.php"; ?>
            </div>
        </div>
    </div>
</div>