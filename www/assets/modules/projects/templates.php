<?php
class Class_pjtemplates
{
    public static function getPage($thisarray)
    {
        global $installedapp;
        global $website;
        global $page;
        global $projcodes;
        if ($installedapp != "yes") {header("Location: /install");}
        sessionClass::page_protect(base64_encode("http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
        $err = array();
        $msg = array();
        $pdo = pdodb::connect();
        $data = sessionClass::getSessUserData();foreach ($data as $key => $val) {${$key} = $val;}
        if (!sessionClass::checkAcc($acclist, "pjm,pja,pjv")) {header("Location:/cp/?");}
        if(isset($_POST["addproj"])){
            $hash = textClass::getRandomStr();
            $serviceid=array();
            $formid=array();
            foreach(json_decode($_POST["finaljsonobj"],true) as $key=>$val){
                if($val["type"]){ $serviceid[]=$val["type"]; }
                if($val["formid"]){ if (!in_array($val["formid"], $formid)){ $formid[]=$val["formid"]; }}
            }
            $sql="insert into config_projtempl(tags,appcode,templcode,templname,templinfo,totalcost,servinfo,formid,serviceid,owner) values(?,?,?,?,?,?,?,?,?,?)";
            $q = $pdo->prepare($sql); 
            if($q->execute(array(
                htmlspecialchars($_POST["tags"]),
                htmlspecialchars($_POST["appname"]),
                $hash,
                htmlspecialchars($_POST["templname"]),
                htmlspecialchars($_POST["templinfo"]),
                htmlspecialchars($_POST["totalcost"]),
                $_POST["finaljsonobj"],
                json_encode($formid,true),
                json_encode($serviceid,true),
                $_SESSION["user"]
            ))){
                gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>htmlspecialchars($_POST["appname"])), "Created new project template:<a href='/pjtemplates/?pjid=".$hash."'>".htmlspecialchars($_POST["templname"])."</a>");
                header('Location: /pjtemplates');
            } else {
                $err[]="There was a problem creating the template.";
            }
        }
        include "public/modules/css.php";
        echo '<link rel="stylesheet" type="text/css" href="/assets/css/jquery-ui.min.css">';
        echo '</head><body class="fix-header card-no-border"><div id="main-wrapper">';
        $breadcrumb["text"] = "Project templates";
        $breadcrumb["midicon"] = "modules";
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
        $sql="select id,appcode,templcode,templname,".(DBTYPE=='oracle'?"to_char(templinfo) as templinfo":"templinfo").",totalcost,".(DBTYPE=='oracle'?"to_char(servinfo) as servinfo":"servinfo").",serviceid, owner from config_projtempl where templcode=? and owner='".$_SESSION["user"]."'";
    } else {
        $sql="select id,appcode,templcode,templname,".(DBTYPE=='oracle'?"to_char(templinfo) as templinfo":"templinfo").",totalcost,".(DBTYPE=='oracle'?"to_char(servinfo) as servinfo":"servinfo").",serviceid, owner from config_projtempl where templcode=?";
    }
    $q = $pdo->prepare($sql); 
    $q->execute(array(htmlspecialchars($_GET["pjid"]))); 
    if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
    ?>


<?php } else { echo "<div class='alert alert-light'>Wrong Template ID</div>"; }} else if($_GET["type"]=="new"){ 
    if (sessionClass::checkAcc($acclist, "pjm,pja")) { 
        array_push($brarr,array(
            "title"=>"Cancel",
            "link"=>"/pjtemplates",
            "midicon"=>"x",
            "active"=>($page=="pjtemplates")?"active":"",
        ));
        ?>
<form name="projform" action="" method="post">

    <div class="row pt-3">
        <div class="col-lg-2">
            <?php include "public/modules/sidebar.php"; ?>
        </div>
        <div class="col-lg-8">
            <div class="row ngctrl" id="ngApp" ng-app="ngApp" ng-controller="ngCtrl">

                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">

                            <div class="form-group">
                                <input required name="templname" type="text" class="form-control"
                                    value="<?php echo $zobj["templname"];?>" placeholder="Template Name">
                            </div>
                            <div class="form-group">
                                <input type="text" id="applauto" class="form-control" required
                                    placeholder="Application name or Code" />
                                <input type="text" id="appname" name="appname" style="display:none;" />
                            </div>
                            <div class="form-group ">
                                <input id="tags" data-role="tagsinput" name="tags" type="text" class="form-control"
                                    value="<?php echo $zobj["tags"];?>" placeholder="Tags">
                            </div>
                            <div class="form-group">
                                <textarea name="templinfo" placeholder="Template Description"
                                    class="form-control textarea"><?php echo $zobj["templinfo"];?></textarea>
                            </div>

                            <br>
                            <div class="form-group">
                                <button type="submit" name="addproj" class="btn btn-light"><svg
                                        class="midico midico-outline">
                                        <use href="/assets/images/icon/midleoicons.svg#i-check"
                                            xlink:href="/assets/images/icon/midleoicons.svg#i-check" />
                                    </svg>&nbsp;Save</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <h4>Owner</h4>
                    <div class="contact-widget position-relative">
                        <a href="/browse/user/<?php echo $_SESSION["user"];?>" target="_blank"
                            class="py-3 px-2 border-bottom d-block text-decoration-none">
                            <div class="user-img position-relative d-inline-block me-2">
                                <img src="<?php echo !empty($uavatar)?$uavatar : '/assets/images/avatar.svg' ;?>"
                                    width="40" alt="<?php echo $usname;?>" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="<?php echo $usname;?>" class="rounded-circle">
                            </div>
                            <div class="mail-contnet d-inline-block align-middle">
                                <h5 class="my-1"><?php echo $usname;?></h5> <span
                                    class="mail-desc font-12 text-truncate overflow-hidden badge badge-info"><?php echo $usertitle;?></span>
                            </div>
                        </a>
                    </div>
                    <br>
                    <h4>Add services</h4>
                    <div class="form-group row">
                        <div class="col-md-9">
                            <input type="text" class="form-control autocomplservlist" placeholder="Find a service" />
                            <input type="text" name="servicetype" id="servicetype" style="display:none;" />
                            <input type="text" name="servicecost" id="servicecost" style="display:none;" />
                            <input type="text" name="servicecurcost" id="servicecurcost" style="display:none;" />
                            <input type="text" name="servicename" id="servicename" style="display:none;" />
                            <input type="text" name="serviceid" id="serviceid" style="display:none;" />
                            <input type="text" name="serviceformid" id="serviceformid" style="display:none;" />

                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-light btn-sm" ng-click="addServtoPj()"><i
                                    class="mdi mdi-plus mdi-24px"></i></button>
                        </div>

                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <table class="table">
                                <tr ng-repeat="(ukey, uval) in finalobj">
                                    <td>{{uval.name}}</td>
                                    <td>{{uval.curcost}}</td>
                                </tr>
                                <tr ng-show="total!=0" class="table-active">
                                    <td>Total:</td>
                                    <td>{{total}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <input type="text" name="totalcost" ng-model="total" style="display:none;">
                    <input type="text" name="finaljsonobj" ng-model="finaljsonobj" style="display:none;">
                </div>


            </div>
        </div>
        <div class="col-md-2">
            <?php include "public/modules/breadcrumbin.php"; ?>
        </div>
    </div>
</form>
<?php } else { echo "<div class='alert alert-light'>Only project manager or Project admin can create new project template</div>"; } } else { 
    array_push($brarr,array(
        "title"=>"Create new project template",
        "link"=>"/pjtemplates/?type=new",
        "midicon"=>"add",
        "active"=>($page=="pjtemplates")?"active":"",
    ));
    ?>
<div class="row pt-3">
    <div class="col-lg-2">
        <?php include "public/modules/sidebar.php"; ?>
    </div>
    <div class="col-lg-10">
        <div class="row" class="ngctrl" id="ngApp" ng-app="ngApp" ng-controller="ngCtrl">
            <div class="col-md-9">
                <ul class="row" ng-init="getProjTempllist('<?php echo $thisarray["p2"];?>')" style="padding:0 15px;">

                    <li class="col-lg-4" style="text-align:center;font-size:1.1em;display:flex;"
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
                        <div class="card waves-effect waves-dark" style="width:100%;">

                            <div class="card-header">
                                <h4>{{ d.templcode }}</h4>
                            </div>
                            <div class="card-body pt-0 pb-1" ng-click="redirect('/pjtemplates/?pjid='+d.templcode)"
                                style="margin-bottom:46px;">

                                <div class="row">
                                    <div class="col-md-12 text-start cardpj ps-4">
                                        <h4 class="font-normal">{{ d.templname }}</h4>
                                        <p class="card-text">
                                            {{ d.templinfo | limitTo:4*textlimit }}{{d.templinfo.length > 4*textlimit ? '...' : ''}}
                                        </p>
                                        <ul class="assignedto list-style-none pb-2">
                                            <li class="d-inline-block border-0 me-1"
                                                ng-repeat="(key,val) in d.serviceid" ng-if="$index < 5">
                                                <span class="badge badge-info">{{val}}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer border-top"
                                style="position:absolute;bottom:0;width:100%;height:46px;">
                                <ul class="list-inline mb-0">
                                    <li ng-show="d.owner=='<?php echo $_SESSION["user"];?>'" class="float-end"><a
                                            ng-click="deletepjtempl(d.id,d.templcode,'<?php echo $_SESSION["user"];?>')"><svg
                                                class="midico midico-outline">
                                                <use href="/assets/images/icon/midleoicons.svg#i-trash"
                                                    xlink:href="/assets/images/icon/midleoicons.svg#i-trash" />
                                            </svg></a></li>
                                </ul>
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
</div>

<?php } ?>
</div>
</section>
<?php
include "public/modules/footer.php";
include "public/modules/js.php";?>
<script src="/assets/js/tagsinput.min.js" type="text/javascript"></script>
<script src="/assets/js/dirPagination.js"></script>
<script type="text/javascript" src="/assets/modules/projects/assets/js/ng-controller.js"></script>
<script src="/assets/js/alasql.min.js"></script>
<script src="/assets/js/xlsx.core.min.js"></script>
<?php
include "public/modules/template_end.php";
        echo '</body></html>';


    }
}