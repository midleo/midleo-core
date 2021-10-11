<?php
$modulelist["monitoring"]["name"]="Midleo monitoring";
include_once "api.php";
class Class_monitoring{
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
    global $monjobprovider;
    global $monjobtype;
    global $monaltype;
    global $monjobsrv;
    if (!empty($env)) {$env = json_decode($env, true);} else { $env = array();}
    if($installedapp!="yes"){ header("Location: /install"); }
    sessionClass::page_protect(base64_encode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
    $err = array();
    $msg = array();
    $pdo = pdodb::connect();
    $data=sessionClass::getSessUserData(); foreach($data as $key=>$val){  ${$key}=$val; } 
    if(isset($_POST["savemon"])){
        $hash = textClass::getRandomStr();
        $sql="insert into mon_jobs (monid,srv,monname,appid,monprovider,montype,monaltype,env,monsoft,owner,monaemail) values(?,?,?,?,?,?,?,?,?,?,?)";
        $q = $pdo->prepare($sql);
        if($q->execute(array(
            $hash,
            htmlspecialchars($_POST["srv"]),
            htmlspecialchars($_POST["monname"]),
            htmlspecialchars($_POST["appid"]),
            htmlspecialchars($_POST["monprovider"]),
            htmlspecialchars($_POST["montype"]),
            htmlspecialchars($_POST["monaltype"]),
            htmlspecialchars($_POST["env"]),
            htmlspecialchars($_POST["monjobsrv"]),
            $_SESSION["user"],
            htmlspecialchars($_POST["monaemail"])
        ))){
            header("Location: /monitoring");
        } else{
            $err[]="Monitoring configuratio failed";
        }
    }
    include "public/modules/css.php";
    if(!empty($thisarray['p1'])){ 
        echo '<link rel="stylesheet" type="text/css" href="/assets/js/datatables/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" type="text/css" href="/assets/js/datatables/responsive.dataTables.min.css">';
        }
    echo '</head><body class="fix-header card-no-border"><div id="main-wrapper">';
    $breadcrumb["text"]="Monitoring section"; 
    include "public/modules/headcontent.php"; ?>
<div class="page-wrapper">
    <div class="container-fluid">
        <?php 
   $brarr=array(
    array(
        "title"=>"Enabled monitoring",
        "link"=>"/".$page,
        "midicon"=>"a-enabled",
        "active"=>empty($thisarray["p2"])?"active":"",
    )
  );
  if (sessionClass::checkAcc($acclist, "monview,monadm")) {
    array_push($brarr,array(
      "title"=>"Define new monitoring",
      "link"=>"/".$page."//new",
      "midicon"=>"add",
      "active"=>($thisarray["p2"]=="new")?"active":"",
    ));
  }
  include "public/modules/breadcrumb.php"; ?>
        <?php 
        if($thisarray["p2"]=="new"){ ?>
        <div class="row pt-3">
    <div class="col-lg-2">
        <?php include "public/modules/sidebar.php"; ?>
    </div>
    <div class="col-lg-10">
        <div class="row" id="ngApp" ng-app="ngApp" ng-controller="ngCtrl">
        <div class="col-lg-9">
            <form name="monform" action="" method="post">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card ngctrl">
                            <div class="card-body">

                                <?php if(!empty($_SESSION["userdata"]["apparr"])){
    $sql="select appcode,appinfo from config_app_codes where appcode in (" . str_repeat('?,', count($_SESSION["userdata"]["apparr"]) - 1) . '?' . ") ";
    $stmt = $pdo->prepare($sql);
    if($stmt->execute($_SESSION["userdata"]["apparr"])){  ?>
                                <div class="form-group row">
                                    <label class="form-control-label text-lg-left col-md-4">Monitor Name</label>
                                    <div class="col-md-8"><input required name="monname" id="monname" type="text"
                                            class="form-control" value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-control-label text-lg-left col-md-4">Application</label>
                                    <?php 
                                    $zobj = $stmt->fetchAll();
                                    if(is_array($zobj)){?>
                                    <div class="col-md-8">
                                        <select required name="appid" ng-model="mon.appid" class="form-control">
                                            <option value="">Please select</option>
                                            <?php 
    foreach($zobj as $val)  { ?>
                                            <option value="<?php echo $val['appcode'];?>">
                                                <?php echo $val['appinfo'];?></option>
                                            <?php  } ?>
                                        </select>
                                    </div>
                                    <?php } ?>
                                </div>
                                <?php if(!empty($_SESSION["userdata"]["ugrarr"])){
    $sql="select serverdns,serverip from env_servers where groupid in (" . str_repeat('?,', count($_SESSION["userdata"]["ugrarr"]) - 1) . '?' . ") ";
    $stmt = $pdo->prepare($sql);
    if($stmt->execute($_SESSION["userdata"]["ugrarr"])){  ?>
                                <div class="form-group row">
                                    <label class="form-control-label text-lg-left col-md-4">Server</label>
                                    <?php 
                                    $zobj = $stmt->fetchAll();
                                    if(is_array($zobj)){?>
                                    <div class="col-md-8">
                                        <select required name="srv" ng-model="mon.srv" class="form-control">
                                            <option value="">Please select</option>
                                            <?php 
    foreach($zobj as $val)  { ?>
                                            <option value="<?php echo $val['serverdns'];?>">
                                                <?php echo $val['serverdns']." (".$val['serverip'].")";?></option>
                                            <?php  } ?>
                                        </select>
                                    </div>
                                    <?php } ?>
                                </div>
                                <?php }} ?>
                                <div class="form-group row">
                                    <label class="form-control-label text-lg-left col-md-4">Monitoring provider</label>
                                    <?php if(is_array($monjobprovider)){?>
                                    <div class="col-md-8">
                                        <select required name="monprovider" ng-model="mon.monprov" class="form-control">
                                            <option value="">Please select</option>
                                            <?php 
    foreach($monjobprovider as $key=>$val) { ?>
                                            <option value="<?php echo $key;?>">
                                                <?php echo $val;?></option>
                                            <?php  } ?>
                                        </select>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row">
                                    <label class="form-control-label text-lg-left col-md-4">Monitoring type</label>
                                    <?php if(is_array($monjobtype)){?>
                                    <div class="col-md-8">
                                        <select required name="montype" ng-model="mon.montype" class="form-control">
                                            <option value="">Please select</option>
                                            <?php 
    foreach($monjobtype as $key=>$val) { ?>
                                            <option value="<?php echo $key;?>">
                                                <?php echo $val;?></option>
                                            <?php  } ?>
                                        </select>
                                    </div>
                                    <?php } ?>
                                </div>

                                <div class="form-group row">
                                    <label class="form-control-label text-lg-left col-md-4">Monitoring Software</label>
                                    <?php if(is_array($monjobsrv)){?>
                                    <div class="col-md-8">
                                        <select required name="monjobsrv" ng-model="mon.monjobsrv" class="form-control">
                                            <option value="">Please select</option>
                                            <?php 
    foreach($monjobsrv as $key=>$val) { ?>
                                            <option value="<?php echo $key;?>">
                                                <?php echo $val;?></option>
                                            <?php  } ?>
                                        </select>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row">
                                    <label class="form-control-label text-lg-left col-md-4">Environment</label>
                                    <?php if(is_array($env)){?>
                                    <div class="col-md-8">
                                        <select required name="env" ng-model="mon.env" class="form-control">
                                            <option value="">Please select</option>
                                            <?php 
    foreach($env as $keyenv=>$valenv) { ?>
                                            <option value="<?php echo $valenv['nameshort'];?>">
                                                <?php echo $valenv['name'];?></option>
                                            <?php  } ?>
                                        </select>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row">
                                    <label class="form-control-label text-lg-left col-md-4">Alert type</label>
                                    <?php if(is_array($monaltype)){?>
                                    <div class="col-md-8">
                                        <select required name="monaltype" ng-model="mon.monaltype" class="form-control">
                                            <option value="">Please select</option>
                                            <?php 
    foreach($monaltype as $key=>$val) { ?>
                                            <option value="<?php echo $key;?>">
                                                <?php echo $val;?></option>
                                            <?php  } ?>
                                        </select>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-8">
                                        <button type="submit" name="savemon" class="btn btn-info"><svg
                                                class="midico midico-outline">
                                                <use href="/assets/images/icon/midleoicons.svg#i-save"
                                                    xlink:href="/assets/images/icon/midleoicons.svg#i-save" />
                                            </svg>&nbsp;Save</button>
                                    </div>
                                </div>
                                <?php }} else { echo "You are not assignd to any Applications";} ?>



                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" ng-show="mon.monaltype">
                        <div class="card ngctrl">
                            <div class="card-body">

                                <div class="form-group row" ng-show="mon.monaltype=='email'">
                                    <label class="form-control-label text-lg-left col-md-4">Email for the alert</label>
                                    <div class="col-md-8">
                                        <input name="monaemail" type="text" id="tags" data-role="tagsinput"
                                            class="form-control" value="">
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </form>
            </div>
    <div class="col-md-3">
        <?php include "public/modules/breadcrumbin.php"; ?>
    </div>
    </div>


        </div>
        <?php } else {
        if(!empty($thisarray['p1'])){ 
            $sql="select monprovider,monsoft,srv from mon_jobs where monid=?";
     $q = $pdo->prepare($sql); 
     $q->execute(array($thisarray['p1']));  
     if($zobj = $q->fetch(PDO::FETCH_ASSOC)){

       if (file_exists(__DIR__ . "/views/" . $zobj["monprovider"].$zobj["monsoft"] . ".php")) { include "views/" . $zobj["monprovider"].$zobj["monsoft"] . ".php";}

     } else {
        textClass::PageNotFound();
     }
        } else { ?>
<div class="row pt-3">
    <div class="col-lg-2">
        <?php include "public/modules/sidebar.php"; ?>
    </div>
    <div class="col-lg-10">

        <div class="row" id="ngApp" ng-app="ngApp" ng-controller="ngCtrl">
           <div class="col-lg-9">
            <div class="card ngctrl p-0">
                        <table class="table table-vmiddle table-hover stylish-table mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width:80px;"><svg class="midico midico-outline">
                                            <use href="/assets/images/icon/midleoicons.svg#i-show"
                                                xlink:href="/assets/images/icon/midleoicons.svg#i-show" />
                                        </svg></th>
                                    <th class="text-center">Monitor</th>
                                    <th class="text-center">Provider</th>
                                    <th class="text-center">Type</th>
                                    <th class="text-center">Software</th>
                                    <th class="text-center">Alert type</th>
                                    <th class="text-center">Server</th>
                                    <th class="text-center" style="width:110px">Status</th>
                                    <th class="text-center" style="width:50px;"></th>
                                </tr>
                            </thead>
                            <tbody ng-init="getallMon('')">
                                <tr ng-hide="contentLoaded">
                                    <td colspan="8" style="text-align:center;font-size:1.1em;"><i
                                            class="mdi mdi-loading iconspin"></i>&nbsp;Loading...</td>
                                </tr>
                                <tr id="contloaded" class="hide"
                                    dir-paginate="d in names | filter:search | itemsPerPage:10" pagination-id="prodx">
                                    <td class="text-center" style="padding: .5rem;"><a
                                            href="/monitoring/{{ d.monid }}"><svg class="midico midico-outline">
                                                <use href="/assets/images/icon/midleoicons.svg#i-show"
                                                    xlink:href="/assets/images/icon/midleoicons.svg#i-show" />
                                            </svg></a></td>
                                    <td class="text-center">{{ d.monname }}</td>
                                    <td class="text-center">{{ d.monprovider }}</td>
                                    <td class="text-center">{{ d.montype }}</td>
                                    <td class="text-center">{{ d.monsoft }}</td>
                                    <td class="text-center">{{ d.alerttype }}</td>
                                    <td class="text-center">{{ d.srv }}</td>
                                    <td class="text-center"><span
                                            class="badge badge-{{ d.statusinfo }}">{{ d.statusinfotxt }}</span></td>
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
    <div>
      <input type="text" ng-model="search" class="form-control topsearch" placeholder="Filter">
      <span class="searchicon"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-search" xlink:href="/assets/images/icon/midleoicons.svg#i-search"/></svg>
  </div>
        <?php include "public/modules/breadcrumbin.php"; ?>
    </div>
    </div>
            </div>
        </div>
    </div>
    <?php }
    }


echo "</div>";
    include "public/modules/footer.php";
    include "public/modules/js.php"; 
    if($thisarray["p2"]=="new"){
    echo '<script src="/assets/js/tagsinput.min.js" type="text/javascript"></script>';
    }
    if(!empty($thisarray['p1'])){ } else {
       echo '<script  type="text/javascript" src="/assets/js/dirPagination.js"></script><script type="text/javascript" src="/assets/modules/monitoring/assets/js/ng-controller.js"></script>';
    }
    if(!empty($thisarray['p1'])){ 
        echo '<script src="/assets/js/datatables/jquery.dataTables.min.js"></script>
        <script src="/assets/js/datatables/dataTables.bootstrap5.min.js"></script>
        <script src="/assets/js/datatables/dataTables.fixedColumns.min.js"></script>'; ?>
    <script type="text/javascript">
    let dtable = $('#data-table').DataTable({
        "oLanguage": {
            "sSearch": ""
        },
        dom: 'Bfrtip',
        "order": [
            [6, "desc"]
        ]
    });
    $('.dtfilter').keyup(function() {
        dtable.search($(this).val()).draw();
    });
    </script>
    <?php }
    include "public/modules/template_end.php";
    echo '</body></html>';
  }
}