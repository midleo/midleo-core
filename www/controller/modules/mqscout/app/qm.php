<?php if(!empty($thisarray['p2'])){ 
    ?>
<div class="row">
    <div class="col-md-3">
        <?php include "mqsidebar.php";?>
    </div>
    <div class="col-md-9">
        <div class="card p-0">
            <div class="card-header border-bottom">
                Connected projects
            </div>
            <div class="body">
                <?php $sql="select appid from mqenv_mq_qm where qmgr=?";
$q = $pdo->prepare($sql);
$q->execute(array($thisarray['p2']));
if ($row = $q->fetch(PDO::FETCH_ASSOC)) {  
if($row["appid"]){
    $sql="select appcode,appname,owner from config_app_codes where appcode in (" . str_repeat('?,', count(array_keys(json_decode($row["appid"],true))) - 1) . '?' . ")";
    $q = $pdo->prepare($sql);
    $q->execute(array_keys(json_decode($row["appid"],true)));
    $zobj = $q->fetchAll();
    if(is_array($zobj)){ ?>
<table class="table table-vmiddle table-hover mb-0">
    <tbody>
    <?php foreach($zobj as $val)  { ?>
<tr><td><a href="/env/apps/?app=<?php echo $val["appcode"];?>" target="_blank"><?php echo $val["appcode"];?></a></td><td><?php echo $val["appname"];?></td><td><a href="/browse/user/<?php echo $val["owner"];?>" target="_blank"><?php echo $val["owner"];?></a></td></tr>
       <?php } ?>
        </tbody>
        </table>
<?php    }
}
}
?>
            </div>
        </div>
        <?php $sql="select qminv,lrun from env_jobs_mq where qmgr=?";
$q = $pdo->prepare($sql);
$q->execute(array($thisarray['p2']));
if ($row = $q->fetch(PDO::FETCH_ASSOC)) { 
$tmp["qminv"]=!empty($row["qminv"])?json_decode($row["qminv"],true)["QMINFO"]:array();
$tmp["lrun"]=$row["lrun"];
?>
        <div class="card p-0">
            <div class="card-header border-bottom">
                Queue manager information
            </div>
            <div class="body">
                <table class="table table-vmiddle table-hover mb-0">
                    <tbody>
                        <tr>
                            <td class="bg-light" style="max-width:25%;width:25%;">Last check</td>
                            <td class="bg-light"><?php echo textClass::ago($tmp["lrun"]);?></td>
                        </tr>
                        <?php foreach($tmp["qminv"] as $key=>$val){ if($val){?>
                        <tr>
                            <td class="bg-light" style="max-width:25%;width:25%;"><?php echo $key;?></td>
                            <td><?php echo $val;?></td>
                        </tr>
                        <?php }} ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<?php } else { 
  array_push($brarr,array(
    "title"=>"Export in excel",
    "link"=>"#",
    "nglink"=>"exportData('".$thisarray['p1']."')",
    "icon"=>"mdi-file-excel",
    "active"=>false,
  ));
  array_push($brarr,array(
    "title"=>"Define new",
    "link"=>"#modal-obj-form",
    "nglink"=>"showCreateForm()",
    "modal"=>true,
    "icon"=>"mdi-plus",
    "active"=>false,
  ));
  array_push($brarr,array(
    "title"=>"Deploy package on server",
    "link"=>"#modal-depl-form",
    "nglink"=>"showDeployForm()",
    "modal"=>true,
    "icon"=>"mdi-upload",
    "ngshow"=>"selectedid.length",
    "active"=>false,
  ));
  ?>
<div class="row">
    <div class="col-lg-12 ">
        <div class="card p-0">
            <table class="table table-vmiddle table-hover stylish-table mb-0">
                <thead>
                    <tr>
                        <th class="text-center">Name</th>
                        <th class="text-center">Environment</th>
                        <th class="text-center">Server</th>
                    </tr>
                </thead>
                <tbody ng-init="getAll('<?php echo $thisarray['p1'];?>','','')">
                    <tr ng-hide="contentLoaded">
                        <td class="text-center placeholder-glow"><small class="placeholder col-12"></small></td>
                        <td class="text-center placeholder-glow"><small class="placeholder col-12"></small></td>
                        <td class="text-center placeholder-glow"><small class="placeholder col-12"></small></td>
                    </tr>
                    <tr id="contloaded" class="hide"
                        dir-paginate="d in names | filter:search | orderBy:'qm':reverse | itemsPerPage:10"
                        pagination-id="prodx" ng-hide="d.name==''">
                        <td class="text-center"><a href="/mqscout/qm/{{ d.name }}">{{ d.name }}</a></td>
                        <td class="text-center">{{ d.env}}</td>
                        <td class="text-center"><a href="/browse/server/{{ d.serverid }}">{{ d.server }}</a></td>
                    </tr>
                </tbody>
            </table>
            <dir-pagination-controls pagination-id="prodx" boundary-links="true"
                on-page-change="pageChangeHandler(newPageNumber)"
                template-url="/<?php echo $website['corebase'];?>assets/templ/pagination.tpl.html">
            </dir-pagination-controls>
            <div class="modal" id="modal-obj-form" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>Define QM definition</h4>
                        </div>
                        <form name="form" ng-app>
                            <div class="modal-body">
                                <div role="tabpanel">
                                    <ul class="nav nav-tabs customtab" role="tablist">
                                        <li class="nav-item"><a class="nav-link active" href="#base"
                                                aria-controls="base" role="tab" data-bs-toggle="tab">Base</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#default" aria-controls="default"
                                                role="tab" data-bs-toggle="tab">Default</a></li>
                                    </ul><br>
                                    <div class="tab-content form-horizontal form-material"
                                        style="width:100%;min-height:300px;max-height:500px;overflow-x:hidden;overflow-y:scroll;">
                                        <div role="tabpanel" class="tab-pane active" id="base">


                                            <div class="form-group row">
                                                <label class="form-control-label text-lg-right col-md-3">Active</label>
                                                <div class="col-md-9"><select class="form-control" ng-model="mq.active">
                                                        <option value="">Please select</option>
                                                        <option value="yes">Yes</option>
                                                        <option value="no">No</option>
                                                    </select></div>
                                            </div>
                                            <?php if(isset($modulelist["budget"]) && !empty($modulelist["budget"])){ ?>
                                            <input ng-model="mq.proj" style="display:none;"
                                                value="<?php echo $thisarray['p2'];?>">
                                            <?php } ?>
                                            <div class="form-group row">
                                                <label class="form-control-label text-lg-right col-md-3">Name</label>
                                                <div class="col-md-9">
                                                    <?php 
$sql="select serverdns,qmname from env_appservers where (serv_type='qm' or serv_type='fte') and proj=? group by qmname";
$stmt = $pdo->prepare($sql);
$stmt->execute(array($thisarray['p2']));
 if($zobjfte = $stmt->fetchAll()){
 ?>
                                                    <select class="form-control" ng-model="mq.qm" ng-required="true">
                                                        <option value="">Please select</option>
                                                        <?php foreach($zobjfte as $val) { echo '<option value="'.$val["qmname"].'">'.$val['qmname'].' ('.$val['serverdns'].')</option>'; } ?>
                                                    </select><?php }  else { ?>
                                                    <input ng-model="mq.qm" ng-required="true" type="text"
                                                        class="form-control">
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="form-control-label text-lg-right col-md-3">Tags</label>
                                                <div class="col-md-8"><input id="tags" data-role="tagsinput" type="text"
                                                        class="form-control"></div>
                                                <div class="col-md-1" style="padding-left:0px;"><button type="button"
                                                        class="btn btn-light" data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="You can search this object with tags"><i
                                                            class="mdi mdi-information-variant mdi-18px"></i></button>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="form-control-label text-lg-right col-md-3">Type</label>
                                                <div class="col-md-9"><select class="form-control"
                                                        ng-init="mq.type='qm'" ng-model="mq.type">
                                                        <option value="qm">Qmanager</option>
                                                        <option value="group">Group</option>
                                                    </select></div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="form-control-label text-lg-right col-md-3">ENV</label>
                                                <div class="col-md-9"><select class="form-control"
                                                        ng-model="mq.mqscout">
                                                        <option value="">Please select</option>
                                                        <?php foreach($menudataenv as $key=>$val){  ?>
                                                        <option value="<?php echo $val['nameshort'];?>">
                                                            <?php echo $val['name'];?></option>
                                                        <?php } ?>
                                                        <option value="all">All</option>
                                                    </select></div>
                                            </div>
                                            <div class="form-group row" ng-show="mq.type=='group'">
                                                <label class="form-control-label text-lg-right col-md-3">QM Name</label>
                                                <div class="col-md-9"><input ng-model="mq.name" type="text"
                                                        class="form-control"></div>
                                            </div>
                                            <div class="form-group"><br></div>
                                        </div>
                                        <div role="tabpanel" class="tab-pane" id="default">
                                            <div class="form-group row">
                                                <label class="form-control-label text-lg-right col-md-3">DEADQ</label>
                                                <div class="col-md-9"><input ng-model="mq.deadq" type="text"
                                                        class="form-control"></div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="form-control-label text-lg-right col-md-3">REPOS</label>
                                                <div class="col-md-9"><input ng-model="mq.repos" ng-maxlength="48"
                                                        type="text" class="form-control"></div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="form-control-label text-lg-right col-md-3">REPOSNL</label>
                                                <div class="col-md-9"><input ng-model="mq.reposnl" type="text"
                                                        class="form-control"></div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="form-control-label text-lg-right col-md-3">CCSID</label>
                                                <div class="col-md-9"><input ng-model="mq.ccsid" type="text"
                                                        class="form-control"></div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="form-control-label text-lg-right col-md-3">MAXMSGL</label>
                                                <div class="col-md-9"><input ng-model="mq.maxmsgl" type="text"
                                                        class="form-control"></div>
                                            </div>
                                            <div class="form-group row">
                                                <label
                                                    class="form-control-label text-lg-right col-md-3">MAXUMSGS</label>
                                                <div class="col-md-9"><input ng-model="mq.maxumsgs" type="text"
                                                        class="form-control"></div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="form-control-label text-lg-right col-md-3">SSLKEYR</label>
                                                <div class="col-md-9"><input ng-maxlength="256" ng-model="mq.sslkeyr"
                                                        type="text" class="form-control"></div>
                                            </div>
                                            <div class="form-group row">
                                                <label
                                                    class="form-control-label text-lg-right col-md-3">CERTLABL</label>
                                                <div class="col-md-9"><input ng-model="mq.certlabl" type="text"
                                                        class="form-control"></div>
                                            </div>

                                            <div class="form-group"><br></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer" style="display:flow-root list-item;">
                                <div class="float-start"><a href="https://www.google.com/search?q=ibm+mq+alter+qmgr"
                                        target="_blank"
                                        class="waves-effect waves-light btn btn-light btn-sm">Information about
                                        Qmanager</a></div>
                                <div class="float-end">
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i
                                            class="mdi mdi-close"></i>&nbsp;Close</button>
                                    <button type="button" id="btn-mqsc-obj"
                                        class="waves-effect waves-light btn btn-primary btn-sm"
                                        ng-click="mqsc('<?php echo $thisarray['p1'];?>',mq.proj,'<?php echo $_SESSION['user'];?>','<?php echo $page;?>')"
                                        ng-href="{{ url }}"><i class="mdi mdi-download"></i>&nbsp;Create mqsc</button>
                                    <?php if($zobj['lockedby']==$_SESSION['user']){?>
                                    <?php if($_SESSION['user_level']>="3"){?>
                                    <button type="button" id="btn-create-obj"
                                        class="waves-effect waves-light btn btn-info btn-sm"
                                        ng-click="form.$valid && create('<?php echo $thisarray['p1'];?>','<?php echo $thisarray['p2'];?>','<?php echo $_SESSION['user'];?>','<?php echo $page;?>')"><i
                                            class="mdi mdi-check"></i>&nbsp;Create</button>
                                    <button type="button" id="btn-update-obj"
                                        class="waves-effect waves-light btn btn-info btn-sm"
                                        ng-click="update('<?php echo $thisarray['p1'];?>','<?php echo $thisarray['p2'];?>','<?php echo $_SESSION['user'];?>','<?php echo $page;?>')"><i
                                            class="mdi mdi-content-save-outline"></i>&nbsp;Save Changes</button>
                                    <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?php } ?>