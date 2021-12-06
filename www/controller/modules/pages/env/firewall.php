<?php if(empty($thisarray['p2'])){ include "applist.php"; } else { 
  if (sessionClass::checkAcc($acclist, "unixadm")) {
    array_push($brarr,array(
    "title"=>"Export in excel",
    "link"=>"#",
    "nglink"=>"exportData('".$thisarray['p1']."')",
    "icon"=>"mdi-file-excel",
    "active"=>false,
  ));
  array_push($brarr,array(
    "title"=>"Import from file",
    "link"=>"#modal-imp-form",
    "modal"=>true,
    "icon"=>"mdi-database-import",
    "active"=>false,
  ));
  array_push($brarr,array(
    "title"=>"Define new",
    "link"=>"#modal-obj-form",
    "nglink"=>"showCreateFormFw()",
    "modal"=>true,
    "icon"=>"mdi-plus",
    "active"=>false,
  ));
  }
  ?>
<div class="row">
    <div class="col-lg-12 pe-0 ps-0">
        <div class="card p-0">
            <table class="table table-vmiddle table-hover stylish-table mb-0">
    <thead>
      <tr>
        <th class="text-center">Application</th>
        <th class="text-center">Port</th>
        <th class="text-center">Source IP</th>
        <th class="text-center">Destination IP</th>
        <th class="text-center">Source DNS</th>
        <th class="text-center">Destination DNS</th>
        <th class="text-center" style="width:130px;">Action</th>
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
        <div class="text-start d-grid gap-2 d-md-block">
          <button type="button" ng-click="readOnefw(d.id)" class="btn waves-effect btn-light btn-sm"><i class="mdi mdi-pencil mdi-18px"></i></button>
          <?php if($_SESSION['user_level']>=3){?><button type="button" ng-click="deletefw(d.id,'<?php echo $_SESSION['user'];?>',d.srcdns,'<?php echo $thisarray['p2'];?>')" class="btn waves-effect btn-light btn-sm"><i class="mdi mdi-close"></i></button><?php } ?>
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
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="mdi mdi-close"></i>&nbsp;Close</button>
       <?php if($_SESSION['user_level']>=3){?>
            <button type="button" id="btn-create-obj" class="waves-effect waves-light btn btn-info btn-sm" ng-click="form.$valid && createfw('<?php echo $thisarray['p2'];?>','<?php echo $_SESSION['user'];?>')"><i class="mdi mdi-check"></i>&nbsp;Create</button>
            <button type="button" id="btn-update-obj" class="waves-effect waves-light btn btn-info btn-sm" ng-click="updatefw('<?php echo $thisarray['p2'];?>','<?php echo $_SESSION['user'];?>')"><i class="mdi mdi-content-save-outline"></i>&nbsp;Save Changes</button>
            <?php } ?>
               </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php if (method_exists("Excel", "import") && is_callable(array("Excel", "import"))) { 
  if (sessionClass::checkAcc($acclist, "unixadm")) {
   ?>
  <div class="modal" id="modal-imp-form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="XMLUpload" >
          <div class="modal-body container">
            <div class="form-group">
              <div class="col-md-12">
                <button type="button" id="docupload" onClick="getFile('dfile')" class="btn btn-primary btn-block"><i class="mdi mdi-plus mdi-18px"></i>&nbsp;upload file</button>
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
            <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="mdi mdi-close"></i>&nbsp;Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  </div>
  </div>
  <?php include $website['corebase']."public/modules/respform.php";?>



  <?php }} ?>
<?php } ?>