<?php
class Class_tibcoscout
{
    public static function getPage($thisarray)
    {
        global $installedapp;
        global $website;
        global $page;
        global $env;
        global $reltypes;
        global $modulelist;
        global $maindir;
        global $typesrv;
        global $countries;
        if ($installedapp != "yes") {header("Location: /install");}
        sessionClass::page_protect(base64_encode("http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
        $err = array();
        $msg = array();
        $pdo = pdodb::connect();
        $data = sessionClass::getSessUserData();foreach ($data as $key => $val) {${$key} = $val;}
        if (!empty($env)) {$menudataenv = json_decode($env, true);} else { $menudataenv = array();}
        include $website['corebase']."public/modules/css.php";
        echo '<link rel="stylesheet" type="text/css" href="/'.$website['corebase'].'assets/css/jquery-ui.min.css">';
        echo '</head><body class="fix-header card-no-border"><div id="main-wrapper">';
        if (!empty($thisarray['p1'])) {
            $breadcrumb["text"] = "TIBCO Scout - TIBCO admin app";
            $breadcrumb["link"] = "/tibcoscout";
            $breadcrumb["text2"] = $thisarray['p1'];
        } else {
            $breadcrumb["text"] = "TIBCO Scout - TIBCO admin app";
        }
        include $website['corebase']."public/modules/headcontent.php";
        echo '<div class="page-wrapper"><div class="container-fluid">';
        $brenvarr=array();
        $brarr=array();
        if (sessionClass::checkAcc($acclist, "tibcoadm,tibcoview")) {
            array_push($brenvarr,
            array(
                "title"=>"Queue definition",
                "link"=>"/".$page."/queues/".(!empty($thisarray['p2'])?$thisarray['p2']:(!empty($_SESSION["userdata"]["lastappid"])?$_SESSION["userdata"]["lastappid"]:"")),
                "icon"=>false,
                "text"=>"Queues",
                "active"=>($thisarray['p1'] == "queues")?"active":"",
            ),
            array(
                "title"=>"Topic definition",
                "link"=>"/".$page."/topics/".(!empty($thisarray['p2'])?$thisarray['p2']:(!empty($_SESSION["userdata"]["lastappid"])?$_SESSION["userdata"]["lastappid"]:"")),
                "icon"=>false,
                "text"=>"Topics",
                "active"=>($thisarray['p1'] == "topics")?"active":"",
            ),
            array(
                "title"=>"ACL definition",
                "link"=>"/".$page."/acl/".(!empty($thisarray['p2'])?$thisarray['p2']:(!empty($_SESSION["userdata"]["lastappid"])?$_SESSION["userdata"]["lastappid"]:"")),
                "icon"=>false,
                "text"=>"ACL",
                "active"=>($thisarray['p1'] == "acl")?"active":"",
              )
        );
        }

          ?>
<div class="row pt-3">
    <div class="col-lg-2 bg-white leftsidebar">
        <?php include "public/modules/sidebar.php"; ?>
    </div>
    <div class="col-lg-10">
        <div class="row" id="ngApp" ng-app="ngApp" ng-controller="ngCtrl">
            <div class="col-md-9">
                <?php include $website['corebase']."public/modules/breadcrumb.php"; ?>
                <br class="hidden">
                <?php if (file_exists(__DIR__ . "/app/" . $thisarray['p1'] . ".php")) {include "app/" . $thisarray['p1'] . ".php";}else { include "app/applist.php"; }?>
                <?php include $website['corebase']."public/modules/respform.php";?>
                </div>
            <div class="col-md-3">
                <?php include $website['corebase']."public/modules/filterbar.php"; ?>
                <?php include $website['corebase']."public/modules/breadcrumbin.php"; ?>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</section>
<?php
include $website['corebase']."public/modules/footer.php";
include $website['corebase']."public/modules/js.php";?>
<script src="/<?php echo $website['corebase'];?>assets/js/tagsinput.min.js" type="text/javascript"></script>
<script src="/<?php echo $website['corebase'];?>assets/js/dirPagination.js"></script>
<script type="text/javascript" src="/<?php echo $website['corebase'];?>assets/js/ng-controller.js"></script>
<script src="/<?php echo $website['corebase'];?>assets/js/alasql.min.js"></script>
<script src="/<?php echo $website['corebase'];?>assets/js/xlsx.core.min.js"></script>
<?php include $website['corebase']."public/modules/template_end.php";
        echo '</body></html>';
    }
}