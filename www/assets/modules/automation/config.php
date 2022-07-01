<?php
$modulelist["automation"]["name"]="Midleo CI/CD automation";
include_once "api.php";
class Class_automation{
  public static function getPage($thisarray){
    global $installedapp;
    global $website;
	global $page;
  	global $modulelist;
    global $maindir;
    global $jobstatus;
    global $typesrv;
    global $env;
    global $typejob;
    $env=json_decode($env,true);
    if($installedapp!="yes"){ header("Location: /install"); }
    sessionClass::page_protect(base64_encode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
    $err = array();
    $msg = array();
    $pdo = pdodb::connect();
    $data=sessionClass::getSessUserData(); foreach($data as $key=>$val){  ${$key}=$val; } 
    if (!empty($env)) {$menudataenv = $env;} else { $menudataenv = array();}
     include $website['corebase']."public/modules/css.php";
     echo '<link href="/'.$website['corebase'].'assets/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />';
    echo '</head><body class="fix-header card-no-border"><div id="main-wrapper">';
    $breadcrumb["text"]="DevOps section"; 
    include $website['corebase']."public/modules/headcontent.php"; ?>
<div class="page-wrapper">
    <div class="container-fluid">
        <?php 
$brarr=array(
    array(
        "title"=>"View enabled jobs",
        "link"=>"/".$page."/".$thisarray["p1"]."/enabled",
        "icon"=>"mdi-play-circle-outline",
        "active"=>($thisarray["p2"]=="enabled" || empty($thisarray["p2"]))?"active":"",
    ),
    array(
        "title"=>"View disabled jobs",
        "link"=>"/".$page."/".$thisarray["p1"]."/disabled",
        "icon"=>"mdi-stop-circle-outline",
        "active"=>($thisarray["p2"]=="disabled")?"active":"",
      )
  );
  if (sessionClass::checkAcc($acclist, "ibmadm,ibmview")) {
    if (method_exists("IBMMQ", "execJava") && is_callable(array("IBMMQ", "execJava"))) {
    array_push($brarr,array(
      "title"=>"IBM MQ inventory jobs",
      "link"=>"/".$page."/".$thisarray["p1"]."/ibmmq",
      "icon"=>"mdi-tray-full",
      "active"=>($thisarray["p2"]=="ibmmq")?"active":"",
    ));
  }
  }

     ?>
        <?php 
        if($thisarray["p2"]=="ibmmq"){
           include "ibmmq.php";
        } else {
        if(!empty($thisarray['p1'])){ 
     $sql="select jobname,id,jobid,reqid,objname,jobtype,proj,env,deplenv,srv,jobstatus,lrun,nrun,objtype,created,jobdata from env_jobs where jobid=?";
     $q = $pdo->prepare($sql); 
     $q->execute(array($thisarray['p1']));  
     if($zobj = $q->fetch(PDO::FETCH_ASSOC)){ 
     ?>
        <div class="row pt-3">
            <div class="col-lg-2 bg-white leftsidebar">
                <?php include "public/modules/sidebar.php"; ?>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Job output</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-1 row">
                            <label class="control-label text-lg-right col-lg-3 col-6">Name</label>
                            <div class="col-lg-9 col-6">
                                <p class="form-control-static"><?php echo $zobj["jobname"];?></p>
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label class="control-label text-lg-right col-lg-3 col-6">Application</label>
                            <div class="col-lg-9 col-6">
                                <p class="form-control-static"><?php echo $zobj["proj"];?></p>
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label class="control-label text-lg-right col-lg-3 col-6">About</label>
                            <div class="col-lg-9 col-6">
                                <p class="form-control-static"><?php echo $zobj["reqid"];?></p>
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label class="control-label text-lg-right col-lg-3 col-6">Server</label>
                            <div class="col-lg-9 col-6">
                                <p class="form-control-static"><?php echo $zobj["srv"];?></p>
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label class="control-label text-lg-right col-lg-3 col-6">Environment</label>
                            <div class="col-lg-9 col-6">
                                <p class="form-control-static">
                                    <?php echo $env[array_search($zobj["env"], array_column($env, 'nameshort'))]["name"];?>
                                </p>
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label class="control-label text-lg-right col-lg-3 col-6">Server type</label>
                            <div class="col-lg-9 col-6">
                                <p class="form-control-static"><?php echo $typesrv[$zobj["deplenv"]];?></p>
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label class="control-label text-lg-right col-lg-3 col-6">Object type</label>
                            <div class="col-lg-9 col-6">
                                <p class="form-control-static"><?php echo str_replace("mqenv_","",$zobj["objtype"]);?>
                                </p>
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label class="control-label text-lg-right col-lg-3 col-6">Job created</label>
                            <div class="col-lg-9 col-6">
                                <p class="form-control-static badge badge-info p-1"><?php echo $zobj["created"];?></p>
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label class="control-label text-lg-right col-lg-3 col-6">Next run</label>
                            <div class="col-lg-9 col-6">
                                <p class="form-control-static badge badge-success p-1"><?php echo $zobj["nrun"];?></p>
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label class="control-label text-lg-right col-lg-3 col-6">Last run</label>
                            <div class="col-lg-9 col-6">
                                <p class="form-control-static badge badge-secondary p-1"><?php echo $zobj["lrun"];?></p>
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label class="control-label text-lg-right col-lg-3 col-6">Job result</label>
                            <div class="col-lg-9 col-6">
                                <pre><code><?php echo $zobj["jobdata"]?json_encode(json_decode($zobj["jobdata"],true),true|JSON_PRETTY_PRINT):"Not yet executed.";?></code></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <br>
                <h4><i class="mdi mdi-animation-play-outline"></i>&nbsp;Job status</h4>
                <br>
                <span 
                    class="badge badge-<?php echo $jobstatus[$zobj["jobstatus"]]["statcolor"];?>"><?php echo $jobstatus[$zobj["jobstatus"]]["name"];?></span>

            </div>
        </div>
        <?php 
    } else {
        textClass::PageNotFound();
     } } else {  ?>
        <div class="row pt-3">
            <div class="col-lg-2 bg-white leftsidebar">
                <?php include "public/modules/sidebar.php"; ?>
            </div>
            <div class="col-lg-10">
                <div class="row" id="ngApp" ng-app="ngApp" ng-controller="ngCtrl">
                    <div class="col-lg-9">
                        <div class="card p-0">
                            <table class="table table-vmiddle table-hover stylish-table mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width:80px;"><i class="mdi mdi-eye-outline"></i>
                                        </th>
                                        <th class="text-center">Job name</th>
                                        <th class="text-center">Object name</th>
                                        <th class="text-center">Application</th>
                                        <th class="text-center">Software</th>
                                        <th class="text-center">Server</th>
                                        <th class="text-center" style="width:110px">Status</th>
                                        <th class="text-center" style="width:120px;">Next run</th>
                                        <th class="text-center" style="width:50px;"></th>
                                    </tr>
                                </thead>
                                <tbody ng-init="getallCICD('<?php echo $thisarray["p2"];?>')">
                                    <tr ng-hide="contentLoaded">
                                        <td colspan="9" style="text-align:center;font-size:1.1em;"><i
                                                class="mdi mdi-loading iconspin"></i>&nbsp;Loading...</td>
                                    </tr>
                                    <tr id="contloaded" class="hide"
                                        dir-paginate="d in names | filter:search | orderBy:'lrun' | itemsPerPage:10"
                                        pagination-id="prodx">
                                        <td class="text-center" style="padding: .5rem;"><a
                                                href="/automation/{{ d.jobid }}"><i
                                                    class="mdi mdi-play-circle-outline"></i></a></td>
                                        <td class="text-center">{{ d.jobname }}</td>
                                        <td class="text-center">{{ d.objname }}</td>
                                        <td class="text-center">{{ d.proj }}</td>
                                        <td class="text-center">{{ d.deplenv }}</td>
                                        <td class="text-center">{{ d.srv }}</td>
                                        <td class="text-center"><span
                                                class="badge badge-{{ d.statusinfo }}">{{ d.statusinfotxt }}</span></td>
                                        <td class="text-center">{{ d.nrun }}</td>
                                        <td class="text-center"></td>

                                    </tr>
                                </tbody>
                            </table>
                            <dir-pagination-controls pagination-id="prodx" boundary-links="true"
                                on-page-change="pageChangeHandler(newPageNumber)"
                                template-url="/assets/templ/pagination.tpl.html"></dir-pagination-controls>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <?php include $website['corebase']."public/modules/filterbar.php"; ?>
                        <?php include $website['corebase']."public/modules/breadcrumbin.php"; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php } 
            }
               echo "</div>";
    include $website['corebase']."public/modules/footer.php";
    include $website['corebase']."public/modules/js.php"; 
    if(!empty($thisarray['p1'])){ } else {
       echo '<script  type="text/javascript" src="/'.$website['corebase'].'assets/js/dirPagination.js"></script><script type="text/javascript" src="/'.$website['corebase'].'assets/modules/automation/assets/js/ng-controller.js"></script>';
    }
    include $website['corebase']."public/modules/template_end.php";
    echo '</body></html>';
  }
}