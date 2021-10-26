<?php
class Class_env
{
    public static function getPage($thisarray)
    {
        global $installedapp;
        global $website;
        global $page;
        global $env;
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
        if(isset($_POST["createpack"])){
          $hash = textClass::getRandomStr(16);
          $sql="insert into env_packages (tags,proj,packname,srvtype,packuid,pkgobjects,created_by) values(?,?,?,?,?,?,?)";
          $stmt = $pdo->prepare($sql);
          if($stmt->execute(array(
              htmlspecialchars($_POST["tags"]),
              htmlspecialchars($_POST["appname"]),
              htmlspecialchars($_POST["pkgname"]),
              htmlspecialchars($_POST["objtype"]),
              $hash,
              $_POST["finalobj"],
              $_SESSION["user"]
          ))){
              $msg[]="Package created";
              if(!empty(htmlspecialchars($_POST["tags"]))){
                gTable::dbsearch(htmlspecialchars($_POST["pkgname"]),"/env/packages/?pkgid=".$hash,htmlspecialchars($_POST["tags"]));
              }
          } else {
              $err[]="Error occured. Please try again.";
          }
          header("Location:/env/packages/".$thisarray['p2']);
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
        if(isset($_POST["saveplace"])){
          $hash = textClass::getRandomStr(16);
          $sql="insert into env_places (tags,proj,placename,plregion,plcity,pltype,pluid,plcontact,created_by) values(?,?,?,?,?,?,?,?,?)";
          $stmt = $pdo->prepare($sql);
          if($stmt->execute(array(
              htmlspecialchars($_POST["tags"]),
              $thisarray['p2'],
              htmlspecialchars($_POST["place"]),
              htmlspecialchars($_POST["region"]),
              htmlspecialchars($_POST["city"]),
              htmlspecialchars($_POST["type"]),
              $hash,
              htmlspecialchars($_POST["contact"]),
              $_SESSION["user"]
          ))){
              $msg[]="Place added";
          } else {
              $err[]="Error occured. Please try again.";
          }
          header("Location:/env/places/".$thisarray['p2']);
        }
        if (!empty($env)) {$menudataenv = json_decode($env, true);} else { $menudataenv = array();}
        include "public/modules/css.php";
        echo '<link rel="stylesheet" type="text/css" href="/assets/css/jquery-ui.min.css">';
        echo '</head><body class="fix-header card-no-border"><div id="main-wrapper">';
        if (!empty($thisarray['p1'])) {
          $breadcrumb["text"] = "Environment";
          $breadcrumb["midicon"] = "enviro";
          $breadcrumb["link"] = "/env/apps";
          $breadcrumb["text2"] = $thisarray['p1'];
      } else {
          $breadcrumb["text"] = "Environment";
          $breadcrumb["midicon"] = "enviro";
      }
        include "public/modules/headcontent.php";
        echo '<div class="page-wrapper"><div class="container-fluid">';
        $arrayibmmqtab = array( "import", "qm", "queues", "channels", "topics", "subs", "process", "service", "dlqh", "authrec");
        $arraytibcoemstab = array("tibqueues", "tibtopics", "tibacl");
        $brenvarr=array();
        $brarr=array();
        if(($page=="apps" && $_GET["type"]=="new") || !empty($_GET["app"])){
          array_push($brenvarr,array(
            "title"=>"Applications",
            "link"=>"/".$page."/apps",
            "midicon"=>"app",
          ));
        } elseif(($thisarray['p1']=="preview")){
          array_push($brenvarr,array(
            "title"=>"Packages",
            "link"=>"/".$page."/packages/".(!empty($thisarray['p2'])?$thisarray['p2']:(!empty($_SESSION["userdata"]["lastappid"])?$_SESSION["userdata"]["lastappid"]:"")),
            "midicon"=>"package",
          ));
        } else {
        if (sessionClass::checkAcc($acclist, "appadm,appview")) {
          array_push($brenvarr,array(
            "title"=>"Applications",
            "link"=>"/".$page."/apps",
            "midicon"=>"app",
            "main"=>true,
            "active"=>($thisarray['p1'] == "apps")?"active":"",
          ),
          array(
            "title"=>"Deploy packages",
            "link"=>"/".$page."/deploy/".(!empty($thisarray['p2'])?$thisarray['p2']:(!empty($_SESSION["userdata"]["lastappid"])?$_SESSION["userdata"]["lastappid"]:"")),
            "icon"=>"mdi-upload",
            "disabled"=>!empty($thisarray['p2'])?"":"disabled",
            "active"=>($thisarray['p1'] == "deploy")?"active":"",
          ),
          array(
            "title"=>"Create packages",
            "link"=>"/".$page."/packages/".(!empty($thisarray['p2'])?$thisarray['p2']:(!empty($_SESSION["userdata"]["lastappid"])?$_SESSION["userdata"]["lastappid"]:"")),
            "midicon"=>"package",
            "disabled"=>!empty($thisarray['p2'])?"":"disabled",
            "active"=>($thisarray['p1'] == "packages")?"active":"",
          )
          );
        }
        if (sessionClass::checkAcc($acclist, "unixadm,unixview")) {
          array_push($brenvarr,array(
            "title"=>"Firewall entries",
            "link"=>"/".$page."/firewall/".(!empty($thisarray['p2'])?$thisarray['p2']:(!empty($_SESSION["userdata"]["lastappid"])?$_SESSION["userdata"]["lastappid"]:"")),
            "midicon"=>"firewall",
            "disabled"=>!empty($thisarray['p2'])?"":"disabled",
            "active"=>($thisarray['p1'] == "firewall")?"active":"",
          ));
        }
        if (sessionClass::checkAcc($acclist, "unixadm,dnsvew")) {
          array_push($brenvarr,array(
            "title"=>"DNS Configuration",
            "link"=>"/".$page."/dns/".(!empty($thisarray['p2'])?$thisarray['p2']:(!empty($_SESSION["userdata"]["lastappid"])?$_SESSION["userdata"]["lastappid"]:"")),
            "midicon"=>"dns",
            "disabled"=>!empty($thisarray['p2'])?"":"disabled",
            "active"=>($thisarray['p1'] == "dns")?"active":"",
          ));
        }
        if (sessionClass::checkAcc($acclist, "unixadm,unixview")) {
          array_push($brenvarr,array(
            "title"=>"Server information",
            "link"=>"/".$page."/servers/".(!empty($thisarray['p2'])?$thisarray['p2']:(!empty($_SESSION["userdata"]["lastappid"])?$_SESSION["userdata"]["lastappid"]:"")),
            "midicon"=>"server",
            "disabled"=>!empty($thisarray['p2'])?"":"disabled",
            "active"=>($thisarray['p1'] == "servers")?"active":"",
          ));
        }
        if (sessionClass::checkAcc($acclist, "appadm,appview")) {
          array_push($brenvarr,array(
            "title"=>"Application servers",
            "link"=>"/".$page."/appservers/".(!empty($thisarray['p2'])?$thisarray['p2']:(!empty($_SESSION["userdata"]["lastappid"])?$_SESSION["userdata"]["lastappid"]:"")),
            "midicon"=>"app-srv",
            "disabled"=>!empty($thisarray['p2'])?"":"disabled",
            "active"=>($thisarray['p1'] == "appservers")?"active":"",
          ));
        }
        if (sessionClass::checkAcc($acclist, "appadm,appview")) {
          array_push($brenvarr,array(
            "title"=>"Variables",
            "link"=>"/".$page."/vars/".(!empty($thisarray['p2'])?$thisarray['p2']:(!empty($_SESSION["userdata"]["lastappid"])?$_SESSION["userdata"]["lastappid"]:"")),
            "midicon"=>"vars",
            "disabled"=>!empty($thisarray['p2'])?"":"disabled",
            "active"=>($thisarray['p1'] == "vars")?"active":"",
          ));
        }
        if (sessionClass::checkAcc($acclist, "ibmadm,ibmview")) {
          if (method_exists("IBMMQ", "execJava") && is_callable(array("IBMMQ", "execJava"))) {
          array_push($brenvarr,array(
            "title"=>"IBM MQ",
            "link"=>"#ibmmq",
            "midicon"=>"mq",
            "disabled"=>!empty($thisarray['p2'])?"":"disabled",
            "tab"=>true,
            "active"=>in_array($thisarray['p1'], $arrayibmmqtab)?"active":"",
          ));
        }
        }
        if (sessionClass::checkAcc($acclist, "ibmadm,ibmview")) {
          array_push($brenvarr,array(
            "title"=>"IBM MQ File transfer",
            "link"=>"/".$page."/fte/".(!empty($thisarray['p2'])?$thisarray['p2']:(!empty($_SESSION["userdata"]["lastappid"])?$_SESSION["userdata"]["lastappid"]:"")),
            "midicon"=>"mq-ft",
            "disabled"=>!empty($thisarray['p2'])?"":"disabled",
            "active"=>($thisarray['p1'] == "fte")?"active":"",
          ));
        }
        if (sessionClass::checkAcc($acclist, "ibmadm,ibmview")) {
          array_push($brenvarr,array(
            "title"=>"IBM ACE/IIB",
            "link"=>"/".$page."/flows/".(!empty($thisarray['p2'])?$thisarray['p2']:(!empty($_SESSION["userdata"]["lastappid"])?$_SESSION["userdata"]["lastappid"]:"")),
            "midicon"=>"iib",
            "disabled"=>!empty($thisarray['p2'])?"":"disabled",
            "active"=>($thisarray['p1'] == "flows")?"active":"",
          ));
        }
        if (sessionClass::checkAcc($acclist, "tibcoadm,tibcoview")) {
          if (method_exists("tibco", "execJava") && is_callable(array("tibco", "execJava"))) {
            array_push($brenvarr,array(
              "title"=>"TIBCO EMS",
              "link"=>"#tibcoems",
              "midicon"=>"tibco",
              "tab"=>true,
              "disabled"=>!empty($thisarray['p2'])?"":"disabled",
              "active"=>in_array($thisarray['p1'], $arraytibcoemstab)?"active":"",
            ));
          }
        }
        if (sessionClass::checkAcc($acclist, "unixadm,unixview")) {
          if (method_exists("tibco", "execJava") && is_callable(array("tibco", "execJava"))) {
            array_push($brenvarr,array(
              "title"=>"Places",
              "link"=>"/".$page."/places/".(!empty($thisarray['p2'])?$thisarray['p2']:(!empty($_SESSION["userdata"]["lastappid"])?$_SESSION["userdata"]["lastappid"]:"")),
              "midicon"=>"dns",
              "disabled"=>!empty($thisarray['p2'])?"":"disabled",
              "active"=>($thisarray['p1'] == "places")?"active":"",
            ));
          }
        }
      }
            $zobj['projid'] == "";
            $zobj['lockedby'] = $_SESSION['user'];
?>
<div class="row pt-3">
    <div class="col-lg-2">
        <?php include "public/modules/sidebar.php"; ?>
    </div>
    <div class="col-lg-10">
        <div class="row" id="ngApp" ng-app="ngApp" ng-controller="ngCtrl">
            <div class="col-md-9">
                <?php include "public/modules/breadcrumb.php"; ?>
                <div class="row">
                  <div class="col-lg-12">
                    <div class="tab-content">
                        <?php if (sessionClass::checkAcc($acclist, "ibmadm,ibmview")) {?>
                        <div class="tab-pane <?php echo in_array($thisarray['p1'], $arrayibmmqtab) ? "active" : ""; ?>"
                            id="ibmmq" role="tabpanel">
                            <ul class="nav nav-tabs customtab">
                                <?php if (method_exists("Excel", "import") && is_callable(array("Excel", "import"))) {?>
                                <li class="nav-item"><a
                                        class="nav-link waves-effect<?php echo $thisarray['p1'] == "import" ? " active" : ""; ?>"
                                        href="/<?php echo $page; ?>/import/<?php echo $thisarray['p2']; ?>">Import</a>
                                </li>
                                <?php }?>
                                <li class="nav-item"><a
                                        class="nav-link waves-effect<?php echo $thisarray['p1'] == "qm" ? " active" : ""; ?>"
                                        href="/<?php echo $page; ?>/qm/<?php echo $thisarray['p2']; ?>">Qmanager</a>
                                </li>
                                <li class="nav-item"><a
                                        class="nav-link waves-effect<?php echo $thisarray['p1'] == "queues" ? " active" : ""; ?>"
                                        href="/<?php echo $page; ?>/queues/<?php echo $thisarray['p2']; ?>">Queues</a>
                                </li>
                                <li class="nav-item"><a
                                        class="nav-link waves-effect<?php echo $thisarray['p1'] == "channels" ? " active" : ""; ?>"
                                        href="/<?php echo $page; ?>/channels/<?php echo $thisarray['p2']; ?>">Channels</a>
                                </li>
                                <li class="nav-item"><a
                                        class="nav-link waves-effect<?php echo $thisarray['p1'] == "topics" ? " active" : ""; ?>"
                                        href="/<?php echo $page; ?>/topics/<?php echo $thisarray['p2']; ?>">Topics</a>
                                </li>
                                <li class="nav-item"><a
                                        class="nav-link waves-effect<?php echo $thisarray['p1'] == "subs" ? " active" : ""; ?>"
                                        href="/<?php echo $page; ?>/subs/<?php echo $thisarray['p2']; ?>">SUB</a></li>
                                <li class="nav-item"><a
                                        class="nav-link waves-effect<?php echo $thisarray['p1'] == "process" ? " active" : ""; ?>"
                                        href="/<?php echo $page; ?>/process/<?php echo $thisarray['p2']; ?>">Process</a>
                                </li>
                                <li class="nav-item"><a
                                        class="nav-link waves-effect<?php echo $thisarray['p1'] == "service" ? " active" : ""; ?>"
                                        href="/<?php echo $page; ?>/service/<?php echo $thisarray['p2']; ?>">Service</a>
                                </li>
                                <li class="nav-item"><a
                                        class="nav-link waves-effect<?php echo $thisarray['p1'] == "dlqh" ? " active" : ""; ?>"
                                        href="/<?php echo $page; ?>/dlqh/<?php echo $thisarray['p2']; ?>">DLQH</a></li>
                                <li class="nav-item"><a
                                        class="nav-link waves-effect<?php echo $thisarray['p1'] == "authrec" ? " active" : ""; ?>"
                                        href="/<?php echo $page; ?>/authrec/<?php echo $thisarray['p2']; ?>">Authrec</a>
                                </li>
                                <!-- <li class="nav-item"><a class="nav-link waves-effect<?php echo $thisarray['p1'] == "prepost" ? " active" : ""; ?>" href="/<?php echo $page . "/" . $thisarray['p1'] . "/" . $secsubpage; ?>/prepost">Pre/Post</a></li>-->
                                <!-- <li class="nav-item"><a class="nav-link waves-effect<?php echo $thisarray['p1'] == "cert" ? " active" : ""; ?>" href="/<?php echo $page . "/" . $thisarray['p1'] . "/" . $secsubpage; ?>/cert">Certificates</a></li>-->
                                <!-- <li class="nav-item"><a class="nav-link waves-effect<?php echo $thisarray['p1'] == "clusters" ? " active" : ""; ?>" href="/<?php echo $page . "/" . $thisarray['p1'] . "/" . $secsubpage; ?>/clusters">Clusters</a></li>-->
                                <!-- <li class="nav-item"><a class="nav-link waves-effect<?php echo $thisarray['p1'] == "nl" ? " active" : ""; ?>" href="/<?php echo $page . "/" . $thisarray['p1'] . "/" . $secsubpage; ?>/nl">Namelists</a></li>-->
                            </ul>
                        </div>
                        <?php }?>
                        <?php if (sessionClass::checkAcc($acclist, "tibcoadm,tibcoview")) {?>
                        <div class="tab-pane <?php echo in_array($thisarray['p1'], $arraytibcoemstab) ? "active" : ""; ?>"
                            id="tibcoems" role="tabpanel">
                            <ul class="nav nav-tabs customtab">
                                <li class="nav-item"><a
                                        class="nav-link waves-effect<?php echo $thisarray['p1'] == "tibqueues" ? " active" : ""; ?>"
                                        href="/<?php echo $page; ?>/tibqueues/<?php echo $thisarray['p2']; ?>">Queues</a>
                                </li>
                                <li class="nav-item"><a
                                        class="nav-link waves-effect<?php echo $thisarray['p1'] == "tibtopics" ? " active" : ""; ?>"
                                        href="/<?php echo $page; ?>/tibtopics/<?php echo $thisarray['p2']; ?>">Topics</a>
                                </li>
                                <li class="nav-item"><a
                                        class="nav-link waves-effect<?php echo $thisarray['p1'] == "tibacl" ? " active" : ""; ?>"
                                        href="/<?php echo $page; ?>/tibacl/<?php echo $thisarray['p2']; ?>">ACL</a></li>
                            </ul>
                        </div>
                        <?php }?>
                        </div>
                        </div>
                </div><br>
                <?php if (file_exists(__DIR__ . "/env/" . $thisarray['p1'] . ".php")) {include "env/" . $thisarray['p1'] . ".php";}?>
                <?php include "public/modules/respform.php";?>
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
</section>
<?php
include "public/modules/footer.php";
include "public/modules/js.php";?>
<script src="/assets/js/tagsinput.min.js" type="text/javascript"></script>
<script src="/assets/js/dirPagination.js"></script>
<script type="text/javascript" src="/assets/js/ng-controller.js"></script>
<script src="/assets/js/alasql.min.js"></script>
<script src="/assets/js/xlsx.core.min.js"></script>
<?php if(($thisarray["p1"]=="apps" && $_GET["type"]=="new") || !empty($_GET["app"])){ ?>
<script type="text/javascript" src="/assets/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="/assets/js/tinymce/mentions.min.js"></script>
<?php } ?>
<?php if($thisarray["p1"]=="places"){?>
<script src="/assets/js/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/js/datatables/dataTables.responsive.min.js"></script>
<script>
$('#data-table').DataTable({
    "oLanguage": {
        "sSearch": ""
    },
    dom: 'Bfrtip'
});
</script>
<?php }
include "public/modules/template_end.php";
        echo '</body></html>';
    }
}