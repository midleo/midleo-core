<?php
 array_push($brarr,array(
    "title"=>"Define new job",
    "link"=>"#modal-obj-form",
    "icon"=>"mdi-plus",
    "modal"=>true,
    "active"=>true,
  ));
  ?><div class="row pt-3">
    <div class="col-lg-2">
        <?php include "public/modules/sidebar.php"; ?>
    </div>
    <div class="col-lg-10">
        <div class="row" id="ngApp" ng-app="ngApp" ng-controller="ngCtrl">
            <div class="col-lg-9">
                <div class="card ngctrl p-0">
                    <table class="table table-vmiddle table-hover stylish-table mb-0">
                        <thead>
                            <tr>
                                <th class="text-center">Job</th>
                                <th class="text-center">Qmanager</th>
                                <th class="text-center">Application</th>
                                <th class="text-center">Server</th>
                                <th class="text-center">Repeat</th>
                                <th class="text-center" style="width:110px">Status</th>
                                <th class="text-center" style="width:120px;">Next run</th>
                                <th class="text-center" style="width:50px;"></th>
                            </tr>
                        </thead>
                        <tbody ng-init="getallMQINV()">
                            <tr ng-hide="contentLoaded">
                                <td colspan="8" style="text-align:center;font-size:1.1em;"><i
                                        class="mdi mdi-loading iconspin"></i>&nbsp;Loading...</td>
                            </tr>
                            <tr id="contloaded" class="hide"
                                dir-paginate="d in names | filter:search | orderBy:'lrun' | itemsPerPage:10"
                                pagination-id="prodx">
                                <td class="text-center">{{ d.jobid }}</td>
                                <td class="text-center">{{ d.qmgr }}</td>
                                <td class="text-center">{{ d.proj }}</td>
                                <td class="text-center">{{ d.srv }}</td>
                                <td class="text-center">{{ d.repeat }}</td>
                                <td class="text-center"><span
                                        class="badge badge-{{ d.statusinfo }}">{{ d.statusinfotxt }}</span></td>
                                <td class="text-center">{{ d.nrun }}</td>
                                <td class="text-center">
                                    <button type="button"
                                        ng-click="deletemqinv(d.id,d.proj,d.qmgr,'<?php echo $_SESSION["user"]; ?>')"
                                        class="btn btn-light btn-sm bg waves-effect"><i
                                            class="mdi mdi-close"></i></button>
                                </td>

                            </tr>
                        </tbody>
                    </table>
                    <dir-pagination-controls pagination-id="prodx" boundary-links="true"
                        on-page-change="pageChangeHandler(newPageNumber)"
                        template-url="/<?php echo $website['corebase'];?>assets/templ/pagination.tpl.html">
                    </dir-pagination-controls>
                </div>

                <div class="modal" id="modal-obj-form" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4>Create IBM MQ inventory job</h4>
                            </div>
                            <form name="form" ng-app>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label class="form-label">Qmanager</label>
                                        <select name="qmgr" class="form-control" ng-model="depl.qm">
                                            <option value="">Please select</option>
                                            <?php if($_SESSION["userdata"]["apparr"]){
    $sql="select * from env_appservers where serv_type='qm' and proj in (" . str_repeat('?,', count($_SESSION["userdata"]["apparr"]) - 1) . '?' . ")";
    $q = $pdo->prepare($sql);
    $q->execute($_SESSION["userdata"]["apparr"]);
    if($zobj = $q->fetchAll()){
        foreach($zobj as $val) {
    ?>
                                            <option
                                                value="<?php echo $val['serverdns']."#".$val['port']."#".$val['qmname']."#".$val['qmchannel']."#".$val['sslenabled']."#".$val['sslkey']."#".$val['sslpass']."#".$val['sslcipher']."#".$val['proj'];?>">
                                                <?php echo $val["qmname"];?>@<?php echo $val["serverdns"];?></option>
                                            <?php }}
 }  ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Environment</label>
                                        <?php if(is_array($menudataenv)){?>
                                        <select ng-model="depl.deplenv" class="form-control">
                                            <option value="">Please select</option>
                                            <?php 
    foreach($menudataenv as $keyenv=>$valenv) { ?>
                                            <option value="<?php echo $valenv['nameshort'];?>">
                                                <?php echo $valenv['name'];?></option>
                                            <?php  } ?>
                                        </select>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group" ng-show="depl.deplenv">
                                        <label class="form-label">Repeat interval</label>
                                        <select name="cronrepeat" ng-model="depl.repeat" class="form-control">
                                            <option value="">Please select</option>
                                            <?php foreach($typejob as $key=>$val) { ?><option
                                                value="<?php echo $key;?>">
                                                <?php echo $val;?></option><?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group" ng-show="depl.repeat">
                                        <label class="form-label">First run</label>
                                        <input name="jobnextrun" class="form-control date-time-picker-depl"
                                            id="jobnextrun" data-toggle="datetimepicker" data-target="#jobnextrun"
                                            type="text">
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i
                                            class="mdi mdi-close"></i>&nbsp;Close</button>
                                    <button ng-show="depl.repeat" type="button"
                                        class="waves-effect waves-light btn btn-info btn-sm"
                                        ng-click="createmqjob('')"><i class="mdi mdi-check"></i>&nbsp;Create
                                        job</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-3">
                <?php include $website['corebase']."public/modules/filterbar.php"; ?>
                <?php include $website['corebase']."public/modules/breadcrumbin.php"; ?>
            </div>
        </div>
    </div>

</div>
</div>