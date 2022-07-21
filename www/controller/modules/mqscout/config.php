<?php
class Class_mqscout
{
    public static function getPage($thisarray)
    {
        global $installedapp;
        global $website;
        global $page;
        global $env;
        global $reltypes;
        global $modulelist;
        global $ibmmqchlciph;
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
            $breadcrumb["text"] = "MQ Scout - IBM MQ admin app";
            $breadcrumb["link"] = "/mqscout";
            $breadcrumb["text2"] = $thisarray['p1'];
        } else {
            $breadcrumb["text"] = "MQ Scout - IBM MQ admin app";
        }
        include $website['corebase']."public/modules/headcontent.php";
        echo '<div class="page-wrapper"><div class="container-fluid">';
        $brenvarr=array();
        $brarr=array();
       
          if (sessionClass::checkAcc($acclist, "ibmadm,ibmview")) {
            array_push($brenvarr,
            array(
              "title"=>"Qmanager definition",
              "link"=>"/".$page."/qm/".(!empty($thisarray['p2'])?$thisarray['p2']:(!empty($_SESSION["userdata"]["lastappid"])?$_SESSION["userdata"]["lastappid"]:"")),
              "icon"=>false,
              "text"=>"Qmanager",
              "active"=>($thisarray['p1'] == "qm")?"active":"",
            ),
            array(
              "title"=>"Queue definition",
              "link"=>"/".$page."/queues/".(!empty($thisarray['p2'])?$thisarray['p2']:(!empty($_SESSION["userdata"]["lastappid"])?$_SESSION["userdata"]["lastappid"]:"")),
              "icon"=>false,
              "text"=>"Queues",
              "active"=>($thisarray['p1'] == "queues")?"active":"",
            ),
            array(
              "title"=>"Channel definition",
              "link"=>"/".$page."/channels/".(!empty($thisarray['p2'])?$thisarray['p2']:(!empty($_SESSION["userdata"]["lastappid"])?$_SESSION["userdata"]["lastappid"]:"")),
              "icon"=>false,
              "text"=>"Channels",
              "active"=>($thisarray['p1'] == "channels")?"active":"",
            ),
            array(
              "title"=>"Topic definition",
              "link"=>"/".$page."/topics/".(!empty($thisarray['p2'])?$thisarray['p2']:(!empty($_SESSION["userdata"]["lastappid"])?$_SESSION["userdata"]["lastappid"]:"")),
              "icon"=>false,
              "text"=>"Topics",
              "active"=>($thisarray['p1'] == "topics")?"active":"",
            ),
            array(
              "title"=>"Other objects",
              "link"=>"#",
              "icon"=>false,
              "text"=>"Others",
              "dropdown"=>true,
              "dropdownarr"=>array(
                "/".$page."/subs/".$thisarray['p2']=>"Subscription",
                "/".$page."/process/".$thisarray['p2']=>"Process",
                "/".$page."/service/".$thisarray['p2']=>"Service",
                "/".$page."/dlqh/".$thisarray['p2']=>"DLQH",
                "/".$page."/authrec/".$thisarray['p2']=>"Authrec",
                "/".$page."/dlqh/".$thisarray['p2']=>"DLQH",
              )
            ),
            array(
              "title"=>"Import objects",
              "link"=>"/".$page."/import/".(!empty($thisarray['p2'])?$thisarray['p2']:(!empty($_SESSION["userdata"]["lastappid"])?$_SESSION["userdata"]["lastappid"]:"")),
              "icon"=>false,
              "text"=>"Import",
              "active"=>($thisarray['p1'] == "import")?"active":"",
            ),
            array(
              "title"=>"Deploy packages",
              "link"=>"/".$page."/deploy/".(!empty($thisarray['p2'])?$thisarray['p2']:(!empty($_SESSION["userdata"]["lastappid"])?$_SESSION["userdata"]["lastappid"]:"")),
              "icon"=>false,
              "text"=>"Deploy",
              "active"=>($thisarray['p1'] == "deploy")?"active":"",
            ),
            array(
              "title"=>"Create packages",
              "link"=>"/".$page."/packages/".(!empty($thisarray['p2'])?$thisarray['p2']:(!empty($_SESSION["userdata"]["lastappid"])?$_SESSION["userdata"]["lastappid"]:"")),
              "icon"=>false,
              "text"=>"Packages",
              "active"=>($thisarray['p1'] == "packages")?"active":"",
            ),
            array(
              "title"=>"IBM MQ File transfer",
              "link"=>"/".$page."/fte/".(!empty($thisarray['p2'])?$thisarray['p2']:(!empty($_SESSION["userdata"]["lastappid"])?$_SESSION["userdata"]["lastappid"]:"")),
              "icon"=>false,
              "text"=>"IBM FTE",
              "active"=>($thisarray['p1'] == "fte")?"active":"",
            ));
          }
          if (sessionClass::checkAcc($acclist, "ibmadm,ibmview")) {
            array_push($brenvarr,array(
              "title"=>"IBM ACE/IIB",
              "link"=>"/".$page."/flows/".(!empty($thisarray['p2'])?$thisarray['p2']:(!empty($_SESSION["userdata"]["lastappid"])?$_SESSION["userdata"]["lastappid"]:"")),
              "icon"=>false,
              "text"=>"IBM ACE",
              "active"=>($thisarray['p1'] == "flows")?"active":"",
            ));
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
                <?php if (file_exists(__DIR__ . "/app/" . $thisarray['p1'] . ".php")) {include "app/" . $thisarray['p1'] . ".php";} else { include "app/applist.php"; }?>
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