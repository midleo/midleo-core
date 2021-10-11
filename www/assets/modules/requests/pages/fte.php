<?php if (!empty($thisarray["p2"])) {
  $sql="select * from requests where sname=?";
  $q = $pdo->prepare($sql);
  $q->execute(array($thisarray["p2"]));
  if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
    ?>
<div class="card">
  <div class="card-header">
    <h2>MQFTE objects for request <b><?php echo $zobj['reqname'];?></b></h2>
  </div>
  <div class="card-body" id="ngApp" ng-app="ngApp" ng-controller="ngCtrl">
    <div class="row">
      <div class="col-md-8">
          <input type="text" ng-model="search" class="form-control  topsearch" placeholder="Search fte configuration...">
      </div>
    </div><br><br>
    <div class="row"><div class="col-md-12">
      <table class="table table-striped table-vmiddle table-hover stylish-table mb-0">
        <thead>
          <tr>
            <th class="text-center">Name</th>
            <th class="text-center">Source Agt</th>
            <th class="text-center">Source Folder</th>
            <th class="text-center">Dest Agt</th>
            <th class="text-center">Dest Folder</th>
            <th class="text-center" style="width:120px;">Action</th>
          </tr>
        </thead>
        <tbody ng-init="getAllfte('<?php echo $thisarray["p2"];?>','requests')">
          <tr dir-paginate="d in names | filter:search | orderBy:'name':reverse | itemsPerPage:10" pagination-id="prodx">
            <td class="text-center">{{ d.mqftename | limitTo:textlimit }}{{d.mqftename.length > textlimit ? '...' : ''}}</td>
            <td class="text-center">{{ d.sourceagt | limitTo:textlimit }}{{d.sourceagt.length > textlimit ? '...' : ''}}</td>
            <td class="text-center">{{ d.sourcedir | limitTo:textlimit }}{{d.sourcedir.length > textlimit ? '...' : ''}}</td>
            <td class="text-center">{{ d.destagt | limitTo:textlimit }}{{d.destagt.length > textlimit ? '...' : ''}}</td>
            <td class="text-center">{{ d.destdir | limitTo:textlimit }}{{d.destdir.length > textlimit ? '...' : ''}}</td>
            <td class="text-center">
              <?php if($zobj['sname']==$thisarray["p2"]){?>
              <a ng-click="readOnefte('<?php echo $thisarray["p2"];?>',d.fteid,'requests')" style="" class="btn btn-primary bg waves-effect"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-edit" xlink:href="/assets/images/icon/midleoicons.svg#i-edit" /></svg></a>&nbsp;
              <?php if($_SESSION['user_level']=="5" || $_SESSION['user_level']=="3"){?><a ng-click="deletefte('<?php echo $thisarray["p2"];?>',d.fteid,'<?php echo $_SESSION['user'];?>','requests','<?php echo $bstep;?>')" class="btn btn-danger bg waves-effect"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-x" xlink:href="/assets/images/icon/midleoicons.svg#i-x" /></svg></a><?php } ?>
              <?php } else {?>
              <a style="" class="btn btn-default bg waves-effect"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-edit" xlink:href="/assets/images/icon/midleoicons.svg#i-edit" /></svg></a>&nbsp;
              <?php if($_SESSION['user_level']=="5" || $_SESSION['user_level']=="3"){?><a class="btn btn-default bg waves-effect"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-x" xlink:href="/assets/images/icon/midleoicons.svg#i-x" /></svg></a><?php } ?>
              <?php } ?>
            </td>
          </tr>
        </tbody>
      </table>
      <dir-pagination-controls pagination-id="prodx" boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="/assets/templ/pagination.tpl.html"></dir-pagination-controls>
      <div class="modal" id="modal-fte-form" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <form name="form" ng-app>
              <div class="modal-body">
                <div role="tabpanel">
                  <ul class="tab-nav" role="tablist">
                    <li class="active"><a href="#base" aria-controls="base" role="tab" data-bs-toggle="tab">Base</a></li>
                    <li><a href="#info" aria-controls="info" role="tab" data-bs-toggle="tab">Info</a></li>
                  </ul>
                  <div class="tab-content form-horizontal" style="width:100%;min-height:300px;max-height:500px;overflow-x:hidden;overflow-y:scroll;">
                    <div role="tabpanel" class="tab-pane active" id="base" >
                      <div class="form-group-sm">
                        <label class="col-md-2 control-label" data-trigger="hover" data-bs-toggle="popover" data-bs-placement="right" data-html="true" data-content="There are three types of file transfers<br><br>File to File - source and destination are files<br>Queue to File - source is a queue and destination - file<br>File to Queue - source is a file and destination - Queue" title="" data-original-title="file transfer type" ng-class="{'has-error':!mqfte.mqftetype}">Type</label>
                        <div class="col-md-10"><div class="fg-line"><select class="form-control" ng-required="true" ng-model="mqfte.mqftetype"><option value="">Please select</option><option value="f2f">File to File</option><option value="f2q">File to Queue</option><option value="q2f">Queue to File</option></select></div></div>
                      </div>
                      <div class="form-group-sm">
                        <label class="col-md-2 control-label" data-trigger="hover" data-bs-toggle="popover" data-bs-placement="right" data-html="true" data-content="You can search this object with tags" title="" data-original-title="Tags">Tags</label>
                        <div class="col-md-10"><div class="fg-line"><input ng-model="mqfte.tags" type="text" class="form-control"></div></div>
                      </div>
                      <div class="form-group-sm">
                        <label class="col-md-2 control-label" data-trigger="hover" data-bs-toggle="popover" data-bs-placement="right" data-html="true" data-content="Name of the transfer" title="" data-original-title="Name" ng-class="{'has-error':!mqfte.mqftename}">Name</label>
                        <div class="col-md-10"><div class="fg-line"><input ng-model="mqfte.mqftename" ng-required="true" type="text" class="form-control"></div></div>
                      </div> 
                      <div class="form-group-sm">
                        <label class="col-md-2 control-label" data-trigger="hover" data-bs-toggle="popover" data-bs-placement="right" data-html="true" data-content="Number of files in one transfer.<br>Default is 1" title="" data-original-title="Batch size">BatchSize</label>
                        <div class="col-md-10"><div class="fg-line"><input ng-model="mqfte.batchsize" type="text" class="form-control"></div></div>
                      </div>
                      <div class="form-group-sm">
                        <label class="col-md-2 control-label" data-trigger="hover" data-bs-toggle="popover" data-bs-placement="right" data-html="true" data-content="The name of the Source FTE agent" title="" data-original-title="Source agent" ng-class="{'has-error':!mqfte.sourceagt}">SourceAGT</label>
                        <div class="col-md-10"><div class="fg-line"><input ng-model="mqfte.sourceagt" ng-required="true" type="text" class="form-control"></div></div>
                      </div>
                      <div class="form-group-sm">
                        <label class="col-md-2 control-label" data-trigger="hover" data-bs-toggle="popover" data-bs-placement="right" data-html="true" data-content="The name of the Source FTE agent Qmanager" title="" data-original-title="Source agent Qmanager" ng-class="{'has-error':!mqfte.sourceagtqmgr}">SAGTQM</label>
                        <div class="col-md-10"><div class="fg-line"><input ng-model="mqfte.sourceagtqmgr" ng-required="true" type="text" class="form-control"></div></div>
                      </div>
                      <div class="form-group-sm">
                        <label class="col-md-2 control-label" data-trigger="hover" data-bs-toggle="popover" data-bs-placement="right" data-html="true" data-content="The name of the Destination FTE agent" title="" data-original-title="Destination agent" ng-class="{'has-error':!mqfte.destagt}">DestAGT</label>
                        <div class="col-md-10"><div class="fg-line"><input ng-model="mqfte.destagt" ng-required="true" type="text" class="form-control"></div></div>
                      </div>
                      <div class="form-group-sm">
                        <label class="col-md-2 control-label" data-trigger="hover" data-bs-toggle="popover" data-bs-placement="right" data-html="true" data-content="The name of the Destination FTE agent Qmanager" title="" data-original-title="Destination agent Qmanager" ng-class="{'has-error':!mqfte.destagtqmgr}">DAGTQM</label>
                        <div class="col-md-10"><div class="fg-line"><input ng-model="mqfte.destagtqmgr" ng-required="true" type="text" class="form-control"></div></div>
                      </div>
                      <div class="form-group-sm">
                        <label class="col-md-2 control-label" data-trigger="hover" data-bs-toggle="popover" data-bs-placement="right" data-html="true" data-content="After successfull transfer you can select either to delete or leave the source file" title="" data-original-title="source Disposition" ng-class="{'has-error':!mqfte.sourcedisp}">sourceDisp</label>
                        <div class="col-md-10"><div class="fg-line"><select class="form-control" ng-required="true" ng-model="mqfte.sourcedisp"><option value="">Please select</option><option value="leave">Leave</option><option value="delete">Delete</option></select></div></div>
                      </div>
                      <div class="form-group-sm">
                        <label class="col-md-2 control-label" data-trigger="hover" data-bs-toggle="popover" data-bs-placement="right" data-html="true" data-content="The type of the transfer - text or binary" title="" data-original-title="text or binary">textOrBinary</label>
                        <div class="col-md-10"><div class="fg-line"><select class="form-control" ng-model="mqfte.textorbinary"><option value="">Please select</option><option value="text">Text</option><option value="binary">Binary</option></select></div></div>
                      </div>
                      <div class="form-group-sm" ng-show="mqfte.textorbinary=='text'">
                        <label class="col-md-2 control-label" data-trigger="hover" data-bs-toggle="popover" data-bs-placement="right" data-html="true" data-content="The CCSID of the source file" title="" data-original-title="source CCSID">sourceCCSID</label>
                        <div class="col-md-10"><div class="fg-line"><input ng-model="mqfte.sourceccsid" type="text" class="form-control"></div></div>
                      </div>
                      <div class="form-group-sm" ng-show="mqfte.textorbinary=='text'">
                        <label class="col-md-2 control-label" data-trigger="hover" data-bs-toggle="popover" data-bs-placement="right" data-html="true" data-content="The CCSID of the destination file" title="" data-original-title="destination CCSID">destCCSID</label>
                        <div class="col-md-10"><div class="fg-line"><input ng-model="mqfte.destccsid" type="text" class="form-control"></div></div>
                      </div>
                      <div class="form-group-sm" ng-show="mqfte.mqftetype=='f2f' || mqfte.mqftetype=='f2q'">
                        <label class="col-md-2 control-label" data-trigger="hover" data-bs-toggle="popover" data-bs-placement="right" data-html="true" data-content="The directory name from where the file will be taken" title="" data-original-title="Monitor directory" >MonDir</label>
                        <div class="col-md-10"><div class="fg-line"><input ng-model="mqfte.sourcedir" type="text" class="form-control"></div></div>
                      </div>
                      <div class="form-group-sm" ng-show="mqfte.mqftetype=='f2f' || mqfte.mqftetype=='f2q'">
                        <label class="col-md-2 control-label" data-trigger="hover" data-bs-toggle="popover" data-bs-placement="right" data-html="true" data-content="The file pattern that will trigger the transfer" title="" data-original-title="Monitor Pattern" >MonFile</label>
                        <div class="col-md-10"><div class="fg-line"><input ng-model="mqfte.sourcefile" type="text" class="form-control"></div></div>
                      </div>
                      <div class="form-group-sm" ng-show="mqfte.mqftetype=='f2f' || mqfte.mqftetype=='f2q'">
                        <label class="col-md-2 control-label" data-trigger="hover" data-bs-toggle="popover" data-bs-placement="right" data-html="true" data-content="is the file pattern regular expression" title="" data-original-title="Regular expression" >Regex</label>
                        <div class="col-md-10"><div class="fg-line"><select class="form-control" ng-model="mqfte.regex"><option value="">No</option><option value="1">Yes</option></select></div></div>
                      </div>
                      <div class="form-group-sm" ng-show="mqfte.mqftetype=='q2f'">
                        <label class="col-md-2 control-label" data-trigger="hover" data-bs-toggle="popover" data-bs-placement="right" data-html="true" data-content="The queue name which will be monitored for messages<br>Note that queue should be visible from the source agent qmanager" title="" data-original-title="Monitor Queue" >MonQueue</label>
                        <div class="col-md-10"><div class="fg-line"><input ng-model="mqfte.sourcequeue" type="text" class="form-control"></div></div>
                      </div>
                      <div class="form-group-sm" ng-show="mqfte.mqftetype=='f2f' || mqfte.mqftetype=='q2f'">
                        <label class="col-md-2 control-label" data-trigger="hover" data-bs-toggle="popover" data-bs-placement="right" data-html="true" data-content="The name of directory on destination side" title="" data-original-title="Destination directory" >DestDir</label>
                        <div class="col-md-10"><div class="fg-line"><input ng-model="mqfte.destdir" type="text" class="form-control"></div></div>
                      </div>
                      <div class="form-group-sm" ng-show="mqfte.mqftetype=='f2f' || mqfte.mqftetype=='q2f'">
                        <label class="col-md-2 control-label" data-trigger="hover" data-bs-toggle="popover" data-bs-placement="right" data-html="true" data-content="The file name on destination side<br>Default is ${FileName}" title="" data-original-title="Destination File" >DestFile</label>
                        <div class="col-md-10"><div class="fg-line"><input ng-model="mqfte.destfile" type="text" class="form-control"></div></div>
                      </div>
                      <div class="form-group-sm" ng-show="mqfte.mqftetype=='f2q'">
                        <label class="col-md-2 control-label" data-trigger="hover" data-bs-toggle="popover" data-bs-placement="right" data-html="true" data-content="The queue name to which messages will be transferred<br>Note that queue should be visible from the destination agent qmanager" title="" data-original-title="Destination Queue" >DestQueue</label>
                        <div class="col-md-10"><div class="fg-line"><input ng-model="mqfte.destqueue" type="text" class="form-control"></div></div>
                      </div>
                      <div class="form-group-sm" ng-show="mqfte.mqftetype!='q2f'">
                        <label class="col-md-2 control-label" data-trigger="hover" data-bs-toggle="popover" data-bs-placement="right" data-html="true" data-content="Post source command that will be triggered after successfull transfer<br>Triggering is on source side" title="" data-original-title="Post Source Command" >SourceCMD</label>
                        <div class="col-md-10"><div class="fg-line"><input ng-model="mqfte.postsourcecmd" type="text" class="form-control"></div></div>
                      </div>
                      <div class="form-group-sm" ng-show="mqfte.mqftetype!='q2f'">
                        <label class="col-md-2 control-label" data-trigger="hover" data-bs-toggle="popover" data-bs-placement="right" data-html="true" data-content="Arguments that will be passed to the command. Please use interval between each argument" title="" data-original-title="Post Source Command arguments" >SCMDARG</label>
                        <div class="col-md-10"><div class="fg-line"><input ng-model="mqfte.postsourcecmdarg" type="text" class="form-control"></div></div>
                      </div>
                      <div class="form-group-sm" ng-show="mqfte.mqftetype!='f2q'">
                        <label class="col-md-2 control-label" data-trigger="hover" data-bs-toggle="popover" data-bs-placement="right" data-html="true" data-content="Post destination command that will be triggered after successfull transfer<br>Triggering is on destination side" title="" data-original-title="Post Destination Command" >DestCMD</label>
                        <div class="col-md-10"><div class="fg-line"><input ng-model="mqfte.postdestcmd" type="text" class="form-control"></div></div>
                      </div>
                      <div class="form-group-sm" ng-show="mqfte.mqftetype!='f2q'">
                        <label class="col-md-2 control-label" data-trigger="hover" data-bs-toggle="popover" data-bs-placement="right" data-html="true" data-content="Arguments that will be passed to the command. Please use interval between each argument" title="" data-original-title="Post Destination Command arguments" >DCMDARG</label>
                        <div class="col-md-10"><div class="fg-line"><input ng-model="mqfte.postdestcmdarg" type="text" class="form-control"></div></div>
                      </div>
                      <div class="form-group"><br></div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="info">
                      <div class="form-group">
                        <label class="col-md-2 control-label" data-trigger="hover" data-bs-toggle="popover" data-bs-placement="right" data-html="true" data-content="Additional information about this transfer. Email, requestor, references, that will help you finding it in future" title="" data-original-title="Information">Info</label>
                        <div class="col-md-10"><div class="fg-line"><textarea ng-model="mqfte.info" class="form-control" rows="6"></textarea></div></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <input ng-model="mqfte.projname" type="text" value="<?php echo $zobj['reqname'];?>" style="display:none;">
                <?php if($_SESSION['user_level']=="5" || $_SESSION['user_level']=="3"){?>
                <a id="btn-create-fte" class="waves-effect waves-light btn btn-primary" ng-click="form.$valid && createfte('<?php echo $thisarray["p2"];?>','<?php echo $_SESSION['user'];?>','requests','<?php echo $bstep;?>')"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-check" xlink:href="/assets/images/icon/midleoicons.svg#i-check"/></svg>&nbsp;Create</a>
                <a id="btn-update-fte" class="waves-effect waves-light btn btn-primary" ng-click="updatefte('<?php echo $thisarray["p2"];?>','<?php echo $_SESSION['user'];?>','requests','<?php echo $bstep;?>')"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-save" xlink:href="/assets/images/icon/midleoicons.svg#i-save"/></svg>&nbsp;Save Changes</a>
                <?php } ?>
                <a id="btn-conf-fte" class="waves-effect waves-light btn btn-success" ng-click="fteconf('<?php echo $thisarray["p2"];?>','requests')" ng-href="{{ url }}"><i class="mdi mdi-download"></i>&nbsp;Create config</a>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-x" xlink:href="/assets/images/icon/midleoicons.svg#i-x" /></svg>&nbsp;Close</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <?php if($zobj['sname']==$thisarray["p2"]){?>
      <?php if($_SESSION['user_level']=="5" || $_SESSION['user_level']=="3"){?>
      <div data-bs-toggle="tooltip" data-bs-placement="left" title="Create new File transfer definition" style="z-index:9999;position:fixed;bottom:45px; right:24px;">
        <a data-bs-toggle="modal" class="waves-effect waves-light btn btn-success btn-icon btnnm" href="#modal-fte-form" ng-click="showCreateFormfte()"><i class="mdi mdi-plus-thick mdi-24px"></i></a>
      </div>
      <?php } ?>
      <?php } ?>
      </div>
    </div>
    <?php include "modules/respform.php";?>
  </div>
</div>
<?php } else { textClass::PageNotFound(); }
} else { textClass::PageNotFound(); } ?>
