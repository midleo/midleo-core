<?php
  $sql="select ".(DBTYPE=='oracle'?"to_char(reqdata) as reqdata":"reqdata")." from requests_data where reqid=? and reqtype=?";
  $q = $pdo->prepare($sql);
  $q->execute(array($thisarray["p3"],$thisarray["p2"])); 
    if($row = $q->fetch(PDO::FETCH_ASSOC)){
      $objects=json_decode($row['reqdata'],true);
      ?>

<div class="row" id="ngApp" ng-app="ngApp" ng-controller="ngCtrl">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h4>Request information</h4></div>
            <div class="card-body">
                <form action="" method="post" class="form-material">
                    <div role="tabpanel">
                        <ul class="nav nav-tabs customtab" role="tablist">
                            <?php $ienv=0;  foreach($menudataenv as $keyenv=>$valenv){  ?>
                            <li class="nav-item"><a class="nav-link <?php echo $ienv==0?"active":"";?>"
                                    href="#mq<?php echo $valenv['nameshort'];?>"
                                    aria-controls="<?php echo $valenv['nameshort'];?>" role="tab"
                                    data-bs-toggle="tab"><?php echo $valenv['name'];?></a></li><?php 
             $ienv++;    }   ?>
                        </ul><br><br>
                        <div class="tab-content">
                            <?php $ienv=0;  foreach($menudataenv as $keyenv=>$valenv){  ?>
                            <div role="tabpanel" class="tab-pane <?php echo $ienv==0?'active':"";?>"
                                id="mq<?php echo $valenv['nameshort'];?>">
                                <?php $tplcase=$thisarray["p2"]; echo Tpl::$tplcase($before=$thisarray["p2"]."_",$after="_".$valenv['nameshort'],$objects); ?>
                                <div class="form-group"><br></div>
                            </div>
                            <?php $ienv++;    }   ?>
                            <div class="form-group">
                                <input type="hidden" name="reqtype" value="<?php echo $thisarray["p2"];?>">
                                <input type="hidden" name="reqid" value="<?php echo $thisarray["p3"];?>">
                                <label class="col-md-5 control-label text-end" style="margin-top: 5px;"></label>
                                <div class="col-md-7">
                                    <a data-bs-toggle="modal" class="waves-effect waves-light btn btn-light"
                                        href="#modal-reqtype"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-save" xlink:href="/assets/images/icon/midleoicons.svg#i-save"/></svg>&nbsp;Save</a>
                                    <div class="modal" id="modal-reqtype" tabindex="-1" role="dialog"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">Information about the change
                                                </div>
                                                <div class="modal-body"
                                                    style="width:100%;min-height:300px;max-height:500px;overflow-x:hidden;overflow-y:scroll;">
                                                    <div class="form-group"><br>
                                                        <div class="col-md-12">
                                                            <textarea class="form-control textarea" name="updinfo"
                                                                rows="10"><?php echo $_SESSION["user"];?> changes:</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="waves-effect waves-light btn btn-light btn-sm"
                                                        type="submit" name="savereqdata"><i
                                                            class="mdi mdi-content-save"></i>&nbsp;Save</button>&nbsp;
                                                    <button type="button"
                                                        class="waves-effect waves-light btn btn-danger btn-sm"
                                                        data-bs-dismiss="modal"><i
                                                            class="mdi mdi-close"></i>&nbsp;Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h4>Navigation</h4></div>
            <div class="card-body  p-0">
                <?php include "reqnav.php";?>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<?php
  } else { textClass::PageNotFound();  }  ?>