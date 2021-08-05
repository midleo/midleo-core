<?php if(empty($thisarray['p2'])){ include "applist.php"; } else { ?>
  <div class="row">
 <div class="col-md-3 position-relative">
 <input type="text" ng-model="search" class="form-control  topsearch" placeholder="Find a fte configuration">
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
 <th class="text-center">Source Agt</th>
 <th class="text-center">Source Folder</th>
 <th class="text-center">Dest Agt</th>
 <th class="text-center">Dest Folder</th>
 <th class="text-center" style="width:120px;">Action</th>
 </tr>
 </thead>
 <tbody ng-init="getAllfte('<?php echo $thisarray['p2'];?>','env')">
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
 <div class="modal-header"><h4>Define MQ FTE definition</h4></div>
 <form name="form" ng-app>
  <div class="modal-body">
  <div role="tabpanel">
  <ul class="nav nav-tabs customtab" role="tablist">
 <li class="nav-item"><a class="nav-link active" href="#base" aria-controls="base" role="tab" data-bs-toggle="tab">Base</a></li>
 <li class="nav-item"><a class="nav-link" href="#info" aria-controls="info" role="tab" data-bs-toggle="tab">Comment</a></li>
</ul><br>
<div class="tab-content container" style="width:100%;min-height:300px;max-height:500px;overflow-x:hidden;overflow-y:scroll;">
 <div role="tabpanel" class="tab-pane active" id="base" >
 <div class="form-group row">
 <label class="form-control-label text-lg-right col-md-3" ng-class="{'has-error':!mqfte.mqftetype}">Type</label>
 <div class="col-md-8"><select class="form-control" ng-required="true" ng-model="mqfte.mqftetype"><option value="">Please select</option><option value="f2f">File to File</option><option value="f2q">File to Queue</option><option value="q2f">Queue to File</option></select></div>
 <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="There are three types of file transfers. File to File - source and destination are files. Queue to File - source is a queue and destination - file. File to Queue - source is a file and destination - Queue" ><i class="mdi mdi-information-variant mdi-18px"></i></button></div>
 </div>
 <div class="form-group row">
 <label class="form-control-label text-lg-right col-md-3">Tags</label>
 <div class="col-md-8"><input id="tags" data-role="tagsinput" type="text" class="form-control"></div>
 <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="You can search this object with tags" ><i class="mdi mdi-information-variant mdi-18px"></i></button></div>
 </div>
 <div class="form-group row">
 <label class="form-control-label text-lg-right col-md-3" ng-class="{'has-error':!mqfte.mqftename}">Name</label>
 <div class="col-md-8"><input ng-model="mqfte.mqftename" ng-required="true" type="text" class="form-control"></div>
 <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Name of the transfer" ><i class="mdi mdi-information-variant mdi-18px"></i></button></div>
 </div>
 <div class="form-group row">
 <label class="form-control-label text-lg-right col-md-3" >BatchSize</label>
 <div class="col-md-8"><input ng-model="mqfte.batchsize" type="text" class="form-control"></div>
 <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Number of files in one transfer.<br>Default is 1" ><i class="mdi mdi-information-variant mdi-18px"></i></button></div>
 </div>
 <div class="form-group row">
 <label class="form-control-label text-lg-right col-md-3" ng-class="{'has-error':!mqfte.sourceagt}">SourceAGT</label>
 <div class="col-md-8">
 <?php 
 $sql="select serverdns,agentname from env_appservers where serv_type='fte' and proj=? group by agentname";
 $stmt = $pdo->prepare($sql);
 $stmt->execute(array($thisarray['p2']));
 if($zobjin = $stmt->fetchAll()){
 ?>
<select class="form-control" ng-model="mqfte.sourceagt" ng-required="true">
<option value="">Please select</option>
<?php foreach($zobjin as $val) { echo '<option value="'.$val["agentname"].'">'.$val['agentname'].' ('.$val['serverdns'].')</option>'; } ?>
</select><?php } else { ?>
 <input ng-model="mqfte.sourceagt" ng-required="true" type="text" class="form-control">
 <?php } ?>
 </div>
 <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="The name of the Source FTE agent" ><i class="mdi mdi-information-variant mdi-18px"></i></button></div>
 </div>
 <div class="form-group row">
 <label class="form-control-label text-lg-right col-md-3" ng-class="{'has-error':!mqfte.sourceagtqmgr}">SAGTQM</label>
 <div class="col-md-8">
 <?php 
 $sql="select serverdns,qmname from env_appservers where  (serv_type='qm' or serv_type='fte') and proj=? group by qmname";
 $stmt = $pdo->prepare($sql);
 $stmt->execute(array($thisarray['p2']));
 if($zobjin = $stmt->fetchAll()){
 ?>
<select class="form-control" ng-model="mqfte.sourceagtqmgr" ng-required="true">
<option value="">Please select</option>
<?php foreach($zobjin as $val) { echo '<option value="'.$val["qmname"].'">'.$val['qmname'].' ('.$val['serverdns'].')</option>'; } ?>
</select><?php } else { ?>
 <input ng-model="mqfte.sourceagtqmgr" ng-required="true" type="text" class="form-control">
 <?php } ?>
 </div>
 <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="The name of the Source FTE agent Qmanager" ><i class="mdi mdi-information-variant mdi-18px"></i></button></div>
 </div>
 <div class="form-group row">
 <label class="form-control-label text-lg-right col-md-3" ng-class="{'has-error':!mqfte.destagt}">DestAGT</label>
 <div class="col-md-8">
 <?php
 $sql="select serverdns,agentname from env_appservers where serv_type='fte' and proj=? group by agentname";
 $stmt = $pdo->prepare($sql);
 $stmt->execute(array($thisarray['p2']));
 if($zobjin = $stmt->fetchAll()){
 ?>
<select class="form-control" ng-model="mqfte.destagt" ng-required="true">
<option value="">Please select</option>
<?php foreach($zobjin as $val) { echo '<option value="'.$val["agentname"].'">'.$val['agentname'].' ('.$val['serverdns'].')</option>'; } ?>
</select><?php } else { ?>
 <input ng-model="mqfte.destagt" ng-required="true" type="text" class="form-control">
 <?php } ?>
 </div>
 <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="The name of the Destination FTE agent" ><i class="mdi mdi-information-variant mdi-18px"></i></button></div>
 </div>
 <div class="form-group row">
 <label class="form-control-label text-lg-right col-md-3" ng-class="{'has-error':!mqfte.destagtqmgr}">DAGTQM</label>
 <div class="col-md-8">
 <?php 
 $sql="select serverdns,qmname from env_appservers where (serv_type='qm' or serv_type='fte') and proj=? group by qmname";
 $stmt = $pdo->prepare($sql);
 $stmt->execute(array($thisarray['p2']));
 if($zobjin = $stmt->fetchAll()){
 ?>
<select class="form-control" ng-model="mqfte.destagtqmgr" ng-required="true">
<option value="">Please select</option>
<?php foreach($zobjin as $val) { echo '<option value="'.$val["qmname"].'">'.$val['qmname'].' ('.$val['serverdns'].')</option>'; } ?>
</select><?php } else { ?>
 <input ng-model="mqfte.destagtqmgr" ng-required="true" type="text" class="form-control">
 <?php } ?>
 </div>
 <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="The name of the Destination FTE agent Qmanager" ><i class="mdi mdi-information-variant mdi-18px"></i></button></div>
 </div>
 <div class="form-group row">
 <label class="form-control-label text-lg-right col-md-3"  ng-class="{'has-error':!mqfte.sourcedisp}">sourceDisp</label>
 <div class="col-md-8"><select class="form-control" ng-required="true" ng-model="mqfte.sourcedisp"><option value="">Please select</option><option value="leave">Leave</option><option value="delete">Delete</option></select></div>
 <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="After successfull transfer you can select either to delete or leave the source file" ><i class="mdi mdi-information-variant mdi-18px"></i></button></div>
 </div>
 <div class="form-group row">
 <label class="form-control-label text-lg-right col-md-3" >textOrBinary</label>
 <div class="col-md-8"><select class="form-control" ng-required="true" ng-model="mqfte.textorbinary"><option value="">Please select</option><option value="text">Text</option><option value="binary">Binary</option></select></div>
 <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="The type of the transfer - text or binary" ><i class="mdi mdi-information-variant mdi-18px"></i></button></div>
 </div>
 <div class="form-group row" ng-show="mqfte.textorbinary=='text'">
 <label class="form-control-label text-lg-right col-md-3" >sourceCCSID</label>
 <div class="col-md-8"><input ng-model="mqfte.sourceccsid" type="text" class="form-control"></div>
 <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="The CCSID of the source file" ><i class="mdi mdi-information-variant mdi-18px"></i></button></div>
 </div>
 <div class="form-group row" ng-show="mqfte.textorbinary=='text'">
 <label class="form-control-label text-lg-right col-md-3">destCCSID</label>
 <div class="col-md-8"><input ng-model="mqfte.destccsid" type="text" class="form-control"></div>
 <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="The CCSID of the destination file" ><i class="mdi mdi-information-variant mdi-18px"></i></button></div>
 </div>
 <div class="form-group row" ng-show="mqfte.mqftetype=='f2f' || mqfte.mqftetype=='f2q'">
 <label class="form-control-label text-lg-right col-md-3"  >MonDir</label>
 <div class="col-md-8"><input ng-model="mqfte.sourcedir" type="text" class="form-control"></div>
 <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="The directory name from where the file will be taken" ><i class="mdi mdi-information-variant mdi-18px"></i></button></div>
 </div>
 <div class="form-group row" ng-show="mqfte.mqftetype=='f2f' || mqfte.mqftetype=='f2q'">
 <label class="form-control-label text-lg-right col-md-3" >MonFile</label>
 <div class="col-md-8"><input ng-model="mqfte.sourcefile" type="text" class="form-control"></div>
 <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="The file pattern that will trigger the transfer" ><i class="mdi mdi-information-variant mdi-18px"></i></button></div>
 </div>
 <div class="form-group row" ng-show="mqfte.mqftetype=='f2f' || mqfte.mqftetype=='f2q'">
 <label class="form-control-label text-lg-right col-md-3"  >Regex</label>
 <div class="col-md-8"><select class="form-control" ng-model="mqfte.regex"><option value="">No</option><option value="1">Yes</option></select></div>
 <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="is the file pattern regular expression" ><i class="mdi mdi-information-variant mdi-18px"></i></button></div>
 </div>
 <div class="form-group row" ng-show="mqfte.mqftetype=='q2f'">
 <label class="form-control-label text-lg-right col-md-3" >MonQueue</label>
 <div class="col-md-8"><input ng-model="mqfte.sourcequeue" type="text" class="form-control"></div>
 <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" data-html="true" title="The queue name which will be monitored for messages<br>Note that queue should be visible from the source agent qmanager" ><i class="mdi mdi-information-variant mdi-18px"></i></button></div>
 </div>
 <div class="form-group row" ng-show="mqfte.mqftetype=='f2f' || mqfte.mqftetype=='q2f'">
 <label class="form-control-label text-lg-right col-md-3"  >DestDir</label>
 <div class="col-md-8"><input ng-model="mqfte.destdir" type="text" class="form-control"></div>
 <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="The name of directory on destination side" ><i class="mdi mdi-information-variant mdi-18px"></i></button></div>
 </div>
 <div class="form-group row" ng-show="mqfte.mqftetype=='f2f' || mqfte.mqftetype=='q2f'">
 <label class="form-control-label text-lg-right col-md-3" >DestFile</label>
 <div class="col-md-8"><input ng-model="mqfte.destfile" type="text" class="form-control"></div>
 <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" data-html="true" title="The file name on destination side<br>Default is ${FileName}" ><i class="mdi mdi-information-variant mdi-18px"></i></button></div>
 </div>
 <div class="form-group row" ng-show="mqfte.mqftetype=='f2q'">
 <label class="form-control-label text-lg-right col-md-3" >DestQueue</label>
 <div class="col-md-8"><input ng-model="mqfte.destqueue" type="text" class="form-control"></div>
 <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="The queue name to which messages will be transferred. Note that queue should be visible from the destination agent qmanager" ><i class="mdi mdi-information-variant mdi-18px"></i></button></div>
 </div>
 <div class="form-group row" ng-show="mqfte.mqftetype!='q2f'">
 <label class="form-control-label text-lg-right col-md-3" >SourceCMD</label>
 <div class="col-md-8"><input ng-model="mqfte.postsourcecmd" type="text" class="form-control"></div>
 <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Post source command that will be triggered after successfull transfer. Triggering is on source side" ><i class="mdi mdi-information-variant mdi-18px"></i></button></div>
 </div>
 <div class="form-group row" ng-show="mqfte.mqftetype!='q2f'">
 <label class="form-control-label text-lg-right col-md-3" >SCMDARG</label>
 <div class="col-md-8"><input ng-model="mqfte.postsourcecmdarg" type="text" class="form-control"></div>
 <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Arguments that will be passed to the command. Please use interval between each argument" ><i class="mdi mdi-information-variant mdi-18px"></i></button></div>
 </div>
 <div class="form-group row" ng-show="mqfte.mqftetype!='f2q'">
 <label class="form-control-label text-lg-right col-md-3" >DestCMD</label>
 <div class="col-md-8"><input ng-model="mqfte.postdestcmd" type="text" class="form-control"></div>
 <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Post destination command that will be triggered after successfull transfer. Triggering is on destination side" ><i class="mdi mdi-information-variant mdi-18px"></i></button></div>

 </div>
 <div class="form-group row" ng-show="mqfte.mqftetype!='f2q'">
 <label class="form-control-label text-lg-right col-md-3" >DCMDARG</label>
 <div class="col-md-8"><input ng-model="mqfte.postdestcmdarg" type="text" class="form-control"></div>
 <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Post Destination Command arguments. Arguments that will be passed to the command. Please use interval between each argument" ><i class="mdi mdi-information-variant mdi-18px"></i></button></div>

 </div>
 <div class="form-group"><br></div>
 </div>
 <div role="tabpanel" class="tab-pane" id="info">
 <div class="form-group row">
 <label class="form-control-label text-lg-right col-md-3">Comment</label>
 <div class="col-md-8"><textarea ng-model="mqfte.info" class="form-control" rows="6"></textarea></div>
 <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Additional information about this transfer. Email, requestor, references, that will help you finding it in future" ><i class="mdi mdi-information-variant mdi-18px"></i></button></div>
 </div>
 </div>
</div>
  </div>
  </div>
  <div class="modal-footer">
  <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-x" xlink:href="/assets/images/icon/midleoicons.svg#i-x"/></svg>&nbsp;Close</button>
    <button type="button" id="btn-conf-fte" class="waves-effect waves-light btn btn-info btn-sm" ng-click="fteconf('<?php echo $thisarray['p2'];?>','env')" ng-href="{{ url }}"><i class="mdi mdi-download"></i>&nbsp;Create config</button>
  <?php if($_SESSION['user_level']>="3"){?>
  <button type="button" id="btn-create-fte" class="waves-effect waves-light btn btn-info btn-sm" ng-click="createfte('<?php echo $thisarray['p2'];?>','<?php echo $_SESSION['user'];?>','env')"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-check" xlink:href="/assets/images/icon/midleoicons.svg#i-check"/></svg>&nbsp;Create</button>
  <button type="button" id="btn-update-fte" class="waves-effect waves-light btn btn-info btn-sm" ng-click="updatefte('<?php echo $thisarray['p2'];?>','<?php echo $_SESSION['user'];?>','env')"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-save" xlink:href="/assets/images/icon/midleoicons.svg#i-save"/></svg>&nbsp;Save Changes</button>
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
 <div class="modal-header">Deploy MQ FTE monitor</div>
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
  <button ng-show="depl.deplenv" type="button" class="waves-effect waves-light btn btn-light btn-sm" ng-click="createjob('<?php echo $thisarray['p2'];?>','fte','mqenv_mqfte','','')"><i class="mdi mdi-play-circle-outline"></i>&nbsp;Create job</button>
  <button ng-show="depl.deplenv" type="button" ng-click="form.$valid && deployMQftesel('<?php echo $thisarray['p2'];?>');" class="waves-effect waves-light btn btn-primary btn-sm"><svg class="midico midico-outline" ><use href="/assets/images/icon/midleoicons.svg#i-deploy" xlink:href="/assets/images/icon/midleoicons.svg#i-deploy"/></svg>&nbsp;Deploy</button>
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