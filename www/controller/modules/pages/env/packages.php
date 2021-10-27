<?php if (sessionClass::checkAcc($acclist, "appadm,appview")) { 
    array_push($brarr,array(
        "title"=>"Create new package",
        "link"=>"/env/packages/".$thisarray['p2']."/?type=new",
        "icon"=>"mdi-plus",
        "active"=>false,
      ));
      ?>
<?php  if($_GET["type"]=="new"){ ?>
<div class="card ">
    <div class="card-body p-0">
        <form action="" method="post">
            <div class="row p-3">
                <div class="col-md-12">
                    <h4 class="card-title">Define objects</h4>
                    <h6 class="card-subtitle">List of objects that will be added in this package</h6>
                </div>
            </div>
            <div class="row p-2">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-3">Name</label>
                        <div class="col-md-9">
                            <input type="text" name="pkgname" class="form-control"
                                placeholder="package name. limited to 80 symbols" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-3">Tags</label>
                        <div class="col-md-8"><input id="tags" name="tags" data-role="tagsinput" type="text"
                                class="form-control"></div>
                        <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="You can search this object with tags"><i
                                    class="mdi mdi-information-variant mdi-18px"></i></button></div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-3">Application</label>
                        <div class="col-md-8">
                            <input type="text" id="applauto" class="form-control"
                                placeholder="write the application name or code" />
                            <input type="text" id="appname" name="appname" style="display:none;" />
                        </div>
                        <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="You can search objects from different applications"><i
                                    class="mdi mdi-information-variant mdi-18px"></i></button></div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-3">Objects type</label>
                        <div class="col-md-9">
                            <select ng-required="true" name="objtype" ng-model="package.objtypes" class="form-control"
                                ng-change="getpkgapps()">
                                <option value="">Please select</option>
                                <option value="qm#queues">IBM MQ Queues</option>
                                <option value="qm#channels">IBM MQ Channels</option>
                                <option value="qm#topics">IBM MQ Topics</option>
                                <option value="qm#subs">IBM MQ Subscriptions</option>
                                <option value="qm#process">IBM MQ Process</option>
                                <option value="qm#service">IBM MQ Service</option>
                                <option value="qm#dlqh">IBM MQ Dead letter queue handler</option>
                                <option value="qm#authrec">IBM MQ Authentication records</option>
                                <option value="fte#fte">IBM MQ File transfer configurations</option>
                                <option value="tibems#queue">TIBCO Queues</option>
                                <option value="tibems#topic">TIBCO Topic</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row" ng-show="package.objtypes">
                        <label class="form-control-label text-lg-right col-md-3">Objects list</label>
                        <div class="col-md-8">
                            <input type="text" ng-model="search" class="form-control  topsearch"
                                placeholder="filter objects">
                            <br>
                            <table class="table table-vmiddle table-hover stylish-table mb-0">
                                <tbody>
                                    <tr ng-hide="contentLoaded">
                                        <td colspan="2" style="text-align:center;font-size:1.1em;"><i
                                                class="mdi mdi-loading iconspin"></i>&nbsp;Loading...</td>
                                    </tr>
                                    <tr id="contloaded" class="hide"
                                        dir-paginate="d in names | filter:search | orderBy:'name':reverse | itemsPerPage:10"
                                        pagination-id="prodx" ng-hide="d.name==''">
                                        <td class="text-center" style="width:50px;">
                                            <div class="toggle-switch">
                                                <input id="pkgchk{{ d.id }}" type="checkbox" value="{{ d.id }}"
                                                    ng-checked="exists(d.id, selectedid)"
                                                    ng-click="toggle(d.id, selectedid)" style="display:none;">
                                                <label for="pkgchk{{ d.id }}" class="ts-helper"></label>
                                            </div>
                                        </td>
                                        <td class="text-start">{{ d.name}}
                                            <input type="text" id="pkgnid{{ d.id }}" value="{{d.name}}"
                                                style="display:none;">
                                            <input type="text" id="pkgapsrvid{{ d.id }}" value="{{d.appsrv}}"
                                                style="display:none;">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-1"><button type="button" ng-click="addtofinal()" class="btn btn-light"><i
                                    class="mdi mdi-plus"></i></button></div>
                    </div>
                    <div class="form-group text-end"><br>
                        <button id="btn-create-obj" type="submit" name="createpack"
                            class="waves-effect waves-light btn btn-light btn-sm"><i class="mdi mdi-plus mdi-18px"></i>&nbsp;Create</button>

                    </div>
                </div>
                <div class="col-md-6">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item" ng-repeat="(key,val) in finalobjnames">{{val.name}} <div
                                class="float-end"><span class="badge badge-info">{{val.type}}</span>
                                <div>
                        </li>
                    </ul>
                    <input type="text" value="{{finalobj}}" name="finalobj" style="display:none;">
                </div>
            </div>
        </form>
    </div>
</div>
<?php } else { ?>
<?php if(empty($thisarray['p2'])){ include "applist.php"; } else { ?>
<div class="card ">
    <div class="card-body p-0">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-vmiddle table-hover stylish-table mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">Name</th>
                            <th class="text-center">Server type</th>
                            <th class="text-center">Package ID</th>
                            <th class="text-center">Released date</th>
                            <th class="text-center">Released by</th>
                            <th class="text-center">Deployed in</th>
                            <th class="text-center"></th>
                            <th class="text-center" width="130px">Action</th>
                        </tr>
                    </thead>
                    <tbody ng-init="getAllpack('<?php echo $thisarray['p2'];?>')">
                        <tr ng-hide="contentLoaded">
                            <td colspan="9" style="text-align:center;font-size:1.1em;"><i
                                    class="mdi mdi-loading iconspin"></i>&nbsp;Loading...</td>
                        </tr>
                        <tr id="contloaded" class="hide"
                            dir-paginate="d in names | filter:search | orderBy:'-released' | itemsPerPage:10"
                            pagination-id="prodx">
                            <td class="text-center">{{ d.name }}</td>
                            <td class="text-center">{{ d.typesrv }}</td>
                            <td class="text-center">{{ d.packuid }}</td>
                            <td class="text-center">{{ d.released }}</td>
                            <td class="text-center">{{ d.user }}</td>
                            <td class="text-center"><span class="badge badge-secondary me-1 mt-1"
                                    ng-repeat="(key,val) in d.deployedin">{{val}}</span></td>
                            <td class="text-center"><span
                                    class="badge badge-{{ d.gitpreparedspan }}">{{ d.gitprepared }}</span>
                            </td>
                            <td>
                                <?php if (sessionClass::checkAcc($acclist, "appadm,appview")) { ?>
                                <div class="text-start d-grid gap-2 d-md-block">
                                    <button ng-click="getQMlist('<?php echo $thisarray['p2'];?>',d.packuid,d.id)"
                                        class="btn btn-light mql{{d.id}}" ng-show="d.srvtype=='qm'"
                                        data-bs-toggle="tooltip" onmouseenter="$(this).tooltip('show')"
                                        title="Preview"><i class="mdi mdi-eye-outline"></i></button>
                                    <button ng-show="d.isgitprepared" ng-click="getGitInfo(d.packuid,d.id)"
                                        class="btn btn-light gl{{d.id}}" data-bs-toggle="tooltip"
                                        onmouseenter="$(this).tooltip('show')" title="Check GIT deployments"><i
                                            class="mdi mdi-git"></i></button>
                                    <button data-bs-toggle="tooltip" onmouseenter="$(this).tooltip('show')" title="Prepare"
                                        type="button" ng-click="preparepack('<?php echo $thisarray['p2'];?>',d.id)"
                                        class="btn btn-light btn-sm waves-effect prepb{{d.id}}"><i class="mdi mdi-file-document-multiple-outline"></i></button>
                                    <button ng-show="d.isdeployed" data-bs-toggle="tooltip"
                                        onmouseenter="$(this).tooltip('show')"
                                        title="You cannot delete a package if it is deployed already." type="button"
                                        class="btn btn-light btn-sm waves-effect"><i class="mdi mdi-information-outline"></i></button>
                                    <button ng-hide="d.isdeployed" data-bs-toggle="tooltip"
                                        onmouseenter="$(this).tooltip('show')" title="Delete" type="button"
                                        ng-click="deletepack('<?php echo $thisarray['p2'];?>',d.id,d.packuid)"
                                        class="btn btn-light btn-sm waves-effect delb{{d.id}}"><i class="mdi mdi-close"></i></button>
                                </div>
                                <?php } ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <dir-pagination-controls pagination-id="prodx" boundary-links="true"
                    on-page-change="pageChangeHandler(newPageNumber)" template-url="/assets/templ/pagination.tpl.html">
                </dir-pagination-controls>

            </div>
        </div>
    </div>
</div>

<div class="modal" id="modal-git-form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="width: 800px;">
        <div class="modal-content">
            <div class="modal-header">
                <h4>GIT Commits</h4>
            </div>
            <div class="modal-body">
                <div ng-if="gitlist.length>0">
                    <table class="table">
                    <thead>
                    <tr>
                    <th>Time</th>
                    <th>System</th>
                    <th>CommitID</th>
                    <th>Folder</th>
                    <th>Type</th>
                    <th>user</th>
                    </tr>
                    </thead>
                        <tbody>
                            <tr ng-repeat="(key, val) in gitlist">
                            <td>{{val.steptime}}</td>
                                <td>{{val.gittype}}</td>
                                <td><span class="badge badge-info" ng-click="copyToClipboard(val.commitid)">{{ val.commitid | limitTo: 10 }}{{val.commitid.length > 10 ? '...' : ''}}</span></td>
                                <td>{{val.fileplace}}</td>
                                <td>{{val.steptype}}</td>
                                <td><a href="/browse/user/{{val.stepuser}}" target="_blank">View</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div ng-if="gitlist.length==0">
                    <div class="alert alert-light">No records found</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="mdi mdi-close"></i>&nbsp;Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modal-obj-form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Preview changes</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Qmanager</label>
                    <select name="qmgr" class="form-control" ng-model="thisqm">
                        <option value="">Please select</option>
                        <option ng-repeat="(key, val) in qmlist" value="{{val}}" ng-show="val">{{val}}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Environment</label>
                    <?php if(is_array($menudataenv)){?>
                    <select ng-model="depl.deplenv" id="pkgenv" class="form-control">
                        <option value="">Please select</option>
                        <?php 
    foreach($menudataenv as $keyenv=>$valenv) { ?>
                        <option value="<?php echo $valenv['nameshort'];?>"><?php echo $valenv['name'];?></option>
                        <?php  } ?>
                    </select>
                    <?php } ?>
                </div>
                <input type="text" id="pkgid" ng-model="pkgid" style="display:none;">
                <div style="display:block;" class="text-center">
                    <div class="loading"><svg class="circular" viewBox="25 25 50 50">
                            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2"
                                stroke-miterlimit="10" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="mdi mdi-close"></i>&nbsp;Close</button>
                <button ng-show="depl.deplenv" type="button" class="waves-effect waves-light btn btn-info btn-sm"
                    ng-click="mqpreview('<?php echo $thisarray['p2'];?>')"><i class="mdi mdi-eye-outline"></i>&nbsp;Make preview</button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modal-resp-form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="width: 800px;">
        <div class="modal-content" style="background-color:#fff;">
            <div class="modal-body">

                <div ng-if="response.diff">
                    <h4 class="text-danger">Difference</h4>
                    <div ng-repeat="(key, val) in response.diff"
                        ng-init="i = $parent.start; $parent.start=$parent.start+1;">
                        <div id="accd{{i}}">
                            <div class="card mb-0 br-0" style="box-shadow:none;border:1px solid #dfdfdf;"
                                ng-repeat="(keyin, valin) in val"
                                ng-init="j = $parent.start; $parent.start=$parent.start+1;">
                                <div class="ribbon ribbon-default ribbon-right">{{key}}</div>
                                <a class="card-header text-decoration-none" id="headingd{{i}}{{j}}">

                                    <button class="btn btn-light" data-bs-toggle="collapse"
                                        data-bs-target="#collapsed{{i}}{{j}}" aria-expanded="true"
                                        aria-controls="collapsed{{i}}{{j}}">
                                        <i class="mdi mdi-arrow-up"></i>&nbsp;{{keyin}}
                                    </button>
                                </a>
                                <div id="collapsed{{i}}{{j}}" class="collapse" aria-labelledby="headingd{{i}}{{j}}"
                                    data-parent="#accd{{i}}">
                                    <div class="card-body">
                                        <table class="table table-striped ">
                                            <thead>
                                                <tr>
                                                    <th>Property</th>
                                                    <th>Old</th>
                                                    <th>New</th>
                                                </tr>
                                            </thead>
                                            <tr ng-repeat="(keyobj, valobj) in valin">
                                                <td>{{keyobj}}</td>
                                                <td class="table-danger">{{valobj.from}}</td>
                                                <td class="table-success">{{valobj.to}}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
                <div ng-if="response.less">
                    <h4 class="text-info">New changes</h4>
                    <div ng-repeat="(key, val) in response.less"
                        ng-init="i = $parent.start; $parent.start=$parent.start+1;">
                        <div id="accl1{{i}}">
                            <div class="card mb-0 br-0" style="box-shadow:none;border:1px solid #dfdfdf;"
                                ng-repeat="(keyin, valin) in val"
                                ng-init="j = $parent.start; $parent.start=$parent.start+1;">
                                <div class="ribbon ribbon-default ribbon-right">{{key}}</div>
                                <a class="card-header text-decoration-none" id="headingl{{i}}{{j}}">
                                    <button class="btn btn-light" data-bs-toggle="collapse"
                                        data-bs-target="#collapsel{{i}}{{j}}" aria-expanded="true"
                                        aria-controls="collapsel{{i}}{{j}}">
                                        <i class="mdi mdi-arrow-up"></i>&nbsp;{{keyin}}
                                    </button>
                                </a>
                                <div id="collapsel{{i}}{{j}}" class="collapse" aria-labelledby="headingl{{i}}{{j}}"
                                    data-parent="#accl1{{i}}">
                                    <div class="card-body">
                                        <table class="table table-striped ">
                                            <thead>
                                                <tr>
                                                    <th>Property</th>
                                                    <th>Value</th>
                                                </tr>
                                            </thead>
                                            <tr ng-repeat="(keyobj, valobj) in valin">
                                                <td>{{keyobj}}</td>
                                                <td class="table-info">{{valobj}}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="mdi mdi-close"></i>&nbsp;Close</button>
            </div>
        </div>
    </div>
</div>

<?php } ?>
<?php } ?>

<?php } ?>