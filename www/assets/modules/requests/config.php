<?php
$modulelist["requests"]["name"]="Request system";
include "functions.php";
include_once "api.php";
class Class_reqinfo{
  public static function getPage($thisarray){
    global $installedapp;
    global $website;
    global $maindir;
    global $page;
    global $typeq;
    global $env;
    global $typereq;
    global $modulelist;
    global $priorityarr;
    include "pages/info.php";
  }
}
class Class_reqsearch{
  public static function getPage($thisarray){
    global $installedapp;
    global $website;
    global $maindir;
    global $page;
    global $typeq;
    global $typereq;
    global $modulelist;
    include "pages/search.php";
  }
}
class Class_reqchat{
  public static function getPage($thisarray){
    global $installedapp;
    global $website;
    global $maindir;
    global $page;
    global $typeq;
    global $typereq;
    global $modulelist;
    include "pages/chat.php";
  }
}
class Class_reqcomm{
  public static function getPage($thisarray){
    global $installedapp;
    global $website;
    global $maindir;
    global $page;
    global $typeq;
    global $typereq;
    global $modulelist;
    include "pages/comments.php";
  }
}
class Class_reqtasks{
  public static function getPage($thisarray){
    global $installedapp;
    global $website;
    global $maindir;
    global $page;
    global $typeq;
    global $typereq;
    global $modulelist;
    include "pages/subtasks.php";
  }
}
class Class_requests{
  public static function getPage($thisarray){
    global $installedapp;
    global $website;
    global $maindir;
    global $page;
    global $env;
    global $typeq;
    global $typereq;
    global $modulelist;
    if($installedapp!="yes"){ header("Location: /install"); }
    sessionClass::page_protect(base64_encode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
    $err = array();
    $msg = array();
    $pdo = pdodb::connect();
    $data=sessionClass::getSessUserData(); foreach($data as $key=>$val){  ${$key}=$val; } 
    if (!sessionClass::checkAcc($acclist, "requests")) { header("Location: /cp/?"); }
    include "public/modules/css.php";
    if (!empty($env)) {    $menudataenv = json_decode($env, true);   } else {    $menudataenv = array();   }
   if(isset($_POST['updreq'])){ $q=requestFunctions::updreq($wid,$_SESSION['user']);$err=$q["err"];  $msg=$q["msg"]; }
   if(isset($_POST['assign'])){  $q=requestFunctions::assign($wid,htmlspecialchars($_POST["reqid"])); $err=$q["err"];  $msg=$q["msg"];  }
   if(isset($_POST['savereqdata'])){ $q=requestFunctions::savereqdata($thisarray["p3"],$thisarray["p2"],$_SESSION["user"],$wid); $err=$q["err"];  $msg=$q["msg"];  }
   if($thisarray["p1"]=="type"){ echo '<link rel="stylesheet" type="text/css" href="/assets/css/jquery-ui.min.css">';   }
   if($thisarray["p1"]=="log" || $thisarray["p1"]=="files"){ ?>
  <link rel="stylesheet" type="text/css" href="/assets/js/datatables/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/js/datatables/responsive.dataTables.min.css">
<?php }  
   echo '</head><body class="fix-header card-no-border"><div id="main-wrapper">';
   if(!empty($thisarray["p1"])){  $breadcrumb["link"]="/requests"; $breadcrumb["text"]="Requests"; } else {
    if(!empty($thisarray["p1"])){
      if($thisarray["p1"]=="type"){ $breadcrumb["link"]="/reqinfo/".$thisarray["p3"]; $breadcrumb["text"]=$thisarray["p3"]; } 
      else if($thisarray["p1"]=="log" || $thisarray["p1"]=="files"){ $breadcrumb["link"]="/reqinfo/".$thisarray["p2"]; $breadcrumb["text"]=$thisarray["p2"]; } 
      else { $breadcrumb["link"]="/reqinfo/".$thisarray["p1"]; $breadcrumb["text"]=$thisarray["p1"]; }
    } else {  $breadcrumb["link"]="/requests"; $breadcrumb["text"]="Requests"; }
  }
    include "public/modules/headcontent.php";
    echo '<div class="page-wrapper"><div class="container-fluid">';
    if (sessionClass::checkAcc($acclist, "smanagementadm")) { 
      $brarr=array(
        array(
          "title"=>"Service catalog",
          "link"=>"/smanagement",
          "midicon"=>"serv-cat",
          "active"=>($page=="smanagement")?"active":"",
        )
      );
      if (sessionClass::checkAcc($acclist, "requests")) {
        array_push($brarr,array(
          "title"=>"Requests",
          "link"=>"/requests",
          "midicon"=>"requests",
          "active"=>($page=="requests")?"active":"",
        ));
      }
      if (sessionClass::checkAcc($acclist, "pjm,pja,pjv")) {
        array_push($brarr,array(
            "title"=>"Projects",
            "link"=>"/projects",
            "midicon"=>"kanban",
            "active"=>($page=="projects")?"active":"",
          ));
        array_push($brarr,array(
            "title"=>"Project Templates",
            "link"=>"/pjtemplates",
            "midicon"=>"modules",
            "active"=>($page=="pjtemplates")?"active":"",
        ));
      }
      array_push($brarr,array(
        "title"=>"Service types",
        "link"=>"/smanagement//types",
        "midicon"=>"b-logic",
        "active"=>($thisarray["p2"]=="types")?"active":"",
      ));
    }
    include "public/modules/breadcrumb.php";
    echo '<div class="row"><div class="col-12">';
    ?>
    <?php if(!empty($thisarray["p1"])){ include "pages/".$thisarray["p1"].".php";} else {  ?>
    
    

<div id="ngApp" ng-app="ngApp" ng-controller="ngCtrl">
<div class="row">
          <div class="col-md-4 position-relative">
              <input type="text" ng-model="search" class="form-control topsearch" placeholder="Find a request">
              <span class="searchicon"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-search" xlink:href="/assets/images/icon/midleoicons.svg#i-search"/></svg>
          </div><div class="col-md-8 text-end">
          <button type="button" ng-click="getAllreq('','','own')" class="btn btn-light">Mine</button>
          <button type="button" ng-click="getAllreq('<?php echo $ugr;?>','<?php echo $thisarray["p2"];?>')" class="btn btn-light">All</button>
            <a class="btn btn-light waves-effect" href="/reqsearch">
            <svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-search" xlink:href="/assets/images/icon/midleoicons.svg#i-search" /></svg>&nbsp;Advanced search
            </a>
          </div>
  </div><br>
<div class="card">
<div class="card-body p-0" >         
        
        <div class="row"><div class="col-md-12">
          <table class="table table-vmiddle table-hover stylish-table mb-0">
            <thead>
              <tr>
                <th style="width:40px"></th>
                <th class="text-center" style="width:80px;"><i class="mdi mdi-cards"></i></th>
                
                <th class="text-center" style="width:80px;">Requestor</th>
                <th class="text-center">Info</th>
                <th class="text-center" style="width:80px">Priority</th>
                <th class="text-center" style="width:80px">Step</th>
                 <th class="text-center" style="width:80px;">Assigned</th>
                <th class="text-center" style="width:80px;">About</th>
                <th class="text-center" style="width:80px;">Deadline</th>
              </tr>
            </thead>
            <tbody ng-init="getAllreq('<?php echo $ugr;?>','<?php echo $thisarray["p2"];?>')">
              <tr ng-hide="contentLoaded"><td colspan="10" style="text-align:center;font-size:1.1em;"><i class="mdi mdi-loading iconspin"></i>&nbsp;Loading...</td></tr>
              <tr id="contloaded" dir-paginate="d in names | filter:search | orderBy:'deadline' | orderBy:'status':reverse| orderBy:'-priorityval' | itemsPerPage:10"  ng-class="d.reqactive==1 ? 'hide active' : 'hide none'" pagination-id="prodx">
                <td class="text-center"><i class="{{ d.statusicon }}"></i></td>
                <td class="text-center"><a href="/reqinfo/{{ d.sname }}" target="_parent">{{ d.sname }}</a></td>
                <!--<td class="text-center"><a href="/view/{{ d.sname }}" target="_parent">{{ d.name | limitTo:2*textlimit }}{{d.name.length > 2*textlimit ? '...' : ''}}</a></td>-->
                <td class="text-center"><a href="/browse/user/{{ d.requser }}">{{ d.requser }}</a></td>
                <td class="text-center">{{ d.name | limitTo:2*textlimit }}{{d.name.length > 2*textlimit ? '...' : ''}}</td>
                <td class="text-center"><span class="badge badge-{{ d.priority.butcolor }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ d.priority.info }}" >{{ d.priority.name }}</span></td>
                <td class="text-center"><span class="badge badge-{{ d.statusinfo }}">{{ d.statusinfotxt }}</span></td>
                <td class="text-center"><a href="/browse/user/{{ d.assigned }}" ng-show="d.assigned!='done' && d.assigned!='canceled' && d.assigned!='Not yet'">{{ d.assigned }}</a><font ng-hide="d.assigned!='done' && d.assigned!='canceled' && d.assigned!='Not yet'">{{ d.assigned }}</font></td>
                <td class="text-center">{{ d.reqabout }}</td>
                <td class="text-center">{{ d.deadline }}</td>
              </tr>
            </tbody>
          </table>
          <dir-pagination-controls pagination-id="prodx" boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="/assets/templ/pagination.tpl.html"></dir-pagination-controls>
         
      
    
    
          </div>
          </div>
        </div>
        </div>
        </div>
      <?php // include "modules/respform.php";?>
 
  <?php }
  echo "</div>";
     include "public/modules/footer.php";
     echo "</div></div>";
     include "public/modules/js.php"; 
     if($thisarray["p1"]=="files" || $thisarray["p1"]=="type"){  echo '<script src="/assets/js/tagsinput.min.js" type="text/javascript"></script>'; }
    if($thisarray["p1"]=="log" || $thisarray["p1"]=="files"){?>
    <script src="/assets/js/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/js/datatables/dataTables.responsive.min.js"></script>
    <script src="/assets/js/datatables/dataTables.buttons.min.js"></script>
    <script src="/assets/js/datatables/buttons.flash.min.js"></script>
    <script src="/assets/js/datatables/jszip.min.js"></script>
    <script src="/assets/js/datatables/pdfmake.min.js"></script>
    <script src="/assets/js/datatables/vfs_fonts.js"></script>
    <script src="/assets/js/datatables/buttons.html5.min.js"></script>
    <script src="/assets/js/datatables/buttons.print.min.js"></script>
    <script>
        let dtable=$('#data-table').DataTable({
           "oLanguage": {
             "sSearch": ""
            },
            dom: 'Bfrtip',
            buttons: [ 'csv', 'excel', 'pdf', 'print'  ]
        });
        $('.dtfilter').keyup(function(){
          dtable.search($(this).val()).draw() ;
        });
    </script>

<?php } else {?>
<script src="/assets/js/dirPagination.js"></script>
<script type="text/javascript" src="/assets/modules/requests/assets/js/ng-controller.js"></script>
<?php } ?>
<?php if($thisarray["p1"]=="mq"){?>
<script src="/assets/js/alasql.min.js"></script>
<script src="/assets/js/xlsx.core.min.js"></script>
<?php }
    include "public/modules/template_end.php";
    echo '</body></html>';
  }
}