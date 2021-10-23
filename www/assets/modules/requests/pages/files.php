<?php
  if (!empty($thisarray["p2"])) {
   $sql="select * from requests where sname=?";
  $q = $pdo->prepare($sql);
  $q->execute(array($thisarray["p2"]));
  if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
    if(isset($_POST['addfile'])){
      $img = $_FILES['dfile'];
      if(!empty($img))
      {
        $img_desc = documentClass::FilesArange($img);
        $log="";
        if (!is_dir('data/requests/'.$thisarray["p2"])) { if (!mkdir('data/requests/'.$thisarray["p2"],0755)) { echo "Cannot create request dir<br>";}}
        foreach($img_desc as $val)
                {
                  $msg[]=documentClass::uploaddocument($val,"data/requests/".$thisarray["p2"]."/")."<br>";
                  $log.=$val['name'].",";
                }
      }
      if(!empty($_POST["tags"])){
        gTable::dbsearch($log, $_SERVER["HTTP_REFERER"], htmlspecialchars($_POST["tags"]));
      }
      gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("reqid"=>$zobj['sname'],"appid"=>"system"), "Updated request <a href='/browse/req/".$zobj['sname']."'>Uploaded files in request:".$zobj['reqname']."</a>");
  //    header("Location: /requests/files/".htmlspecialchars($thisarray["p2"])."/?");
    } 
    ?>
    
    <div class="row">
          <div class="col-md-3 position-relative">
              <input type="text" ng-model="search" class="form-control topsearch dtfilter" placeholder="Search in logs">
              <span class="searchicon"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-search" xlink:href="/assets/images/icon/midleoicons.svg#i-search"/></svg>
          </div>
  </div><br>
          <div class="row">
            <div class="col-md-8">
            <div class="card">
  <div class="card-body p-0">
            <table id="data-table" class="table table-hover stylish-table mb-0" aria-busy="false">
                <thead>
                  <tr>
                  <th class="text-start">File</th>
                  <th class="text-start">Type</th>
                    <th class="text-center" style="width:100px;">Size/Info</th>
                    <th class="text-center" style="width:160px;">Last change</th>
                  </tr>
                </thead>
                <tbody>
                <?php
  if(is_dir("data/requests/" . $thisarray["p2"])){
  $files = scandir("data/requests/" . $thisarray["p2"]);
  foreach ($files as $key => $value) {
      if (!in_array($value, array(".", ".."))) {
?>
<tr><td><a target="_blank" href="/data/requests/<?php echo $thisarray["p2"];?>/<?php echo $value;?>"><?php echo $value;?></a></td><td class="text-start"><span class="badge badge-info">uploaded document</span></td><td class="text-center"><?php echo filesize("data/requests/" . $thisarray["p2"] . "/" . $value) == 0 ? filesize("data/requests/" . $thisarray["p2"] . "/" . $value) : serverClass::fsConvert(filesize("data/requests/" . $thisarray["p2"]. "/" . $value));?></td><td class="text-center"><?php echo date("d.m.Y H:i:s", filemtime("data/requests/" . $thisarray["p2"] . "/" . $value));?></td></tr>
<?php
          
        }
  }
} ?>

<?php
     $q=gTable::read("config_diagrams","desname,desid,desdate"," where reqid='".$thisarray["p2"]."'");
    if($zobj = $q->fetchAll()){ 
      foreach($zobj as $val) { ?> 
      <tr><td><a target="_blank" href="/browse/draw/<?php echo $val["desid"];?>"><?php echo $val["desname"];?></a></td><td class="text-start"><span class="badge badge-info">Diagram</span></td><td class="text-center"></td><td class="text-center"><?php echo date("d.m.Y H:i:s",strtotime($val["desdate"]));?></td></tr>
     <?php  } 
  }  ?>

<?php
     $q=gTable::read("external_files","file_name,filetype,filelink,filedate"," where reqid='".$thisarray["p2"]."'");
    if($zobj = $q->fetchAll()){ 
      foreach($zobj as $val) { ?> 
      <tr><td><a target="_blank" href="<?php echo $val["filelink"];?>"><?php echo $val["file_name"];?></a></td><td class="text-start"><span class="badge badge-info"><?php echo $val["filetype"];?></span></td><td class="text-center"></td><td class="text-center"><?php echo date("d.m.Y H:i:s",strtotime($val["filedate"]));?></td></tr>
     <?php  } 
  }  ?>

          </tbody>
              </table>
            </div></div></div>
            <div class="col-md-4">
            <div class="card"  >
       <div class="card-body p-0" >
                <?php include "reqnav.php";?>
                </div>
        </div>
            
            </div>
          </div>
        </div>
    </div>
    <?php if($_SESSION['user_level']>=1){ ?>
    <div class="modal" id="modal-flow-form" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form action="" method="post" enctype="multipart/form-data" name="frmUpload">
            <div class="modal-body form-horizontal">
              <div class="form-group">
                <div class="col-md-12">
                  <button type="button" id="docupload" onClick="getFile('dfile')" class="btn btn-primary btn-block"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-add" xlink:href="/assets/images/icon/midleoicons.svg#i-add"/></svg>&nbsp;add file/files</button>
                  <div style='height: 0px;width: 0px; overflow:hidden;'><input required="required" type="file" name="dfile[]" id="dfile" onChange="sub(this,'docupload')" multiple=""/></div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-md-12">
                  <ul id="fileList" class="list-unstyled"><li>No Files Selected</li></ul>
                </div>
              </div>
               <div class="form-group">
                 <div class="col-md-12">
                    <label class="control-label">Tags</label>
                    <input type="text" name="tags" id="tags" value="" class="form-control" data-role="tagsinput">
                  </div>
                 </div>
            </div>
            <div class="modal-footer">
              <button type="submit" name="addfile" class="waves-effect waves-light btn btn-light btn-sm"><i class="mdi mdi-upload"></i>&nbsp;Upload</button>
              <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div style="z-index:9999;position:fixed;bottom:45px; right:24px;">
      <a data-bs-toggle="modal" class="waves-effect waves-light btn btn-primary btn-circle btnnm" href="#modal-flow-form"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-deploy" xlink:href="/assets/images/icon/midleoicons.svg#i-deploy" /></svg></a>
    </div>
    <?php } ?>
    <?php
  } else { textClass::PageNotFound();  }}
  else { textClass::PageNotFound();  } ?>
