<?php if(empty($thisarray['p2'])){ include "applist.php"; } else { 
  array_push($brarr,array(
    "title"=>"Export in excel",
    "link"=>"#",
    "nglink"=>"exportData('".$thisarray['p1']."')",
    "icon"=>"mdi-file-excel",
    "active"=>false,
  ));
  array_push($brarr,array(
    "title"=>"Define new",
    "link"=>"#modal-obj-form",
    "nglink"=>"showCreateForm()",
    "modal"=>true,
    "icon"=>"mdi-plus",
    "active"=>false,
  ));
  array_push($brarr,array(
    "title"=>"Deploy package on server",
    "link"=>"#modal-depl-form",
    "nglink"=>"showDeployForm()",
    "modal"=>true,
    "icon"=>"mdi-upload",
    "ngshow"=>"selectedid.length",
    "active"=>false,
  ));
  ?>
  <div class="card ">
<div class="card-body p-0">
<div class="row"><div class="col-md-12">
  <table class="table table-vmiddle table-hover stylish-table mb-0">
    <thead>
      <tr>
        <th class="text-center" style="width:50px;"></th>
        <th class="text-center">Job</th>
         <th class="text-center">QM</th>
        <th class="text-center">Queue</th>
        <th class="text-center">Type</th>
        <th class="text-center">Cluster</th>
        <th class="text-center">Maxmsgl</th>
        <th class="text-center">Maxdepth</th>
        <th class="text-center" style="width:130px;">Action</th>
      </tr>
    </thead>
    <tbody ng-init="getAll('<?php echo $thisarray['p1'];?>','<?php echo $thisarray['p2'];?>','<?php echo $page;?>')">
      <tr ng-hide="contentLoaded"><td colspan="9" style="text-align:center;font-size:1.1em;"><i class="mdi mdi-loading iconspin"></i>&nbsp;Loading...</td></tr>
      <tr id="contloaded" class="hide" dir-paginate="d in names | filter:search | orderBy:'qm':reverse | itemsPerPage:10" pagination-id="prodx" ng-hide="d.qm==''">
        <td class="text-center">
        <div class="toggle-switch" >
           <input id="deplqmid{{ d.qmid }}" type="checkbox" class="checkall"  value="{{ d.qmid }}" ng-checked="exists(d.qmid, selectedid)" ng-click="toggle(d.qmid, selectedid)" style="display:none;">
          <label for="deplqmid{{ d.qmid }}" class="ts-helper"></label>
       </div>

         </td>
         <td class="text-center" style="padding: .5rem;"><a href="/automation/{{d.jobid}}" ng-show="d.jobrun==1"><i class="mdi mdi-play-circle-outline mdi-24px"></i></a></td>
        <td class="text-center">{{ d.qm }}</td>
        <td class="text-center">{{ d.name}}</td>
        <td class="text-center">{{ d.type }}</td>
        <td class="text-center">{{ d.cluster }}</td>
        <td class="text-center">{{ d.maxmsgl }}</td>
        <td class="text-center">{{ d.maxdepth }}</td>
        <td class="text-center">
        <div class="text-start d-grid gap-2 d-md-block">
          <button type="button" ng-click="readOne('<?php echo $thisarray['p1'];?>',d.qid,d.qmid,d.proj,'<?php echo $page;?>')" style="" class="btn btn-light btn-sm bg waves-effect" title="Read"><i class="mdi mdi-pencil mdi-18px"></i></button>
          <?php if($_SESSION['user_level']>="3"){?>
            <button type="button" ng-click="duplicate('<?php echo $thisarray['p1'];?>',d.qid,d.qmid,'<?php echo $_SESSION['user'];?>','<?php echo $thisarray['p2'];?>')" class="btn btn-light btn-sm bg waves-effect" title="Duplicate"><i class="mdi mdi-content-duplicate mdi-18px"></i></button>
            <button type="button" ng-click="delete('<?php echo $thisarray['p1'];?>',d.qid,d.qmid,d.proj,'<?php echo $_SESSION['user'];?>','<?php echo $page;?>')" class="btn btn-light btn-sm bg waves-effect" title="Delete"><i class="mdi mdi-close"></i></button>
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
      <div class="modal-header"><h4>Define Queue definition</h4></div>
        <form name="form" ng-app>
          <div class="modal-body">
            <div role="tabpanel">
              <ul class="nav nav-tabs customtab" role="tablist">
                <li class="nav-item"><a class="nav-link active" href="#base" aria-controls="base" role="tab" data-bs-toggle="tab">Base</a></li>
                <li class="nav-item"><a class="nav-link" href="#default" aria-controls="default" role="tab" data-bs-toggle="tab">Default</a></li>
                <li class="nav-item"><a class="nav-link" href="#various" aria-controls="various" role="tab" data-bs-toggle="tab">Various</a></li>
                <li class="nav-item" ng-show="mq.type=='qlocal' || mq.type=='qremote' || mq.type=='qalias'"><a class="nav-link" href="#cluster" aria-controls="cluster" role="tab" data-bs-toggle="tab">Cluster</a></li>
                <li class="nav-item" ng-show="mq.type=='qlocal' || mq.type=='qmodel'"><a class="nav-link" href="#trigger" aria-controls="trigger" role="tab" data-bs-toggle="tab">Trigger</a></li>
                <li class="nav-item" ng-show="mq.type=='qlocal' || mq.type=='qmodel'"><a class="nav-link" href="#event" aria-controls="event" role="tab" data-bs-toggle="tab">Event</a></li>
              </ul>
              <br>
              <div class="tab-content container form-material" style="width:100%;min-height:300px;max-height:500px;overflow-x:hidden;overflow-y:scroll;">
                <div role="tabpanel" class="tab-pane active" id="base" >
                  
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  >Active</label>
                    <div class="col-md-9"><select class="form-control" ng-init="mq.active='yes'" ng-model="mq.active"><option value="">Please select</option><option value="yes">Yes</option><option value="no">No</option></select></div>
                  </div>
                   <?php if(isset($modulelist["budget"]) && !empty($modulelist["budget"])){ ?>
                  <input ng-model="mq.proj" style="display:none;" value="<?php echo $thisarray['p2'];?>">
                      <?php } ?>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  >QMGR</label>
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
                    <label class="form-control-label text-lg-right col-md-3"  >Name</label>
                    <div class="col-md-9"><input ng-maxlength="48" ng-model="mq.name" ng-required="true" type="text" class="form-control"></div>
                  </div>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  >Type</label>
                    <div class="col-md-9"><select class="form-control" ng-model="mq.type" ng-minlength="1" ng-required="true"><option value="">Please select</option><option value="qlocal" ng-click="mq.distl='no';mq.qdploev='disabled';mq.qdphiev='disabled';mq.qdpmaxev='disabled';mq.qsvciev='none';mq.propctl='compat';mq.clwluseq='qmgr';mq.monq='qmgr';mq.distl='no';mq.trigger='notrigger';mq.get='enabled';mq.targtype='';mq.statq='qmgr';mq.defbind='notfixed';mq.usage='normal';mq.put='enabled';mq.defpsist='yes';">Local</option><option value="qremote" ng-click="mq.put='enabled';mq.defpsist='yes';mq.targtype='';mq.qdploev='';mq.qdphiev='';mq.qdpmaxev='';mq.qsvciev='';mq.propctl='';mq.clwluseq='';mq.monq='';mq.statq='';mq.distl='';mq.trigger='';mq.get='';mq.defbind='notfixed';mq.usage=''">Remote</option><option value="qalias" ng-click="mq.statq='';mq.distl='';mq.targtype='queue';mq.propctl='compat';mq.get='enabled';mq.defbind='notfixed';mq.usage='';mq.put='enabled';mq.defpsist='yes';mq.clwluseq='';">Alias</option><option value="qmodel" ng-click="mq.put='enabled';mq.defpsist='yes';mq.usage='normal';mq.defbind='';mq.propctl='compat';mq.qdploev='disabled';mq.qdphiev='disabled';mq.qdpmaxev='disabled';mq.qsvciev='none';mq.monq='qmgr';mq.distl='no';mq.trigger='notrigger';mq.get='enabled';mq.targtype='';mq.statq='qmgr';mq.clwluseq='';">Model</option></select></div>
                  </div>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  >Descr</label>
                    <div class="col-md-9"><input ng-maxlength="64" ng-model="mq.descr" ng-required="true" type="text" class="form-control"></div>
                  </div>
                  <div class="form-group row" ng-show="mq.type=='qlocal' || mq.type=='qmodel'">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" >Usage</label>
                    <div class="col-md-9"><select class="form-control" ng-model="mq.usage"><option value="">Please select</option><option value="normal">Normal</option><option value="xmitq">Transmission</option></select></div>
                  </div>
                  <div class="form-group row" ng-show="mq.type=='qlocal' || mq.type=='qremote' || mq.type=='qalias'">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" >Cluster</label>
                    <div class="col-md-9"><input ng-model="mq.cluster" type="text" class="form-control"></div>
                  </div>
                  <div class="form-group row" ng-show="mq.type=='qlocal' || mq.type=='qremote' || mq.type=='qalias'">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" >CLUSNL</label>
                    <div class="col-md-9"><input ng-model="mq.clusnl" type="text" class="form-control"></div>
                  </div>
                  <div class="form-group row" ng-show="mq.type=='qlocal' || mq.type=='qmodel'">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" >MAXMSGL</label>
                    <div class="col-md-9"><input ng-model="mq.maxmsgl" type="text" class="form-control"></div>
                  </div>
                  <div class="form-group row" ng-show="mq.type=='qlocal' || mq.type=='qmodel'">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" >MAXDEPTH</label>
                    <div class="col-md-9"><input ng-model="mq.maxdepth" type="text" class="form-control"></div>
                  </div>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" >PUTENABLED</label>
                    <div class="col-md-9"><select class="form-control" ng-model="mq.put"><option value="">Please select</option><option value="enabled">Allowed</option><option value="disabled">Inhibited</option></select></div>
                  </div>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" >GETENABLED</label>
                    <div class="col-md-9"><select class="form-control" ng-model="mq.get"><option value="">Please select</option><option value="enabled">Allowed</option><option value="disabled">Inhibited</option></select></div>
                  </div>
                  <div class="form-group row" ng-show="mq.type=='qlocal' || mq.type=='qmodel'">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" >BOQNAME</label>
                    <div class="col-md-9"><input ng-model="mq.boqname" type="text" class="form-control"></div>
                  </div>
                  <div class="form-group row" ng-show="mq.type=='qlocal' || mq.type=='qmodel'">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" >BOTHRESH</label>
                    <div class="col-md-9"><input ng-model="mq.bothresh" type="text" class="form-control"></div>
                  </div>
                  <div class="form-group row" ng-show="mq.type=='qalias'">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" >TARGET</label>
                    <div class="col-md-9"><input ng-model="mq.target" type="text" class="form-control"></div>
                  </div>
                  <div class="form-group row" ng-show="mq.type=='qremote'">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" >RNAME</label>
                    <div class="col-md-9"><input ng-model="mq.rname" type="text" class="form-control"></div>
                  </div>
                  <div class="form-group row" ng-show="mq.type=='qremote'">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" >RQMNAME</label>
                    <div class="col-md-9"><input ng-model="mq.rqmname" type="text" class="form-control"></div>
                  </div>
                  <div class="form-group row" ng-show="mq.type=='qremote'">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" >XMITQ</label>
                    <div class="col-md-9"><input ng-model="mq.xmitq" type="text" class="form-control"></div>
                  </div>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" >DEFBIND</label>
                    <div class="col-md-9"><select class="form-control" ng-model="mq.defbind"><option value="">Please select</option><option value="notfixed">Notfixed</option><option value="open">Open</option><option value="group">Group</option></select></div>
                  </div>
                  <div class="form-group"><br></div>
                </div>
                <div role="tabpanel" class="tab-pane" id="default">
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" >DEFPRTY</label>
                    <div class="col-md-9"><input ng-model="mq.defprty" type="text" class="form-control"></div>
                  </div>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" >DEFPSIST</label>
                    <div class="col-md-9"><select class="form-control" ng-model="mq.defpsist"><option value="">Please select</option><option value="yes">Yes</option><option value="no">No</option></select></div>
                  </div>
                  
                  
                  <div class="form-group"><br></div>  
                </div>
                <div role="tabpanel" class="tab-pane" id="various">
                  
                  <div class="form-group row" ng-show="mq.type=='qlocal' || mq.type=='qmodel' || mq.type=='qalias'">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" >PROPCTL</label>
                    <div class="col-md-9"><select class="form-control" ng-model="mq.propctl"><option value="">Please select</option><option value="compat">COMPAT</option><option value="all">ALL</option><option value="force">FORCE</option><option value="none">NONE</option><option value="v6compat">V6COMPAT</option></select></div>
                  </div>
                  <div class="form-group row" ng-show="mq.type=='qalias'">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" >TARGTYPE</label>
                    <div class="col-md-9"><select class="form-control" ng-model="mq.targtype"><option value="">Please select</option><option value="queue">QUEUE</option><option value="topic">TOPIC</option></select></div>
                  </div>
                  <div class="form-group row" ng-show="mq.type=='qlocal' || mq.type=='qmodel'">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" >INITQ</label>
                    <div class="col-md-9"><input ng-model="mq.initq" type="text" class="form-control"></div>
                  </div>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" >STATQ</label>
                    <div class="col-md-9"><select class="form-control" ng-model="mq.statq"><option value="">Please select</option><option value="qmgr">QMGR</option><option value="on">ON</option><option value="off">OFF</option></select></div>
                  </div>
                  <div class="form-group row" ng-show="mq.type=='qlocal' || mq.type=='qmodel'">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" >MONQ</label>
                    <div class="col-md-9"><select class="form-control" ng-model="mq.monq"><option value="">Please select</option><option value="qmgr">QMGR</option><option value="off">OFF</option><option value="low">LOW</option><option value="medium">MEDIUM</option><option value="high">HIGH</option></select></div>
                  </div>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" >DISTL</label>
                    <div class="col-md-9"><select class="form-control" ng-model="mq.distl"><option value="">Please select</option><option value="qmgr">NO</option><option value="yes">YES</option><option value="high">HIGH</option></select></div>
                  </div>
                  
                </div>
                <div role="tabpanel" class="tab-pane" id="cluster" ng-show="mq.type=='qlocal' || mq.type=='qremote' || mq.type=='qalias'">
                  
                  <div class="form-group row" ng-show="mq.type=='qlocal' || mq.type=='qremote' || mq.type=='qalias'">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" >CLWLPRTY</label>
                    <div class="col-md-9"><input ng-model="mq.clwlprty" type="text" class="form-control"></div>
                  </div>
                  <div class="form-group row" ng-show="mq.type=='qlocal' || mq.type=='qremote' || mq.type=='qalias'">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" >CLWLRANK</label>
                    <div class="col-md-9"><input ng-model="mq.clwlrank" type="text" class="form-control"></div>
                  </div>
                  <div class="form-group row" ng-show="mq.type=='qlocal'">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" >CLWLUSEQ</label>
                    <div class="col-md-9"><select class="form-control" ng-model="mq.clwluseq"><option value="">Please select</option><option value="qmgr">QMGR</option><option value="any">ANY</option><option value="local">LOCAL</option></select></div>
                  </div>
                  
                  <div class="form-group"><br></div>
                  
                </div>
                <div role="tabpanel" class="tab-pane" id="trigger" ng-show="mq.type=='qlocal' || mq.type=='qmodel'">
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" >TRIGGER</label>
                    <div class="col-md-9"><select class="form-control" ng-model="mq.trigger"><option value="">Please select</option><option value="notrigger">NOTRIGGER</option><option value="trigger">TRIGGER</option></select></div>
                  </div>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" >PROCESS</label>
                    <div class="col-md-9"><input ng-model="mq.process" type="text" class="form-control"></div>
                  </div>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" >TRIGDATA</label>
                    <div class="col-md-9"><input ng-model="mq.trigdata" type="text" class="form-control"></div>
                  </div>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" >TRIGTYPE</label>
                    <div class="col-md-9"><select class="form-control" ng-model="mq.trigtype"><option value="">Please select</option><option value="none">NONE</option><option value="every">EVERY</option><option value="depth">DEPTH</option><option value="first">FIRST</option></select></div>
                  </div>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" >TRIGDPTH</label>
                    <div class="col-md-9"><input ng-model="mq.trigdpth" type="text" class="form-control"></div>
                  </div>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" >TRIGMPRI</label>
                    <div class="col-md-9"><input ng-model="mq.trigmpri" type="text" class="form-control"></div>
                  </div>
                  
                  <div class="form-group"><br></div> 
                </div>
                <div role="tabpanel" class="tab-pane" id="event" ng-show="mq.type=='qlocal' || mq.type=='qmodel'">
                  
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" >QDEPTHHI</label>
                    <div class="col-md-9"><input ng-model="mq.qdepthhi" type="text" class="form-control"></div>
                  </div>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" >QDPHIEV</label>
                    <div class="col-md-9"><select class="form-control" ng-model="mq.qdphiev"><option value="">Please select</option><option value="disabled">Disabled</option><option value="enabled">Enabled</option></select></div>
                  </div>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" >QDEPTHLO</label>
                    <div class="col-md-9"><input ng-model="mq.qdepthlo" type="text" class="form-control"></div>
                  </div>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" >QDPLOEV</label>
                    <div class="col-md-9"> <select class="form-control" ng-model="mq.qdploev"><option value="">Please select</option><option value="disabled">Disabled</option><option value="enabled">Enabled</option></select></div>
                  </div>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" >QDPMAXEV</label>
                    <div class="col-md-9"><select class="form-control" ng-model="mq.qdpmaxev"><option value="">Please select</option><option value="disabled">Disabled</option><option value="enabled">Enabled</option></select></div>
                  </div>
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"  data-html="true" >QSVCIEV</label>
                    <div class="col-md-9"><select class="form-control" ng-model="mq.qsvciev"><option value="">Please select</option><option value="none">None</option><option value="high">High</option><option value="ok">Ok</option></select></div>
                  </div>
                  
                  <div class="form-group"><br></div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer" style="display:flow-root list-item;">
          <div class="float-start"><a href="https://www.google.com/search?q=ibm+mq+define+queues" target="_blank" class="waves-effect waves-light btn btn-light btn-sm">Information about Queues</a></div>
          <div class="float-end">
            
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="mdi mdi-close"></i>&nbsp;Close</button>
            <button type="button"  id="btn-mqsc-obj" class="waves-effect waves-light btn btn-info btn-sm" ng-click="mqsc('<?php echo $thisarray['p1'];?>',mq.proj,'<?php echo $_SESSION['user'];?>','<?php echo $page;?>')" ng-href="{{ url }}"><i class="mdi mdi-download"></i>&nbsp;Create mqsc</button>
            <?php if($zobj['lockedby']==$_SESSION['user']){?>
            <?php if($_SESSION['user_level']>="3"){?>
            <button type="button" id="btn-create-obj" class="waves-effect waves-light btn btn-info btn-sm" ng-click="form.$valid && create('<?php echo $thisarray['p1'];?>','<?php echo $thisarray['p2'];?>','<?php echo $_SESSION['user'];?>','<?php echo $page;?>')"><i class="mdi mdi-check"></i>&nbsp;Create</button>
            <button type="button"  id="btn-update-obj" class="waves-effect waves-light btn btn-info btn-sm" ng-click="update('<?php echo $thisarray['p1'];?>','<?php echo $thisarray['p2'];?>','<?php echo $_SESSION['user'];?>','<?php echo $page;?>')"><i class="mdi mdi-content-save-outline"></i>&nbsp;Save Changes</button>
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