<?php
 if(isset($_POST['instmodule'])){
    $img = $_FILES['dfile'];
    $moddir="assets/modules/";
    if(!empty($img))
    {
      $img_desc = documentClass::FilesArange($img); 
      foreach($img_desc as $valimg)
              {
                $msg[]=documentClass::uploaddocument($valimg,$moddir)."<br>"; 
                $zip = new ZipArchive;
                if ($zip->open(str_replace("//", "/", $moddir.$valimg["name"])) === true) {
                   $zip->extractTo($moddir);
                   $zip->close();
                   unlink($moddir.$valimg["name"]);
               }
        if(!file_exists($moddir.str_replace(".zip", "", $valimg["name"])."/config.php") && !empty(str_replace(".zip", "", $valimg["name"]))){
          documentClass::rRD($moddir.str_replace(".zip", "", $valimg["name"]));
          $err[]="Module ".str_replace(".zip", "", $valimg["name"])." is not official MidLEO module!"; 
        } else {
          $msg[]="Module ".str_replace(".zip", "", $valimg["name"])." successfully installed"; 
        }
      }
    }  
 }
 ?>
  <div class="row">
          <div class="col-md-3 position-relative">
              <input type="text" ng-model="search" class="form-control topsearch dtfilter" placeholder="Find a module">
              <span class="searchicon"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-search" xlink:href="/assets/images/icon/midleoicons.svg#i-search"/></svg>
          </div>
          <div class="col-md-9 text-end" >
          <a data-bs-toggle="modal" class="waves-effect waves-light btn btn-info" href="#modal-module"><svg data-bs-toggle="tooltip" data-bs-placement="top" title="Install new module" class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-deploy" xlink:href="/assets/images/icon/midleoicons.svg#i-deploy" /></svg>&nbsp;Install</a>

          </div>
  </div><br>
<div class="card" id="ngApp" ng-app="ngApp" ng-controller="ngCtrl">
        <div class="card-body p-0"> 
    <form action="" method="post" enctype="multipart/form-data" name="frmUpload">
     <table id="data-table" class="table table-hover stylish-table" aria-busy="false" style="margin-top:0px!important;">
        <thead>
          <tr>
            <th data-column-id="id" data-identifier="true" data-align="center" data-header-align="center" data-width="150px">Module</th>
            <th data-column-id="info" data-align="center" data-header-align="center"  >Name</th>
             <th data-column-id="system" data-align="center" data-width="110px" data-header-align="center" >System</th>
           <th data-column-id="commands" data-formatter="commands" data-sortable="false" data-align="center" data-width="110px" data-header-align="center" >Action</th>
          </tr>
        </thead>
        <tbody><?php
 foreach($modulelist as $key=>$val){ if($key!="css" && $key!="js" && $key){
          ?><tr id="mod<?php echo $key;?>"><td><?php echo $key;?></td><td><?php echo $val["name"];?></td><td><?php echo isset($val["system"])?"*":"";?></td><td></td></tr>
          <?php }} ?>
        </tbody>
      </table>
      
      <div class="modal" id="modal-module" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-body">
            
              <div class="form-group">
              <div class="col-md-12">
                <button type="button" id="docupload" onClick="getFile('dfile')" class="btn btn-light"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-add" xlink:href="/assets/images/icon/midleoicons.svg#i-add" /></svg>&nbsp;upload file</button>
                <div style='height: 0px;width: 0px; overflow:hidden;'><input required="required" type="file" name="dfile[]" id="dfile" onChange="sub(this,'docupload')"/></div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12">
                <ul id="fileList" class="list-unstyled"><li>No Files Selected</li></ul>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12">
                <b>Please use original Midleo modules (zip file).<br>Otherwise your application will not work correctly.</b>
              </div>
            </div>
          </div>
          <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-x" xlink:href="/assets/images/icon/midleoicons.svg#i-x"/></svg>&nbsp;Close</button>
		  <button class="btn btn-info btn-sm" type="submit" name="instmodule"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-save" xlink:href="/assets/images/icon/midleoicons.svg#i-save"/></svg>&nbsp;Install</button>&nbsp;
          </div>
      </div>
    </div>
  </div>
      
    </form>
  </div> </div> 