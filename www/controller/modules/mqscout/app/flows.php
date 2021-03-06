<?php if(empty($thisarray['p2'])){ include "applist.php"; } else { 
  array_push($brarr,array(
    "title"=>"Define new",
    "link"=>"#modal-flow-form",
    "nglink"=>"showCreateFormflow()",
    "modal"=>true,
    "icon"=>"mdi-plus",
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
                        <th class="text-center">Changed</th>
                        <th class="text-center" style="width:120px;">Commands</th>
                    </tr>
                </thead>
                <tbody ng-init="getAllflows('<?php echo $thisarray['p2'];?>','env')">
                    <tr ng-hide="contentLoaded">
                    <td class="text-center placeholder-glow"><small class="placeholder col-12"></small></td>
                    <td class="text-center placeholder-glow"><small class="placeholder col-12"></small></td>
                    <td class="text-center placeholder-glow"><small class="placeholder col-12"></small></td>
                    </tr>
                    <tr id="contloaded" class="hide"
                        dir-paginate="d in names | filter:search | orderBy:'name':reverse | itemsPerPage:10"
                        pagination-id="prodx">
                        <td class="text-center"><a href="/flows/{{ d.flowid }}/env"
                                target="_parent">{{ d.flowname | limitTo:2*textlimit }}{{d.name.length > 2*textlimit ? '...' : ''}}</a>
                        </td>
                        <td class="text-center">{{ d.modified }}</td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button type="button" class="btn middrmbut btn-light btn-sm dropdown-toggle"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                        class="mdi mdi-animation"></i></button>
                                <div class="dropdown-menu">
                                    <a ng-click="gitInit(d.flowid,'env')" class="dropdown-item waves-effect"><i
                                            class="mdi mdi-git"></i>&nbsp;GIT init</a>
                                    <a ng-click="gitCommit(d.flowid,'env')" class="dropdown-item waves-effect"><i
                                            class="mdi mdi-git"></i>&nbsp;GIT commit</a>
                                    <a href="/requests/flow/env/{{ d.flowid }}/log" target="_parent"
                                        class="dropdown-item waves-effect"><i class="mdi mdi-history"></i>&nbsp;Log
                                        history</a>
                                    <?php if(sessionClass::checkAcc($acclist, "ibmadm")){?>
                                    <a ng-show="d.insvn=='1'"
                                        ng-click="deleteflowsgit('',d.flowid,'<?php echo $_SESSION['user'];?>','env')"
                                        class="dropdown-item waves-effect"><i class="mdi mdi-close"></i>&nbsp;Delete</a>
                                    <a ng-show="d.insvn=='0'"
                                        ng-click="deleteflows('',d.flowid,'<?php echo $_SESSION['user'];?>','env')"
                                        class="dropdown-item waves-effect"><i class="mdi mdi-close"></i>&nbsp;Delete</a>
                                    <?php } ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <dir-pagination-controls pagination-id="prodx" boundary-links="true"
                on-page-change="pageChangeHandler(newPageNumber)" template-url="/<?php echo $website['corebase'];?>assets/templ/pagination.tpl.html">
            </dir-pagination-controls>
            <div class="modal fade" id="modal-flow-form" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form name="form">
                            <div class="modal-header">
                                <h4>Message flow info</h4>
                            </div>
                            <div class="modal-body container">
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
                                    <label class="form-control-label text-lg-right col-md-3" data-trigger="hover"
                                        data-bs-toggle="popover" data-bs-placement="right" data-html="true"
                                        data-content="Please use unique names. The name is limited to 256 chars/numbers"
                                        title="" data-original-title="Name of the message flow/set"
                                        ng-class="{'has-error':!flow.name}">Name</label>
                                    <div class="col-md-9"><input ng-required="true" ng-maxlength="256"
                                            ng-model="flow.name" type="text" class="form-control form-control-sm"></div>
                                </div>
                                <div class=" row">
                                    <label class="form-control-label text-lg-right col-md-3">Comment</label>
                                    <div class="col-md-9"><textarea ng-model="flow.info" rows="2"
                                            class="form-control form-control-sm"></textarea></div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i
                                        class="mdi mdi-close"></i>&nbsp;Close</button>
                                <button type="button" id="btn-create-obj"
                                    class="waves-effect waves-light btn btn-info btn-sm"
                                    ng-click="form.$valid && createflow('<?php echo $thisarray['p2'];?>','<?php echo $_SESSION['user'];?>','env')"><i
                                        class="mdi mdi-check"></i>&nbsp;Create</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>