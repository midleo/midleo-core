<?php if(empty($thisarray['p2'])){ include "applist.php"; } else { ?>
  <div class="row">
 <div class="col-md-9 text-end">
<?php if ($_SESSION['user_level'] >= 3 ) {?><span><button data-bs-toggle="tooltip" data-bs-placement="top" title="Export the objects in excel" type="button" class="waves-effect waves-light btn btn-light" ng-click="exportData('<?php echo $thisarray['p1']; ?>')">Export&nbsp;<svg class="midico midico-outline" ><use href="/assets/images/icon/midleoicons.svg#i-up" xlink:href="/assets/images/icon/midleoicons.svg#i-up"/></svg></button> </span><?php }?>
 <span data-bs-toggle="tooltip" data-bs-placement="top" title="Define new ACL configuration"><button type="button" class="waves-effect waves-light btn btn-info" data-bs-toggle="modal" href="#modal-tibacl-form" ng-click="showCreateFormtibacl()"><svg class="midico midico-outline" ><use href="/assets/images/icon/midleoicons.svg#i-add" xlink:href="/assets/images/icon/midleoicons.svg#i-add"/></svg>&nbsp;Create</button></span>
  </div>
</div><br>
 <div class="card ">
<div class="card-body p-0">

<div class="row"><div class="col-md-12">
 <table class="table table-vmiddle table-hover stylish-table mb-0">
 <thead>
 <tr>
 <th class="text-center">Object type</th>
 <th class="text-center">Object name</th>
 <th class="text-center">ACL type</th>
 <th class="text-center">ACL name</th>
 <th class="text-center">Permissions</th>
 <th class="text-center" style="width:120px;">Action</th>
 </tr>
 </thead>
 <tbody ng-init="getAllTibacl('<?php echo $thisarray['p2'];?>')">
 <tr ng-hide="contentLoaded"><td colspan="6" style="text-align:center;font-size:1.1em;"><i class="mdi mdi-loading iconspin"></i>&nbsp;Loading...</td></tr>
 <tr id="contloaded" class="hide" dir-paginate="d in names | filter:search | orderBy:'name':reverse | itemsPerPage:10" ng-init="tperm=parJson(d.perm)" pagination-id="prodx">

 <td class="text-center">{{ d.objtype }}</td>
 <td class="text-center">{{ d.objname }}</td>
 <td class="text-center">{{ d.acltype }}</td>
 <td class="text-center">{{ d.aclname }}</td>
 <td class="text-center"><span ng-repeat="thisperm in tperm" class="badge badge-info" style="margin-right:3px;">{{thisperm}}</span></td>
 <td class="text-center">
 <div class="text-start d-grid gap-2 d-md-block">
  <button type="button" ng-click="readOnetibacl('<?php echo $thisarray['p2'];?>',d.id)" style="" class="btn btn-light btn-sm bg waves-effect"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-edit" xlink:href="/assets/images/icon/midleoicons.svg#i-edit"/></svg></button>
  <?php if($_SESSION['user_level']>="3"){?><button type="button" ng-click="delltibacl('<?php echo $thisarray['p2'];?>',d.id,d.objname,'<?php echo $_SESSION['user'];?>')" class="btn btn-light btn-sm bg waves-effect"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-x" xlink:href="/assets/images/icon/midleoicons.svg#i-x"/></svg></button><?php } ?>
 </div>
 </td>
 </tr>
 </tbody>
 </table>
 <dir-pagination-controls pagination-id="prodx" boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="/assets/templ/pagination.tpl.html"></dir-pagination-controls>
 <div class="modal" id="modal-tibacl-form" tabindex="-1" role="dialog" aria-hidden="true">
 <div class="modal-dialog">
 <div class="modal-content">
 <form name="form" ng-app>
  <div class="modal-body">
  <div role="tabpanel">
  <ul class="nav nav-tabs customtab" role="tablist">
 <li class="nav-item"><a class="nav-link active" href="#base" aria-controls="base" role="tab" data-bs-toggle="tab">Base</a></li>
 <li class="nav-item"><a class="nav-link" href="#info" aria-controls="info" role="tab" data-bs-toggle="tab">Comment</a></li>
</ul><br>
<div class="tab-content container" style="width:100%;min-height:300px;max-height:500px;overflow-x:hidden;overflow-y:scroll;">
 <div role="tabpanel" class="tab-pane active" id="base" >
 <input style="display:none;"  id="selectedobj" value="{{selectedid | json}}">
 <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  ng-class="{'has-error':!tibacl.srv}">EMS Server</label>
                    <div class="col-md-9"><?php 
 $sql="select serverdns from env_appservers where serv_type='tibems'";
 $stmt = $pdo->prepare($sql);
 $stmt->execute();
 if($zobjin = $stmt->fetchAll()){
 ?>
<select class="form-control" ng-model="tibacl.srv" ng-required="true">
<option value="">Please select</option>
<?php foreach($zobjin as $val) { echo '<option value="'.$val["serverdns"].'">'.$val['serverdns'].'</option>'; } ?>
</select><?php }  else { ?>
  <input ng-model="tibacl.srv" ng-required="true" type="text" class="form-control">
 <?php } ?>  
 </div>
                  </div>
                  
                  <div class="form-group row">
 <label class="form-control-label text-lg-right col-md-3" >Tags</label>
 <div class="col-md-8"><input id="tags" data-role="tagsinput" type="text" class="form-control"></div>
 <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="You can search this object with tags" ><i class="mdi mdi-information-variant mdi-18px"></i></button></div>
 </div>
 <div class="form-group row">
 <label class="form-control-label text-lg-right col-md-3" >Object Type</label>
 <div class="col-md-9"><select class="form-control" ng-required="true" ng-model="tibacl.objtype"><option value="">Please select</option><option value="queue">Queue</option><option value="topic">Topic</option></select></div>
 </div>
 <div class="form-group row">
 <label class="form-control-label text-lg-right col-md-3" >Object Name</label>
 <div class="col-md-9"><input ng-model="tibacl.objname" ng-required="true" type="text" class="form-control"></div>
 </div>
 <div class="form-group row">
 <label class="form-control-label text-lg-right col-md-3" >ACL Type</label>
 <div class="col-md-9"><select class="form-control" ng-required="true" ng-model="tibacl.acltype"><option value="">Please select</option><option value="user">User</option><option value="group">Group</option></select></div>
 </div>
 <div class="form-group row">
 <label class="form-control-label text-lg-right col-md-3" >ACL Name</label>
 <div class="col-md-9"><input ng-model="tibacl.aclname" ng-required="true" type="text" class="form-control"></div>
 </div>
 <div class="form-group row">
 <label class="form-control-label text-lg-right col-md-3" >Permissions</label>
 <div class="col-md-9">


 <input id="checkboxpublish" ng-checked="tibacl.perm.publish" class="chkbang" type="checkbox" value="publish" style="display:none" /><button ng-click="toggle('publish', selectedtibaclm)" class="waves-effect chkbb  btn  btn-sm  btn-light" ng-class=" tibacl.perm.publish ? 'btn-primary' : 'btn-light'" type="button" id="checkboxpublish_proxy" style="margin-bottom:5px;"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-check" xlink:href="/assets/images/icon/midleoicons.svg#i-check"/></svg>&nbsp;Publish</button>&nbsp;
 <input id="checkboxsubscribe" ng-checked="tibacl.perm.subscribe" class="chkbang" type="checkbox" value="subscribe" style="display:none" /><button ng-click="toggle('subscribe', selectedtibaclm)" class="waves-effect chkbb  btn  btn-sm  btn-light" ng-class=" tibacl.perm.subscribe ? 'btn-primary' : 'btn-light'" type="button" id="checkboxsubscribe_proxy" style="margin-bottom:5px;"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-check" xlink:href="/assets/images/icon/midleoicons.svg#i-check"/></svg>&nbsp;Subscribe</button>&nbsp;
 <input id="checkboxdurable" ng-checked="tibacl.perm.durable" class="chkbang" type="checkbox" value="durable" style="display:none" /><button ng-click="toggle('durable', selectedtibaclm)" class="waves-effect chkbb  btn  btn-sm  btn-light" ng-class=" tibacl.perm.durable ? 'btn-primary' : 'btn-light'" type="button" id="checkboxdurable_proxy" style="margin-bottom:5px;"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-check" xlink:href="/assets/images/icon/midleoicons.svg#i-check"/></svg>&nbsp;Durable</button>&nbsp;
 <input id="checkboxsend" ng-checked="tibacl.perm.send" class="chkbang" type="checkbox" value="send" style="display:none" /><button ng-click="toggle('send', selectedtibaclm)" class="waves-effect chkbb  btn  btn-sm  btn-light" ng-class=" tibacl.perm.send ? 'btn-primary' : 'btn-light'" type="button" id="checkboxsend_proxy" style="margin-bottom:5px;"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-check" xlink:href="/assets/images/icon/midleoicons.svg#i-check"/></svg>&nbsp;Send</button>&nbsp;
 <input id="checkboxreceive" ng-checked="tibacl.perm.receive" class="chkbang" type="checkbox" value="receive" style="display:none" /><button ng-click="toggle('receive', selectedtibaclm)" class="waves-effect chkbb  btn  btn-sm  btn-light" ng-class=" tibacl.perm.receive ? 'btn-primary' : 'btn-light'" type="button" id="checkboxreceive_proxy" style="margin-bottom:5px;"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-check" xlink:href="/assets/images/icon/midleoicons.svg#i-check"/></svg>&nbsp;Receive</button>&nbsp;
 <input id="checkboxbrowse" ng-checked="tibacl.perm.browse" class="chkbang" type="checkbox" value="browse" style="display:none" /><button ng-click="toggle('browse', selectedtibaclm)" class="waves-effect chkbb  btn  btn-sm  btn-light" ng-class=" tibacl.perm.browse ? 'btn-primary' : 'btn-light'" type="button" id="checkboxbrowse_proxy" style="margin-bottom:5px;"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-check" xlink:href="/assets/images/icon/midleoicons.svg#i-check"/></svg>&nbsp;Browse</button>&nbsp;
 
 
 
 <input type="text"   value="{{selectedtibaclm}}" id="selectedperm" style="display:none;">    

 </div>
 </div>
 </div>
 <div role="tabpanel" class="tab-pane" id="info">
 <div class="form-group row">
 <label class="form-control-label text-lg-right col-md-3">Comment</label>
 <div class="col-md-9"><textarea ng-model="tibacl.info" class="form-control" rows="6"></textarea></div>
 </div>
 </div>
</div>
  </div>
  </div>
  <div class="modal-footer">
  <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-x" xlink:href="/assets/images/icon/midleoicons.svg#i-x"/></svg>&nbsp;Close</button>
 <button type="button" id="btn-conf-tibacl" class="waves-effect waves-light btn btn-info btn-sm" ng-click="tibaclconf('<?php echo $thisarray['p2'];?>','<?php echo $_SESSION['user'];?>')" ng-href="{{ url }}"><i class="mdi mdi-download"></i>&nbsp;Create config</button>
  <?php if($_SESSION['user_level']>="3"){?>
  <button type="button" id="btn-create-tibacl" class="waves-effect waves-light btn btn-info btn-sm" ng-click="createtibacl('<?php echo $thisarray['p2'];?>','<?php echo $_SESSION['user'];?>')"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-check" xlink:href="/assets/images/icon/midleoicons.svg#i-check"/></svg>&nbsp;Create</button>
  <button type="button" id="btn-update-tibacl" class="waves-effect waves-light btn btn-info btn-sm" ng-click="updatetibacl('<?php echo $thisarray['p2'];?>','<?php echo $_SESSION['user'];?>')"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-save" xlink:href="/assets/images/icon/midleoicons.svg#i-save"/></svg>&nbsp;Save Changes</button>
  <?php } ?>
   </div>
 </form>
 </div>
 </div>
 </div>
 <?php if($_SESSION['user_level']>=3){?>
 <?php // include $maindir."/public/modules/deplform.php";?>
 <?php // include $maindir."/public/modules/respform.php";?>
 
 <?php } ?>
 </div>
</div>
</div>
</div>
<?php } ?>