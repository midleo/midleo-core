<?php
class Class_env
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
        if (!sessionClass::checkAcc($acclist, "environment")) {header("Location:/cp/?");}
        if(isset($_POST["addapp"])){
          $sql="insert into config_app_codes(tags,appcode,appname,appinfo,owner) values(?,?,?,?,?)";
          $q = $pdo->prepare($sql); 
          if($q->execute(array(
            htmlspecialchars($_POST["tags"]),
            htmlspecialchars($_POST["appcode"]),
            htmlspecialchars($_POST["appname"]),
            $_POST["appinfo"],
            $_SESSION["user"]
        ))){
          $img = $_FILES['dfile']; 
          if(!empty($img['tmp_name'][0]))
          {
             $img_desc = documentClass::FilesArange($img);
             if (!is_dir('data/apps/'.htmlspecialchars($_POST["appcode"]))) { if (!mkdir('data/apps/'.htmlspecialchars($_POST["appcode"]),0755)) { echo "Cannot create request dir data/apps/".htmlspecialchars($_POST["appcode"])."<br>";}}
             foreach($img_desc as $val)
                {
                 $msg[]=documentClass::uploaddocument($val,"data/apps/".htmlspecialchars($_POST["appcode"])."/")."<br>";
                }
              }
              $sql="select id,appid from users where mainuser=?";
              $q = $pdo->prepare($sql);
              $q->execute(array($_SESSION["user"]));
              if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
                if(!empty($zobj['appid'])){ $tmp=json_decode($zobj['appid'],true); } else { $tmp=array(); }
                if(!is_array($tmp)){ $tmp=array(); }
                $tmp[htmlspecialchars($_POST["appcode"])]="1";
                $sql="update users set appid=? where id=?";
                $q = $pdo->prepare($sql);
                $q->execute(array(json_encode($tmp,true),$zobj["id"]));
              }
              gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>htmlspecialchars($_POST["appcode"])), "Created new application:<a href='/env/apps/".htmlspecialchars($_POST["appcode"])."'>".htmlspecialchars($_POST["appcode"])."</a>");
          header('Location: /env/apps');
      } else {
          $err[]="Problem creating the application";
      }

        }
        if(isset($_POST["updplace"])){
          $sql="update env_places set tags=?, placename=?, plregion=?, pltype=?".(!empty($_POST["contact"])?",plcontact='".htmlspecialchars($_POST["contact"])."'":"")." where pluid=?";
          $stmt = $pdo->prepare($sql); 
          if($stmt->execute(array(
            htmlspecialchars($_POST["tags"]),
            htmlspecialchars($_POST["place"]),
            htmlspecialchars($_POST["region"]),
            htmlspecialchars($_POST["type"]),
            htmlspecialchars($_GET["uid"])
          ))){
            $msg[]="Place updated";
          } else {
            $err[]="Error occured. Please try again.";
          }
        }
        if(isset($_POST["updrelease"])){
          $sql="update env_releases set versionmatch=?, releasename=?, relperiod=?, inpmethod=?, latestver=?, relversion=?, reltype=?".(!empty($_POST["contact"])?",relcontact='".htmlspecialchars($_POST["contact"])."'":"")." where relid=?";
          $stmt = $pdo->prepare($sql); 
          if($stmt->execute(array(
            htmlspecialchars($_POST["versionmatch"]),
            htmlspecialchars($_POST["release"]),
            htmlspecialchars($_POST["relperiod"]),
            htmlspecialchars($_POST["inpmethod"]),
            htmlspecialchars($_POST["latestver"]),
            htmlspecialchars($_POST["relversion"]),
            htmlspecialchars($_POST["reltype"]),
            htmlspecialchars($_GET["uid"])
          ))){
            $msg[]="Release updated";
          } else {
            $err[]="Error occured. Please try again.";
          }
        }
        if(isset($_POST["saverelease"])){
          $hash = textClass::getRandomStr(8);
          $sql="insert into env_releases (relid,versionmatch,releasename,relperiod,inpmethod,latestver,reltype,relcontact,relversion,created_by) values(?,?,?,?,?,?,?,?,?,?)";
          $stmt = $pdo->prepare($sql);
          if($stmt->execute(array(
              $hash,
              htmlspecialchars($_POST["versionmatch"]),
              htmlspecialchars($_POST["release"]),
              htmlspecialchars($_POST["relperiod"]),
              htmlspecialchars($_POST["inpmethod"]),
              htmlspecialchars($_POST["latestver"]),
              htmlspecialchars($_POST["reltype"]),
              htmlspecialchars($_POST["contact"]),
              htmlspecialchars($_POST["relversion"]),
              $_SESSION["user"]
          ))){
              gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => "system"), "Defined new release:<a href='/env/release//?type=edit&uid=" . $hash . "'>" . htmlspecialchars($_POST["release"]) . "</a>");
              $msg[]="Release added";
          } else {
              $err[]="Error occured. Please try again.";
          }
          header("Location:/env/release/");
        }
        if(isset($_POST["saveplace"])){
          $hash = textClass::getRandomStr(16);
          $sql="insert into env_places (tags,placename,plregion,plcity,pltype,pluid,plcontact,created_by) values(?,?,?,?,?,?,?,?)";
          $stmt = $pdo->prepare($sql);
          if($stmt->execute(array(
              htmlspecialchars($_POST["tags"]),
              htmlspecialchars($_POST["place"]),
              htmlspecialchars($_POST["region"]),
              htmlspecialchars($_POST["city"]),
              htmlspecialchars($_POST["type"]),
              $hash,
              htmlspecialchars($_POST["contact"]),
              $_SESSION["user"]
          ))){
              gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => "system"), "Defined new place:<a href='/env/places//?type=edit&uid=" . $hash . "'>" . htmlspecialchars($_POST["place"]) . "</a>");
              $msg[]="Place added";
          } else {
              $err[]="Error occured. Please try again.";
          }
          header("Location:/env/places/");
        }
        if (!empty($env)) {$menudataenv = json_decode($env, true);} else { $menudataenv = array();}
        include $website['corebase']."public/modules/css.php";
        echo '<link rel="stylesheet" type="text/css" href="/'.$website['corebase'].'assets/css/jquery-ui.min.css">';
        echo '</head><body class="fix-header card-no-border"><div id="main-wrapper">';
        if (!empty($thisarray['p1'])) {
          $breadcrumb["text"] = "Environment";
          $breadcrumb["link"] = "/env/apps";
          $breadcrumb["text2"] = $thisarray['p1'];
      } else {
          $breadcrumb["text"] = "Environment";
      }
        include $website['corebase']."public/modules/headcontent.php";
        echo '<div class="page-wrapper"><div class="container-fluid">';
        $brenvarr=array();
        $brarr=array();
        if (sessionClass::checkAcc($acclist, "appadm,appview")) {
          array_push($brenvarr,array(
            "title"=>"Applications",
            "link"=>"/".$page."/apps",
            "icon"=>false,
            "text"=>(!empty($thisarray['p2']) && $thisarray['p2']!="?type=new")?"&nbsp;".$thisarray['p2']:"Apps",
            "main"=>true,
            "active"=>($thisarray['p1'] == "apps")?"active":"",
          )
        );
        }
        if (sessionClass::checkAcc($acclist, "unixadm,unixview")) {
          array_push($brenvarr,array(
            "title"=>"Firewall entries",
            "link"=>"/".$page."/firewall/".(!empty($thisarray['p2'])?$thisarray['p2']:(!empty($_SESSION["userdata"]["lastappid"])?$_SESSION["userdata"]["lastappid"]:"")),
            "icon"=>false,
            "text"=>"Firewall",
            "disabled"=>!empty($thisarray['p2'])?"":"disabled",
            "active"=>($thisarray['p1'] == "firewall")?"active":"",
          ));
        }
        if (sessionClass::checkAcc($acclist, "unixadm,dnsvew")) {
          array_push($brenvarr,array(
            "title"=>"DNS Configuration",
            "link"=>"/".$page."/dns/".(!empty($thisarray['p2'])?$thisarray['p2']:(!empty($_SESSION["userdata"]["lastappid"])?$_SESSION["userdata"]["lastappid"]:"")),
            "icon"=>false,
            "text"=>"DNS",
            "disabled"=>!empty($thisarray['p2'])?"":"disabled",
            "active"=>($thisarray['p1'] == "dns")?"active":"",
          ));
        }
        if (sessionClass::checkAcc($acclist, "appadm,appview")) {
          array_push($brenvarr,array(
            "title"=>"Application servers",
            "link"=>"/".$page."/appservers/".(!empty($thisarray['p2'])?$thisarray['p2']:(!empty($_SESSION["userdata"]["lastappid"])?$_SESSION["userdata"]["lastappid"]:"")),
            "icon"=>false,
            "text"=>"APPS",
            "disabled"=>!empty($thisarray['p2'])?"":"disabled",
            "active"=>($thisarray['p1'] == "appservers")?"active":"",
          ));
        }
        if (sessionClass::checkAcc($acclist, "unixadm,unixview")) {
          array_push($brenvarr,array(
            "title"=>"Server information",
            "link"=>"/".$page."/servers/",
            "icon"=>false,
            "text"=>"Servers",
            "active"=>($thisarray['p1'] == "servers")?"active":"",
          ));
        }
        if (sessionClass::checkAcc($acclist, "appadm,appview")) {
            array_push($brenvarr,array(
              "title"=>"Places",
              "link"=>"/".$page."/places/",
              "icon"=>false,
              "text"=>"Places",
              "active"=>($thisarray['p1'] == "places")?"active":"",
            ));
            array_push($brenvarr,array(
              "title"=>"Software Releases",
              "link"=>"/".$page."/release/",
              "icon"=>false,
              "text"=>"Releases",
              "active"=>($thisarray['p1'] == "release")?"active":"",
            ));
            array_push($brenvarr,array(
              "title"=>"Release status on servers",
              "link"=>"/".$page."/relstatus/",
              "icon"=>false,
              "text"=>"Release status",
              "active"=>($thisarray['p1'] == "relstatus")?"active":"",
            ));
        }
    
            $zobj['projid'] == "";
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
                <?php if (file_exists(__DIR__ . "/env/" . $thisarray['p1'] . ".php")) {include "env/" . $thisarray['p1'] . ".php";}?>
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
<?php if(($thisarray["p1"]=="apps" && $_GET["type"]=="new") || !empty($_GET["app"])){ ?>
<script type="text/javascript" src="/<?php echo $website['corebase'];?>assets/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="/<?php echo $website['corebase'];?>assets/js/tinymce/mentions.min.js"></script>
<?php } ?>
<?php if($thisarray["p1"]=="relstatus"){?>
<script src="/<?php echo $website['corebase'];?>assets/js/datatables/jquery.dataTables.min.js"></script>
<script src="/<?php echo $website['corebase'];?>assets/js/datatables/dataTables.responsive.min.js"></script>
<script>
let dtable = $('.datainfo').DataTable({
    "oLanguage": {
        "sSearch": ""
    },
    dom: 'Bfrtip',
    responsive: true,
    columnDefs: [{
            responsivePriority: 3,
            targets: 0
        },
        {
            responsivePriority: 2,
            targets: -1
        }
    ]
});
$('.dtfilter').keyup(function() {
    dtable.search($(this).val()).draw();
});
</script>
<?php }
include $website['corebase']."public/modules/template_end.php";
        echo '</body></html>';
    }
}