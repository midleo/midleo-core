<?php
$ugr="";
if(!empty($ugroups)){ 
  foreach($ugroups as $key=>$val){ if($key){ $ugr.=$key.","; }}
  $ugr=rtrim($ugr, ",");
}
?>
<div class="row">
    <div class="col-lg-12 pe-0 ps-0">
        <div class="card p-0">
            <table class="table table-vmiddle table-hover stylish-table mb-0">
                <thead>
                    <tr>
                        <th class="text-center" style="width:50px;"></th>
                        <th class="text-center">Server</th>
                        <th class="text-center">Type</th>
                        <th class="text-center">IP</th>
                        <th class="text-center">Updated</th>
                        <th class="text-center" style="width:130px;">Action</th>
                    </tr>
                </thead>
                <tbody ng-init="getAllsrv('<?php echo $ugr;?>')">
                    <tr ng-hide="contentLoaded">
                        <td colspan="6" style="text-align:center;font-size:1.1em;"><i
                                class="mdi mdi-loading iconspin"></i>&nbsp;Loading...</td>
                    </tr>
                    <tr id="contloaded" class="hide"
                        dir-paginate="d in names | filter:search | orderBy:'server':reverse | itemsPerPage:10"
                        pagination-id="prodx">
                        <td class="text-center"><a class="btn btn-light btn-sm" href="/browse/server/{{ d.serverid}}"><i
                                    class="mdi mdi-server mdi-24px"></i></a></td>
                        <td class="text-center">{{ d.server}}</td>
                        <td class="text-center">{{ d.servertype}}</td>
                        <td class="text-center">{{ d.serverip}}</td>
                        <td class="text-center">{{ d.servupdated }}</td>
                        <td class="text-center">
                            <div class="text-start d-grid gap-2 d-md-block">
                                <button type="button" ng-click="readoneSrv(d.id)"
                                    class="btn waves-effect btn-light btn-sm"><i
                                        class="mdi mdi-pencil mdi-18px"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <dir-pagination-controls pagination-id="prodx" boundary-links="true"
                on-page-change="pageChangeHandler(newPageNumber)" template-url="/assets/templ/pagination.tpl.html">
            </dir-pagination-controls>
            <div class="modal" id="modal-obj-form" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>Server info</h4>
                        </div>
                        <form name="form" ng-app>
                            <div class="modal-body container form-material"
                                style="width:100%;min-height:300px;max-height:500px;overflow-x:hidden;overflow-y:scroll;">
                                <div class="form-group row">
                                    <label class="form-control-label text-lg-right col-md-3">Tags</label>
                                    <div class="col-md-8"><input id="tags" data-role="tagsinput" type="text"
                                            class="form-control"></div>
                                    <div class="col-md-1" style="padding-left:0px;"><button type="button"
                                            class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="You can search this object with tags"><i
                                                class="mdi mdi-information-variant mdi-18px"></i></button></div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-control-label text-lg-right col-md-3">Shared to Public</label>
                                    <div class="col-md-9"><select class="form-control" ng-model="serv.srvpublic">
                                            <option value="">Please select</option>
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select></div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-control-label text-lg-right col-md-3">Place</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control placeauto" placeholder="place name" />
                                        <input type="text" id="pluid" style="display:none;" />
                                    </div>
                                </div>
                                <div class="form-group row" style="margin-bottom:0px;">
                                    <label class="form-control-label text-lg-right col-md-3">Update period</label>
                                    <div class="col-md-9">
                                        <p class="form-control-static text-uppercase">{{serv.updperiod}} min.</p>
                                    </div>
                                </div>
                                <div class="form-group row" style="margin-bottom:0px;">
                                    <label class="form-control-label text-lg-right col-md-3">DNS</label>
                                    <div class="col-md-9">
                                        <p class="form-control-static text-uppercase">{{serv.serverdns}}</p>
                                    </div>
                                </div>
                                <div class="form-group row" style="margin-bottom:0px;">
                                    <label class="form-control-label text-lg-right col-md-3">IP</label>
                                    <div class="col-md-9">
                                        <p class="form-control-static text-uppercase">{{serv.serverip}}</p>
                                    </div>
                                </div>
                                <div class="form-group row" style="margin-bottom:0px;">
                                    <label class="form-control-label text-lg-right col-md-3">OS</label>
                                    <div class="col-md-9">
                                        <p class="form-control-static text-uppercase">{{serv.servertype}}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i
                                        class="mdi mdi-close"></i>&nbsp;Close</button>
                                <?php if($_SESSION['user_level']>=3){?>
                                <button type="button" id="btn-update-obj"
                                    class="waves-effect waves-light btn btn-info btn-sm"
                                    ng-click="updateserv('<?php echo $_SESSION['user'];?>','<?php echo $ugr;?>','<?php echo $thisarray['p2'];?>')"><i
                                        class="mdi mdi-content-save-outline"></i>&nbsp;Save Changes</button>
                                <?php } ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>