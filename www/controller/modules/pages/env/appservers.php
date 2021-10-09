<?php if(empty($thisarray['p2'])){ include "applist.php"; } else { ?>
  <div class="row">
  <div class="col-md-3 position-relative">
      <input type="text" ng-model="search" class="form-control topsearch" placeholder="Find a server">
      <span class="searchicon"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-search" xlink:href="/assets/images/icon/midleoicons.svg#i-search"/></svg>
  </div>
  <div class="col-md-9 text-end">
 <span data-bs-toggle="tooltip" data-bs-placement="top" title="Add configuration for a server"><button type="button" class="waves-effect waves-light btn btn-info" data-bs-toggle="modal" href="#modal-obj-form" ng-click="showCreateFormServ()"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-add" xlink:href="/assets/images/icon/midleoicons.svg#i-add"/></svg>&nbsp;Create</button></span>
  </div>
</div><br>
 <div class="card ">
<div class="card-body p-0">

<div class="row"><div class="col-md-12">
  <table class="table table-vmiddle table-hover stylish-table mb-0">
    <thead>
      <tr>
      <th class="text-center" style="width:50px;"></th>
      <th class="text-center">Server</th>
      <th class="text-center">Name</th>
        <th class="text-center">Port</th>
        <th class="text-center">Type</th>
        <th class="text-center">QM</th>
        <th class="text-center" style="width:120px;">Action</th>
      </tr>
    </thead>
    <tbody ng-init="getAllAppserv('<?php echo $thisarray['p2'];?>')">
      <tr ng-hide="contentLoaded"><td colspan="6" style="text-align:center;font-size:1.1em;"><i class="mdi mdi-loading iconspin"></i>&nbsp;Loading...</td></tr>
      <tr id="contloaded" class="hide" dir-paginate="d in names | filter:search | orderBy:'server':reverse | itemsPerPage:10" pagination-id="prodx">
      <td class="text-center"><a class="btn btn-light btn-sm" href="/browse/appserver/{{ d.id}}"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-app-srv" xlink:href="/assets/images/icon/midleoicons.svg#i-app-srv"/></svg></a></td>
      <td class="text-center">{{ d.server}}</td>
      <td class="text-center">{{ d.appsrvname}}</td>
        <td class="text-center">{{ d.port}}</td>
        <td class="text-center">{{ d.serv_type}}</td>
        <td class="text-center">{{ d.qmname }}</td>
        <td class="text-center">
        <div class="text-start d-grid gap-2 d-md-block">
          <button type="button" ng-click="readOneAppserv(d.id,'<?php echo $thisarray['p2'];?>')" class="btn waves-effect btn-light btn-sm"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-edit" xlink:href="/assets/images/icon/midleoicons.svg#i-edit"/></svg></button>
          <?php if($_SESSION['user_level']>=3){?>
            <button type="button" ng-click="duplappsrv(d.id,'<?php echo $_SESSION['user'];?>','<?php echo $thisarray['p2'];?>')" class="btn btn-light btn-sm bg waves-effect" title="Duplicate"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-documents" xlink:href="/assets/images/icon/midleoicons.svg#i-documents"/></svg></button>
          <button type="button" ng-click="delappsrv(d.id,'<?php echo $_SESSION['user'];?>','<?php echo $thisarray['p2'];?>')" class="btn waves-effect btn-light btn-sm"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-x" xlink:href="/assets/images/icon/midleoicons.svg#i-x"/></svg></button><?php } ?>
         </div>
         </td>
      </tr>
    </tbody>
  </table>
  <dir-pagination-controls pagination-id="prodx" boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="/assets/templ/pagination.tpl.html"></dir-pagination-controls>
  <div class="modal" id="modal-obj-form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
      <div class="modal-header"><h4>Application server</h4></div>
        <form name="form" ng-app>
          <div class="modal-body container form-material" style="width:100%;min-height:300px;max-height:500px;overflow-x:hidden;overflow-y:scroll;">
          <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3">Tags</label>
                    <div class="col-md-8"><input id="tags" data-role="tagsinput" type="text" class="form-control"></div>
                    <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="You can search this object with tags" ><i class="mdi mdi-information-variant mdi-18px"></i></button></div>
              </div>
            <div class="form-group row" >
              <label class="form-control-label text-lg-right col-md-3">Name</label>
              <div class="col-md-9"><input ng-model="serv.appsrvname" type="text" class="form-control"></div>
            </div>
            <div class="form-group row">
              <label class="form-control-label text-lg-right col-md-3" >Type</label>
              <div class="col-md-9"><select class="form-control" ng-model="serv.serv_type"><option value="">Please select</option>
<?php foreach($typesrv as $keyin=>$valin){?><option value="<?php echo $keyin;?>"><?php echo $valin;?></option><?php } ?></select></div>
            </div>

            <div class="form-group row">
  <label class="form-control-label text-lg-right col-md-3">Server</label>
  <div class="col-md-9">
  <input type="text"  class="form-control autocomplsrv" placeholder="write the server name" />
  <input type="text" id="server" style="display:none;" />
  <input type="text" id="serverid" style="display:none;" />
  <input type="text" id="serverip" style="display:none;" />
</div>
</div>
            <div class="form-group row">
              <label class="form-control-label text-lg-right col-md-3" data-trigger="hover" ng-class="{'has-error':!serv.port}">Port</label>
              <div class="col-md-8"><input ng-model="serv.port" type="text" class="form-control"></div>
              <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Port address on which Qmanager/FTE command QM/Broker is listening" ><i class="mdi mdi-information-variant mdi-18px"></i></button></div>
            </div>
            <div class="form-group row" ng-show="serv.serv_type=='qm' || serv.serv_type=='fte'">
              <label class="form-control-label text-lg-right col-md-3">QManager</label>
              <div class="col-md-9"><input ng-model="serv.qmname" type="text" class="form-control"></div>
            </div>
            <div class="form-group row" ng-show="serv.serv_type=='qm' || serv.serv_type=='fte'">
              <label class="form-control-label text-lg-right col-md-3">Channel</label>
              <div class="col-md-8"><input ng-model="serv.qmchannel" type="text" class="form-control"></div>
              <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Channel name. In case of FTE agent - Command Qmanager Channel name" ><i class="mdi mdi-information-variant mdi-18px"></i></button></div>
            </div>
            <div class="form-group row"  ng-show="serv.serv_type=='fte'">
              <label class="form-control-label text-lg-right col-md-3">Fte agent</label>
              <div class="col-md-9"><input ng-model="serv.agentname" type="text" class="form-control"></div>
            </div>
            <div class="form-group row" ng-show="serv.serv_type=='ibmiib'">
              <label class="form-control-label text-lg-right col-md-3">Broker name</label>
              <div class="col-md-9"><input ng-model="serv.brokername" type="text" class="form-control"></div>
            </div>
            <div class="form-group row" ng-show="serv.serv_type=='ibmiib'">
              <label class="form-control-label text-lg-right col-md-3">Exec group</label>
              <div class="col-md-9"> <input ng-model="serv.execgname" type="text" class="form-control"></div>
            </div>
            <div class="form-group row" ng-show="serv.serv_type=='tibems'">
              <label class="form-control-label text-lg-right col-md-3" >Username</label>
              <div class="col-md-9"> <input ng-model="serv.srvuser" type="text" class="form-control"></div>
            </div>
            <div class="form-group row" ng-show="serv.serv_type=='tibems'">
              <label class="form-control-label text-lg-right col-md-3" >Password</label>
              <div class="col-md-9"> <input ng-model="serv.srvpass" type="password" class="form-control"></div>
            </div>
            <div class="form-group row">
              <label class="form-control-label text-lg-right col-md-3" >Comment</label>
              <div class="col-md-9"> <input ng-model="serv.info" type="text" class="form-control">
              <input ng-model="serv.id" type="hidden">
              </div>
            </div>
            <div class="form-group row">
                  <div class="col-md-3"></div><div class="col-md-9">
                   <div class="toggle-switch" >
                    <input id="sslenabled" value="1" ng-model="serv.sslenabled" type="checkbox" ng-checked="serv.sslenabled=='1'" style="display:none;">
                    <label for="sslenabled" class="ts-helper">SSL enabled channel</label>
                 </div>
             </div>
           </div>
           <div class="form-group row" ng-show="serv.sslenabled=='1'">
              <label class="form-control-label text-lg-right col-md-3" >SSL key</label>
              <div class="col-md-9"> <input ng-model="serv.sslkey" type="text" class="form-control"></div>
            </div>
            <div class="form-group row" ng-show="serv.sslenabled=='1'">
              <label class="form-control-label text-lg-right col-md-3" >SSL password</label>
              <div class="col-md-9"> <input ng-model="serv.sslpass" type="text" class="form-control"></div>
            </div>
            <div class="form-group row" ng-show="serv.sslenabled=='1'">
              <label class="form-control-label text-lg-right col-md-3" >SSL Cipher</label>
              <div class="col-md-9"> <input ng-model="serv.sslcipher" type="text" class="form-control"></div>
            </div>


          </div>
          <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-x" xlink:href="/assets/images/icon/midleoicons.svg#i-x"/></svg>&nbsp;Close</button>
   <?php if($_SESSION['user_level']>=3){?>
            <button type="button" id="btn-create-obj" class="waves-effect waves-light btn btn-info btn-sm" ng-click="form.$valid && addappsrv('<?php echo $_SESSION['user'];?>','<?php echo $thisarray['p2'];?>')"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-check" xlink:href="/assets/images/icon/midleoicons.svg#i-check"/></svg>&nbsp;Create</button>
            <button type="button" id="btn-update-obj" class="waves-effect waves-light btn btn-info btn-sm" ng-click="updappsrv('<?php echo $_SESSION['user'];?>','<?php echo $thisarray['p2'];?>')"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-save" xlink:href="/assets/images/icon/midleoicons.svg#i-save"/></svg>&nbsp;Save Changes</button>
            <?php } ?>
                 </div>
        </form>
      </div>
    </div>
  </div>
  </div>
</div>
</div>
</div>
<?php } ?>