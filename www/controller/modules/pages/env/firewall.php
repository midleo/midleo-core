<?php if(empty($thisarray['p2'])){ include "applist.php"; } else { ?>
  <div class="row">
  <div class="col-md-3 position-relative">
      <input type="text" ng-model="search" class="form-control topsearch" placeholder="Find a firewall rule">
      <span class="searchicon"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-search" xlink:href="/assets/images/icon/midleoicons.svg#i-search"/></svg>
  </div>
  <div class="col-md-9 text-end">
<?php if ($_SESSION['user_level'] >= 3){?><span><button data-bs-toggle="tooltip" data-bs-placement="top" title="Export the objects in excel" type="button" class="waves-effect waves-light btn btn-light" ng-click="exportData('<?php echo $thisarray['p1']; ?>')">Export&nbsp;<svg class="midico midico-outline" ><use href="/assets/images/icon/midleoicons.svg#i-up" xlink:href="/assets/images/icon/midleoicons.svg#i-up"/></svg></button> </span><?php }?>
<?php if (sessionClass::checkAcc($acclist, "fwadmin")) { ?>
  <span data-bs-toggle="tooltip" data-bs-placement="top" title="Import firewall rules from Excel file"><button type="button" class="waves-effect waves-light btn btn-light" data-bs-toggle="modal" href="#modal-imp-form" ><i class="mdi mdi-database-import"></i>&nbsp;Import</button></span>
<span data-bs-toggle="tooltip" data-bs-placement="top" title="Add new firewall rule"><button type="button" class="waves-effect waves-light btn btn-info" data-bs-toggle="modal" href="#modal-obj-form" ng-click="showCreateFormFw()"><svg class="midico midico-outline" ><use href="/assets/images/icon/midleoicons.svg#i-add" xlink:href="/assets/images/icon/midleoicons.svg#i-add"/></svg>&nbsp;Create</button></span>
 <?php }?>
  </div>
</div><br>
  <div class="card ">
<div class="card-body p-0">

<div class="row"><div class="col-md-12">
  <table class="table table-vmiddle table-hover stylish-table mb-0">
    <thead>
      <tr>
        <th class="text-center">Application</th>
        <th class="text-center">Port</th>
        <th class="text-center">Source IP</th>
        <th class="text-center">Destination IP</th>
        <th class="text-center">Source DNS</th>
        <th class="text-center">Destination DNS</th>
        <th class="text-center" style="width:120px;">Action</th>
      </tr>
    </thead>
    <tbody ng-init="getAllfw('<?php echo $thisarray['p2'];?>')">
      <tr ng-hide="contentLoaded"><td colspan="6" style="text-align:center;font-size:1.1em;"><i class="mdi mdi-loading iconspin"></i>&nbsp;Loading...</td></tr>
      <tr id="contloaded" class="hide" dir-paginate="d in names | filter:search | orderBy:'srcdns':reverse | itemsPerPage:10" pagination-id="prodx">
        <td class="text-center">{{ d.proj }}</td>
        <td class="text-center">{{ d.port}}</td>
        <td class="text-center">{{ d.srcip}}</td>
        <td class="text-center">{{ d.destip}}</td>
        <td class="text-center">{{ d.srcdns }}</td>
        <td class="text-center">{{ d.destdns }}</td>
        <td class="text-center">
        <div class="btn-group" role="group">
          <button type="button" ng-click="readOnefw(d.id)" class="btn waves-effect btn-light btn-sm"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-edit" xlink:href="/assets/images/icon/midleoicons.svg#i-edit"/></svg></button>
          <?php if($_SESSION['user_level']>=3){?><button type="button" ng-click="deletefw(d.id,'<?php echo $_SESSION['user'];?>',d.srcdns,'<?php echo $thisarray['p2'];?>')" class="btn waves-effect btn-light btn-sm"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-x" xlink:href="/assets/images/icon/midleoicons.svg#i-x"/></svg></button><?php } ?>
         </div>
      </td>
      </tr>
    </tbody>
  </table>
  <dir-pagination-controls pagination-id="prodx" boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="/assets/templ/pagination.tpl.html"></dir-pagination-controls>
  <div class="modal" id="modal-obj-form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
      <div class="modal-header"><h4>Firewall rule</h4></div>
        <form name="form" ng-app>
          <div class="modal-body container form-material" style="width:100%;min-height:300px;max-height:500px;overflow-x:hidden;overflow-y:scroll;">
          <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3">Tags</label>
                    <div class="col-md-8"><input id="tags" data-role="tagsinput" type="text" class="form-control"></div>
                    <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="You can search this object with tags" ><i class="mdi mdi-information-variant mdi-18px"></i></button></div>
              </div>
             <?php if(isset($modulelist["budget"]) && !empty($modulelist["budget"])){ ?>
                  <input ng-model="fw.proj" style="display:none;" value="<?php echo $thisarray['p2'];?>">
              <?php } ?>
            <div class="form-group row">
              <label class="form-control-label text-lg-right col-md-3" >Port</label>
              <div class="col-md-9"> <input ng-model="fw.port" ng-required="true" type="text" class="form-control"></div>
            </div>
            <div class="form-group row">
              <label  class="form-control-label text-lg-right col-md-3">Source IP</label>
              <div class="col-md-9"> <input ng-model="fw.srcip" ng-required="true" type="text" class="form-control"></div>
            </div>
            <div class="form-group row">
              <label class="form-control-label text-lg-right col-md-3" >Source DNS</label>
              <div class="col-md-9"> <input ng-model="fw.srcdns" ng-required="true" type="text" class="form-control"></div>
            </div>
            <div class="form-group row">
              <label  class="form-control-label text-lg-right col-md-3">Destination IP</label>
              <div class="col-md-9"> <input ng-model="fw.destip" ng-required="true" type="text" class="form-control"></div>
            </div>
            <div class="form-group row">
              <label class="form-control-label text-lg-right col-md-3" >Destination DNS</label>
              <div class="col-md-9"> <input ng-model="fw.destdns" ng-required="true" type="text" class="form-control"></div>
            </div>
            <div class="form-group row">
              <label  class="form-control-label text-lg-right col-md-3" data-trigger="hover" data-bs-toggle="popover" data-bs-placement="right" data-html="true" data-content="Info about this rule" title="" data-original-title="Info">Comment</label>
              <div class="col-md-9"> <input ng-model="fw.info" type="text" class="form-control"></div>
            </div>
          </div>
          <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-x" xlink:href="/assets/images/icon/midleoicons.svg#i-x"/></svg>&nbsp;Close</button>
       <?php if($_SESSION['user_level']>=3){?>
            <button type="button" id="btn-create-obj" class="waves-effect waves-light btn btn-info btn-sm" ng-click="form.$valid && createfw('<?php echo $thisarray['p2'];?>','<?php echo $_SESSION['user'];?>')"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-check" xlink:href="/assets/images/icon/midleoicons.svg#i-check"/></svg>&nbsp;Create</button>
            <button type="button" id="btn-update-obj" class="waves-effect waves-light btn btn-info btn-sm" ng-click="updatefw('<?php echo $thisarray['p2'];?>','<?php echo $_SESSION['user'];?>')"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-save" xlink:href="/assets/images/icon/midleoicons.svg#i-save"/></svg>&nbsp;Save Changes</button>
            <?php } ?>
               </div>
        </form>
      </div>
    </div>
  </div>
  </div>
</div>
<?php if (method_exists("Excel", "import") && is_callable(array("Excel", "import"))) { 
  if (sessionClass::checkAcc($acclist, "fwadmin")) {
   ?>
  <div class="modal" id="modal-imp-form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="XMLUpload" >
          <div class="modal-body container">
            <div class="form-group">
              <div class="col-md-12">
                <button type="button" id="docupload" onClick="getFile('dfile')" class="btn btn-primary btn-block"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-add" xlink:href="/assets/images/icon/midleoicons.svg#i-add"/></svg>&nbsp;upload file</button>
                <div style='height: 0px;width: 0px; overflow:hidden;'><input type="file" name="dfile[]" id="dfile" onChange="sub(this,'docupload')"/></div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12">
                <ul id="fileList" class="list-unstyled"><li>No Files Selected</li></ul>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12">
                <b>Please use the correct format for the import.<br> Sample file</b> -> <a href="/data/env/samples/importfw.xlsx">Download</a>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-primary btn-sm uplwait" style="display:none;" type="button"><i class="mdi mdi-loading iconspin"></i>&nbsp;Please wait...</button>
            <button class="waves-effect waves-light btn btn-light btn-sm uplbut" type="button" style="display:none;" ng-click="uploadXMLFile('importfw','<?php echo $thisarray['p2'];?>')"><i class="mdi mdi-cloud-upload"></i>&nbsp;Import</button>
            <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-x" xlink:href="/assets/images/icon/midleoicons.svg#i-x"/></svg>&nbsp;Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  </div>
  </div>
  <?php include "public/modules/respform.php";?>



  <?php }} ?>
<?php } ?>