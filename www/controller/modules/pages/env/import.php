<?php if (method_exists("Excel", "import") && is_callable(array("Excel", "import"))) { 
    array_push($brarr,array(
      "title"=>"Import from file",
      "link"=>"#modal-imp-form",
      "modal"=>true,
      "icon"=>"mdi-database-import",
      "active"=>false,
    ));
     ?>
  <div class="card ">
<div class="card-body p-0">
<div class="row"><div class="col-md-12">
  <table class="table table-vmiddle table-hover stylish-table mb-0">
    <thead>
      <tr>
        <th class="text-center">Date</th>
        <th class="text-center">File</th>
        <th class="text-center">Imported objects</th>
        <th class="text-center">Imported by</th>
      </tr>
    </thead>
    <tbody ng-init="getAllimp('<?php echo $thisarray['p2'];?>')">
      <tr ng-hide="contentLoaded"><td colspan="4" style="text-align:center;font-size:1.1em;"><i class="mdi mdi-loading iconspin"></i>&nbsp;Loading...</td></tr>
      <tr id="contloaded" class="hide" dir-paginate="d in names | filter:search | orderBy:'-impdate' | itemsPerPage:10" pagination-id="prodx">
        <td class="text-center">{{ d.impdate }}</td>
        <td class="text-center">{{ d.impfile }}</td>
        <td class="text-center">{{ d.impobjects }}</td>
        <td class="text-center">{{ d.impby }}</td>
      </tr>
    </tbody>
  </table>
  <dir-pagination-controls pagination-id="prodx" boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="/assets/templ/pagination.tpl.html"></dir-pagination-controls>
  <?php if ($_SESSION['user_level'] == "5") {?>
  <div class="modal" id="modal-imp-form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
      <div class="modal-header"><h4>Import MQ definitions</h4></div>
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
                <b>Please use the correct format for the import.<br> Sample file</b> -> <a href="/data/env/samples/import.xlsx">Download</a>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-primary btn-sm uplwait" style="display:none;" type="button"><i class="mdi mdi-loading iconspin"></i>&nbsp;Please wait...</button>
            <button class="waves-effect waves-light btn btn-light btn-sm uplbut" type="button" style="display:none;" ng-click="uploadXMLFile('importibmmq','<?php echo $thisarray['p2'];?>')"><i class="mdi mdi-cloud-upload"></i>&nbsp;Import</button>
            <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-x" xlink:href="/assets/images/icon/midleoicons.svg#i-x"/></svg>&nbsp;Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php include "public/modules/respform.php";?>
  
  <?php }?>
  </div>
</div>
</div>
</div>
<?php } else {echo "<div class='row'><div class='col-md-2'></div><div class='col-md-8'><div class='alert alert-danger text-center'>Import module not found!</div></div><div class='col-md-2'></div></div>";}?>
