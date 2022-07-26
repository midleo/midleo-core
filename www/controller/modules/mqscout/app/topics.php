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
<div class="col-md-3">
        <?php include "mqsidebar.php";?>
    </div>
    <div class="col-md-9">
        <div class="card p-0">
            <table class="table table-vmiddle table-hover stylish-table mb-0">
                <thead>
                    <tr>
                        <th class="text-center" style="width:50px;"></th>
                        <th class="text-center">Job</th>
                        <th class="text-center">Topic</th>
                        <th class="text-center">Topicstr</th>
                        <th class="text-center" style="width:130px;">Action</th>
                    </tr>
                </thead>
                <tbody
                ng-init="getAll('<?php echo $thisarray['p1'];?>','<?php echo $thisarray['p3'];?>','<?php echo $page;?>','<?php echo $thisarray['p2'];?>')">
                    <tr ng-hide="contentLoaded">
                    <td class="text-center placeholder-glow"><small class="placeholder col-12"></small></td>
                    <td class="text-center placeholder-glow"><small class="placeholder col-12"></small></td>
                    <td class="text-center placeholder-glow"><small class="placeholder col-12"></small></td>
                    <td class="text-center placeholder-glow"><small class="placeholder col-12"></small></td>
                    <td class="text-center placeholder-glow"><small class="placeholder col-12"></small></td>
                    </tr>
                    <tr id="contloaded" class="hide"
                        dir-paginate="d in names | filter:search | orderBy:sortKey:reverse | itemsPerPage:10"
                        pagination-id="prodx" ng-hide="d.qm==''">
                        <td class="text-center">
                            <div class="toggle-switch">
                                <input id="deplqmid{{ d.qmid }}" type="checkbox" class="checkall" value="{{ d.qmid }}"
                                    ng-checked="exists(d.qmid, selectedid)" ng-click="toggle(d.qmid, selectedid)"
                                    style="display:none;">
                                <label for="deplqmid{{ d.qmid }}" class="ts-helper"></label>
                            </div>
                        </td>
                        <td class="text-center" style="padding: .5rem;"><a href="/automation/{{d.jobid}}"
                                ng-show="d.jobrun==1"><i class="mdi mdi-play-circle-outline mdi-24px"></i></a></td>
                        <td class="text-center">{{ d.name}}</td>
                        <td class="text-center">{{ d.topicstr }}</td>
                        <td class="text-center">
                            <div class="text-start d-grid gap-2 d-md-block">
                                <button type="button"
                                    ng-click="readOne('<?php echo $thisarray['p1'];?>',d.qid,d.qmid,'<?php echo $thisarray['p3'];?>','<?php echo $page;?>')"
                                    style="" class="btn btn-light btn-sm bg waves-effect"><i
                                        class="mdi mdi-pencil mdi-18px"></i></button>
                                <?php if($_SESSION['user_level']>="3"){?>
                                <button type="button"
                                ng-click="duplicate('<?php echo $thisarray['p1'];?>',d.qid,d.qmid,'<?php echo $_SESSION['user'];?>','<?php echo $thisarray['p3'];?>','<?php echo $thisarray['p2'];?>')"
                                    class="btn btn-light btn-sm bg waves-effect" title="Duplicate"><i
                                        class="mdi mdi-content-duplicate mdi-18px"></i></button>
                                <button type="button"
                                ng-click="delete('<?php echo $thisarray['p1'];?>',d.qid,d.qmid,d.proj,'<?php echo $_SESSION['user'];?>','<?php echo $page;?>','<?php echo $thisarray['p2'];?>')"
                                        class="btn btn-light btn-sm bg waves-effect"><i class="mdi mdi-close"></i></button>
                                <?php } ?>
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
                            <div class="modal-body container form-material"
                                style="width:100%;min-height:300px;max-height:500px;overflow-x:hidden;overflow-y:scroll;">
                                <input ng-model="mq.type" ng-init="mq.type='sub'" style="display:none;">

                                <input id="thisact" value="yes" type="text"
                                            style="display:none;">
                                            <input id="thisproj" style="display:none;"
                                                value="<?php echo $thisarray['p3'];?>">
                                            <input id="thisqm" value="<?php echo $thisarray['p2'];?>" type="text"
                                            style="display:none;">
                                <div class=" row">
                                    <label class="form-control-label text-lg-right col-md-3">Tags</label>
                                    <div class="col-md-8"><input id="tags" data-role="tagsinput" type="text"
                                            class="form-control form-control-sm"></div>
                                    <div class="col-md-1" style="padding-left:0px;"><button type="button"
                                            class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="You can search this object with tags"><i
                                                class="mdi mdi-information-variant mdi-18px"></i></button></div>
                                </div>
                                <div class=" row">
                                    <label class="form-control-label text-lg-right col-md-3"
                                        ng-class="{'has-error':!mq.name}">Name</label>
                                    <div class="col-md-9"><input ng-model="mq.name" ng-required="true" type="text"
                                            class="form-control form-control-sm"></div>
                                </div>
                                <div class=" row">
                                    <label class="form-control-label text-lg-right col-md-3">TOPICSTR</label>
                                    <div class="col-md-9"><input ng-model="mq.topicstr" type="text"
                                            class="form-control form-control-sm"></div>
                                </div>
                                <div class=" row">
                                    <label class="form-control-label text-lg-right col-md-3">CLUSTER</label>
                                    <div class="col-md-9"><input ng-model="mq.cluster" type="text" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class=" row">
                                    <label class="form-control-label text-lg-right col-md-3">DEFPSIST</label>
                                    <div class="col-md-9"><select class="form-control form-control-sm" ng-model="mq.defpsist">
                                            <option value="">ASPARENT</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select></div>
                                </div>
                                <div class=" row">
                                    <label class="form-control-label text-lg-right col-md-3">PUB</label>
                                    <div class="col-md-9"><select class="form-control form-control-sm" ng-model="mq.pub">
                                            <option value="">ASPARENT</option>
                                            <option value="enabled">Enabled</option>
                                            <option value="disabled">Disabled</option>
                                        </select></div>
                                </div>
                                <div class=" row">
                                    <label class="form-control-label text-lg-right col-md-3">PUBSCOPE</label>
                                    <div class="col-md-9"><select class="form-control form-control-sm" ng-model="mq.pubscope">
                                            <option value="">ASPARENT</option>
                                            <option value="qmgr">Qmgr</option>
                                            <option value="all">All</option>
                                        </select></div>
                                </div>
                                <div class=" row">
                                    <label class="form-control-label text-lg-right col-md-3">SUB</label>
                                    <div class="col-md-9"><select class="form-control form-control-sm" ng-model="mq.sub">
                                            <option value="">ASPARENT</option>
                                            <option value="enabled">Enabled</option>
                                            <option value="disabled">Disabled</option>
                                        </select></div>
                                </div>
                                <div class=" row">
                                    <label class="form-control-label text-lg-right col-md-3">SUBSCOPE</label>
                                    <div class="col-md-9"><select class="form-control form-control-sm" ng-model="mq.subscope">
                                            <option value="">ASPARENT</option>
                                            <option value="qmgr">Qmgr</option>
                                            <option value="all">All</option>
                                        </select></div>
                                </div>
                                <div class=" row">
                                    <label class="form-control-label text-lg-right col-md-3">WILDCARD</label>
                                    <div class="col-md-9"><select class="form-control form-control-sm" ng-model="mq.wildcard">
                                            <option value="">PASSTHRU</option>
                                            <option value="block">Block</option>
                                        </select></div>
                                </div>
                            </div>
                            <div class="modal-footer" style="display:flow-root list-item;">
                                <div class="float-start"><a href="https://www.google.com/search?q=ibm+mq+define+topic"
                                        target="_blank"
                                        class="waves-effect waves-light btn btn-light btn-sm">Information about
                                        Topic</a></div>
                                <div class="float-end">

                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i
                                            class="mdi mdi-close"></i>&nbsp;Close</button>
                                    <button type="button" id="btn-mqsc-obj"
                                        class="waves-effect waves-light btn btn-info btn-sm"
                                        ng-click="mqsc('<?php echo $thisarray['p1'];?>',mq.proj,'<?php echo $_SESSION['user'];?>','<?php echo $page;?>')"
                                        ng-href="{{ url }}"><i class="mdi mdi-download"></i>&nbsp;Create mqsc</button>
                                    <?php if($_SESSION['user_level']>="3"){?>
                                    <button type="button" id="btn-create-obj"
                                        class="waves-effect waves-light btn btn-info btn-sm"
                                        ng-click="form.$valid && create('<?php echo $thisarray['p1'];?>','<?php echo $thisarray['p3'];?>','<?php echo $_SESSION['user'];?>','<?php echo $page;?>','<?php echo $thisarray['p2'];?>')"><i
                                            class="mdi mdi-check"></i>&nbsp;Create</button>
                                    <button type="button" id="btn-update-obj"
                                        class="waves-effect waves-light btn btn-info btn-sm"
                                        ng-click="update('<?php echo $thisarray['p1'];?>','<?php echo $thisarray['p3'];?>','<?php echo $_SESSION['user'];?>','<?php echo $page;?>','<?php echo $thisarray['p2'];?>')"><i
                                            class="mdi mdi-content-save-outline"></i>&nbsp;Save Changes</button>
                                    <?php } ?>
                                </div>
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