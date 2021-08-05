<?php if(empty($thisarray['p2'])){ include "applist.php"; } else { ?>
  <div class="row">
  <div class="col-md-3 position-relative">
      <input type="text" ng-model="search" class="form-control  topsearch" placeholder="Find a channel">
      <span class="searchicon"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-search" xlink:href="/assets/images/icon/midleoicons.svg#i-search"/></svg><span>
 </div>
  <div class="col-md-9 text-end">
<span data-bs-toggle="tooltip" data-bs-placement="top" title="Deploy package on server"><a ng-show="selectedid.length" data-bs-toggle="modal"  class="waves-effect waves-light btn btn-light" href="#modal-depl-form" ng-click="showDeployForm()"><svg class="midico midico-outline" ><use href="/assets/images/icon/midleoicons.svg#i-deploy" xlink:href="/assets/images/icon/midleoicons.svg#i-deploy"/></svg>&nbsp;Deploy</a></span>
<?php if ($_SESSION['user_level'] >= 3 && !in_array($thisarray['p1'], array("packages", "appservers", "servers", "import", "deploy", "flows", "fte"))) {?><span><button data-bs-toggle="tooltip" data-bs-placement="top" title="Export the objects in excel" type="button" class="waves-effect waves-light btn btn-light" ng-click="exportData('<?php echo $thisarray['p1']; ?>')">Export&nbsp;<svg class="midico midico-outline" ><use href="/assets/images/icon/midleoicons.svg#i-up" xlink:href="/assets/images/icon/midleoicons.svg#i-up"/></svg></button> </span><?php }?>
<span data-bs-toggle="tooltip" data-bs-placement="top" title="Define new Channel definitions"><button type="button" class="waves-effect waves-light btn btn-info" data-bs-toggle="modal" href="#modal-obj-form" ng-click="showCreateForm()"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-add" xlink:href="/assets/images/icon/midleoicons.svg#i-add"/></svg>&nbsp;New</button></span>
  </div>
</div>    <br>
  <div class="card ">
<div class="card-body p-0">

<div class="row"><div class="col-md-12">
  <table class="table table-vmiddle table-hover stylish-table mb-0">
    <thead>
      <tr>
        <th class="text-center" style="width:50px;"></th>
        <th class="text-center">Job</th>
        <th class="text-center">QM</th>
        <th class="text-center">Channel</th>
        <th class="text-center">Type</th>
        <th class="text-center">Cluster</th>
        <th class="text-center">Maxmsgl</th>
        <th class="text-center">SSL</th>
        <th class="text-center" style="width:120px;">Action</th>
      </tr>
    </thead>
    <tbody ng-init="getAll('<?php echo $page=="env"?$thisarray['p1']:$thisarray['p3'];?>','<?php echo $thisarray['p2'];?>','<?php echo $page;?>')">
      <tr ng-hide="contentLoaded"><td colspan="8" style="text-align:center;font-size:1.1em;"><i class="mdi mdi-loading iconspin"></i>&nbsp;Loading...</td></tr>
      <tr id="contloaded" class="hide" dir-paginate="d in names | filter:search | orderBy:sortKey:reverse | itemsPerPage:10" pagination-id="prodx" ng-hide="d.qm==''">
        <td class="text-center">
        <div class="toggle-switch" >
           <input id="deplqmid{{ d.qmid }}" type="checkbox" class="checkall"  value="{{ d.qmid }}" ng-checked="exists(d.qmid, selectedid)" ng-click="toggle(d.qmid, selectedid)" style="display:none;">
          <label  for="deplqmid{{ d.qmid }}" class="ts-helper"></label>
       </div>
       </td>
       <td class="text-center" style="padding: .5rem;"><a href="/automation/{{d.jobid}}" ng-show="d.jobrun==1"><i class="mdi mdi-play-circle-outline mdi-24px"></i></a></td>
       <td class="text-center">{{ d.qm }}</td>
        <td class="text-center">{{ d.name }}</td>
        <td class="text-center">{{ d.chltype }}</td>
        <td class="text-center">{{ d.cluster }}</td>
        <td class="text-center">{{ d.maxmsgl }}</td>
        <td class="text-center">{{ d.ssl }}</td>
        <td class="text-center">
        <div class="btn-group" role="group">
        <button type="button" ng-click="readOne('<?php echo $page=="env"?$thisarray['p1']:$thisarray['p3'];?>',d.qid,d.qmid,'<?php echo $thisarray['p2'];?>','<?php echo $page;?>')" style="" class="btn btn-light btn-sm bg waves-effect"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-edit" xlink:href="/assets/images/icon/midleoicons.svg#i-edit"/></svg></button>
          <?php if($_SESSION['user_level']>="3"){?>
            <button type="button" ng-click="duplicate('<?php echo $thisarray['p1'];?>',d.qid,d.qmid,'<?php echo $_SESSION['user'];?>','<?php echo $thisarray['p2'];?>')" class="btn btn-light btn-sm bg waves-effect" title="Duplicate"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-documents" xlink:href="/assets/images/icon/midleoicons.svg#i-documents"/></svg></button>
            <button type="button" ng-click="delete('<?php echo $page=="env"?$thisarray['p1']:$thisarray['p3'];?>',d.qid,d.qmid,'<?php echo $thisarray['p2'];?>','<?php echo $page;?>')" class="btn btn-light btn-sm bg waves-effect"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-x" xlink:href="/assets/images/icon/midleoicons.svg#i-x"/></svg></button>
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
      <div class="modal-header"><h4>Define Channel definition</h4></div>
        <form name="form" ng-app>
          <div class="modal-body">
            
            <div role="tabpanel">
              <input ng-model="mq.type" ng-init="mq.type='channel'" style="display:none;">
              <ul class="nav nav-tabs customtab" role="tablist">
                <li class="nav-item"><a class="nav-link active" href="#base" aria-controls="base" role="tab" data-bs-toggle="tab">Base</a></li>
                <li class="nav-item"><a class="nav-link" href="#net" aria-controls="net" role="tab" data-bs-toggle="tab">Net</a></li>
                <li class="nav-item" ng-show="mq.chltype=='clussdr' || mq.chltype=='clusrcvr'"><a class="nav-link" href="#cluster" aria-controls="cluster" role="tab" data-bs-toggle="tab">Cluster</a></li>
                <li class="nav-item"><a class="nav-link" href="#misc" aria-controls="trigger" role="tab" data-bs-toggle="tab">Misc</a></li>
              </ul>
              <br>
              <div class="tab-content container form-material" style="width:100%;min-height:300px;max-height:500px;overflow-x:hidden;overflow-y:scroll;">
                <div role="tabpanel" class="tab-pane active" id="base" >
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  data-trigger="hover" data-bs-toggle="popover" data-bs-placement="top" >Active</label>
                    <div class="col-md-9"><select class="form-control" ng-init="mq.active='yes'" ng-model="mq.active"><option value="">Please select</option><option value="yes" ng-selected="mq.active==yes">Yes</option><option value="no">No</option></select></div>
                  </div>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  data-trigger="hover" data-bs-toggle="popover" data-bs-placement="top" >QMGR</label>
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
                    <label class="form-control-label text-lg-right col-md-3"  data-trigger="hover" data-bs-toggle="popover" data-bs-placement="top" >Name</label>
                    <div class="col-md-9"><input ng-maxlength="20" ng-model="mq.name" ng-required="true" type="text" class="form-control"></div>
                  </div>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  data-trigger="hover" data-bs-toggle="popover" data-bs-placement="top" >Type</label>
                    <div class="col-md-9"><select class="form-control" ng-model="mq.chltype" ng-required="true">
                      <option value="" ng-click="mq.sslcauth='';mq.convert=''">Please select</option>
                      <option value="sdr" ng-click="mq.convert='no';mq.sslcauth=''">Sender</option>
                      <option value="rcvr" ng-click="mq.sslcauth='';mq.convert=''">Receiver</option>
                      <option value="svr" ng-click="mq.sslcauth='';mq.convert='no'">Server channel</option>
                      <option value="rqstr" ng-click="mq.sslcauth='';mq.convert=''">Requester channel</option>
                      <option value="clntconn" ng-click="mq.sslcauth='';mq.convert=''">Client connection</option>
                      <option value="svrconn" ng-click="mq.sslcauth='';mq.convert=''">Server connection</option>
                      <option value="clussdr" ng-click="mq.convert='no';mq.sslcauth=''">Cluster sender</option>
                      <option value="clusrcvr" ng-click="mq.sslcauth='';mq.convert='no'">Cluster receiver</option></select></div>
                  </div>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  data-trigger="hover" data-bs-toggle="popover" data-bs-placement="top" >Descr</label>
                    <div class="col-md-9"><input ng-maxlength="64" ng-model="mq.descr" ng-required="true" type="text" class="form-control"></div>
                  </div>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  >XMITQ</label>
                    <div class="col-md-9"> <input ng-model="mq.xmitq" type="text" class="form-control"></div>
                  </div>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" data-trigger="hover" data-bs-toggle="popover" data-bs-placement="top" data-content="The name of the cluster to which the channel belongs" title="" data-original-title="Cluster" >Cluster</label>
                    <div class="col-md-9"><input ng-maxlength="48" ng-model="mq.cluster" type="text" class="form-control"></div>
                  </div>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  >CLUSNL</label>
                    <div class="col-md-9"> <input ng-model="mq.clusnl" type="text" class="form-control"></div>
                  </div>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  >CONNAME</label>
                    <div class="col-md-9"> <input ng-maxlength="264" ng-model="mq.conname" type="text" class="form-control"></div>
                  </div>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  >MAXMSGL</label>
                    <div class="col-md-9"><input ng-model="mq.maxmsgl" type="text" class="form-control"></div>
                  </div>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  >SSLCAUTH</label>
                    <div class="col-md-9"><select class="form-control" ng-model="mq.sslcauth"><option value="" ng-click="mq.sslciph='';mq.sslpeer=''">Disabled</option><option value="optional">Optional</option><option value="required">Required</option></select></div>
                  </div>
                  <div class="form-group row" ng-show="mq.sslcauth=='optional' || mq.sslcauth=='required'">
                    <label class="form-control-label text-lg-right col-md-3"  >SSLCIPH</label>
                    <div class="col-md-9"><select class="form-control" ng-model="mq.sslciph">
                    <option value="">Please select</option>
                    <?php foreach($ibmmqchlciph as $keyin=>$valin){ ?>
                      <option value="<?php echo $valin;?>"><?php echo $valin;?></option>
                    <?php } ?>
                    </select>
                    </div>
                  </div>
                  <div class="form-group row" ng-show="mq.sslcauth=='optional' || mq.sslcauth=='required'">
                    <label class="form-control-label text-lg-right col-md-3" >SSLPEER</label>
                    <div class="col-md-9"> <input ng-model="mq.sslpeer" type="text" class="form-control"></div>
                  </div>
                  
                  <div class="form-group"><br></div>
                </div>
                <div role="tabpanel" class="tab-pane" id="net" >
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  >LOCLADDR</label>
                    <div class="col-md-9"> <input ng-model="mq.locladdr" type="text" class="form-control"></div>
                  </div>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  >NETPRTY</label>
                    <div class="col-md-9"> <input ng-model="mq.netprty" type="text" class="form-control"></div>
                  </div>
                  <div class="form-group row" ng-show="mq.chltype=='clussdr' || mq.chltype=='clusrcvr' || mq.chltype=='svr' || mq.chltype=='sdr'">
                    <label class="form-control-label text-lg-right col-md-3"  >CONVERT</label>
                    <div class="col-md-9"><select class="form-control" ng-model="mq.convert"><option value="">Please select</option><option value="no">Disabled</option><option value="yes">Enabled</option></select></div>
                  </div>
                  
                  <div class="form-group"><br></div>
                </div>
                <div role="tabpanel" class="tab-pane" id="cluster" ng-show="mq.chltype=='clussdr' || mq.chltype=='clusrcvr'">
                  
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  >CLWLPRTY</label>
                    <div class="col-md-9"><input ng-model="mq.clwlprty" type="text" class="form-control"></div>
                  </div>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  >CLWLRANK</label>
                    <div class="col-md-9"> <input ng-model="mq.clwlrank" type="text" class="form-control"></div>
                  </div>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  >CLWLWGHT</label>
                    <div class="col-md-9"><input ng-model="mq.clwlwght" type="text" class="form-control"></div>
                  </div>
                  
                  <div class="form-group"><br></div>
                </div>
                <div role="tabpanel" class="tab-pane" id="misc">
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  >MCAUSER</label>
                    <div class="col-md-9"><input ng-model="mq.mcauser" type="text" class="form-control"></div>
                  </div>
                  <div class="form-group row" ng-show="mq.chltype=='svrconn'">
                    <label class="form-control-label text-lg-right col-md-3"  >MAXINST</label>
                    <div class="col-md-9"><input ng-model="mq.maxinst" type="text" class="form-control"></div>
                  </div>
                  <div class="form-group row" ng-show="mq.chltype=='svrconn'">
                    <label class="form-control-label text-lg-right col-md-3"  >MAXINSTC</label>
                    <div class="col-md-9"><input ng-model="mq.maxinstc" type="text" class="form-control"></div>
                  </div>
                  
                  <div class="form-group"><br></div>
                </div>
                
              </div>
            </div>
            
          </div>
          <div class="modal-footer" style="display:flow-root list-item;">
          <div class="float-start"><a href="https://www.google.com/search?q=ibm+mq+define+channel" target="_blank" class="waves-effect waves-light btn btn-light btn-sm">Information about Channels</a></div>
          <div class="float-end">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-x" xlink:href="/assets/images/icon/midleoicons.svg#i-x"/></svg>&nbsp;Close</button>
             <button type="button" id="btn-mqsc-obj" class="waves-effect waves-light btn btn-info btn-sm" ng-click="mqsc('<?php echo $page=="env"?$thisarray['p1']:$thisarray['p3'];?>',mq.proj,'<?php echo $_SESSION['user'];?>','<?php echo $page;?>')" ng-href="{{ url }}"><i class="mdi mdi-download"></i>&nbsp;Create mqsc</button>
             <?php if($zobj['lockedby']==$_SESSION['user']){?>
            <?php if($_SESSION['user_level']>="3"){?>
            <button type="button" id="btn-create-obj" class="waves-effect waves-light btn btn-info btn-sm" ng-click="form.$valid && create('<?php echo $page=="env"?$thisarray['p1']:$thisarray['p3'];?>','<?php echo $thisarray['p2'];?>','<?php echo $_SESSION['user'];?>','<?php echo $page;?>')"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-add" xlink:href="/assets/images/icon/midleoicons.svg#i-add"/></svg>&nbsp;Create</button>
            <button type="button" id="btn-update-obj" class="waves-effect waves-light btn btn-info btn-sm" ng-click="update('<?php echo $page=="env"?$thisarray['p1']:$thisarray['p3'];?>','<?php echo $thisarray['p2'];?>','<?php echo $_SESSION['user'];?>','<?php echo $page;?>')"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-save" xlink:href="/assets/images/icon/midleoicons.svg#i-save"/></svg>&nbsp;Save Changes</button>
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
</div>
</div>
<?php } ?>