<?php
$modulelist["ibmiib"]["name"]="IBM Integration Bus controller";
class Class_flows{
  public static function getPage($thisarray){
    global $installedapp;
    global $website;
  	global $page;
	  global $modulelist;
    global $maindir;
    if($installedapp!="yes"){ header("Location: /install"); }
    sessionClass::page_protect(base64_encode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
    $err = array();
    $msg = array();
    $pdo = pdodb::connect();
    include "public/modules/css.php";
    $data=sessionClass::getSessUserData(); foreach($data as $key=>$val){  ${$key}=$val; } 
    if (!sessionClass::checkAcc($acclist, "ibmadm,ibmview")) { header("Location: /cp/?"); }
    echo '</head><body class="card-no-border"> <div id="main-wrapper">';
    $sql="select * from iibenv_flows where flowid=? limit 1";
    $q = $pdo->prepare($sql);
    $q->execute(array($thisarray['p1']));
    $zobj = $q->fetch(PDO::FETCH_ASSOC); 
    $breadcrumb["text"]="Flows";
    $breadcrumb["text2"]=$zobj['flowname'];
    $breadcrumb["link"]="/env/flows/".$zobj['projid'];
    include "public/modules/headcontent.php"; 
    echo '<div class="page-wrapper"><div class="container-fluid">';
    include "public/modules/breadcrumb.php";
   if(!empty($thisarray['p1'])){ 
  $sql="select count(id) from iibenv_flows where flowid=?";
  $q = $pdo->prepare($sql);
  $q->execute(array($thisarray['p1'])); 
  if($q->fetchColumn()>0){
    ?>
  <?php  
    if(isset($_POST['addfile'])){
      $img = $_FILES['dfile'];
      if(!empty($img))
      {
        $img_desc = documentClass::FilesArange($img);
        $log="";
        foreach($img_desc as $val)
                {
                  $msg[]=documentClass::uploaddocument($val,"data/flows/".$thisarray['p2']."/".htmlspecialchars($thisarray['p1'])."/")."<br>";
                  $log.=$val['name'].",";
                }
      }
      gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>$thisarray['p2']=="env"?"system":$zobj['projid']), "Uploaded files in flow:<a href='/env/flows/".($thisarray['p2']=="env"?"system":$zobj['projid'])."'>".$zobj['flowname']."</a>");
      //header("Location: /flows/".htmlspecialchars($thisarray['p1'])."/".$thisarray['p2']."/?");
    } 
    ?>
    <div id="ngApp" ng-app="ngApp" ng-controller="ngCtrl">
    <div class="row">
          <div class="col-md-3 position-relative">
              <input type="text" ng-model="search" class="form-control  topsearch" placeholder="Search file name...">
              <span class="searchicon"><i class="mdi mdi-magnify"></i></span>
</div>
          <div class="col-md-9 text-end">
          <span data-bs-toggle="tooltip" data-bs-placement="top" title="Upload new files"><a  data-bs-toggle="modal"  class="waves-effect waves-light btn btn-info" href="#modal-flow-form"><i class="mdi mdi-upload"></i>&nbsp;Upload</a></span>
          </div>
          </div><br>
    <div class="card">
    <div class="card-header"><h4>File list for flow <b><?php echo $zobj['flowname'];?></b></h4></div>
      <div class="card-body p-0"> 
        
          <div class="row">
            <div class="col-md-12">
              <table class="table table-vmiddle table-hover stylish-table mb-0">
                <thead>
                  <tr>
                    <th class="text-start">File</th>
                    <th class="text-center" style="width:100px;">Size</th>
                    <th class="text-center" style="width:160px;">Last change</th>
                    <th class="text-center" style="width:60px;">Action</th>
                  </tr>
                </thead>
                <tbody ng-init="getflow('<?php echo $thisarray['p1'];?>','<?php echo $thisarray['p2'];?>')">
                  <tr ng-hide="contentLoaded"><td colspan="4" style="text-align:center;font-size:1.1em;"><i class="mdi mdi-loading iconspin"></i>&nbsp;Loading...</td></tr>
                  <tr id="contloaded" class="hide" dir-paginate="d in names | filter:search | orderBy:'name':reverse | itemsPerPage:10" pagination-id="prodx">
                    <td class="text-start"><a href="/data/flows/<?php echo $thisarray['p2'];?>/<?php echo $thisarray['p1'];?>/{{ d.file }}" download>{{ d.file | limitTo:2*textlimit }}{{d.file.length > 2*textlimit ? '...' : ''}}</a></td>
                    <td class="text-center">{{ d.size }}</td>
                    <td class="text-center">{{ d.changed }}</td>
                    <td class="text-center">
                      <?php if(sessionClass::checkAcc($acclist, "ibmadm")){ ?> 
                      <button type="button" ng-click="deleteflow('<?php echo $thisarray['p2']=="env"?"":$zobj['projid'];?>','<?php echo $_SESSION['user'];?>','<?php echo $thisarray['1'];?>',d.file,'<?php echo $thisarray['2']=="env"?"env":"requests";?>','<?php echo $zobj['flowname'];?>')" class="btn btn-light btn-sm bg waves-effect"><i class="mdi mdi-close"></i></button>
                      <?php } else {?>
                        <button type="button" class="btn btn-light btn-sm bg waves-effect"><i class="mdi mdi-close"></i></button>
                      <?php } ?>
                    </td>
                  </tr>
                </tbody>
              </table>
              <dir-pagination-controls pagination-id="prodx" boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="/assets/templ/pagination.tpl.html"></dir-pagination-controls>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php if(sessionClass::checkAcc($acclist, "ibmadm")){ ?>
    <div class="modal" id="modal-flow-form" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header"><h4>Message flow files</h4></div>
          <form action="" method="post" enctype="multipart/form-data" name="frmUpload">
            <div class="modal-body form-horizontal">
              <div class="form-group">
                <div class="col-md-12">
                  <button type="button" id="docupload" onClick="getFile('dfile')" class="btn btn-light btn-sm"><i class="mdi mdi-plus mdi-18px"></i>&nbsp;add file/files</button>
                  <div style='height: 0px;width: 0px; overflow:hidden;'><input required="required" type="file" name="dfile[]" id="dfile" onChange="sub(this,'docupload')" multiple=""/></div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-md-12">
                  <ul id="fileList" class="list-unstyled"><li>No Files Selected</li></ul>
                </div>
              </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="mdi mdi-close"></i>&nbsp;Close</button>
          <button type="submit" name="addfile" class="waves-effect waves-light btn btn-info btn-sm"><i class="mdi mdi-upload"></i>&nbsp;Upload</button>
           </div>
          </form>
        </div>
      </div>
    </div>
  
    <?php } ?>
    <?php
  } else { textClass::PageNotFound();  }}
  else { textClass::PageNotFound();  } ?>
  </div>
</div>
<?php
    include "public/modules/footer.php";
    echo "</div></div>";
    include "public/modules/js.php"; 
    echo '<script src="/assets/js/dirPagination.js"></script><script type="text/javascript" src="/assets/js/ng-controller.js"></script>';
    include "public/modules/template_end.php";
    echo '</body></html>';
  }
}