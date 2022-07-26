<?php if(empty($thisarray['p2'])){ ?>

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
  ?>
<div class="row">
<div class="col-md-3">
        <?php include "mqsidebar.php";?>
    </div>
    <div class="col-md-9">
        <div class="card p-0">
            <table class="table table-vmiddle table-hover stylish-table mb-0">
                <thead>
                    <tr>
                        <th class="text-center">App code</th>
                        <th class="text-center" style="width:120px;">Name</th>
                        <th class="text-center">Value</th>
                        <th class="text-center" style="width:130px;">Action</th>
                    </tr>
                </thead>
                <tbody
                ng-init="getAll('<?php echo $thisarray['p1'];?>','<?php echo $thisarray['p3'];?>','<?php echo $page;?>','<?php echo $thisarray['p2'];?>')">
                    <tr ng-hide="contentLoaded">
                        <td colspan="4" style="text-align:center;font-size:1.1em;"><i
                                class="mdi mdi-loading iconspin"></i>&nbsp;Loading...</td>
                    </tr>
                    <tr id="contloaded" class="hide"
                        dir-paginate="d in names | filter:search | orderBy:sortKey:reverse | itemsPerPage:10"
                        pagination-id="prodx" ng-hide="d.varname==''">
                        <td class="text-center">{{ d.proj }}</td>
                        <td class="text-center">{{ d.varname }}</td>
                        <td class="text-center">{{ d.varvalue }}</td>
                        <td class="text-center">
                            <div class="text-start d-grid gap-2 d-md-block">
                                <button type="button"
                                    ng-click="readOne('<?php echo $thisarray['p1'];?>',d.varid,d.varid,'<?php echo $page;?>')"
                                    style="" class="btn btn-light btn-sm bg waves-effect"><i
                                        class="mdi mdi-pencil mdi-18px"></i></button>
                        
                                <?php if($_SESSION['user_level']>="3"){?><button type="button"
                                    ng-click="deletevar(d.varname,d.varid,'<?php echo $_SESSION['user'];?>','<?php echo $thisarray['p3'];?>')"
                                    class="btn btn-light btn-sm bg waves-effect"><i
                                        class="mdi mdi-close"></i></button><?php } ?>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <dir-pagination-controls pagination-id="prodx" boundary-links="true"
                on-page-change="pageChangeHandler(newPageNumber)" template-url="/<?php echo $website['corebase'];?>assets/templ/pagination.tpl.html">
            </dir-pagination-controls>
            <div class="modal" id="modal-obj-form" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form name="form" ng-app>
                            <div class="modal-body container"
                                style="width:100%;min-height:120px;max-height:500px;overflow-x:hidden;overflow-y:scroll;">
                                <input ng-model="mq.type" ng-init="mq.type='var'" style="display:none;">
                                <div class=" row mb-1">
                                    <label class="form-control-label text-lg-right col-md-5">Tags</label>
                                    <div class="col-md-6"><input id="tags" data-role="tagsinput" type="text"
                                            class="form-control form-control-sm"></div>
                                    <div class="col-md-1" style="padding-left:0px;"><button type="button"
                                            class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="You can search this object with tags"><i
                                                class="mdi mdi-information-variant mdi-18px"></i></button></div>
                                </div>
                                <div class=" row">
                                    <label class="form-control-label text-lg-right col-md-5"
                                        ng-class="{'has-error':!mq.varname}">Variable</label>
                                    <div class="col-md-7"> <input ng-model="mq.name" ng-required="true" type="text"
                                            class="form-control form-control-sm"></div>
                                </div>
                                <div class=" row">
                                    <label class="form-control-label text-lg-right col-md-5"
                                        ng-class="{'has-error':!mq.type}">Type</label>
                                    <div class="col-md-7"><select class="form-control form-control-sm" ng-model="mq.vartype"
                                            ng-minlength="1" ng-required="true">
                                            <option value="">Please select</option>
                                            <option value="envrelated">Different for each environment</option>
                                            <option value="envsame">Same for all environment</option>
                                        </select></div>
                                </div>
                                <?php foreach($menudataenv as $key=>$val){  ?>
                                <div class=" row" ng-show="mq.vartype=='envrelated'">
                                    <label
                                        class="form-control-label text-lg-right col-md-5"><?php echo $val['name'];?></label>
                                    <div class="col-md-7"><input ng-model="mq.env.<?php echo $val['nameshort'];?>"
                                            type="text" class="form-control form-control-sm"></div>
                                </div>
                                <?php } ?>
                                <div class=" row" ng-show="mq.vartype=='envsame'">
                                    <label class="form-control-label text-lg-right col-md-5">Value</label>
                                    <div class="col-md-7"><input ng-model="mq.varvaluesame" type="text"
                                            class="form-control form-control-sm"></div>
                                </div>
                                <div class=""><br></div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i
                                        class="mdi mdi-close"></i>&nbsp;Close</button>
                                <?php if($_SESSION['user_level']>="3"){?>
                                <button type="button" id="btn-create-obj"
                                    class="waves-effect waves-light btn btn-info btn-sm"
                                    ng-click="form.$valid && createvar('<?php echo $_SESSION['user'];?>','<?php echo $thisarray['p3'];?>')"><i
                                        class="mdi mdi-check"></i>&nbsp;Create</button>
                                <button type="button" id="btn-update-obj"
                                    class="waves-effect waves-light btn btn-info btn-sm"
                                    ng-click="updatevar('<?php echo $_SESSION['user'];?>','<?php echo $thisarray['p3'];?>')"><i
                                        class="mdi mdi-content-save-outline"></i>&nbsp;Save Changes</button>
                                <?php } ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php if($_SESSION['user_level']>="3"){?>
            <?php include $maindir."/public/modules/deplform.php";?>
            <?php include $maindir."/public/modules/respform.php";?>
            <?php } ?>
        </div>
    </div>
</div>
<?php } ?>