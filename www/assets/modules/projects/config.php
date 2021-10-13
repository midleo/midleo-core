<?php
include_once "api.php";
include_once "templates.php";
class Class_projects
{
    public static function getPage($thisarray)
    {
        global $installedapp;
        global $website;
        global $page;
        global $projcodes;
        global $modulelist;
        if ($installedapp != "yes") {header("Location: /install");}
        sessionClass::page_protect(base64_encode("http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
        $err = array();
        $msg = array();
        $pdo = pdodb::connect();
        $year = date("Y");
        $data = sessionClass::getSessUserData();foreach ($data as $key => $val) {${$key} = $val;}
        if (!sessionClass::checkAcc($acclist, "pjm,pja,pjv")) {header("Location:/cp/?");}
        if(isset($_POST["updproj"])){
            $sql="update config_projects set tags=?,projname=?, projinfo=?, projstartdate=?, projduedate=?, budget=?, projstatus=? where projcode=?";
            $q = $pdo->prepare($sql); 
            if($q->execute(array(htmlspecialchars($_POST["tags"]),htmlspecialchars($_POST["projname"]),$_POST["projinfo"],htmlspecialchars($_POST["projstart"]),htmlspecialchars($_POST["projend"]),htmlspecialchars($_POST["projbudget"]),htmlspecialchars($_POST["projstatus"]),htmlspecialchars($_GET["pjid"])))){
                $img = $_FILES['dfile']; 
                if(!empty($img['tmp_name'][0]))
                {
                   $img_desc = documentClass::FilesArange($img);
                   if (!is_dir('data/projects/'.htmlspecialchars($_GET["pjid"]))) { if (!mkdir('data/projects/'.htmlspecialchars($_GET["pjid"]),0755)) { echo "Cannot create request dir data/projects/".htmlspecialchars($_GET["pjid"])."<br>";}}
                   foreach($img_desc as $val)
                      {
                       $msg[]=documentClass::uploaddocument($val,"data/projects/".htmlspecialchars($_GET["pjid"])."/")."<br>";
                      }
                    }         
                $msg[]="Project updated";
            } else {
                $err[]="Error updating the project";
            }
         
        }
        if(isset($_POST["projdel"])){
            $sql="delete from config_projects where projcode=? and owner=?";
            $q = $pdo->prepare($sql); 
            if($q->execute(array(htmlspecialchars($_GET["pjid"]),$_SESSION["user"]))){
                header('Location: /projects');
            } else {
                $err[]="Problem deleting the project";
            }
        }
        if(isset($_POST["addproj"])){
            $sql="insert into config_projects(tags,projcode,projname,projinfo,projstartdate,projduedate,budget,owner) values(?,?,?,?,?,?,?,?)";
            $q = $pdo->prepare($sql); 
            if($q->execute(array(
                htmlspecialchars($_POST["tags"]),
                htmlspecialchars($_POST["projcode"]),
                htmlspecialchars($_POST["projname"]),
                $_POST["projinfo"],
                htmlspecialchars($_POST["projstart"]),
                htmlspecialchars($_POST["projend"]),
                htmlspecialchars($_POST["projbudget"]),
                $_SESSION["user"]
            ))){
                $img = $_FILES['dfile']; 
                if(!empty($img['tmp_name'][0]))
                {
                   $img_desc = documentClass::FilesArange($img);
                   if (!is_dir('data/projects/'.htmlspecialchars($_POST["projcode"]))) { if (!mkdir('data/projects/'.htmlspecialchars($_POST["projcode"]),0755)) { echo "Cannot create request dir data/projects/".htmlspecialchars($_POST["projcode"])."<br>";}}
                   foreach($img_desc as $val)
                      {
                       $msg[]=documentClass::uploaddocument($val,"data/projects/".htmlspecialchars($_POST["projcode"])."/")."<br>";
                      }
                    }
                    $sql="select id,pjid from users where mainuser=?";
              $q = $pdo->prepare($sql);
              $q->execute(array($_SESSION["user"]));
              if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
                if(!empty($zobj['pjid'])){ $tmp=json_decode($zobj['pjid'],true); } else { $tmp=array(); }
                if(!is_array($tmp)){ $tmp=array(); }
                $tmp[htmlspecialchars($_POST["projcode"])]="1";
                $sql="update users set pjid=? where id=?";
                $q = $pdo->prepare($sql);
                $q->execute(array(json_encode($tmp,true),$zobj["id"]));
              }
                gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("projid"=>htmlspecialchars($_POST["projcode"]),"appid"=>"system"), "Created new project:<a href='/projects'>".htmlspecialchars($_POST["projcode"])."</a>");
                header('Location: /projects');
            } else {
                $err[]="There was a problem creating the project";
            }

        }
        include "public/modules/css.php";
        echo '<link rel="stylesheet" type="text/css" href="/assets/css/jquery-ui.min.css">';
        if (empty($thisarray["p1"])) {
            foreach ($modulelist["kanban"]["css"] as $csskey => $csslink) {
                if (!empty($csslink)) {?><link rel="stylesheet" type="text/css" href="<?php echo $csslink; ?>"><?php }
             }
            }
        echo '</head><body class="fix-header card-no-border"><div id="main-wrapper">';
        $breadcrumb["text"] = "Projects";
        $breadcrumb["midicon"] = "kanban";
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
              array_push($brarr,array(
                  "title"=>"Opened",
                  "link"=>"/".$page."//opened",
                  "midicon"=>"time-mgmt",
                  "active"=>($thisarray["p2"]=="opened" || empty($thisarray["p2"]))?"active":"",
                ));
              array_push($brarr,array(
                  "title"=>"Completed",
                  "link"=>"/".$page."//completed",
                  "midicon"=>"tasks",
                  "active"=>($thisarray["p2"]=="completed")?"active":"",
              ));
            }
            array_push($brarr,array(
              "title"=>"Service types",
              "link"=>"/smanagement//types",
              "midicon"=>"b-logic",
              "active"=>($thisarray["p2"]=="types")?"active":"",
            ));
          }
        
    
           
            ?>

