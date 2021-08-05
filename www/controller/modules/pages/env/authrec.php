<?php if(empty($thisarray['p2'])){ include "applist.php"; } else { ?>
   <div class="row">
  <div class="col-md-3 position-relative">
      <input type="text" ng-model="search" class="form-control  topsearch" placeholder="Find a record">
      <span class="searchicon"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-search" xlink:href="/assets/images/icon/midleoicons.svg#i-search"/></svg></span>
</div>
  <div class="col-md-9 text-end">
<span data-bs-toggle="tooltip" data-bs-placement="top" title="Deploy package on server"><a ng-show="selectedid.length" data-bs-toggle="modal"  class="waves-effect waves-light btn btn-light" href="#modal-depl-form" ng-click="showDeployForm()"><svg class="midico midico-outline" ><use href="/assets/images/icon/midleoicons.svg#i-deploy" xlink:href="/assets/images/icon/midleoicons.svg#i-deploy"/></svg>&nbsp;Deploy</a></span>
<?php if ($_SESSION['user_level'] >= 3 && !in_array($thisarray['p1'], array("packages", "appservers", "servers", "import", "deploy", "flows", "fte"))) {?><span><button data-bs-toggle="tooltip" data-bs-placement="top" title="Export the objects in excel" type="button" class="waves-effect waves-light btn btn-light" ng-click="exportData('<?php echo $thisarray['p1']; ?>')">Export&nbsp;<svg class="midico midico-outline" ><use href="/assets/images/icon/midleoicons.svg#i-up" xlink:href="/assets/images/icon/midleoicons.svg#i-up"/></svg></button> </span><?php }?>
<span data-bs-toggle="tooltip" data-bs-placement="top" title="Define new AUTH definitions"><button type="button" class="waves-effect waves-light btn btn-info" data-bs-toggle="modal" href="#modal-obj-form" ng-click="showCreateForm()"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-add" xlink:href="/assets/images/icon/midleoicons.svg#i-add"/></svg>&nbsp;New</button></span>
</div>
</div><br>
   <div class="card ">
<div class="card-body p-0">

  <div class="row"><div class="col-md-12">
    <table class="table table-vmiddle table-hover stylish-table mb-0">
      <thead>
        <tr>
          <th class="text-center" style="width:50px;"></th>
          <th class="text-center" style="width:50px;">Job</th>
          <th class="text-center">QM</th>
          <th class="text-center">Name</th>
          <th class="text-center">Type</th>
          <th class="text-center">Group</th>
          <th class="text-center" style="width:120px;">Action</th>
        </tr>
      </thead>
      <tbody ng-init="getAll('<?php echo $page=="env"?$thisarray['p1']:$thisarray['p3'];?>','<?php echo $thisarray['p2'];?>','<?php echo $page;?>')">
        <tr ng-hide="contentLoaded"><td colspan="7" style="text-align:center;font-size:1.1em;"><i class="mdi mdi-loading iconspin"></i>&nbsp;Loading...</td></tr>
        <tr id="contloaded" class="hide" dir-paginate="d in names | filter:search | orderBy:sortKey:reverse | itemsPerPage:10" pagination-id="prodx" ng-hide="d.qm==''">
          <td class="text-center">
          <div class="toggle-switch" >
           <input id="deplqmid{{ d.qmid }}" type="checkbox" class="checkall"  value="{{ d.qmid }}" ng-checked="exists(d.qmid, selectedid)" ng-click="toggle(d.qmid, selectedid)" style="display:none;">
           <label  for="deplqmid{{ d.qmid }}" class="ts-helper"></label>
       </div>
         </td>
         <td class="text-center" style="padding: .5rem;"><a href="/automation/{{d.jobid}}" ng-show="d.jobrun==1"><i class="mdi mdi-play-circle-outline mdi-24px"></i></a></td>
          <td class="text-center">{{ d.qm }}</td>
          <td class="text-center">{{ d.name}}</td>
          <td class="text-center">{{ d.type}}</td>
          <td class="text-center">{{ d.group}}</td>
          <td class="text-center">
          <div class="btn-group" role="group">
          <button type="button" ng-click="readOne('<?php echo $page=="env"?$thisarray['p1']:$thisarray['p3'];?>',d.qid,d.qmid,'<?php echo $thisarray['p2'];?>','<?php echo $page;?>')" class="btn btn-light btn-sm bg waves-effect"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-edit" xlink:href="/assets/images/icon/midleoicons.svg#i-edit"/></svg></button>
              <?php if($_SESSION['user_level']>="3"){?>
               <button type="button" ng-click="duplicate('<?php echo $thisarray['p1'];?>',d.qid,d.qmid,'<?php echo $_SESSION['user'];?>','<?php echo $thisarray['p2'];?>')" class="btn btn-light btn-sm bg waves-effect" title="Duplicate"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-documents" xlink:href="/assets/images/icon/midleoicons.svg#i-documents"/></svg></button>
            <button type="button" ng-click="delete('<?php echo $page=="env"?$thisarray['p1']:$thisarray['p3'];?>',d.qid,d.qmid,'<?php echo $thisarray['p2'];?>','<?php echo $_SESSION['user'];?>','<?php echo $page;?>')" class="btn btn-light btn-sm bg waves-effect"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-x" xlink:href="/assets/images/icon/midleoicons.svg#i-x"/></svg></button>
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
        <div class="modal-header"><h4>Define AUTH definition</h4></div>
          <form name="form" ng-app>
            <div class="modal-body">
              
              <div role="tabpanel">
                <input ng-model="mq.type" ng-init="mq.type='auth'" style="display:none;">
                <ul class="nav nav-tabs customtab" role="tablist">
                  <li class="nav-item"><a class="nav-link active" href="#base" aria-controls="base" role="tab" data-bs-toggle="tab">Base</a></li>
                  <li class="nav-item"><a class="nav-link" href="#authority" aria-controls="authority" role="tab" data-bs-toggle="tab">Authority</a></li>
                </ul><br>
                <div class="tab-content container form-material" style="width:100%;min-height:300px;max-height:500px;overflow-x:hidden;overflow-y:scroll;">
                  <div role="tabpanel" class="tab-pane active" id="base" >
                    
                    <div class="form-group row">
                       <label class="form-control-label text-lg-right col-md-3"   data-trigger="hover" data-bs-toggle="popover" data-bs-placement="top" data-html="true" data-content="If this object is active.<br> Default - yes.<br>If NO, it will not appear in the builds" title="" data-original-title="Active">Active</label>
                       <div class="col-md-9"><select class="form-control" ng-init="mq.active='yes'" ng-model="mq.active"><option value="">Please select</option><option value="yes" ng-selected="mq.active==yes">Yes</option><option value="no">No</option></select></div>
                    </div>
                    <div class="form-group row">
                       <label class="form-control-label text-lg-right col-md-3"   data-trigger="hover" data-bs-toggle="popover" data-bs-placement="top" data-html="true" data-content="Qmanager name" title="" data-original-title="Qmanager" ng-class="{'has-error':!mq.qm}">QMGR</label>
                       <div class="col-md-9">
                       <?php 
$sql="select serverdns,qmname from env_appservers where (serv_type='qm' or serv_type='fte') and proj=? group by qmname";
$stmt = $pdo->prepare($sql);
$stmt->execute(array($thisarray['p2']));
 if($zobjfte = $stmt->fetchAll()){
 ?>
<select class="form-control" ng-model="mq.qm" ng-required="true">
<option value="">Please select</option>
<?php foreach($zobjfte as $val) { echo '<option value="'.$val["qmname"].'">'.$val['qmname'].' ('.$val['serverdns'].')</option>'; } ?>
</select><?php } else { ?>
  <input ng-model="mq.qm" ng-required="true" type="text" class="form-control">
 <?php } ?>  
 </div>
                    </div>
                    <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3">Tags</label>
                    <div class="col-md-8"><input id="tags" data-role="tagsinput" type="text" class="form-control"></div>
                    <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="You can search this object with tags" ><i class="mdi mdi-information-variant mdi-18px"></i></button></div>
                  </div>
                    <div class="form-group row">
                       <label class="form-control-label text-lg-right col-md-3"  ng-class="{'has-error':!mq.name}">Name</label>
                       <div class="col-md-9"><input ng-model="mq.name" ng-required="true" type="text" class="form-control"></div>
                    </div>  
                    <div class="form-group row">
                       <label class="form-control-label text-lg-right col-md-3" >Object type</label>
                       <div class="col-md-9"> <select class="form-control" ng-model="mq.authtype"><option value="">Null</option><option value="authinfo">An authentication information object</option><option value="channel">A channel</option><option value="clntconn">A client connection channel</option><option value="comminfo">A communication information object</option><option value="listener">A listener</option><option value="namelist">A namelist</option><option value="process">A process</option><option value="queue">A queue</option><option value="qmgr">A queue manager</option><option value="rqmname">A remote queue manager name</option><option value="service">A service</option><option value="topic">A topic</option></select></div>
                    </div>
                    <div class="form-group row">
                       <label class="form-control-label text-lg-right col-md-3" >Group</label>
                       <div class="col-md-9"><input ng-model="mq.group" type="text" class="form-control"></div>
                    </div>
                    
                  </div>
                  <div role="tabpanel" class="tab-pane" id="authority" >
                    <div class="form-group row">
                       <label class="form-control-label text-lg-right col-md-3"  >ALL</label>
                       <div class="col-md-9"> <select class="form-control" ng-model="mq.authrec.all"><option value="">NULL</option><option value="+all">Add</option><option value="-all">Remove</option></select></div>
                    </div>
                    <div class="form-group row">
                       <label class="form-control-label text-lg-right col-md-3"   ng-class="{'has-error':mq.authrec.alladm && ['rqmname'].indexOf(mq.authtype)!=-1}">ALLADM</label>
                       <div class="col-md-9"><select class="form-control" ng-model="mq.authrec.alladm"><option value="">NULL</option><option value="+alladm">Add</option><option value="-alladm">Remove</option></select></div>
                    </div>
                    <div class="form-group row">
                       <label class="form-control-label text-lg-right col-md-3"   ng-class="{'has-error':mq.authrec.allmqi && ['clntconn','channel','listener','service','comminfo'].indexOf(mq.authtype)!=-1}">ALLMQI</label>
                       <div class="col-md-9"><select class="form-control" ng-model="mq.authrec.allmqi"><option value="">NULL</option><option value="+allmqi">Add</option><option value="-allmqi">Remove</option></select></div>
                    </div>
                    <div class="form-group row">
                       <label class="form-control-label text-lg-right col-md-3"   ng-class="{'has-error':mq.authrec.none && ['topic'].indexOf(mq.authtype)!=-1}">NONE</label>
                       <div class="col-md-9"><select class="form-control" ng-model="mq.authrec.none"><option value="">NULL</option><option value="+none">Add</option><option value="-none">Remove</option></select></div>
                    </div>
                    <div class="form-group row">
                       <label class="form-control-label text-lg-right col-md-3"   ng-class="{'has-error':mq.authrec.altusr && ['queue','process','rqmname','namelist','topic','authinfo','clntconn','channel','listener','service','comminfo'].indexOf(mq.authtype)!=-1}">ALTUSR</label>
                       <div class="col-md-9"><select class="form-control" ng-model="mq.authrec.altusr"><option value="">NULL</option><option value="+altusr">Add</option><option value="-altusr">Remove</option></select></div>
                    </div>
                    <div class="form-group row">
                       <label class="form-control-label text-lg-right col-md-3"   ng-class="{'has-error':mq.authrec.browse && ['process','qmgr','rqmname','namelist','topic','authinfo','clntconn','channel','listener','service','comminfo'].indexOf(mq.authtype)!=-1}">BROWSE</label>
                       <div class="col-md-9"> <select class="form-control" ng-model="mq.authrec.browse"><option value="">NULL</option><option value="+browse">Add</option><option value="-browse">Remove</option></select></div>
                    </div>
                    <div class="form-group row">
                       <label class="form-control-label text-lg-right col-md-3"   ng-class="{'has-error':mq.authrec.chg && ['rqmname'].indexOf(mq.authtype)!=-1}">CHG</label>
                       <div class="col-md-9"><select class="form-control" ng-model="mq.authrec.chg"><option value="">NULL</option><option value="+chg">Add</option><option value="-chg">Remove</option></select></div>
                    </div>
                    <div class="form-group row">
                       <label class="form-control-label text-lg-right col-md-3"   ng-class="{'has-error':mq.authrec.clr && ['process','qmgr','rqmname','namelist','authinfo','clntconn','channel','listener','service','comminfo'].indexOf(mq.authtype)!=-1}">CLR</label>
                       <div class="col-md-9"><select class="form-control" ng-model="mq.authrec.clr"><option value="">NULL</option><option value="+clr">Add</option><option value="-clr">Remove</option></select></div>
                    </div>
                    <div class="form-group row">
                       <label class="form-control-label text-lg-right col-md-3"   ng-class="{'has-error':mq.authrec.connect && ['queue','process','rqmname','namelist','topic','authinfo','clntconn','channel','listener','service','comminfo'].indexOf(mq.authtype)!=-1}">CONNECT</label>
                       <div class="col-md-9"><select class="form-control" ng-model="mq.authrec.connect"><option value="">NULL</option><option value="+connect">Add</option><option value="-connect">Remove</option></select></div>
                    </div>
                    <div class="form-group row">
                       <label class="form-control-label text-lg-right col-md-3"   ng-class="{'has-error':mq.authrec.crt && ['rqmname'].indexOf(mq.authtype)!=-1}">CRT</label>
                       <div class="col-md-9"><select class="form-control" ng-model="mq.authrec.crt"><option value="">NULL</option><option value="+crt">Add</option><option value="-crt">Remove</option></select></div>
                    </div>
                    <div class="form-group row">
                       <label class="form-control-label text-lg-right col-md-3"   ng-class="{'has-error':mq.authrec.ctrl && ['queue','process','qmgr','rqmname','namelist','authinfo','clntconn','comminfo'].indexOf(mq.authtype)!=-1}">CTRL</label>
                       <div class="col-md-9"><select class="form-control" ng-model="mq.authrec.ctrl"><option value="">NULL</option><option value="+ctrl">Add</option><option value="-ctrl">Remove</option></select></div>
                    </div>
                    <div class="form-group row">
                       <label class="form-control-label text-lg-right col-md-3"   ng-class="{'has-error':mq.authrec.ctrlx && ['queue','process','qmgr','rqmname','namelist','topic','authinfo','clntconn','listener','service','comminfo'].indexOf(mq.authtype)!=-1}">CTRLX</label>
                       <div class="col-md-9"><select class="form-control" ng-model="mq.authrec.ctrlx"><option value="">NULL</option><option value="+ctrlx">Add</option><option value="-ctrlx">Remove</option></select></div>
                    </div>
                    <div class="form-group row">
                       <label class="form-control-label text-lg-right col-md-3"   ng-class="{'has-error':mq.authrec.dlt && ['rqmname'].indexOf(mq.authtype)!=-1}">DLT</label>
                       <div class="col-md-9"> <select class="form-control" ng-model="mq.authrec.dlt"><option value="">NULL</option><option value="+dlt">Add</option><option value="-dlt">Remove</option></select></div>
                    </div>
                    <div class="form-group row">
                       <label class="form-control-label text-lg-right col-md-3"   ng-class="{'has-error':mq.authrec.dsp && ['rqmname'].indexOf(mq.authtype)!=-1}">DSP</label>
                       <div class="col-md-9"><select class="form-control" ng-model="mq.authrec.dsp"><option value="">NULL</option><option value="+dsp">Add</option><option value="-dsp">Remove</option></select></div>
                    </div>
                    <div class="form-group row">
                       <label class="form-control-label text-lg-right col-md-3"   ng-class="{'has-error':mq.authrec.get && ['process','qmgr','rqmname','namelist','topic','authinfo','clntconn','channel','listener','service','comminfo'].indexOf(mq.authtype)!=-1}">GET</label>
                       <div class="col-md-9"><select class="form-control" ng-model="mq.authrec.get"><option value="">NULL</option><option value="+get">Add</option><option value="-get">Remove</option></select></div>
                    </div>
                    <div class="form-group row">
                       <label class="form-control-label text-lg-right col-md-3"   ng-class="{'has-error':mq.authrec.pub && ['queue','process','qmgr','rqmname','namelist','authinfo','clntconn','channel','listener','service','comminfo'].indexOf(mq.authtype)!=-1}">PUB</label>
                       <div class="col-md-9"><select class="form-control" ng-model="mq.authrec.pub"><option value="">NULL</option><option value="+pub">Add</option><option value="-pub">Remove</option></select></div>
                    </div>
                    <div class="form-group row">
                       <label class="form-control-label text-lg-right col-md-3"   ng-class="{'has-error':mq.authrec.put && ['process','qmgrnamelist','topic','authinfo','clntconn','channel','listener','service','comminfo'].indexOf(mq.authtype)!=-1}">PUT</label>
                       <div class="col-md-9"><select class="form-control" ng-model="mq.authrec.put"><option value="">NULL</option><option value="+put">Add</option><option value="-put">Remove</option></select></div>
                    </div>
                    <div class="form-group row">
                       <label class="form-control-label text-lg-right col-md-3"   ng-class="{'has-error':mq.authrec.inq && ['rqmname','topic','clntconn','channel','listener','service','comminfo'].indexOf(mq.authtype)!=-1}">INQ</label>
                       <div class="col-md-9"><select class="form-control" ng-model="mq.authrec.inq"><option value="">NULL</option><option value="+inq">Add</option><option value="-inq">Remove</option></select></div>
                    </div>
                    <div class="form-group row">
                       <label class="form-control-label text-lg-right col-md-3"   ng-class="{'has-error':mq.authrec.passall && ['process','qmgr','rqmname','namelist','topic','authinfo','clntconn','channel','listener','service','comminfo'].indexOf(mq.authtype)!=-1}">PASSALL</label>
                       <div class="col-md-9"><select class="form-control" ng-model="mq.authrec.passall"><option value="">NULL</option><option value="+passall">Add</option><option value="-passall">Remove</option></select></div>
                    </div>
                    <div class="form-group row">
                       <label class="form-control-label text-lg-right col-md-3"   ng-class="{'has-error':mq.authrec.passid && ['process','qmgr','rqmname','namelist','topic','authinfo','clntconn','channel','listener','service','comminfo'].indexOf(mq.authtype)!=-1}">PASSID</label>
                       <div class="col-md-9"><select class="form-control" ng-model="mq.authrec.passid"><option value="">NULL</option><option value="+passid">Add</option><option value="-passid">Remove</option></select></div>
                    </div>
                    <div class="form-group row">
                       <label class="form-control-label text-lg-right col-md-3"   ng-class="{'has-error':mq.authrec.resume && ['queue','process','qmgr','rqmname','namelist','authinfo','clntconn','channel','listener','service','comminfo'].indexOf(mq.authtype)!=-1}">RESUME</label>
                       <div class="col-md-9"><select class="form-control" ng-model="mq.authrec.resume"><option value="">NULL</option><option value="+resume">Add</option><option value="-resume">Remove</option></select></div>
                    </div>
                    <div class="form-group row">
                       <label class="form-control-label text-lg-right col-md-3"   ng-class="{'has-error':mq.authrec.set && ['rqmname','namelist','topic','authinfo','clntconn','channel','listener','service','comminfo'].indexOf(mq.authtype)!=-1}">SET</label>
                       <div class="col-md-9"><select class="form-control" ng-model="mq.authrec.set"><option value="">NULL</option><option value="+set">Add</option><option value="-set">Remove</option></select></div>
                    </div>
                    <div class="form-group row">
                       <label class="form-control-label text-lg-right col-md-3"   ng-class="{'has-error':mq.authrec.setall && ['process','rqmname','namelist','topic','authinfo','clntconn','channel','listener','service','comminfo'].indexOf(mq.authtype)!=-1}">SETALL</label>
                       <div class="col-md-9"><select class="form-control" ng-model="mq.authrec.setall"><option value="">NULL</option><option value="+setall">Add</option><option value="-setall">Remove</option></select></div>
                    </div>
                    <div class="form-group row">
                       <label class="form-control-label text-lg-right col-md-3"   ng-class="{'has-error':mq.authrec.setid && ['process','rqmname','namelist','authinfo','clntconn','channel','listener','service','comminfo'].indexOf(mq.authtype)!=-1}">SETID</label>
                       <div class="col-md-9"><select class="form-control" ng-model="mq.authrec.setid"><option value="">NULL</option><option value="+setid">Add</option><option value="-setid">Remove</option></select></div>
                    </div>
                    <div class="form-group row">
                       <label class="form-control-label text-lg-right col-md-3"   ng-class="{'has-error':mq.authrec.sub && ['queue','process','qmgr','rqmname','namelist','authinfo','clntconn','channel','listener','service','comminfo'].indexOf(mq.authtype)!=-1}">SUB</label>
                       <div class="col-md-9"><select class="form-control" ng-model="mq.authrec.sub"><option value="">NULL</option><option value="+sub">Add</option><option value="-sub">Remove</option></select></div>
                    </div>
                    <div class="form-group row">
                       <label class="form-control-label text-lg-right col-md-3"   ng-class="{'has-error':mq.authrec.system && ['queue','process','rqmname','namelist','topic','authinfo','clntconn','channel','listener'].indexOf(mq.authtype)!=-1}">SYSTEM</label>
                       <div class="col-md-9"><select class="form-control" ng-model="mq.authrec.system"><option value="">NULL</option><option value="+system">Add</option><option value="-system">Remove</option></select></div>
                    </div>
                  </div>
                </div>
              </div>
              
            </div>
            <div class="modal-footer" style="display:flow-root list-item;">
          <div class="float-start"><a href="https://www.google.com/search?q=ibm+mq+setmqauth" target="_blank" class="waves-effect waves-light btn btn-light btn-sm">Information about AUTHREC</a></div>
          <div class="float-end">
              <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-x" xlink:href="/assets/images/icon/midleoicons.svg#i-x"/></svg>&nbsp;Close</button>
              <button type="button"  id="btn-mqsc-obj" class="waves-effect waves-light btn btn-info btn-sm" ng-click="auth('<?php echo $thisarray['p2'];?>','<?php echo $_SESSION['user'];?>','<?php echo $page;?>')" ng-href="{{ url }}"><i class="mdi mdi-download"></i>&nbsp;Create auth</button>
          <?php if($zobj['lockedby']==$_SESSION['user']){?>
                <?php if($_SESSION['user_level']>="3"){?>
              <button type="button" id="btn-create-obj" class="waves-effect waves-light btn btn-info btn-sm" ng-click="form.$valid && create('<?php echo $page=="env"?$thisarray['p1']:$thisarray['p3'];?>','<?php echo $thisarray['p2'];?>','<?php echo $_SESSION['user'];?>','<?php echo $page;?>')"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-check" xlink:href="/assets/images/icon/midleoicons.svg#i-check"/></svg>&nbsp;Create</button>
              <button type="button"  id="btn-update-obj" class="waves-effect waves-light btn btn-info btn-sm" ng-click="update('<?php echo $page=="env"?$thisarray['p1']:$thisarray['p3'];?>','<?php echo $thisarray['p2'];?>','<?php echo $_SESSION['user'];?>','<?php echo $page;?>')"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-save" xlink:href="/assets/images/icon/midleoicons.svg#i-save"/></svg>&nbsp;Save Changes</button>
              <?php } ?>
              <?php } ?>
              </div>
              </div>
          </form>
        </div>
      </div>
    </div>
    <?php if($zobj['lockedby']==$_SESSION['user']){?>
    <?php if($_SESSION['user_level']>="3"){?>
    <?php include $maindir."/public/modules/deplform.php";?>
    <?php include $maindir."/public/modules/respform.php";?>
   
  <?php } ?>
    <?php } ?>
    </div>
  </div>
  </div></div>
<?php } ?>