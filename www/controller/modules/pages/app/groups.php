<div id="ngApp" ng-app="ngApp" ng-controller="ngCtrl">
    <div class="row">
        <div class="col-md-3 position-relative">
            <input type="text" ng-model="search" class="form-control topsearch" placeholder="Find a group">
            <span class="searchicon"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-search" xlink:href="/assets/images/icon/midleoicons.svg#i-search"/></svg></span>
        </div>
        <div class="col-md-9 text-end">
            <?php if($_SESSION['user_level']>=3){?>
            <a data-bs-toggle="modal" class="waves-effect waves-light btn btn-info" href="#modal-user-form"
                ng-click="showCreateGroup()"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-add" xlink:href="/assets/images/icon/midleoicons.svg#i-add"/></svg>&nbsp;Create</a>
            <?php } ?>
        </div>
    </div><br>
    <div class="card">
        <div class="card-body p-0">
            <div class="row">
                <div class="col-md-12">

                    <table class="table table-vmiddle table-hover stylish-table mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:60px;"></th>
                                <th class="text-start">Group</th>
                                <th class="text-center" style="width:100px;"></th>
                            </tr>
                        </thead>
                        <tbody ng-init="getAllgroups()">
                            <tr ng-hide="contentLoaded">
                                <td colspan="5" style="text-align:center;font-size:1.1em;"><i
                                        class="mdi mdi-loading iconspin"></i>&nbsp;Loading...</td>
                            </tr>
                            <tr id="contloaded" class="hide"
                                dir-paginate="d in names | filter:search | orderBy:'group_name':reverse | itemsPerPage:10"
                                pagination-id="prodx">
                                <td class="text-center" style="padding: .5rem;vertical-align:top!important;"><span
                                        class="uavatar" style="background-color:{{d.uacolor}}">{{d.shortname}}</span>
                                </td>
                                <td class="text-start"><a ng-click="showgrinfo(d.id)" style="cursor:pointer;"
                                        id="grtext{{d.id}}"><span id="svg{{d.id}}"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-add" xlink:href="/assets/images/icon/midleoicons.svg#i-add" /></svg></span>&nbsp;{{ d.group_name | limitTo:textlimit }}{{d.group_name.length > textlimit ? '...' : ''}}</a>
                                    <div class="grudiv{{d.id}} grudivnt">
                                        <ul style="margin: 0 auto;padding: 5px;" class="list-group list-group-flush">
                                            <li style="width:300px;padding:5px;" ng-repeat="(ukey, user) in d.users"
                                                class="list-group-item border-bottom usr_{{d.id}}{{ukey}}">{{user}}<a
                                                    class="float-end"
                                                    ng-click="delusrgr(d.id,ukey,'<?php echo $_SESSION['user'];?>')"
                                                    style="cursor:pointer;"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-trash" xlink:href="/assets/images/icon/midleoicons.svg#i-trash"/></svg></a></li>
                                        </ul>
                                    </div>
                                </td>
                                <td style="vertical-align:top!important;">
                                    <div class="btn-group" role="group">
                                        <button ng-click="readgroup(d.id,'<?php echo $_SESSION['user'];?>')"
                                            type="button" class="btn waves-effect btn-sm btn-light"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-edit" xlink:href="/assets/images/icon/midleoicons.svg#i-edit"/></svg></button>
                                        <?php if($_SESSION['user_level']>=3){?><button
                                            ng-click="delgroup(d.id,'<?php echo $_SESSION['user'];?>')" type="button"
                                            class="btn waves-effect btn-light btn-sm"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-trash" xlink:href="/assets/images/icon/midleoicons.svg#i-trash"/></svg></button><?php } ?>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <dir-pagination-controls pagination-id="prodx" boundary-links="true"
                        on-page-change="pageChangeHandler(newPageNumber)"
                        template-url="/assets/templ/pagination.tpl.html"></dir-pagination-controls>
                    <?php if($_SESSION['user_level']>=3){?><div class="modal" id="modal-user-form" tabindex="-1"
                        role="dialog" aria-hidden="true">
                        <div class="modal-dialog" style="width:900px;">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4>Create/edit group</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form name="form" ng-app>
                                    <div class="modal-body form-horizontal form-material">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row" ng-show="group.latname">
                                                    <label
                                                        class="form-control-label text-lg-right col-md-3">GroupID</label>
                                                    <div class="col-md-9"><input ng-model="group.latname" type="text"
                                                            disabled="disabled" class="form-control"></div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="form-control-label text-lg-right col-md-3"
                                                        ng-class="{'has-error':!group.name}">Group name</label>
                                                    <div class="col-md-9"><input ng-required="true"
                                                            ng-model="group.name" type="text" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label
                                                        class="form-control-label text-lg-right col-md-3">Email</label>
                                                    <div class="col-md-9"><input ng-model="group.email" type="text"
                                                            class="form-control"></div>
                                                </div>
                                                <div class="form-group row usersdiv">
                                                    <label
                                                        class="form-control-label text-lg-right col-md-3">Users</label>
                                                    <div class="col-md-6">
                                                        <input name="users" id="groupuser" type="text"
                                                            class="form-control">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <button type="button"
                                                            ng-click="addusrgr(group.name,group.id,'<?php echo $_SESSION["user"];?>')"
                                                            class="btn btn-success btn-sm addusrgr"
                                                            style="display:none;"><i
                                                                class="mdi mdi-account-multiple-plus-outline"></i>Add</button>
                                                    </div>
                                                </div>
                                                <div class="form-group row usersdiv">
                                                    <div class="col-md-3"></div>
                                                    <div class="col-md-9">
                                                        <input type="text" ng-model="group.user" id="groupuserselected"
                                                            style="display:none;">
                                                        <div
                                                            style="display:block;width:100%;padding:10px 5px;background: rgba(0,0,0,0.05);min-height: 100px;border: 1px solid #ddd;">
                                                            <span class="list-comma"
                                                                ng-repeat="user in groupusers">{{user}}</span></div>
                                                        <br>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="form-control-label text-lg-right col-md-4">Access
                                                        groups</label>
                                                    <div class="col-md-8" style="overflow-y:auto;max-height:500px;">
                                                        <div class="row">
                                                            <?php foreach($gracclist as $keyin=>$valin){?>
                                                            <div class="col-md-12 mb-2">
                                                                <input
                                                                    ng-click="toggle('<?php echo $keyin;?>', selectedid)"
                                                                    type="checkbox" value="<?php echo $keyin;?>"
                                                                    id="checkbox<?php echo $keyin;?>"
                                                                    class="material-inputs filled-in chk-col-blue"
                                                                    ng-checked="group.modules.<?php echo $keyin;?>" />
                                                                <label class="chbl"
                                                                    for="checkbox<?php echo $keyin;?>"><?php echo $valin;?></label>
                                                            </div>
                                                            <?php } ?>
                                                        </div>
                                                        <input type="text" value="{{selectedid}}" id="selectedmodules"
                                                            style="display:none;">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-x" xlink:href="/assets/images/icon/midleoicons.svg#i-x"/></svg>&nbsp;Close</button>
                                        <button type="button" id="btn-create-group"
                                            class="waves-effect btn btn-info btn-sm"
                                            ng-click="form.$valid && creategroup('<?php echo $_SESSION['user'];?>')"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-check" xlink:href="/assets/images/icon/midleoicons.svg#i-check"/></svg>&nbsp;Create</button>
                                        <button type="button" id="btn-update-group"
                                            class="waves-effect btn btn-info btn-sm"
                                            ng-click="form.$valid && updategroup('<?php echo $_SESSION['user'];?>')"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-save" xlink:href="/assets/images/icon/midleoicons.svg#i-save"/></svg>&nbsp;Save</button>
                                        
                                    </div>
                            </div>
                            </form>
                        </div>
                    </div><?php } ?>

                </div>
            </div>
        </div>
    </div>