<?php if($_GET["pjid"]){ 
    if($_GET["type"]=="edit"){
        $sql="select id,tags,projcode,projname,projinfo,owner,projstatus,projstartdate,projduedate,totalcost from config_projrequest where projcode=? and owner='".$_SESSION["user"]."'";
    } else {
        $sql="select id,tags,projcode,projname,projinfo,owner,projstatus,projstartdate,projduedate,totalcost from config_projrequest where projcode=?";
    }
    $q = $pdo->prepare($sql); 
    $q->execute(array(htmlspecialchars($_GET["pjid"]))); 
    if($zobj = $q->fetch(PDO::FETCH_ASSOC)){ 
    ?>
<div class="row ngctrl" id="ngApp" ng-app="ngApp" ng-controller="ngCtrl">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><?php if($_GET["type"]=="edit"){} else { ?><?php echo $zobj["projname"];?><?php } ?>
                    <?php if($_SESSION["user"]==$zobj["owner"]){?>
                    <div class="float-end">
                        <form name="projform" action="" method="post">
                            <?php if($_GET["type"]=="edit"){ ?>
                            <a href="/projects/?pjid=<?php echo $_GET["pjid"];?>" class="btn btn-light btn-sm"><svg
                                    class="midico midico-outline">
                                    <use href="/assets/images/icon/midleoicons.svg#i-check"
                                        xlink:href="/assets/images/icon/midleoicons.svg#i-check" />
                                </svg>&nbsp;Done</a>&nbsp;
                            <?php } else { ?>
                            <a href="/projects/?pjid=<?php echo $_GET["pjid"];?>&type=edit"
                                class="btn btn-light btn-sm"><svg class="midico midico-outline">
                                    <use href="/assets/images/icon/midleoicons.svg#i-edit"
                                        xlink:href="/assets/images/icon/midleoicons.svg#i-edit" />
                                </svg>&nbsp;Edit</a>&nbsp;
                            <?php } ?>
                          <!--  <button type="submit" name="projdel" class="btn btn-light btn-sm"><svg
                                    class="midico midico-outline">
                                    <use href="/assets/images/icon/midleoicons.svg#i-x"
                                        xlink:href="/assets/images/icon/midleoicons.svg#i-x" />
                                </svg>&nbsp;Delete</button>
-->
                        </form>
                    </div>
                    <?php } ?>
                </h4>
            </div>
            <div class="card-body">
                <?php if($_GET["type"]=="edit"){?>
                <form name="projform" action="" enctype="multipart/form-data" method="post">
                    <div class="form-group row">
                        <label class="form-control-label text-lg-left col-md-3">Project status</label>
                        <div class="col-md-9">

                        <div class="btn-group">
                                <?php foreach($projcodes as $key=>$val){
                                    if($key!=0 && $key!=$zobj["projstatus"]) { ?>
                                <button type="button" ng-click="projstatus('<?php echo $key;?>','/projects/?pjid=<?php echo $_GET["pjid"];?>&type=edit')" class="btn btn-light"><?php echo $val["name"];?></button>
                       <?php  }}?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-left col-md-3">Project Name</label>
                        <div class="col-md-9"><input required name="projname" type="text" class="form-control"
                                value="<?php echo $zobj["projname"];?>"></div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-left col-md-3">Project identifier</label>
                        <div class="col-md-9"><input type="text" class="form-control" disabled
                                value="<?php echo $_GET["pjid"];?>"></div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-left col-md-3">Tags</label>
                        <div class="col-md-8"><input id="tags" data-role="tagsinput" name="tags" type="text"
                                class="form-control" value="<?php echo $zobj["tags"];?>">
                        </div>
                        <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="You can search for this project with tags"><i
                                    class="mdi mdi-information-variant mdi-18px"></i></button></div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-left col-md-3">Project Description</label>
                        <div class="col-md-9"><textarea name="projinfo"
                                class="form-control textarea"><?php echo $zobj["projinfo"];?></textarea></div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-left col-md-3">Project Date</label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="text" class="form-control text-center"
                                    placeholder="Start Date" name="projstart" disabled
                                    value="Start: <?php echo $zobj["projstartdate"];?>" />
                                <input type="text" class="form-control text-center" disabled
                                    placeholder="End Date" name="projend" value="End: <?php echo $zobj["projduedate"];?>" />
                            </div>

                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-left col-md-3">Budget</label>
                        <div class="col-md-9"><label
                                class="form-control-label" style="font-weight:bold;"><?php echo $zobj["totalcost"];?>&nbsp;<?php echo $website["currency_unit"];?></label></div>
                    </div>

                    <div class="form-group row" id="reqfiles">
                        <label class="form-control-label text-lg-right col-md-3"></label>
                        <div class="col-md-9">
                            <button type="button" id="docupload" onClick="getFile('dfile')" class="btn btn-light"><svg
                                    class="midico midico-outline">
                                    <use href="/assets/images/icon/midleoicons.svg#i-add"
                                        xlink:href="/assets/images/icon/midleoicons.svg#i-add" />
                                </svg>&nbsp;Upload file/files</button>
                            <div style='height: 0px;width: 0px; overflow:hidden;'><input type="file" name="dfile[]"
                                    id="dfile" onChange="sub(this,'docupload')" multiple="" /></div>
                            <br>
                            <ul id="fileList" class="list-unstyled">
                                <li>No Files Selected</li>
                            </ul>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-left col-md-3"></label>
                        <div class="col-md-9">
                            <button type="submit" name="updproj" class="btn btn-info"><svg
                                    class="midico midico-outline">
                                    <use href="/assets/images/icon/midleoicons.svg#i-check"
                                        xlink:href="/assets/images/icon/midleoicons.svg#i-check" />
                                </svg>&nbsp;Save</button>
                        </div>
                    </div>


                </form>
                <?php } else { ?>
                <h5 class="font-size-15 mt-1 mb-2">Project Details :</h5>
                <?php echo $zobj["projinfo"];?>

                <div class="row task-dates">
                    <div class="col-md-4">
                        <div class="mt-4">
                            <h5 class="font-size-14"><i class="mdi mdi-calendar me-1 text-primary"></i> Start Date</h5>
                            <p class="mb-0">
                                <?php echo $zobj["projstartdate"]?$zobj["projstartdate"]:"Not yet started";?></p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mt-4">
                            <h5 class="font-size-14"><i class="mdi mdi-calendar me-1 text-primary"></i> Due Date
                            </h5>
                            <p class="mb-0"><?php echo $zobj["projduedate"];?></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mt-4">
                            <h5 class="font-size-14"><i class="mdi mdi-currency-eur me-1 text-primary"></i> Budget</h5>
                            <p class="mb-0"><input type="hidden" id="projbudget"
                                    value="<?php echo $zobj["totalcost"];?>"><?php echo $zobj["totalcost"];?>
                                <?php echo $website["currency_unit"];?></p>
                        </div>
                    </div>
                </div>

                <?php } ?>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h4>Owner</h4>
            </div>
            <div class="card-body  p-0">
                <?php 
$sql="select avatar,fullname,utitle,user_online,user_online_show from users where mainuser=?";
$qin = $pdo->prepare($sql); 
$qin->execute(array($zobj["owner"])); 
if($zobjin = $qin->fetch(PDO::FETCH_ASSOC)){ ?>
                <div class="contact-widget position-relative">
                    <a href="/browse/user/<?php echo $zobj["owner"];?>" target="_blank"
                        class="py-3 px-2 border-bottom d-block text-decoration-none">
                        <div class="user-img position-relative d-inline-block me-2">
                            <img src="<?php echo !empty($zobjin["avatar"])?$zobjin["avatar"] : '/assets/images/avatar.svg' ;?>"
                                width="40" alt="<?php echo $zobjin["fullname"];?>" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="<?php echo $zobjin["fullname"];?>" class="rounded-circle">
                            <span
                                class="profile-status pull-right d-inline-block position-absolute bg-<?php echo $zobjin["user_online_show"]==0?"secondary":($zobjin["user_online"]==1?"success":"danger");?> rounded-circle"></span>
                        </div>
                        <div class="mail-contnet d-inline-block align-middle">
                            <h5 class="my-1"><?php echo $zobjin["fullname"];?></h5> <span
                                class="mail-desc font-12 text-truncate overflow-hidden badge badge-info"><?php echo $zobjin["utitle"];?></span>
                        </div>
                    </a>
                </div>
                <?php } ?>

            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4>Team members</h4>
            </div>
            <div class="card-body  p-<?php echo $_GET["type"]=="edit"?"1":"0";?>">
                <?php if($_GET["type"]=="edit"){?>


                <div class="form-group row usersdiv"
                    ng-init="readpjgr('<?php echo $_GET["pjid"];?>','<?php echo $_SESSION['user'];?>')">
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="autocomplete" placeholder="Find a user">
                        <input type="text" ng-model="group.user" id="respusersselected" style="display:none;" value="">
                    </div>
                    <div class="col-md-4"> <button type="button" class="waves-effect btn btn-light btn-sm"
                            ng-click="addpjgr('<?php echo $_GET["pjid"];?>','<?php echo $_SESSION["user"];?>')"><svg
                                class="midico midico-outline">
                                <use href="/assets/images/icon/midleoicons.svg#i-add"
                                    xlink:href="/assets/images/icon/midleoicons.svg#i-add" />
                            </svg>&nbsp;Add</button> </div>
                </div>
                <div class="grudivnt grudivtg" style="display:block;">
                    <ul style="margin: 0 auto;padding: 5px;" class="list-group list-group-flush">
                        <li style="padding:5px;" ng-repeat="(ukey, user) in respusers"
                            class="list-group-item usr_{{ukey}}">{{user.uname}}<a class="float-end"
                                ng-click="delpjgr('<?php echo $_GET["pjid"];?>',ukey,'<?php echo $_SESSION['user'];?>')"
                                style="cursor:pointer;"><svg class="midico midico-outline">
                                    <use href="/assets/images/icon/midleoicons.svg#i-x"
                                        xlink:href="/assets/images/icon/midleoicons.svg#i-x" title="Delete" />
                                </svg></a></li>
                    </ul>
                </div>




                <?php } else { ?>

                <?php if($zobj["projusers"]){ ?>
                <div class="contact-widget position-relative">
                    <?php
   foreach(json_decode($zobj["projusers"],true) as $key=>$val){?>
                    <a href="/browse/user/<?php echo $key;?>" target="_blank"
                        class="py-3 px-2 border-bottom d-block text-decoration-none">
                        <div class="user-img position-relative d-inline-block me-2">
                            <img src="<?php echo !empty($val["uavatar"])?'/assets/images/users/'.$val["uavatar"] : '/assets/images/avatar.svg' ;?>"
                                width="40" alt="<?php echo $val["uname"];?>" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="<?php echo $val["uname"];?>" class="rounded-circle">
                        </div>
                        <div class="mail-contnet d-inline-block align-middle">
                            <h5 class="my-1"><?php echo $val["uname"];?></h5> <span
                                class="mail-desc font-12 text-truncate overflow-hidden badge badge-info"><?php echo $val["utitle"];?></span>
                        </div>
                    </a>
                    <?php } ?>
                </div>
                <?php } else { echo "<div class='alert'>No members yet</div>"; }  ?>
                <?php } ?>
            </div>
        </div>

    </div>
</div>
<?php if($_GET["type"]=="edit"){} else { ?>
<div class="row">
    <div class="col-md-4">
        <div class="card">

            <div class="card-body p-0">
                <?php
$sql="select r.sname,r.reqname, r.reqapp, r.reqtype, w.wfcurcost from requests r, config_workflows w where r.projnum=? and r.projapproved='1' and r.wid=w.wid";
$qin = $pdo->prepare($sql); 
$qin->execute(array(htmlspecialchars($_GET["pjid"]))); 
if($zobjin = $qin->fetchAll()){ ?>
                <div class="contact-widget position-relative">
                    <a class="py-3 px-2 border-bottom d-block text-decoration-none">
                        <div class="mail-contnet align-middle">
                            <h4 class="my-1">Total Used</h4>
                            <span class="mail-desc font-12 text-truncate overflow-hidden badge badgetotal"></span>
                            <p class="mb-0 float-end" id="budget_total"></p>
                        </div>
                    </a>
                    <?php   foreach($zobjin as $valin) { ?>
                    <a href="/reqinfo/<?php echo $valin["sname"];?>" target="_blank"
                        class="py-3 px-2 border-bottom d-block text-decoration-none">
                        <div class="mail-contnet align-middle">
                            <h5 class="my-1"><?php echo $valin["reqname"];?></h5> <span
                                class="mail-desc font-12 text-truncate overflow-hidden badge badge-info"><?php echo $valin["reqapp"];?></span>
                            <span
                                class="mail-desc font-12 text-truncate overflow-hidden badge badge-secondary"><?php echo $valin["reqtype"];?></span>
                            <p class="mb-0 float-end"><input type="hidden" class="reqsum"
                                    value="<?php echo !empty($valin["wfcurcost"])?$valin["wfcurcost"]:0;?>"><?php echo !empty($valin["wfcurcost"])?$valin["wfcurcost"]:0;?>
                                <?php echo $website["currency_unit"];?></p>
                        </div>
                    </a>

                    <?php } ?>

                </div>
                <?php }
?>

            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h4>Attached files</h4>
            </div>
            <div class="card-body p-0">

                <?php
  if(is_dir("data/projects/" . $_GET["pjid"])){ ?>
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap">
                        <thead>
                            <tr>
                                <td>Name</td>
                                <td>Size</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
  $files = scandir("data/projects/" . $_GET["pjid"]);
  foreach ($files as $key => $value) {
      if (!in_array($value, array(".", ".."))) {
?>
                            <tr>
                                <td><a target="_blank"
                                        href="/data/projects/<?php echo $_GET["pjid"];?>/<?php echo $value;?>"><?php echo $value;?></a>
                                </td>
                                <td>
                                    <?php echo filesize("data/projects/" . $_GET["pjid"] . "/" . $value) == 0 ? filesize("data/projects/" . $_GET["pjid"] . "/" . $value) : serverClass::fsConvert(filesize("data/projects/" . $_GET["pjid"]. "/" . $value));?>
                                </td>
                            </tr>
                            <?php
          
        }
  } ?>
                        </tbody>
                    </table>
                </div>
                <?php } ?>


            </div>
        </div>

    </div>
</div>
<?php } ?>
<?php } else { echo "<div class='alert alert-light'>Wrong Project ID</div>"; }} else if($_GET["type"]=="new"){?>
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Create a project
                    <div class="float-end">
                        <a href="/projects" class="btn btn-light btn-sm"><svg class="midico midico-outline">
                                <use href="/assets/images/icon/midleoicons.svg#i-x"
                                    xlink:href="/assets/images/icon/midleoicons.svg#i-x" />
                            </svg>&nbsp;Cancel</a>&nbsp;

                    </div>
                </h4>
            </div>
            <div class="card-body">
                <form name="projform" action="" enctype="multipart/form-data" method="post">
                    <div class="form-group row">
                        <label class="form-control-label text-lg-left col-md-3">Project Name</label>
                        <div class="col-md-9"><input required name="projname" id="projname" type="text"
                                class="form-control" value=""></div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-left col-md-3">Project Identifier</label>
                        <div class="col-md-9"><input required name="projcode" type="text" id="projcode"
                                class="form-control" value=""></div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-left col-md-3">Tags</label>
                        <div class="col-md-8"><input id="tags" data-role="tagsinput" name="tags" type="text"
                                class="form-control" value="">
                        </div>
                        <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="You can search this project with tags"><i
                                    class="mdi mdi-information-variant mdi-18px"></i></button></div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-left col-md-3">Project Description</label>
                        <div class="col-md-9"><textarea name="projinfo" class="form-control textarea"></textarea></div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-left col-md-3">Project Date</label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="text" class="form-control text-center date-picker-unl"
                                    placeholder="Start Date" name="projstart" id="projstart"
                                    data-toggle="datetimepicker" data-target="#projstart" value="" />
                                <input type="text" class="form-control text-center date-picker-unl"
                                    placeholder="End Date" name="projend" id="projend" data-toggle="datetimepicker"
                                    data-target="#projend" value="" />
                            </div>

                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-left col-md-3">Budget</label>
                        <div class="col-md-5"><input name="projbudget" type="number" step="0.01" class="form-control"
                                value=""></div>
                        <div class="col-md-4"><label
                                class="form-control-label"><?php echo $website["currency_unit"];?></label></div>
                    </div>


                    <div class="form-group row" id="reqfiles">
                        <label class="form-control-label text-lg-right col-md-3"></label>
                        <div class="col-md-9">
                            <button type="button" id="docupload" onClick="getFile('dfile')" class="btn btn-light"><svg
                                    class="midico midico-outline">
                                    <use href="/assets/images/icon/midleoicons.svg#i-add"
                                        xlink:href="/assets/images/icon/midleoicons.svg#i-add" />
                                </svg>&nbsp;Upload file/files</button>
                            <div style='height: 0px;width: 0px; overflow:hidden;'><input type="file" name="dfile[]"
                                    id="dfile" onChange="sub(this,'docupload')" multiple="" /></div>
                            <br>
                            <ul id="fileList" class="list-unstyled">
                                <li>No Files Selected</li>
                            </ul>
                        </div>
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
</div>
<?php } else { ?>
<div class="ngctrl" id="ngApp" ng-app="ngApp" ng-controller="ngCtrl">
    <div class="row">
        <div class="col-md-4">

        </div>
        <div class="col-md-8 text-end">

            <?php if (sessionClass::checkAcc($acclist, "pjm,pja")) { ?>
            <span data-bs-toggle="tooltip" data-bs-placement="top" title="Import Projects from Excel file"><button
                    type="button" class="waves-effect waves-light btn btn-light" data-bs-toggle="modal"
                    href="#modal-imp-form"><svg class="midico midico-outline">
                        <use href="/assets/images/icon/midleoicons.svg#i-deploy"
                            xlink:href="/assets/images/icon/midleoicons.svg#i-deploy" />
                    </svg>&nbsp;Import</button></span>
            <span data-bs-toggle="tooltip" data-bs-placement="top" title="Create new project"><a href="/projects/?type=new"
                    class="waves-effect waves-light btn btn-info"><svg class="midico midico-outline">
                        <use href="/assets/images/icon/midleoicons.svg#i-add"
                            xlink:href="/assets/images/icon/midleoicons.svg#i-add" />
                    </svg>&nbsp;Create</a></span>
            <?php } ?>
        </div>
    </div><br>

    <div class="row">
        <div class="col-md-12">
            <div id="kanban"></div>
        </div>
    </div>

</div>
</div>
</div>
<?php } ?>
<?php if (method_exists("Excel", "import") && is_callable(array("Excel", "import"))) { 
   ?>
<div class="modal" id="modal-imp-form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="XMLUpload">
                <div class="modal-body container">
                    <div class="form-group">
                        <div class="col-md-12">
                            <button type="button" id="docupload" onClick="getFile('dfile')"
                                class="btn btn-primary btn-block"><svg class="midico midico-outline">
                                    <use href="/assets/images/icon/midleoicons.svg#i-add"
                                        xlink:href="/assets/images/icon/midleoicons.svg#i-add" />
                                </svg>&nbsp;upload file</button>
                            <div style='height: 0px;width: 0px; overflow:hidden;'><input type="file" name="dfile[]"
                                    id="dfile" onChange="sub(this,'docupload')" /></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <ul id="fileList" class="list-unstyled">
                                <li>No Files Selected</li>
                            </ul>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <b>Please use the correct format for the import.<br> Sample file</b> -> <a
                                href="/data/env/samples/importpj.xlsx">Download</a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary btn-sm uplwait" style="display:none;" type="button"><i
                            class="mdi mdi-loading iconspin"></i>&nbsp;Please wait...</button>
                    <button class="waves-effect waves-light btn btn-light btn-sm uplbut" type="button"
                        style="display:none;" ng-click="uploadXMLFile('importpj','<?php echo $thisarray['p2'];?>')"><i
                            class="mdi mdi-cloud-upload"></i>&nbsp;Import</button>
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
<?php include "public/modules/respform.php";?>
<?php } ?>
</div>
</section>
<?php
include "public/modules/footer.php";
include "public/modules/js.php";?>
<?php if($_GET["type"]=="new"){?>
<script type="text/javascript">
$('#projname').keyup(function() {
    let words = this.value.toLowerCase();
    let matches = words.match(/\b(\w)/g);
    let pjcode = matches.join('');
    $('#projcode').val(pjcode.toUpperCase());
});
</script>
<?php } ?>
<?php if($_GET["pjid"]){  ?>
<script type="text/javascript">
$(document).ready(function() {
    var sum = 0;
    $('.reqsum').each(function() {
        sum += parseFloat(this.value);
    });
    var percent = (parseInt(sum) * 100 / parseInt($("#projbudget").val())).toFixed();
    if (percent < 70) {
        $(".badgetotal").addClass("badge-success");
        $(".badgetotal").html("There is still enough budget");
    } else if (percent >= 70 && percent < 95) {
        $(".badgetotal").addClass("badge-warning");
        $(".badgetotal").html("Budget is coming to its limits");
    } else {
        $(".badgetotal").addClass("badge-danger");
        $(".badgetotal").html("Not enough budget");
    }
    $("#budget_total").html(sum + " <?php echo $website["currency_unit"];?>");
});
</script>
<?php } ?>
<script src="/assets/js/tagsinput.min.js" type="text/javascript"></script>
<script src="/assets/js/dirPagination.js"></script>
<script type="text/javascript" src="/assets/modules/projects/assets/js/ng-controller.js"></script>
<script src="/assets/js/alasql.min.js"></script>
<script src="/assets/js/xlsx.core.min.js"></script>
<?php if (empty($thisarray["p1"])) { 
    if(empty($_SESSION["userdata"]["pjarr"])){ $_SESSION["userdata"]["pjarr"] = array(); $argpjarr=0; } else { $argpjarr=1;}
foreach ($modulelist["kanban"]["js"] as $jskey => $jslink) {
    if (!empty($jslink)) {?><script type="text/javascript" src="<?php echo $jslink; ?>"></script><?php }
} ?>

<script type="text/javascript">
    $('#kanban').kanban({
      <?php if (!empty($projcodes) && count($projcodes)>0) {?>
        titles: [ <?php foreach ($projcodes as $keyin => $valin) {echo "'" . $valin["name"] . "',";}?>],
        colours: [ <?php foreach ($projcodes as $keyin => $valin) {echo "'" . $valin["color"] . "',";}?>],
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
WHERE 
".(!empty($_SESSION["userdata"]["pjarr"])?"( owner='".$_SESSION["user"]."' or serviceid in (" . str_repeat('?,', count($_SESSION["userdata"]["pjarr"]) - $argpjarr) . '?' . "))":" requser=?")."
".($thisarray["p2"]=="completed"?" and projstatus='4'":" and projstatus<>'4'")."
       AND " . ((DBTYPE == "oracle" || DBTYPE == "postgresql") ? "EXTRACT(YEAR FROM created)=?" : "YEAR(created)=?");
        $q = $pdo->prepare($sql);
        if(!empty($_SESSION["userdata"]["pjarr"])){
            $q->execute(array_merge($_SESSION["userdata"]["pjarr"], array($year)));
        } else {
            $q->execute(array_merge(array($_SESSION["user"]), array($year))); 
        } 
        if ($zobj = $q->fetchAll()) { 
            $arrreq = array();
            $id = 0;
            foreach ($zobj as $val) {
              $id++;
                $arrreq[] = array(
                    "id" => $id,
                    "title" => $val["projname"],
                    "block" => (isset($val["projstatus"]) ? $projcodes[$val["projstatus"]]["name"] : "Not defined"),
                    "link" => "/projects/?pjid=" . $val["projcode"],
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
<?php } ?>
<?php
include "public/modules/template_end.php";
        echo '</body></html>';
    }
}