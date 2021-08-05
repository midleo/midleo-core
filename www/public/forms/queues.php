<div class="row" ng-show="showform">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div role="tabpanel">
                    <ul class="nav nav-tabs customtab navunderline" role="tablist">
                        <?php $ienv=0;  foreach($menudataenv as $keyenv=>$valenv){  ?>
                        <li class="nav-item"><a class="nav-link <?php echo $ienv==0?"active":"";?>"
                                href="#mq<?php echo $valenv['nameshort'];?>"
                                aria-controls="<?php echo $valenv['nameshort'];?>" role="tab"
                                data-bs-toggle="tab"><?php echo $valenv['name'];?></a></li><?php 
 $ienv++;}   ?>
                    </ul><br>
                    <div class="tab-content form-material">
                        <?php $ienv=0;  foreach($menudataenv as $keyenv=>$valenv){  ?>
                        <div role="tabpanel" class="tab-pane <?php echo $ienv==0?'active':"";?>"
                            id="mq<?php echo $valenv['nameshort'];?>">
                            <?php echo Tpl::queues($before="queues_",$after="_".$valenv['nameshort']); ?>
                            <div class="form-group"><br></div>
                        </div>
                        <?php $ienv++;}   ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <button type="button" class="btn btn-success" ng-click="showform=false"><i
                class="mdi mdi-check"></i>&nbsp;Ready</button>
    </div>
</div>
<input name="reqtype" type="text" value="queues" style="display:none;">