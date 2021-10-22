<?php
class Class_smanagement
{
    public static function getPage($thisarray)
    {
        global $installedapp;
        global $website;
        global $env;
        global $bsteps;
        global $page;
        global $stypes;
        global $typereq;
        global $modulelist;
        global $maindir;
        global $projcodes;
        $year=date("Y");
        if ($installedapp != "yes") {header("Location: /install");}
        sessionClass::page_protect(base64_encode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
        $err = array();
        $msg = array();
        $pdo = pdodb::connect();
        $data=sessionClass::getSessUserData(); foreach($data as $key=>$val){  ${$key}=$val; } 
        if (!is_array($website)) {$website = json_decode($website, true);}
        // if (!sessionClass::checkAcc($acclist, "smanagement")) { header("Location:/cp/?");}
       if (!empty($stypes)) { $tmp["stypes"] = json_decode($stypes, true);} else { $tmp["stypes"] = array();}
        if (isset($_POST['addwf'])) {
            $sql = "insert into config_workflows (wid,wname,wuser_updated,winfo,wowner) values(?,?,?,?,?)";
            $q = $pdo->prepare($sql);
            $q->execute(array(textClass::getRandomStr(), htmlspecialchars($_POST['wname']), $_SESSION["user"], htmlspecialchars($_POST['winfo']), $_SESSION["user"]));
            $msg[] = "Service created successfully";
        }
        if(isset($_POST["addproj"])){
           $hash = textClass::getRandomStr();
           $sql="insert into config_projrequest(tags,appcode,projcode,projname,projinfo,totalcost,servinfo,serviceid,projstartdate,projduedate,formid,owner,requser) values(?,?,?,?,?,?,?,?,?,?,?,?,?)";
           $q = $pdo->prepare($sql); 
           if($q->execute(array(
            htmlspecialchars($_POST["tags"]),
            htmlspecialchars($_POST["appcode"]),
            $hash,
            htmlspecialchars($_POST["newpjname"]),
            $_POST["newpjinfo"],
            htmlspecialchars($_POST["totalcost"]),
            $_POST["finalpjinfo"],
            $_POST["serviceid"],
            htmlspecialchars($_POST["projstart"]),
            htmlspecialchars($_POST["projend"]),
            $_POST["formids"],
            htmlspecialchars($_POST["owner"]),
            $_SESSION["user"]
           ))){
            gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>"system","projid"=>$hash), "Opened new project:<a href='/smanagement/view/".$hash."'>".htmlspecialchars($_POST["newpjname"])."</a>");
            send_mailfinal(
                $website['system_mail'],
                htmlspecialchars($_POST["owneremail"]),
                "[MidlEO] Projects: New project",
                $_SESSION["user"]." have opened a new Project,",
                "<br>Please check the next steps",
                array(
                    "User requested"=>$_SESSION["user"],
                    "Project name"=>htmlspecialchars($_POST["newpjname"]),
                    "Project start"=>htmlspecialchars($_POST["projstart"]),
                    "Project end"=>htmlspecialchars($_POST["projend"]),
                    "Total cost"=>htmlspecialchars($_POST["totalcost"])
                ),
                "full"
              );
            header('Location: /smanagement');
           } else {
               $err[]="There was a problem submitting this project. Please try again";
           }

        }
        include "public/modules/css.php";
        if (sessionClass::checkAcc($acclist, "pjm,pja,pjv")) { 
        if (!empty($thisarray["p1"])) {?>
<link rel="stylesheet" type="text/css" href="/assets/css/jquery-ui.min.css">
<link rel="stylesheet" type="text/css" href="/assets/modules/smanagement/assets/css/midleo-workflow.css">
<?php } } else {
if (empty($thisarray["p1"])) {
    foreach ($modulelist["kanban"]["css"] as $csskey => $csslink) {
        if (!empty($csslink)) {?>
<link rel="stylesheet" type="text/css" href="<?php echo $csslink; ?>"><?php }
     }
    }
} ?>
<?php 
echo '</head><body class="fix-header card-no-border"><div id="main-wrapper">';
$breadcrumb["text"] = "Service management";
include "public/modules/headcontent.php";
echo '<div class="page-wrapper"><div class="container-fluid">';
if (sessionClass::checkAcc($acclist, "pjm,pja,pjv")) {
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
} else {
    $brarr=array(
        array(
            "title"=>"View your projects",
            "link"=>"/smanagement",
            "midicon"=>"serv-cat",
            "active"=>($page=="smanagement" && empty($thisarray["p1"]))?"active":"",  
        ),
        array(
            "title"=>"Plan a project",
            "link"=>"/smanagement/new",
            "midicon"=>"edit",
            "active"=>($thisarray["p1"]=="new")?"active":"",
        )
      );
      
}
?>
<?php 

if (sessionClass::checkAcc($acclist, "pjm,pja,pjv")) {
if($thisarray["p2"]=="types"){ 
    array_push($brarr,array(
        "title"=>"Define new type",
        "link"=>"#",
        "id"=>"add-nmitemicon",
        "midicon"=>"add",
        "active"=>true,
      ));

  ?>
<div class="row pt-3">
    <div class="col-lg-2">
        <?php include "public/modules/sidebar.php"; ?>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4>Define service types</h4>
            </div>
            <div class="card-body">
                <form method="post" action="">
                    <?php if(isset($modulelist["sysnestable"]) && !empty($modulelist["sysnestable"])){ ?>
                    <?php echo SysNestable::createMenuIcon($tmp["stypes"],"1");   ?>
                    <input type="text" id="thistype" value="stypes" style="display:none;">
                    <?php } ?>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="col-md-2">
    <?php include "public/modules/breadcrumbin.php"; ?>
</div>
</div>
<?php } else {
  
if(!empty($thisarray["p1"])){ 
    array_push($brarr,array(
        "title"=>"Back",
        "link"=>"/smanagement",
        "icon"=>"mdi-arrow-left",
        "active"=>true,
      ));


  $sql="select ".(DBTYPE=='oracle'?"to_char(wdata) as wdata":"wdata").",formid,wname,winfo,wtype,wfcost,wfcurcost,wowner,haveconf,haveappr from config_workflows where wid=?";
  $q = $pdo->prepare($sql);
  $q->execute(array($thisarray["p1"]));
  if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
   $menudata=!empty($zobj["wdata"])?json_decode($zobj["wdata"],true):json_decode("[{}]",true);   
   if($zobj["wowner"]==$_SESSION["user"]){
    array_push($brarr,array(
        "title"=>"Save workflow",
        "link"=>"javascript:void(0)",
        "midicon"=>"save",
        "onclick"=>"_saveFlowchart();",
        "active"=>true,
      ));
      array_push($brarr,array(
        "title"=>"Delete workflow",
        "link"=>"javascript:void(0)",
        "midicon"=>"x",
        "onclick"=>"_delFlowchart();",
        "active"=>true,
      ));
   }
  ?>
<div class="row pt-3">
    <div class="col-lg-2">
        <?php include "public/modules/sidebar.php"; ?>
    </div>
    <div class="col-lg-8">
        <div class="card p-2" id="ngApp" ng-app="ngApp" ng-controller="ngCtrl">


            <input id="wid" name="wid" value="<?php echo $thisarray["p1"];?>" type="text" style="display:none;">

                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <input type="text" id="wname" value="<?php echo $zobj["wname"];?>"
                                    class="form-control" placeholder="Name, eg. Workflow for team X" required>
                        </div>
                        <div class="form-group">
                            <input type="text" id="winfo" value="<?php echo $zobj["winfo"];?>"
                                    class="form-control" placeholder="Information, eg. Workflow for team X" required>
                        </div>
                        <div class="form-group">
                                <select id="formid" class="form-control">
                                <option value="">Input form</option>
                                <option value="">Not necessary</option>
                                    <?php 
    foreach($typereq as $keyin=>$valin) { ?>
                                    <option value="<?php echo $keyin;?>"
                                        <?php if(!empty($keyin) && $keyin==$zobj['formid']){ echo "selected";}?>>
                                        <?php echo $valin;?></option>
                                    <?php  }
  ?>
                                </select>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-8"><input type="number" min="0" oninput="validity.valid||(value='');"
                                    id="wfcost" value="<?php echo $zobj["wfcost"];?>" class="form-control" placeholder="Efforts">
                            </div>
                            <div class="form-control-label text-lg-left col-md-4"><?php echo $website['effort_unit'];?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-8"><input type="number" min="0" oninput="validity.valid||(value='');"
                                    id="wfcurcost" value="<?php echo $zobj["wfcurcost"];?>" class="form-control" placeholder="Costs">
                            </div>
                            <div class="form-control-label text-lg-left col-md-4">
                                <?php echo $website['currency_unit'];?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="toggle-switch">
                                <input id="haveappr" name="haveappr" value="1" type="checkbox"
                                    <?php if($zobj['haveappr']==1){?> checked="checked" <?php } ?>
                                    style="display:none;">
                                <label for="haveappr" class="ts-helper">Add approval (optional)</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="toggle-switch">
                                <input id="haveconf" name="haveconf" value="1" type="checkbox"
                                    <?php if($zobj['haveconf']==1){?>checked="checked" <?php } ?> style="display:none;">
                                <label for="haveconf" class="ts-helper">Add confirmation (optional)</label>
                            </div>
                        </div>
                        
                    </div>
                    <div class="col-md-4">

                        <div class="form-group row">
                            <div class="col-md-9">
                                <select id="wtype" class="form-control">
                                    <option value="">Service type</option>
                                    <?php 
    foreach($tmp["stypes"] as $keyin=>$valin) { ?>
                                    <option value="<?php echo $valin["nameshort"];?>"
                                        <?php if(!empty($valin["nameshort"]) && $valin["nameshort"]==$zobj['wtype']){ echo "selected";}?>>
                                        <?php echo $valin["name"];?></option>
                                    <?php  }
  ?>
                                </select>
                            </div>
                            <div class="col-md-3 text-start">
                                <a href="/smanagement//types" target="_parent" class="btn btn-light"><svg
                                        class="midico midico-outline">
                                        <use href="/assets/images/icon/midleoicons.svg#i-search"
                                            xlink:href="/assets/images/icon/midleoicons.svg#i-search" />
                                    </svg></a>
                            </div>
                        </div>
                        <div class="btn-group-vertical">
                            <button type="button" class="btn btn-light text-start"
                                ng-click="readrespusr('<?php echo $thisarray["p1"];?>','<?php echo $_SESSION['user'];?>')"><svg
                                    class="midico midico-outline">
                                    <use href="/assets/images/icon/midleoicons.svg#i-groups"
                                        xlink:href="/assets/images/icon/midleoicons.svg#i-groups" />
                                </svg>&nbsp;Responsible Groups</button>
                        </div>
                    </div>
                </div>
<hr>

                    <div class="jtk-canvas canvas-wide flowchart jtk-surface  jtk-surface-nopan" id="canvas">
                    </div>
                    
                    <div class="modal" id="nmModal" tabindex="-1" role="dialog" aria-labelledby="nmmodallbl">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-body form-material">
                                        <div class="form-group row">
                                            <label class="form-control-label text-lg-right col-md-3"
                                                for="nmnameen">Name</label>
                                            <div class="col-md-9"><input type="text" class="form-control" id="nmnameen">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="form-control-label text-lg-right col-md-3"
                                                for="nmassign">Assign
                                                to</label>
                                            <div class="col-md-9"> <select class="form-control" id="nmassign"></select>
                                            </div>
                                        </div>
                                        <?php if(!empty($bsteps)){ $temp["bsteps"]=json_decode($bsteps,true); ?>
                                        <div class="form-group row">
                                            <label class="form-control-label text-lg-right col-md-3"
                                                for="bstep">Business
                                                step</label>
                                            <div class="col-md-9"> <select class="form-control" id="bstep">
                                                    <option value="">Please select</option>
                                                    <?php foreach($temp["bsteps"] as $keyin=>$valin) { ?>
                                                    <option value="<?php echo $valin["nameshort"];?>">
                                                        <?php echo $valin["name"];?></option>
                                                    <?php  } ?>
                                                </select></div>
                                        </div>
                                        <?php } ?>
                                        <?php if($zobj['haveappr']==1){ ?>
                                        <div class="form-group row">
                                            <div class="col-md-3"></div>
                                            <div class="col-md-8">
                                                <div class="toggle-switch">
                                                    <input id="checkapprove" value="1" type="checkbox"
                                                        style="display:none;">
                                                    <label for="checkapprove" class="ts-helper">Aproval
                                                        (optional)</label>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <?php if($zobj['haveconf']==1){?>
                                        <div class="form-group row">
                                            <div class="col-md-3"></div>
                                            <div class="col-md-8">
                                                <div class="toggle-switch">
                                                    <input id="canconfirm" value="1" type="checkbox"
                                                        style="display:none;">
                                                    <label for="canconfirm" class="ts-helper">Confirmation
                                                        (optional)</label>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <div class="form-group row">
                                            <div class="col-md-3"></div>
                                            <div class="col-md-8">
                                                <div class="toggle-switch">
                                                    <input id="candeploy" value="1" type="checkbox"
                                                        style="display:none;">
                                                    <label for="candeploy" class="ts-helper">Deploy packages
                                                        (optional)</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-3"></div>
                                            <div class="col-md-8">
                                                <div class="toggle-switch">
                                                    <input id="canefforts" value="1" type="checkbox"
                                                        style="display:none;">
                                                    <label for="canefforts" class="ts-helper">Can give efforts
                                                        (optional)</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-3"></div>
                                            <div class="col-md-8">
                                                <div class="toggle-switch">
                                                    <input id="canchtask" value="1" type="checkbox"
                                                        style="display:none;">
                                                    <label for="canchtask" class="ts-helper">Create change task
                                                        (optional)</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="text" value="" style="display:none;" id="nmid">
                                        <button type="button" class="btn btn-secondary btn-sm"
                                            data-bs-dismiss="modal"><svg class="midico midico-outline">
                                                <use href="/assets/images/icon/midleoicons.svg#i-x"
                                                    xlink:href="/assets/images/icon/midleoicons.svg#i-x" />
                                            </svg>&nbsp;Close</button>
                                        <button type="button" onclick="savethisnm()" class="btn btn-info btn-sm"><svg
                                                class="midico midico-outline">
                                                <use href="/assets/images/icon/midleoicons.svg#i-check"
                                                    xlink:href="/assets/images/icon/midleoicons.svg#i-check" />
                                            </svg>&nbsp;Save changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal" id="modal-user-form" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4>Responsible groups</h4>
                                    </div>
                                    <div class="modal-body form-horizontal form-material">
                                        <div class="form-group row usersdiv">
                                            <label class="form-control-label text-lg-right col-md-3">Users</label>
                                            <div class="col-md-6"><input type="text" class="form-control"
                                                    id="autocomplete">
                                                <input type="text" ng-model="group.user" id="respusersselected"
                                                    style="display:none;" value="">
                                            </div>
                                            <div class="col-md-3 mt-1"><button type="button"
                                                    class="waves-effect btn btn-info btn-sm"
                                                    ng-click="addrespusr('<?php echo $thisarray["p1"];?>','<?php echo $_SESSION["user"];?>')"><svg
                                                        class="midico midico-outline">
                                                        <use href="/assets/images/icon/midleoicons.svg#i-add"
                                                            xlink:href="/assets/images/icon/midleoicons.svg#i-add" />
                                                    </svg>&nbsp;Add</button></div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-3"></div>
                                            <div class="col-md-9">
                                                <div class="grudivnt grudivtg" style="display:block;">
                                                    <ul style="margin: 0 auto;padding: 5px;"
                                                        class="list-group list-group-flush">
                                                        <li style="width:300px;padding:5px;"
                                                            ng-repeat="(ukey, user) in respusers"
                                                            class="list-group-item usr_{{ukey}}">{{user.uname}}<a
                                                                class="float-end"
                                                                ng-click="delusrsel('<?php echo $thisarray["p1"];?>',ukey,user.type,'<?php echo $_SESSION['user'];?>')"
                                                                style="cursor:pointer;margin-bottom: 0px;line-height: inherit;"><svg
                                                                    class="midico midico-outline">
                                                                    <use href="/assets/images/icon/midleoicons.svg#i-x"
                                                                        xlink:href="/assets/images/icon/midleoicons.svg#i-x" />
                                                                </svg></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" onclick="reloadgr();" class="btn btn-secondary btn-sm"
                                            data-bs-dismiss="modal"><svg class="midico midico-outline">
                                                <use href="/assets/images/icon/midleoicons.svg#i-x"
                                                    xlink:href="/assets/images/icon/midleoicons.svg#i-x" />
                                            </svg>&nbsp;Close</button>

                                    </div>
                                </div>
                            </div>

            </div>
        </div>
    </div>
    <div class="col-md-2">
        <?php include "public/modules/breadcrumbin.php"; ?>
        <br><br>
<h4><i class="mdi mdi-gate-nand"></i>&nbsp;Logical Blocks</h4>
<br>
        <div class="jtk-canvas flowchart">
                        <input id="currentST" style="display:none;" value="<?php echo $thisarray["p1"];?>">
                        <div class="flowchartmenu list-group">
                            <a class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action window jsplumb-connected" id="startEv"><i class="mdi mdi-play-circle-outline"></i>&nbsp;Start block</a>
                            <a class="window  jsplumb-connected-step waves-effect waves-light list-group-item list-group-item-light list-group-item-action" id="stepEv"><i class="mdi mdi-step-forward"></i>&nbsp;Step block</a>
                            <a class="window  jsplumb-connected-step waves-effect waves-light list-group-item list-group-item-light list-group-item-action" id="descEv"><i class="mdi mdi-arrow-decision"></i>&nbsp;Decision block</a>
                            <a class="window  jsplumb-connected-end waves-effect waves-light list-group-item list-group-item-light list-group-item-action" id="endEv"><i class="mdi mdi-stop-circle-outline"></i>&nbsp;End block</a>
                    </div> 
      </div>
    </div>
</div>
<?php } else { textClass::PageNotFound();  } } else { 
     array_push($brarr,array(
        "title"=>"Add new service",
        "link"=>"#modalwf",
        "midicon"=>"add",
        "modal"=>true,
      ));
      
      ?>
<div class="row pt-3">
    <div class="col-lg-2">
        <?php include "public/modules/sidebar.php"; ?>
    </div>
    <div class="col-lg-10" id="ngApp" ng-app="ngApp" ng-controller="ngCtrl">
        <div class="row">
            <div class="col-lg-9">
                <ul class="row" ng-init="getAllservice('<?php echo $wid;?>')" style="padding:0 15px;">

                    <li class="col-md-4" style="text-align:center;font-size:1.1em;display:flex;"
                        ng-hide="contentLoaded">
                        <div class="card" aria-hidden="true" style="width:100%;">
                            <div class="card-body">
                                <h5 class="card-title placeholder-glow">
                                    <span class="placeholder col-6"></span>
                                </h5>
                                <p class="card-text placeholder-glow">
                                    <span class="placeholder col-7"></span>
                                    <span class="placeholder col-4"></span>
                                    <span class="placeholder col-4"></span>
                                    <span class="placeholder col-6"></span>
                                    <span class="placeholder col-8"></span>
                                </p>
                            </div>
                        </div>
                    </li>

                    <li class="col-md-4" id="contloaded" dir-paginate="d in names | filter:search | itemsPerPage:12"
                        ng-class="hide" pagination-id="prodx" style="display: flex;">
                        <div ng-click="redirect('/smanagement/'+d.wid)" class="card waves-effect waves-dark"
                            style="width:100%;">

                            <div class="card-header">
                                <h4><i class="mdi mdi-account-clock"></i> Efforts: {{ d.wfcost }}
                                    <?php echo $website["effort_unit"];?></h4>
                            </div>
                            <div class="card-body">

                                <div class="row ">
                                    <div class="col-md-12 text-start cardwf">
                                        <h5 class="font-normal">{{ d.wname }}</h5>
                                        <p class="card-text">
                                            {{ d.winfo | limitTo:4*textlimit }}{{d.winfo.length > 4*textlimit ? '...' : ''}}
                                        </p>
                                        <div class="d-flex no-block align-items-center mb-3">
                                            <span><i class="mdi mdi-calendar"></i> Last updated: {{ d.modified }}</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </li>
                </ul>



                <dir-pagination-controls pagination-id="prodx" boundary-links="true"
                    on-page-change="pageChangeHandler(newPageNumber)" template-url="/assets/templ/pagination.tpl.html">
                </dir-pagination-controls>


            </div>


            <div class="col-md-3">
            <?php include "public/modules/filterbar.php"; ?>
                <?php include "public/modules/breadcrumbin.php"; ?>
            </div>
        </div>
    </div>
</div>
</div>


<div class="modal" id="modalwf" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="">
                <div class="modal-header">
                    <h4>Add new service</h4>
                </div>
                <div class="modal-body container form-material">
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-3">Service name</label>
                        <div class="col-md-9"><input type="text" name="wname" value="" class="form-control"
                                placeholder="eg. Service for team X" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-3">Information</label>
                        <div class="col-md-9"><textarea name="winfo" value="" class="form-control"
                                placeholder="Additional information about this service"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="addwf" class="btn btn-light btn-sm"><svg class="midico midico-outline">
                            <use href="/assets/images/icon/midleoicons.svg#i-save"
                                xlink:href="/assets/images/icon/midleoicons.svg#i-save" />
                        </svg>&nbsp;Save</button>
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><svg
                            class="midico midico-outline">
                            <use href="/assets/images/icon/midleoicons.svg#i-x"
                                xlink:href="/assets/images/icon/midleoicons.svg#i-x" />
                        </svg>&nbsp;Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<?php } 
}?>

<?php } else {  


    if($thisarray["p1"]=="new"){ ?>
<form name="projform" action="" method="post" class="ngctrl" id="ngApp" ng-app="ngApp" ng-controller="ngCtrl">
    <div class="row">
        <div class="col-md-8">
            <h3 style="font-weight:bold;">Plan a New project</h3>
        </div>
        <div class="col-md-4 text-end">
            <a href="/smanagement" target="_parent" class="btn btn-outline-secondary"><svg
                    class="midico midico-outline">
                    <use href="/assets/images/icon/midleoicons.svg#i-x"
                        xlink:href="/assets/images/icon/midleoicons.svg#i-x" />
                </svg>&nbsp;Cancel</a>&nbsp;
            <span data-bs-toggle="tooltip" data-bs-placement="top" title="Submit the project"><button type="submit"
                    name="addproj" ng-disabled="!pjinfo.templcode" class="waves-effect waves-light btn btn-info"><svg
                        class="midico midico-outline">
                        <use href="/assets/images/icon/midleoicons.svg#i-check"
                            xlink:href="/assets/images/icon/midleoicons.svg#i-check" />
                    </svg>&nbsp;Submit</button></span>
        </div>
    </div><br>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 style="margin: 0 auto;">Step 1: Pick a Category</h4>
                </div>
                <div class="card-body">
                    <div class="row">

                        <?php if(!empty($tmp["stypes"])){ ?>
                        <?php
        foreach($tmp["stypes"] as $key=>$val){?>

                        <div class="col-lg-3 col-md-6">
                            <div class="card card-border catcard"
                                ng-class="(catselected=='<?php echo $val["nameshort"];?>') ? 'selected' : ''"
                                style="cursor:pointer;"
                                ng-click="catselected='<?php echo $val["nameshort"];?>';showPJTempl('<?php echo $val["nameshort"];?>')">
                                <div class="card-body" style="padding:5px;">
                                    <div class="selinfo"
                                        ng-class="(catselected=='<?php echo $val["nameshort"];?>') ? 'selected' : ''">
                                        <svg class="midico midico-outline midicoinfo">
                                            <use href="/assets/images/icon/midleoicons.svg#i-check"
                                                xlink:href="/assets/images/icon/midleoicons.svg#i-check" />
                                        </svg>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 round-lg d-inline-block text-center"
                                            style="padding-right: 0px;font-size:inherit;line-height: 58px;">
                                            <img src="/assets/images/stypes/<?php echo !empty($val["icon"])?$val["icon"]:"application";?>.svg"
                                                style="width:40px;height:40px;">
                                        </div>
                                        <div class="col-md-9 align-self-center">
                                            <?php echo $val["name"];?>
                                        </div>
                                    </div>



                                </div>
                            </div>
                        </div>



                        <?php } ?>

                        <?php } else { echo "<div class='alert alert-light'>There are still no categories defined</div>";
        }?>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header border-bottom" id="templsel">
                    <h4 style="margin: 0 auto;">Step 2: Select a Template</h4>
                </div>
                <div class="card-body" ng-show="catselected">


                    <div class="row">

                        <div class="col-md-4" style="text-align:center;font-size:1.1em;display:flex;"
                            ng-hide="contentLoaded">
                            <i class="mdi mdi-loading iconspin"></i>&nbsp;Loading...
                        </div>

                        <div class="col-md-4" id="contloaded"
                            dir-paginate="d in names | filter:search | itemsPerPage:12" ng-class="hide"
                            pagination-id="prodx" style="display: flex;">
                            <div class="card card-border catcard"
                                ng-class="(templselected==d.templcode) ? 'selected' : ''"
                                style="width:100%;cursor:pointer;" ng-click="showPJFinal(d.templcode);">
                                <div class="card-header">
                                    <h4>{{ d.templname }}</h4>
                                </div>
                                <div class="card-body pt-0 pb-1">
                                    <div class="selinfo" ng-class="(templselected==d.templcode) ? 'selected' : ''"><svg
                                            class="midico midico-outline midicoinfo">
                                            <use href="/assets/images/icon/midleoicons.svg#i-check"
                                                xlink:href="/assets/images/icon/midleoicons.svg#i-check" />
                                        </svg></div>
                                    <div class="row">
                                        <div class="col-md-12 text-start cardpj ps-4">
                                            <p class="card-text">
                                                {{ d.templinfo | limitTo:4*textlimit }}{{d.templinfo.length > 4*textlimit ? '...' : ''}}
                                            </p>
                                            <ul class="assignedto list-style-none pb-2">
                                                <li class="d-inline-block border-0 me-1"
                                                    ng-repeat="(key,val) in d.serviceid" ng-if="$index < 5">
                                                    <span class="badge"
                                                        ng-class="(key==catselected) ? 'badge-info' : 'badge-light'">{{val}}</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header border-bottom" id="projsubm">
                    <h4 style="margin: 0 auto;">Step 3: Project Details</h4>
                </div>
                <div class="card-body" ng-show="templselected">
                    <div class="row">
                        <div class="col-md-4" style="text-align:center;font-size:1.1em;display:flex;"
                            ng-hide="contentpjLoaded">
                            <i class="mdi mdi-loading iconspin"></i>&nbsp;Loading...
                        </div>
                        <div class="col-md-12" ng-show="contentpjLoaded">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label style="font-weight:bold;">Project Name</label>
                                        <input type="text" name="newpjname" ng-model="pjinfo.templname"
                                            class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label style="font-weight:bold;">Template Description</label>
                                        <textarea ui-tinymce="tinyOpts" name="newpjinfo"
                                            ng-model="pjinfo.templinfo"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label style="font-weight:bold;">Project Date</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control text-center date-picker-unl"
                                                placeholder="Start Date" name="projstart" id="projstart"
                                                data-toggle="datetimepicker" data-target="#projstart" value="" />
                                            <input type="text" class="form-control text-center date-picker-unl"
                                                placeholder="End Date" name="projend" id="projend"
                                                data-toggle="datetimepicker" data-target="#projend" value="" />
                                        </div>
                                    </div>


                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h4>Owner</h4>

                                            <div class="contact-widget position-relative">
                                                <a href="/browse/user/{{pjinfo.owner.user}}" target="_blank"
                                                    class="py-3 px-2 d-block text-decoration-none">
                                                    <div class="user-img position-relative d-inline-block me-2">
                                                        <img src="{{pjinfo.owner.avatar}}" width="40"
                                                            alt="{{pjinfo.owner.fullname}}" data-bs-toggle="tooltip"
                                                            data-bs-placement="top" title="{{pjinfo.owner.fullname}}"
                                                            class="rounded-circle">
                                                        <span
                                                            class="profile-status pull-right d-inline-block position-absolute bg-{{pjinfo.owner.user_online}} rounded-circle"></span>
                                                    </div>
                                                    <div class="mail-contnet d-inline-block align-middle">
                                                        <h5 class="my-1">{{pjinfo.owner.fullname}}</h5> <span
                                                            class="mail-desc font-12 text-truncate overflow-hidden badge badge-info">{{pjinfo.owner.utitle}}</span>
                                                    </div>
                                                </a>
                                            </div>

                                        </div>
                                        <div class="col-md-12" ng-if="pjinfo.servinfo"><br><br>
                                            <h4>Included services in this project</h4>
                                            <table class="table">
                                                <tr ng-repeat="(key,val) in pjinfo.servinfo">
                                                    <td>{{val.name}}</td>
                                                    <td class="text-end">{{val.curcost}}</td>
                                                    <td><?php echo $website["currency_unit"];?></td>
                                                </tr>
                                                <tr class="table-active">
                                                    <td>Total for this project</td>
                                                    <td class="text-end">{{pjinfo.totalcost}}</td>
                                                    <td><?php echo $website["currency_unit"];?></td>
                                                </tr>
                                            </table>
                                            <input type="text" name="totalcost" ng-model="pjinfo.totalcost"
                                                style="display:none;">
                                            <input type="text" name="owner" ng-model="pjinfo.owner.user"
                                                style="display:none;">
                                            <input type="text" name="serviceid" ng-model="pjinfo.serviceid"
                                                style="display:none;">
                                            <input type="text" name="formids" ng-model="pjinfo.formids"
                                                style="display:none;">
                                            <input type="text" name="finalpjinfo" ng-model="pjinfo.projinfo"
                                                style="display:none;">
                                            <input type="text" name="owneremail" ng-model="pjinfo.owner.email"
                                                style="display:none;">
                                            <input type="text" name="appcode" ng-model="pjinfo.appcode"
                                                style="display:none;">
                                        </div>
                                        <div class="col-md-12" ng-if="pjinfo.formid"><br><br>
                                            <h4>Additional forms in this template</h4>
                                            <div class="alert alert-light">You are required to fill additional form
                                                input once you open this project.</div>
                                        </div>
                                    </div>

                                </div>
                            </div>


                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>




    <div class="row">
        <div class="col-md-8">
        </div>
        <div class="col-md-4 text-end">
            <a href="/smanagement" target="_parent" class="btn btn-outline-secondary"><svg
                    class="midico midico-outline">
                    <use href="/assets/images/icon/midleoicons.svg#i-x"
                        xlink:href="/assets/images/icon/midleoicons.svg#i-x" />
                </svg>&nbsp;Cancel</a>&nbsp;
            <span data-bs-toggle="tooltip" data-bs-placement="top" title="Submit the project"><button type="submit"
                    name="addproj" ng-disabled="!pjinfo.templcode" class="waves-effect waves-light btn btn-info"><svg
                        class="midico midico-outline">
                        <use href="/assets/images/icon/midleoicons.svg#i-check"
                            xlink:href="/assets/images/icon/midleoicons.svg#i-check" />
                    </svg>&nbsp;Submit</button></span>
        </div>
    </div>

</form>
<?php } else {

       if(empty($_GET["pjid"])){
           echo '<div id="kanban"></div>';
       } else {
        $sql="select id,projcode,projname,".(DBTYPE=='oracle'?"to_char(projinfo) as projinfo":"projinfo").",projstatus,projduedate,projstartdate,reqinfo,serviceid,totalcost,owner from config_projrequest where projcode=?";
        $q = $pdo->prepare($sql); 
        $q->execute(array(htmlspecialchars($_GET["pjid"]))); 
        if($zobj = $q->fetch(PDO::FETCH_ASSOC)){ ?>


<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><?php echo $zobj["projname"];?>
                </h4>
            </div>
            <div class="card-body">
                <form name="projform" action="" enctype="multipart/form-data" method="post">
                    <div class="form-group row">
                        <label class="form-control-label text-lg-left col-md-3">Project Identifier</label>
                        <div class="col-md-9"><input required name="projcode" type="text" id="projcode"
                                class="form-control" value="<?php echo $zobj["projcode"];?>"></div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-left col-md-3">Tags</label>
                        <div class="col-md-8"><input id="tags" data-role="tagsinput" name="tags" type="text"
                                class="form-control" value="<?php echo $zobj["tags"];?>">
                        </div>
                        <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="You can search this project with tags"><i
                                    class="mdi mdi-information-variant mdi-18px"></i></button></div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-left col-md-3">Project Description</label>
                        <div class="col-md-9"><textarea name="projinfo"
                                class="form-control textarea"><?php echo $zobj["projinfo"];?></textarea></div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-left col-md-3">Project Dates</label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="text" class="form-control text-center" placeholder="Start Date"
                                    name="projstart" value="Start: <?php echo $zobj["projstartdate"];?>" disabled />
                                <input type="text" class="form-control text-center" placeholder="End Date"
                                    name="projend" value="End: <?php echo $zobj["projduedate"];?>" disabled />
                            </div>

                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-left col-md-3">Total cost</label>
                        <div class="col-md-9 "><label class="form-control-label"><b><?php echo $zobj["totalcost"];?></b>
                                <?php echo $website["currency_unit"];?></label></div>
                    </div>

                    <div class="form-group row">
                        <label class="form-control-label text-lg-left col-md-3"></label>
                        <div class="col-md-9">
                            <button type="submit" name="addproj" class="btn btn-info"><svg
                                    class="midico midico-outline">
                                    <use href="/assets/images/icon/midleoicons.svg#i-check"
                                        xlink:href="/assets/images/icon/midleoicons.svg#i-check" />
                                </svg>&nbsp;Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <?php if(!empty($zobj["reqinfo"])){ ?>
        <div class="card">
            <div class="card-header">
                <h4>Opened requests </h4>
            </div>
            <div class="card-body">
                <?php foreach(json_decode($zobj["reqinfo"],true) as $key=>$val){
                $sql="select sname,reqapp,reqname from requests where sname=?";
                $q = $pdo->prepare($sql);
                $q->execute(array($val));
                if($zobjin = $q->fetchAll()){ ?>
                <table class="table">
                    <?php foreach($zobjin as $valin) { ?>
                    <tr>
                        <td><a href="/browse/req/<?php echo $valin["sname"];?>"
                                target="_parent"><?php echo $valin["reqname"];?></a></td>
                        <td class="text-end"><?php echo $valin["reqapp"];?></td>
                    </tr>
                    <?php } ?>
                </table>
                <?php }
            } ?>

            </div>
        </div>

        <?php };?>
    </div>
</div>



<?php } else { echo "<div class='alert alert-light'>Wrong Project ID</div>"; }
       } 
    } 
    } ?>


<?php
include "public/modules/footer.php";
        echo "</div></div>";
        include "public/modules/js.php";
        if (sessionClass::checkAcc($acclist, "pjm,pja,pjv")) {
        if (!empty($thisarray["p1"])) {?>
<script type="text/javascript" src="/assets/modules/smanagement/assets/js/jsplumb.min.js"></script>
<script type="text/javascript" src="/assets/modules/smanagement/assets/js/midleo-workflow.js"></script>
<?php } ?>
<?php if($thisarray["p2"]=="types"){  
     if(is_dir("assets/images/stypes")){  ?>
<script type="text/javascript">
<?php
$files = scandir("assets/images/stypes");
foreach ($files as $key => $value) {
    if (!in_array($value, array(".", ".."))) {
       $arrfiles[]=basename($value,".svg");
    }
}
        ?>
var icons = [<?php foreach($arrfiles as $key=>$val){ echo '{name:"'.$val.'"},'; }?>];
var iconlist = document.getElementById("iconlist");
var content = "";
icons.forEach((obj, k) => {
    content = content + '<input name="icons" type="radio" id="radioic_' + obj.name + '" value="' + obj.name +
        '" />' +
        '<label for="radioic_' + obj.name + '"><img src="/assets/images/stypes/' + obj.name +
        '.svg" style="width:40px;height:40px;"></label>&nbsp;&nbsp;&nbsp;';
});
iconlist.innerHTML = content;
</script>
<?php } ?>
<?php } 
} else { ?>
<?php if (empty($thisarray["p1"])) { 
foreach ($modulelist["kanban"]["js"] as $jskey => $jslink) {
    if (!empty($jslink)) {?><script type="text/javascript" src="<?php echo $jslink; ?>"></script><?php }
} ?>
<script type="text/javascript">
$('#kanban').kanban({
    <?php if (!empty($projcodes) && count($projcodes)>0) {?>
    titles: [<?php foreach ($projcodes as $keyin => $valin) {echo "'" . $valin["name"] . "',";}?>],
    colours: [<?php foreach ($projcodes as $keyin => $valin) {echo "'" . $valin["color"] . "',";}?>],
    <?php } else {?>
    titles: ['Not defined'],
    colours: ['#000'],
    <?php }?>
    <?php $sql = "
SELECT config_projrequest.projcode,
config_projrequest.projname,
config_projrequest.totalcost,
config_projrequest.projstatus,
config_projrequest.owner,
        users.mainuser,
        users.email ,
        users.fullname,
        users.avatar
FROM config_projrequest
LEFT JOIN users
   ON config_projrequest.owner = users.mainuser
WHERE  requser=?
       AND " . ((DBTYPE == "oracle" || DBTYPE == "postgresql") ? "EXTRACT(YEAR FROM created)=?" : "YEAR(created)=?");
        $q = $pdo->prepare($sql);
        $q->execute(array_merge(array($_SESSION["user"]), array($year))); 
        if ($zobj = $q->fetchAll()) { 
            $arrreq = array();
            $id = 0;
            foreach ($zobj as $val) {
              $id++;
                $arrreq[] = array(
                    "id" => $id,
                    "title" => $val["projname"],
                    "block" => (isset($val["projstatus"]) ? $projcodes[$val["projstatus"]]["name"] : "Not defined"),
                    "link" => "/smanagement/?pjid=" . $val["projcode"],
                    "link_text" => $val["projcode"],
                    "footer" => "Project manager",
                    "footer_avatar" => !empty($val["avatar"]) ? $val["avatar"] : "/assets/images/avatar.svg",
                    "footer_avatar_name" => !empty($val["fullname"]) ? $val["fullname"] : "",
                    "div_color" => (isset($val["projstatus"]) ? $projcodes[$val["projstatus"]]["color"] : "#000"),
                );
            }
            echo "items: " . json_encode($arrreq);
        } else {echo "items: []";}?>
});
</script>
<?php } 
} ?>
<script type="text/javascript" src="/assets/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="/assets/js/tinymce/mentions.min.js"></script>
<script type="text/javascript" src="/assets/js/tinymce/angular.tinymce.min.js"></script>
<script src="/assets/js/dirPagination.js" type="text/javascript"></script>
<script type="text/javascript" src="/assets/modules/smanagement/assets/js/ng-controller.js"></script>
<script src="/assets/js/tagsinput.min.js" type="text/javascript"></script>
<?php 
include "public/modules/template_end.php";
echo '</body></html>';

    }
}