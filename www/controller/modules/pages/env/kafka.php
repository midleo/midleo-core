<?php if(empty($thisarray['p2'])){ include "applist.php"; } else { 
  #https://www.programcreek.com/java-api-examples/?api=org.apache.kafka.clients.admin.AdminClient
  #https://kafka.apache.org/documentation/#topicconfigs

  
  ?>
  <div class="row">
 <div class="col-md-3 position-relative">
 <input type="text" ng-model="search" class="form-control  topsearch" placeholder="Find a kafka object">
 <span class="searchicon"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-search" xlink:href="/assets/images/icon/midleoicons.svg#i-search"/></svg></span>
</div>
 <div class="col-md-9 text-end">
<span data-bs-toggle="tooltip" data-bs-placement="top" title="Deploy package on server"><a ng-show="selectedid.length" data-bs-toggle="modal"  class="waves-effect waves-info btn btn-info" href="#modal-depl-form" ng-click="showDeployForm()"><svg class="midico midico-outline" ><use href="/assets/images/icon/midleoicons.svg#i-deploy" xlink:href="/assets/images/icon/midleoicons.svg#i-deploy"/></svg>&nbsp;Deploy</a></span>
 <span data-bs-toggle="tooltip" data-bs-placement="top" title="Define new File transfer configuration"><button type="button" class="waves-effect waves-light btn btn-info" data-bs-toggle="modal" href="#modal-fte-form" ng-click="showCreateFormfte()"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-add" xlink:href="/assets/images/icon/midleoicons.svg#i-add"/></svg>&nbsp;New</button></span>
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
 <th class="text-center">Name</th>
 <th class="text-center">Message bytes</th>
 <th class="text-center">Timestam type</th>
 <th class="text-center">Retention bytes</th>
 <th class="text-center">Dest Folder</th>
 <th class="text-center" style="width:120px;">Action</th>
 </tr>
 </thead>
 <tbody ng-init="getAllkafka('<?php echo $thisarray['p2'];?>','env')">
 <tr ng-hide="contentLoaded"><td colspan="8" style="text-align:center;font-size:1.1em;"><i class="mdi mdi-loading iconspin"></i>&nbsp;Loading...</td></tr>
 <tr id="contloaded" class="hide" dir-paginate="d in names | filter:search | orderBy:'name':reverse | itemsPerPage:10" pagination-id="prodx">
 <td class="text-center">
 <div class="toggle-switch" >
  <input id="deplqmid{{ d.id }}" type="checkbox" class="checkall" value="{{ d.id }}" ng-checked="exists(d.id, selectedid)" ng-click="toggle(d.id, selectedid)" style="display:none;">
  <label for="deplqmid{{ d.id }}" class="ts-helper"></label>
 </div>

  </td>
  <td class="text-center" style="padding: .5rem;"><a href="/automation/{{d.jobid}}" ng-show="d.jobrun==1"><i class="mdi mdi-play-circle-outline mdi-24px"></i></a></td>
 <td class="text-center">{{ d.mqftename | limitTo:textlimit }}{{d.mqftename.length > textlimit ? '...' : ''}}</td>
 <td class="text-center">{{ d.sourceagt | limitTo:textlimit }}{{d.sourceagt.length > textlimit ? '...' : ''}}</td>
 <td class="text-center">{{ d.sourcedir | limitTo:textlimit }}{{d.sourcedir.length > textlimit ? '...' : ''}}</td>
 <td class="text-center">{{ d.destagt | limitTo:textlimit }}{{d.destagt.length > textlimit ? '...' : ''}}</td>
 <td class="text-center">{{ d.destdir | limitTo:textlimit }}{{d.destdir.length > textlimit ? '...' : ''}}</td>
 <td class="text-center">
 <div class="btn-group" role="group">
  <button type="button" ng-click="readOnefte('<?php echo $thisarray['p2'];?>',d.fteid,'env')" style="" class="btn btn-light btn-sm bg waves-effect"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-edit" xlink:href="/assets/images/icon/midleoicons.svg#i-edit"/></svg></button>
  <?php if($_SESSION['user_level']>="3"){?><button type="button" ng-click="deletefte('<?php echo $thisarray['p2'];?>',d.fteid,'<?php echo $_SESSION['user'];?>','env')" class="btn btn-light btn-sm bg waves-effect"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-x" xlink:href="/assets/images/icon/midleoicons.svg#i-x"/></svg></button><?php } ?>
 </div>
 </td>
 </tr>
 </tbody>
 </table>
 <dir-pagination-controls pagination-id="prodx" boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="/assets/templ/pagination.tpl.html"></dir-pagination-controls>
 <div class="modal" id="modal-fte-form" tabindex="-1" role="dialog" aria-hidden="true">
 <div class="modal-dialog">
 <div class="modal-content">
 <div class="modal-header"><h4>Define Apache kafka object</h4></div>
 <form name="form" ng-app>
  <div class="modal-body">
  
  

  </div>
  <div class="modal-footer">
  <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-x" xlink:href="/assets/images/icon/midleoicons.svg#i-x"/></svg>&nbsp;Close</button>
  <?php if($_SESSION['user_level']>="3"){?>
  <button type="button" id="btn-create-fte" class="waves-effect waves-light btn btn-info btn-sm" ng-click="createkafka('<?php echo $thisarray['p2'];?>','<?php echo $_SESSION['user'];?>','env')"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-check" xlink:href="/assets/images/icon/midleoicons.svg#i-check"/></svg>&nbsp;Create</button>
  <button type="button" id="btn-update-fte" class="waves-effect waves-light btn btn-info btn-sm" ng-click="updatekafka('<?php echo $thisarray['p2'];?>','<?php echo $_SESSION['user'];?>','env')"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-save" xlink:href="/assets/images/icon/midleoicons.svg#i-save"/></svg>&nbsp;Save Changes</button>
  <?php } ?>
  </div>
 </form>
 </div>
 </div>
 </div>
 <?php if($_SESSION['user_level']>=3){?>
 <div class="modal" id="modal-depl-form" tabindex="-1" role="dialog" aria-hidden="true">
 <div class="modal-dialog">
 <div class="modal-content">
 <form action="" method="post" name="form">
 <div class="modal-header">Deploy Kafka object</div>
  <div class="modal-body container form-material">
  <div class="form-group row">
<input style="display:none;" id="selectedobj" value="{{selectedid | json}}">
 <label class="form-control-label text-lg-right col-md-3">Environment</label>
 <?php if(is_array($menudataenv)){?>
 <div class="col-md-8"> <select ng-required="true" ng-model="depl.deplenv" class="form-control"><option value="">Please select</option>
 <?php 
 foreach($menudataenv as $keyenv=>$valenv) { ?>
 <option value="<?php echo $valenv['nameshort'];?>"><?php echo $valenv['name'];?></option>
 <?php }
 ?>
 </select>
 <?php } ?>
</div>
</div>
<div class="form-group row" ng-show="depl.deplenv">
<label class="form-control-label text-lg-right col-md-3">Request Number</label>
<div class="col-md-9"> <input type="text" ng-model="depl.reqname" id="reqauto" class="form-control" />
<input type="text"  id="reqname" name="reqname" style="display:none;" /> </div>
</div>
<div class="form-group row" ng-show="depl.deplenv">
<label class="form-control-label text-lg-right col-md-3">Job time</label>
<div class="col-md-9">
<input name="jobnextrun" class="form-control date-time-picker-depl"  id="jobnextrun" data-bs-toggle="datetimepicker" data-target="#jobnextrun" type="text">
</div>
</div>
  <div style="display:block;" class="text-center"><div class="loading"><svg class="circular" viewBox="25 25 50 50">
<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
</div></div>
  </div>
  <div class="modal-footer">
  <button ng-show="depl.deplenv" type="button" class="waves-effect waves-light btn btn-light btn-sm" ng-click="createjob('<?php echo $thisarray['p2'];?>','kafka','kafka_obj','','')"><i class="mdi mdi-play-circle-outline"></i>&nbsp;Create job</button>
  <button ng-show="depl.deplenv" type="button" ng-click="form.$valid && deployKafkasel('<?php echo $thisarray['p2'];?>');" class="waves-effect waves-light btn btn-primary btn-sm"><svg class="midico midico-outline" ><use href="/assets/images/icon/midleoicons.svg#i-deploy" xlink:href="/assets/images/icon/midleoicons.svg#i-deploy"/></svg>&nbsp;Deploy</button>
  <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-x" xlink:href="/assets/images/icon/midleoicons.svg#i-x"/></svg>&nbsp;Close</button>
  </div>
 </form>
 </div>
 </div>
 </div>
 <div class="modal" id="modal-deplfte-response" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="width:1000px;">
      <div class="modal-content">
      <div class="modal-body" style="width:100%;max-height:500px;overflow-x:hidden;overflow-y:scroll;padding: 3px;">
      <pre>
        <p>{{ responsedatafte.message }}</p>
        <p ng-repeat="(key,val) in responsedataftemess.messages" style="white-space: pre;word-break: break-word;margin-bottom:0px;">{{val.message}}<br/>  </p>
        </pre>
      </div>
          <div class="modal-footer" style="border-top:0px solid transparent;">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-x" xlink:href="/assets/images/icon/midleoicons.svg#i-x"/></svg>&nbsp;Close</button>
          </div>
      </div>
    </div>
  </div>
 <?php // include $maindir."/public/modules/deplform.php";?>
 <?php // include $maindir."/public/modules/respform.php";?>

 <?php } ?>
 </div>
</div>
</div>
</div>
<?php } ?>