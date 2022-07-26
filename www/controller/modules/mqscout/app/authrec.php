<?php if(empty($thisarray['p2'])){ ?>


<?php } else { 
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
<div class="row">
<div class="col-md-3">
        <?php include "mqsidebar.php";?>
    </div>
    <div class="col-md-9">
        <div class="card p-0">
            <table class="table table-vmiddle table-hover stylish-table mb-0">
                <thead>
                    <tr>
                        <th class="text-center" style="width:50px;"></th>
                        <th class="text-center" style="width:50px;">Job</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Type</th>
                        <th class="text-center">Group</th>
                        <th class="text-center" style="width:130px;">Action</th>
                    </tr>
                </thead>
                <tbody
                    ng-init="getAll('<?php echo $thisarray['p1'];?>','<?php echo $thisarray['p3'];?>','<?php echo $page;?>','<?php echo $thisarray['p2'];?>')">
                    <tr ng-hide="contentLoaded">
                    <td class="text-center placeholder-glow"><small class="placeholder col-12"></small></td>
                    <td class="text-center placeholder-glow"><small class="placeholder col-12"></small></td>
                    <td class="text-center placeholder-glow"><small class="placeholder col-12"></small></td>
                    <td class="text-center placeholder-glow"><small class="placeholder col-12"></small></td>
                    <td class="text-center placeholder-glow"><small class="placeholder col-12"></small></td>
                    <td class="text-center placeholder-glow"><small class="placeholder col-12"></small></td>
                    </tr>
                    <tr id="contloaded" class="hide"
                        dir-paginate="d in names | filter:search | orderBy:sortKey:reverse | itemsPerPage:10"
                        pagination-id="prodx" ng-hide="d.qm==''">
                        <td class="text-center">
                            <div class="toggle-switch">
                                <input id="deplqmid{{ d.qmid }}" type="checkbox" class="checkall" value="{{ d.qmid }}"
                                    ng-checked="exists(d.qmid, selectedid)" ng-click="toggle(d.qmid, selectedid)"
                                    style="display:none;">
                                <label for="deplqmid{{ d.qmid }}" class="ts-helper"></label>
                            </div>
                        </td>
                        <td class="text-center" style="padding: .5rem;"><a href="/automation/{{d.jobid}}"
                                ng-show="d.jobrun==1"><i class="mdi mdi-play-circle-outline mdi-24px"></i></a></td>
                        <td class="text-center">{{ d.name}}</td>
                        <td class="text-center">{{ d.type}}</td>
                        <td class="text-center">{{ d.group}}</td>
                        <td class="text-center">
                            <div class="text-start d-grid gap-2 d-md-block">
                            <button type="button"
                                    ng-click="readOne('<?php echo $thisarray['p1'];?>',d.qid,d.qmid,d.proj,'<?php echo $page;?>')"
                                     class="btn btn-light btn-sm bg waves-effect" title="Read"><i
                                        class="mdi mdi-pencil mdi-18px"></i></button>
                                <?php if($_SESSION['user_level']>="3"){?>
                                <button type="button"
                                    ng-click="duplicate('<?php echo $thisarray['p1'];?>',d.qid,d.qmid,'<?php echo $_SESSION['user'];?>','<?php echo $thisarray['p3'];?>','<?php echo $thisarray['p2'];?>')"
                                    class="btn btn-light btn-sm bg waves-effect" title="Duplicate"><i
                                        class="mdi mdi-content-duplicate mdi-18px"></i></button>
                                <button type="button"
                                    ng-click="delete('<?php echo $thisarray['p1'];?>',d.qid,d.qmid,d.proj,'<?php echo $_SESSION['user'];?>','<?php echo $page;?>','<?php echo $thisarray['p2'];?>')"
                                    class="btn btn-light btn-sm bg waves-effect" title="Delete"><i
                                        class="mdi mdi-close"></i></button>
                                <?php } ?>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <dir-pagination-controls pagination-id="prodx" boundary-links="true"
                on-page-change="pageChangeHandler(newPageNumber)" template-url="/<?php echo $website['corebase'];?>assets/templ/pagination.tpl.html">
            </dir-pagination-controls>
            <div class="modal" id="modal-obj-form" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form name="form" ng-app>
                            <div class="modal-body">

                                <div role="tabpanel">
                                    <input ng-model="mq.type" ng-init="mq.type='auth'" style="display:none;">
                                    <ul class="nav nav-tabs customtab" role="tablist">
                                        <li class="nav-item"><a class="nav-link active" href="#base"
                                                aria-controls="base" role="tab" data-bs-toggle="tab">Base</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#authority"
                                                aria-controls="authority" role="tab" data-bs-toggle="tab">Authority</a>
                                        </li>
                                    </ul><br>
                                    <div class="tab-content container form-material"
                                        style="width:100%;min-height:300px;max-height:500px;overflow-x:hidden;overflow-y:scroll;">
                                        <div role="tabpanel" class="tab-pane active" id="base">

                                        <input id="thisact" value="yes" type="text"
                                            style="display:none;">
                                            <input id="thisproj" style="display:none;"
                                                value="<?php echo $thisarray['p3'];?>">
                                            <input id="thisqm" value="<?php echo $thisarray['p2'];?>" type="text"
                                            style="display:none;">
                                            <div class=" row">
                                                <label class="form-control-label text-lg-right col-md-3">Tags</label>
                                                <div class="col-md-8"><input id="tags" data-role="tagsinput" type="text"
                                                        class="form-control form-control-sm"></div>
                                                <div class="col-md-1" style="padding-left:0px;"><button type="button"
                                                        class="btn btn-light" data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="You can search this object with tags"><i
                                                            class="mdi mdi-information-variant mdi-18px"></i></button>
                                                </div>
                                            </div>
                                            <div class=" row">
                                                <label class="form-control-label text-lg-right col-md-3"
                                                    ng-class="{'has-error':!mq.name}">Name</label>
                                                <div class="col-md-9"><input ng-model="mq.name" ng-required="true"
                                                        type="text" class="form-control form-control-sm"></div>
                                            </div>
                                            <div class=" row">
                                                <label class="form-control-label text-lg-right col-md-3">Object
                                                    type</label>
                                                <div class="col-md-9"> <select class="form-control form-control-sm"
                                                        ng-model="mq.authtype">
                                                        <option value="">Null</option>
                                                        <option value="authinfo">An authentication information object
                                                        </option>
                                                        <option value="channel">A channel</option>
                                                        <option value="clntconn">A client connection channel</option>
                                                        <option value="comminfo">A communication information object
                                                        </option>
                                                        <option value="listener">A listener</option>
                                                        <option value="namelist">A namelist</option>
                                                        <option value="process">A process</option>
                                                        <option value="queue">A queue</option>
                                                        <option value="qmgr">A queue manager</option>
                                                        <option value="rqmname">A remote queue manager name</option>
                                                        <option value="service">A service</option>
                                                        <option value="topic">A topic</option>
                                                    </select></div>
                                            </div>
                                            <div class=" row">
                                                <label class="form-control-label text-lg-right col-md-3">Group</label>
                                                <div class="col-md-9"><input ng-model="mq.group" type="text"
                                                        class="form-control form-control-sm"></div>
                                            </div>

                                        </div>
                                        <div role="tabpanel" class="tab-pane" id="authority">
                                            <div class=" row">
                                                <label class="form-control-label text-lg-right col-md-3">ALL</label>
                                                <div class="col-md-9"> <select class="form-control form-control-sm"
                                                        ng-model="mq.authrec.all">
                                                        <option value="">NULL</option>
                                                        <option value="+all">Add</option>
                                                        <option value="-all">Remove</option>
                                                    </select></div>
                                            </div>
                                            <div class=" row">
                                                <label class="form-control-label text-lg-right col-md-3"
                                                    ng-class="{'has-error':mq.authrec.alladm && ['rqmname'].indexOf(mq.authtype)!=-1}">ALLADM</label>
                                                <div class="col-md-9"><select class="form-control form-control-sm"
                                                        ng-model="mq.authrec.alladm">
                                                        <option value="">NULL</option>
                                                        <option value="+alladm">Add</option>
                                                        <option value="-alladm">Remove</option>
                                                    </select></div>
                                            </div>
                                            <div class=" row">
                                                <label class="form-control-label text-lg-right col-md-3"
                                                    ng-class="{'has-error':mq.authrec.allmqi && ['clntconn','channel','listener','service','comminfo'].indexOf(mq.authtype)!=-1}">ALLMQI</label>
                                                <div class="col-md-9"><select class="form-control form-control-sm"
                                                        ng-model="mq.authrec.allmqi">
                                                        <option value="">NULL</option>
                                                        <option value="+allmqi">Add</option>
                                                        <option value="-allmqi">Remove</option>
                                                    </select></div>
                                            </div>
                                            <div class=" row">
                                                <label class="form-control-label text-lg-right col-md-3"
                                                    ng-class="{'has-error':mq.authrec.none && ['topic'].indexOf(mq.authtype)!=-1}">NONE</label>
                                                <div class="col-md-9"><select class="form-control form-control-sm"
                                                        ng-model="mq.authrec.none">
                                                        <option value="">NULL</option>
                                                        <option value="+none">Add</option>
                                                        <option value="-none">Remove</option>
                                                    </select></div>
                                            </div>
                                            <div class=" row">
                                                <label class="form-control-label text-lg-right col-md-3"
                                                    ng-class="{'has-error':mq.authrec.altusr && ['queue','process','rqmname','namelist','topic','authinfo','clntconn','channel','listener','service','comminfo'].indexOf(mq.authtype)!=-1}">ALTUSR</label>
                                                <div class="col-md-9"><select class="form-control form-control-sm"
                                                        ng-model="mq.authrec.altusr">
                                                        <option value="">NULL</option>
                                                        <option value="+altusr">Add</option>
                                                        <option value="-altusr">Remove</option>
                                                    </select></div>
                                            </div>
                                            <div class=" row">
                                                <label class="form-control-label text-lg-right col-md-3"
                                                    ng-class="{'has-error':mq.authrec.browse && ['process','qmgr','rqmname','namelist','topic','authinfo','clntconn','channel','listener','service','comminfo'].indexOf(mq.authtype)!=-1}">BROWSE</label>
                                                <div class="col-md-9"> <select class="form-control form-control-sm"
                                                        ng-model="mq.authrec.browse">
                                                        <option value="">NULL</option>
                                                        <option value="+browse">Add</option>
                                                        <option value="-browse">Remove</option>
                                                    </select></div>
                                            </div>
                                            <div class=" row">
                                                <label class="form-control-label text-lg-right col-md-3"
                                                    ng-class="{'has-error':mq.authrec.chg && ['rqmname'].indexOf(mq.authtype)!=-1}">CHG</label>
                                                <div class="col-md-9"><select class="form-control form-control-sm"
                                                        ng-model="mq.authrec.chg">
                                                        <option value="">NULL</option>
                                                        <option value="+chg">Add</option>
                                                        <option value="-chg">Remove</option>
                                                    </select></div>
                                            </div>
                                            <div class=" row">
                                                <label class="form-control-label text-lg-right col-md-3"
                                                    ng-class="{'has-error':mq.authrec.clr && ['process','qmgr','rqmname','namelist','authinfo','clntconn','channel','listener','service','comminfo'].indexOf(mq.authtype)!=-1}">CLR</label>
                                                <div class="col-md-9"><select class="form-control form-control-sm"
                                                        ng-model="mq.authrec.clr">
                                                        <option value="">NULL</option>
                                                        <option value="+clr">Add</option>
                                                        <option value="-clr">Remove</option>
                                                    </select></div>
                                            </div>
                                            <div class=" row">
                                                <label class="form-control-label text-lg-right col-md-3"
                                                    ng-class="{'has-error':mq.authrec.connect && ['queue','process','rqmname','namelist','topic','authinfo','clntconn','channel','listener','service','comminfo'].indexOf(mq.authtype)!=-1}">CONNECT</label>
                                                <div class="col-md-9"><select class="form-control form-control-sm"
                                                        ng-model="mq.authrec.connect">
                                                        <option value="">NULL</option>
                                                        <option value="+connect">Add</option>
                                                        <option value="-connect">Remove</option>
                                                    </select></div>
                                            </div>
                                            <div class=" row">
                                                <label class="form-control-label text-lg-right col-md-3"
                                                    ng-class="{'has-error':mq.authrec.crt && ['rqmname'].indexOf(mq.authtype)!=-1}">CRT</label>
                                                <div class="col-md-9"><select class="form-control form-control-sm"
                                                        ng-model="mq.authrec.crt">
                                                        <option value="">NULL</option>
                                                        <option value="+crt">Add</option>
                                                        <option value="-crt">Remove</option>
                                                    </select></div>
                                            </div>
                                            <div class=" row">
                                                <label class="form-control-label text-lg-right col-md-3"
                                                    ng-class="{'has-error':mq.authrec.ctrl && ['queue','process','qmgr','rqmname','namelist','authinfo','clntconn','comminfo'].indexOf(mq.authtype)!=-1}">CTRL</label>
                                                <div class="col-md-9"><select class="form-control form-control-sm"
                                                        ng-model="mq.authrec.ctrl">
                                                        <option value="">NULL</option>
                                                        <option value="+ctrl">Add</option>
                                                        <option value="-ctrl">Remove</option>
                                                    </select></div>
                                            </div>
                                            <div class=" row">
                                                <label class="form-control-label text-lg-right col-md-3"
                                                    ng-class="{'has-error':mq.authrec.ctrlx && ['queue','process','qmgr','rqmname','namelist','topic','authinfo','clntconn','listener','service','comminfo'].indexOf(mq.authtype)!=-1}">CTRLX</label>
                                                <div class="col-md-9"><select class="form-control form-control-sm"
                                                        ng-model="mq.authrec.ctrlx">
                                                        <option value="">NULL</option>
                                                        <option value="+ctrlx">Add</option>
                                                        <option value="-ctrlx">Remove</option>
                                                    </select></div>
                                            </div>
                                            <div class=" row">
                                                <label class="form-control-label text-lg-right col-md-3"
                                                    ng-class="{'has-error':mq.authrec.dlt && ['rqmname'].indexOf(mq.authtype)!=-1}">DLT</label>
                                                <div class="col-md-9"> <select class="form-control form-control-sm"
                                                        ng-model="mq.authrec.dlt">
                                                        <option value="">NULL</option>
                                                        <option value="+dlt">Add</option>
                                                        <option value="-dlt">Remove</option>
                                                    </select></div>
                                            </div>
                                            <div class=" row">
                                                <label class="form-control-label text-lg-right col-md-3"
                                                    ng-class="{'has-error':mq.authrec.dsp && ['rqmname'].indexOf(mq.authtype)!=-1}">DSP</label>
                                                <div class="col-md-9"><select class="form-control form-control-sm"
                                                        ng-model="mq.authrec.dsp">
                                                        <option value="">NULL</option>
                                                        <option value="+dsp">Add</option>
                                                        <option value="-dsp">Remove</option>
                                                    </select></div>
                                            </div>
                                            <div class=" row">
                                                <label class="form-control-label text-lg-right col-md-3"
                                                    ng-class="{'has-error':mq.authrec.get && ['process','qmgr','rqmname','namelist','topic','authinfo','clntconn','channel','listener','service','comminfo'].indexOf(mq.authtype)!=-1}">GET</label>
                                                <div class="col-md-9"><select class="form-control form-control-sm"
                                                        ng-model="mq.authrec.get">
                                                        <option value="">NULL</option>
                                                        <option value="+get">Add</option>
                                                        <option value="-get">Remove</option>
                                                    </select></div>
                                            </div>
                                            <div class=" row">
                                                <label class="form-control-label text-lg-right col-md-3"
                                                    ng-class="{'has-error':mq.authrec.pub && ['queue','process','qmgr','rqmname','namelist','authinfo','clntconn','channel','listener','service','comminfo'].indexOf(mq.authtype)!=-1}">PUB</label>
                                                <div class="col-md-9"><select class="form-control form-control-sm"
                                                        ng-model="mq.authrec.pub">
                                                        <option value="">NULL</option>
                                                        <option value="+pub">Add</option>
                                                        <option value="-pub">Remove</option>
                                                    </select></div>
                                            </div>
                                            <div class=" row">
                                                <label class="form-control-label text-lg-right col-md-3"
                                                    ng-class="{'has-error':mq.authrec.put && ['process','qmgrnamelist','topic','authinfo','clntconn','channel','listener','service','comminfo'].indexOf(mq.authtype)!=-1}">PUT</label>
                                                <div class="col-md-9"><select class="form-control form-control-sm"
                                                        ng-model="mq.authrec.put">
                                                        <option value="">NULL</option>
                                                        <option value="+put">Add</option>
                                                        <option value="-put">Remove</option>
                                                    </select></div>
                                            </div>
                                            <div class=" row">
                                                <label class="form-control-label text-lg-right col-md-3"
                                                    ng-class="{'has-error':mq.authrec.inq && ['rqmname','topic','clntconn','channel','listener','service','comminfo'].indexOf(mq.authtype)!=-1}">INQ</label>
                                                <div class="col-md-9"><select class="form-control form-control-sm"
                                                        ng-model="mq.authrec.inq">
                                                        <option value="">NULL</option>
                                                        <option value="+inq">Add</option>
                                                        <option value="-inq">Remove</option>
                                                    </select></div>
                                            </div>
                                            <div class=" row">
                                                <label class="form-control-label text-lg-right col-md-3"
                                                    ng-class="{'has-error':mq.authrec.passall && ['process','qmgr','rqmname','namelist','topic','authinfo','clntconn','channel','listener','service','comminfo'].indexOf(mq.authtype)!=-1}">PASSALL</label>
                                                <div class="col-md-9"><select class="form-control form-control-sm"
                                                        ng-model="mq.authrec.passall">
                                                        <option value="">NULL</option>
                                                        <option value="+passall">Add</option>
                                                        <option value="-passall">Remove</option>
                                                    </select></div>
                                            </div>
                                            <div class=" row">
                                                <label class="form-control-label text-lg-right col-md-3"
                                                    ng-class="{'has-error':mq.authrec.passid && ['process','qmgr','rqmname','namelist','topic','authinfo','clntconn','channel','listener','service','comminfo'].indexOf(mq.authtype)!=-1}">PASSID</label>
                                                <div class="col-md-9"><select class="form-control form-control-sm"
                                                        ng-model="mq.authrec.passid">
                                                        <option value="">NULL</option>
                                                        <option value="+passid">Add</option>
                                                        <option value="-passid">Remove</option>
                                                    </select></div>
                                            </div>
                                            <div class=" row">
                                                <label class="form-control-label text-lg-right col-md-3"
                                                    ng-class="{'has-error':mq.authrec.resume && ['queue','process','qmgr','rqmname','namelist','authinfo','clntconn','channel','listener','service','comminfo'].indexOf(mq.authtype)!=-1}">RESUME</label>
                                                <div class="col-md-9"><select class="form-control form-control-sm"
                                                        ng-model="mq.authrec.resume">
                                                        <option value="">NULL</option>
                                                        <option value="+resume">Add</option>
                                                        <option value="-resume">Remove</option>
                                                    </select></div>
                                            </div>
                                            <div class=" row">
                                                <label class="form-control-label text-lg-right col-md-3"
                                                    ng-class="{'has-error':mq.authrec.set && ['rqmname','namelist','topic','authinfo','clntconn','channel','listener','service','comminfo'].indexOf(mq.authtype)!=-1}">SET</label>
                                                <div class="col-md-9"><select class="form-control form-control-sm"
                                                        ng-model="mq.authrec.set">
                                                        <option value="">NULL</option>
                                                        <option value="+set">Add</option>
                                                        <option value="-set">Remove</option>
                                                    </select></div>
                                            </div>
                                            <div class=" row">
                                                <label class="form-control-label text-lg-right col-md-3"
                                                    ng-class="{'has-error':mq.authrec.setall && ['process','rqmname','namelist','topic','authinfo','clntconn','channel','listener','service','comminfo'].indexOf(mq.authtype)!=-1}">SETALL</label>
                                                <div class="col-md-9"><select class="form-control form-control-sm"
                                                        ng-model="mq.authrec.setall">
                                                        <option value="">NULL</option>
                                                        <option value="+setall">Add</option>
                                                        <option value="-setall">Remove</option>
                                                    </select></div>
                                            </div>
                                            <div class=" row">
                                                <label class="form-control-label text-lg-right col-md-3"
                                                    ng-class="{'has-error':mq.authrec.setid && ['process','rqmname','namelist','authinfo','clntconn','channel','listener','service','comminfo'].indexOf(mq.authtype)!=-1}">SETID</label>
                                                <div class="col-md-9"><select class="form-control form-control-sm"
                                                        ng-model="mq.authrec.setid">
                                                        <option value="">NULL</option>
                                                        <option value="+setid">Add</option>
                                                        <option value="-setid">Remove</option>
                                                    </select></div>
                                            </div>
                                            <div class=" row">
                                                <label class="form-control-label text-lg-right col-md-3"
                                                    ng-class="{'has-error':mq.authrec.sub && ['queue','process','qmgr','rqmname','namelist','authinfo','clntconn','channel','listener','service','comminfo'].indexOf(mq.authtype)!=-1}">SUB</label>
                                                <div class="col-md-9"><select class="form-control form-control-sm"
                                                        ng-model="mq.authrec.sub">
                                                        <option value="">NULL</option>
                                                        <option value="+sub">Add</option>
                                                        <option value="-sub">Remove</option>
                                                    </select></div>
                                            </div>
                                            <div class=" row">
                                                <label class="form-control-label text-lg-right col-md-3"
                                                    ng-class="{'has-error':mq.authrec.system && ['queue','process','rqmname','namelist','topic','authinfo','clntconn','channel','listener'].indexOf(mq.authtype)!=-1}">SYSTEM</label>
                                                <div class="col-md-9"><select class="form-control form-control-sm"
                                                        ng-model="mq.authrec.system">
                                                        <option value="">NULL</option>
                                                        <option value="+system">Add</option>
                                                        <option value="-system">Remove</option>
                                                    </select></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer" style="display:flow-root list-item;">
                                <div class="float-start"><a href="https://www.google.com/search?q=ibm+mq+setmqauth"
                                        target="_blank"
                                        class="waves-effect waves-light btn btn-light btn-sm">Information about
                                        AUTHREC</a></div>
                                <div class="float-end">
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i
                                            class="mdi mdi-close"></i>&nbsp;Close</button>
                                    <button type="button" id="btn-mqsc-obj"
                                        class="waves-effect waves-light btn btn-info btn-sm"
                                        ng-click="auth('<?php echo $thisarray['p2'];?>','<?php echo $_SESSION['user'];?>','<?php echo $page;?>')"
                                        ng-href="{{ url }}"><i class="mdi mdi-download"></i>&nbsp;Create auth</button>
                                        <?php if($_SESSION['user_level']>="3"){?>
                                    <button type="button" id="btn-create-obj"
                                        class="waves-effect waves-light btn btn-info btn-sm"
                                        ng-click="form.$valid && create('<?php echo $thisarray['p1'];?>','<?php echo $thisarray['p3'];?>','<?php echo $_SESSION['user'];?>','<?php echo $page;?>','<?php echo $thisarray['p2'];?>')"><i
                                            class="mdi mdi-check"></i>&nbsp;Create</button>
                                    <button type="button" id="btn-update-obj"
                                        class="waves-effect waves-light btn btn-info btn-sm"
                                        ng-click="update('<?php echo $thisarray['p1'];?>','<?php echo $thisarray['p3'];?>','<?php echo $_SESSION['user'];?>','<?php echo $page;?>','<?php echo $thisarray['p2'];?>')"><i
                                            class="mdi mdi-content-save-outline"></i>&nbsp;Save Changes</button>
                                    <?php } ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php if($_SESSION['user_level']>="3"){?>
            <?php include $maindir."/public/modules/deplform.php";?>
            <?php include $maindir."/public/modules/respform.php";?>
            <?php } ?>
        </div>
    </div>
</div>
<?php } ?>