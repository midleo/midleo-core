<?php if(empty($thisarray['p2'])){ include "applist.php"; } else { ?>
  <div class="row">
  <div class="col-md-3 position-relative">
      <input type="text" ng-model="search" class="form-control  topsearch" placeholder="Find a queue">
      <span class="searchicon"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-search" xlink:href="/assets/images/icon/midleoicons.svg#i-search"/></svg></span>
</div>
  <div class="col-md-9 text-end">
<span data-bs-toggle="tooltip" data-bs-placement="top" title="Deploy package on server"><a ng-show="selectedid.length" data-bs-toggle="modal"  class="waves-effect waves-light btn btn-light" href="#modal-depl-form" ng-click="showDeployForm()"><svg class="midico midico-outline" ><use href="/assets/images/icon/midleoicons.svg#i-deploy" xlink:href="/assets/images/icon/midleoicons.svg#i-deploy"/></svg>&nbsp;Deploy</a></span>
<?php if ($_SESSION['user_level'] >= 3 && !in_array($thisarray['p1'], array("packages", "appservers", "servers", "import", "deploy", "flows", "fte"))) {?><span><button data-bs-toggle="tooltip" data-bs-placement="top" title="Export the objects in excel" type="button" class="waves-effect waves-light btn btn-light" ng-click="exportData('<?php echo $thisarray['p1']; ?>')">Export&nbsp;<svg class="midico midico-outline" ><use href="/assets/images/icon/midleoicons.svg#i-up" xlink:href="/assets/images/icon/midleoicons.svg#i-up"/></svg></button> </span><?php }?>
 <span data-bs-toggle="tooltip" data-bs-placement="top" title="Define new Tibco queue"><button type="button" class="waves-effect waves-light btn btn-info" data-bs-toggle="modal" href="#modal-obj-form" ng-click="showCreateFormTib()"><svg class="midico midico-outline" ><use href="/assets/images/icon/midleoicons.svg#i-add" xlink:href="/assets/images/icon/midleoicons.svg#i-add"/></svg>&nbsp;Create</button></span>
  </div>
</div><br>
<div class="card ">
<div class="card-body p-0">

<div class="row"><div class="col-md-12">
  <table class="table table-vmiddle table-hover stylish-table mb-0">
    <thead>
      <tr>
        <th class="text-center" style="width:50px;"></th>
        <th class="text-center">Job</th>
        <th class="text-center">SRV</th>
        <th class="text-center">Queue</th>
        <th class="text-center">Maxmsgs</th>
        <th class="text-center">Maxbytes</th>
        <th class="text-center" style="width:120px;">Action</th>
      </tr>
    </thead>
    <tbody ng-init="getAllTib('queue','<?php echo $thisarray['p2'];?>')">
      <tr ng-hide="contentLoaded"><td colspan="9" style="text-align:center;font-size:1.1em;"><i class="mdi mdi-loading iconspin"></i>&nbsp;Loading...</td></tr>
      <tr id="contloaded" class="hide" dir-paginate="d in names | filter:search | orderBy:'srv':reverse | itemsPerPage:10" pagination-id="prodx" ng-hide="d.srv==''">
        <td class="text-center">
        <div class="toggle-switch" >
           <input id="depltibobj{{ d.id }}" type="checkbox" class="checkall"  value="{{ d.id }}" ng-checked="exists(d.id, selectedid)" ng-click="toggle(d.id, selectedid)" style="display:none;">
          <label for="depltibobj{{ d.id }}" class="ts-helper"></label>
       </div>

         </td>
         <td class="text-center" style="padding: .5rem;"><a href="/automation/{{d.jobid}}" ng-show="d.jobrun==1"><i class="mdi mdi-play-circle-outline mdi-24px"></i></a></td>
        <td class="text-center">{{ d.srv }}</td>
        <td class="text-center">{{ d.name}}</td>
        <td class="text-center">{{ d.maxmsgs }}</td>
        <td class="text-center">{{ d.maxbytes }}</td>
        <td class="text-center">
        <div class="btn-group" role="group">
          <button type="button" ng-click="readOneTib('queue',d.id,d.proj)" style="" class="btn btn-light btn-sm bg waves-effect"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-edit" xlink:href="/assets/images/icon/midleoicons.svg#i-edit"/></svg></button>
          <?php if($_SESSION['user_level']>="3"){?>
          <button type="button" ng-click="duplicate('tibco','queue',d.id,'<?php echo $_SESSION['user'];?>','<?php echo $thisarray['p2'];?>')" class="btn btn-light btn-sm bg waves-effect" title="Duplicate"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-documents" xlink:href="/assets/images/icon/midleoicons.svg#i-documents"/></svg></button>
          <button type="button" ng-click="deletetib('queue',d.id,d.name,d.proj,'<?php echo $_SESSION['user'];?>')" class="btn btn-light btn-sm bg waves-effect"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-x" xlink:href="/assets/images/icon/midleoicons.svg#i-x"/></svg></button>
          <?php } ?>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
  <dir-pagination-controls pagination-id="prodx" boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="/assets/templ/pagination.tpl.html"></dir-pagination-controls>
  <div class="modal" id="modal-obj-form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
      <div class="modal-header">
       <h4 class="modal-title">Tibco queue definition</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
        <form name="form" ng-app>
          <div class="modal-body container form-material" style="width:100%;min-height:300px;max-height:500px;overflow-x:hidden;overflow-y:scroll;">

                   <?php if(isset($modulelist["budget"]) && !empty($modulelist["budget"])){ ?>
                  <input ng-model="tibq.proj" style="display:none;" value="<?php echo $thisarray['p2'];?>">
                      <?php } ?>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  ng-class="{'has-error':!tibq.srv}">EMS Server</label>
                    <div class="col-md-9"><?php
 $sql="select serverdns from env_appservers where serv_type='tibems' and proj=?";
 $stmt = $pdo->prepare($sql);
 $stmt->execute(array($thisarray['p2']));
 if($zobjfte = $stmt->fetchAll()){
 ?>
<select class="form-control" ng-model="tibq.srv" ng-required="true">
<option value="">Please select</option>
<?php foreach($zobjfte as $val) { echo '<option value="'.$val["serverdns"].'">'.$val['serverdns'].'</option>'; } ?>
</select><?php }  else { ?>
  <input ng-model="tibq.srv" ng-required="true" type="text" class="form-control">
 <?php } ?>  
 </div>
                  </div>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3">Tags</label>
                    <div class="col-md-8"><input id="tags" data-role="tagsinput" type="text" class="form-control"></div>
                    <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="You can search this object with tags" ><i class="mdi mdi-information-variant mdi-18px"></i></button></div>
                  </div>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3" ng-class="{'has-error':!tibq.name}">Name</label>
                    <div class="col-md-8"><input ng-maxlength="48" ng-model="tibq.name" ng-required="true" type="text" class="form-control"></div>
                    <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Queue name" ><i class="mdi mdi-information-variant mdi-18px"></i></button></div>
                  </div>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3" >Maxmsgs</label>
                    <div class="col-md-9"><input  ng-model="tibq.maxmsgs" type="number" class="form-control"></div>
                  </div>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3" >Maxbytes</label>
                    <div class="col-md-9"><input  ng-model="tibq.maxbytes" type="number" class="form-control"></div>
                  </div>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3" >Maxredel</label>
                    <div class="col-md-9"><input  ng-model="tibq.maxredel" type="number" class="form-control"></div>
                  </div>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3" >Store</label>
                    <div class="col-md-9"><input ng-model="tibq.store" type="text" class="form-control"></div>
                  </div>
                  <div class="form-group row">
                      <div class="col-md-3"></div><div class="col-md-9">  <div class="toggle-switch" >
                    <input id="secure" value="1" ng-model="tibq.secure" type="checkbox" ng-checked="tibq.secure=='1'" style="display:none;">
                    <label for="secure" class="ts-helper">Secure</label>
                     </div>
                       </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-md-3"></div><div class="col-md-9">  <div class="toggle-switch" >
                    <input id="exclusive" value="1" ng-model="tibq.exclusive" type="checkbox" ng-checked="tibq.exclusive=='1'" style="display:none;">
                    <label for="exclusive" class="ts-helper">Exclusive</label>
                     </div>
                       </div>
                    </div>
                 
          </div>
          <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-x" xlink:href="/assets/images/icon/midleoicons.svg#i-x"/></svg>&nbsp;Close</button>
          <?php if($_SESSION['user_level']>="3"){?>
            <button type="button" id="btn-create-obj" class="waves-effect waves-light btn btn-info btn-sm" ng-click="form.$valid && createtib('queue','<?php echo $thisarray['p2'];?>','<?php echo $_SESSION['user'];?>')"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-check" xlink:href="/assets/images/icon/midleoicons.svg#i-check"/></svg>&nbsp;Create</button>
            <button type="button"  id="btn-update-obj" class="waves-effect waves-light btn btn-info btn-sm" ng-click="updatetib('queue','<?php echo $thisarray['p2'];?>','<?php echo $_SESSION['user'];?>')"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-save" xlink:href="/assets/images/icon/midleoicons.svg#i-save"/></svg>&nbsp;Save Changes</button>
            <?php } ?>
           </div>
        </form>
      </div>
    </div>
  </div>
  <?php if($_SESSION['user_level']>="3"){?>
<?php include $maindir."/public/modules/depltibform.php";?>
<?php include $maindir."/public/modules/respform.php";?>

  <?php } ?>
  </div>
</div>
</div>
</div>
<?php } ?>