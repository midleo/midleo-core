<div class="modal" id="modal-depl-form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="post" name="deplform">
            <div class="modal-header">Deployment form</div>
                <div class="modal-body form-horizontal">
                    <div class="form-group row">
                        <input style="display:none;" id="selectedobj" value="{{selectedid | json}}">
                        <label class="form-control-label text-lg-right col-md-3">Environment</label>
                        <?php if(is_array($menudataenv)){?>
                        <div class="col-md-9"> <select ng-required="true" ng-model="depl.deplenv" class="form-control">
                                <option value="">Please select</option>
                                <?php 
    foreach($menudataenv as $keyenv=>$valenv) { ?>
                                <option value="<?php echo $valenv['nameshort'];?>"><?php echo $valenv['name'];?>
                                </option>
                                <?php  } ?>
                            </select>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group row" ng-show="depl.deplenv">
                        <label class="form-control-label text-lg-right col-md-3">Qmanager</label>
                        <div class="col-md-9">
                            <select name="deplqmgr" ng-required="true" ng-model="depl.qm" class="form-control">
                                <option value="">Please select</option>
                                <?php 
  $sql="select * from env_appservers where serv_type='qm' and proj=? group by qmname";
  $q = $pdo->prepare($sql);
  $q->execute(array($thisarray['p2']));
  if($zobj = $q->fetchAll()){
    foreach($zobj as $row) { ?>
                                <option
                                    value="<?php echo $row['serverdns']."#".$row['port']."#".$row['qmname']."#".$row['qmchannel']."#".$row['sslenabled']."#".$row['sslkey']."#".$row['sslpass']."#".$row['sslcipher'];?>">
                                    <?php echo $row['qmname'];?></option>
                                <?php  }
  }  ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row" ng-show="depl.qm">
                                <label class="form-control-label text-lg-right col-md-3">Request Number</label>
                                <div class="col-md-9"> <input type="text" ng-model="depl.reqname" id="reqauto" class="form-control" />
                                    <input type="text"  id="reqname" name="reqname" style="display:none;" /> </div>
                            </div>
                    <div class="form-group row" ng-show="depl.qm">
                        <label class="form-control-label text-lg-right col-md-3">Job time</label>
                        <div class="col-md-9">
                        <input name="jobnextrun" class="form-control date-time-picker-depl"  id="jobnextrun" data-toggle="datetimepicker" data-target="#jobnextrun" type="text">
                        </div>
                    </div>
                    <div style="display:block;" class="text-center">
                        <div class="loading"><svg class="circular" viewBox="25 25 50 50">
                                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2"
                                    stroke-miterlimit="10" /> </svg>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-x" xlink:href="/assets/images/icon/midleoicons.svg#i-x"/></svg>&nbsp;Close</button>
                    <button ng-show="depl.reqname" type="button" class="waves-effect waves-light btn btn-light btn-sm"
                        ng-click="createjob('<?php echo $thisarray['p2'];?>','qm','mqenv_mq_<?php echo $thisarray['p1'];?>','<?php echo $thisarray['p1'];?>','<?php echo $page;?>')"><i
                            class="mdi mdi-play-circle-outline"></i>&nbsp;Create job</button>
                    <button ng-show="depl.reqname" type="button"
                        ng-click="deplform.$valid && deployMQSelected('<?php echo $thisarray['p1'];?>','<?php echo $thisarray['p2'];?>');"
                        class="waves-effect waves-light btn btn-primary btn-sm"><i
                            class="mdi mdi-upload"></i>&nbsp;Deploy</button>
                    
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal" id="modal-depl-response" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="width:900px;">
        <div class="modal-content">
        <div class="modal-header">Deployment output</div>
      <div class="modal-body p-0" style="width:100%;min-height:300px;max-height:500px;overflow-x:hidden;overflow-y:scroll;">
                <pre>
        <p ng-show="responsedata.error" style="white-space: normal;word-break: break-word;margin-bottom:0px;">Error:<br>{{responsedata.errorlog}}</p>
        <p ng-show="!responsedata.error" ng-repeat="(key,val) in responsedata" style="white-space: normal;word-break: break-word;margin-bottom:0px;"> request:<br/>{{val.request}}<br/><font style="color:red;">response: {{val.response}}</font><br/>  </p>
        </pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i
                        class="mdi mdi-close"></i>&nbsp;Close</button>
            </div>
        </div>
    </div>
</div>