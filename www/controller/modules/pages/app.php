<?php
class Class_appconfig
{
    public static function getPage($thisarray)
    {
        global $installedapp;
        global $website;
        global $env;
        global $bsteps;
        global $usermodules;
        global $gracclist;
        global $page;
        global $typereq;
        global $modulelist;
        global $accrights;
        global $maindir;
        if ($installedapp != "yes") {header("Location: /install");}
        if (empty($thisarray['p1'])) {header("Location: /appconfig/groups");}
        sessionClass::page_protect(base64_encode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
        $err = array();
        $msg = array();
        $pdo = pdodb::connect();
        $data=sessionClass::getSessUserData(); foreach($data as $key=>$val){  ${$key}=$val; } 
        if (!is_array($website)) {$website = json_decode($website, true);}
        if (!sessionClass::checkAcc($acclist, "appconfig")) { header("Location:/cp/?");}
        if ($thisarray['p1']!="groups" && $_SESSION["user_level"]<3){ header("Location:/cp/?"); }
        if (isset($_GET['truncate']) == "yes") {
            $pdo->beginTransaction();
            $st = $pdo->prepare("truncate table requests");
            $st->execute();
            $st = $pdo->prepare("truncate table requests_approval");
            $st->execute();
            $st = $pdo->prepare("truncate table requests_deployments");
            $st->execute();
            $st = $pdo->prepare("truncate table requests_confirmation");
            $st->execute();
            $st = $pdo->prepare("truncate table requests_efforts");
            $st->execute();
            $st = $pdo->prepare("truncate table requests_efforts_all");
            $st->execute();
            $st = $pdo->prepare("truncate table requests_flow");
            $st->execute();
            $st = $pdo->prepare("truncate table requests_fte");
            $st->execute();
            $st = $pdo->prepare("truncate table requests_general");
            $st->execute();
            $st = $pdo->prepare("truncate table requests_seq");
            $st->execute();
            $st = $pdo->prepare("truncate table tracking");
            $st->execute();
            $st = $pdo->prepare("truncate table log");
            $st->execute();
            $st = $pdo->prepare("truncate table requests_queues");
            $st->execute();
            $pdo->commit();
            $msg[] = "Requests table truncated";
        }
        if (isset($_POST['addldap']) && !empty($_POST['ldapserver'])) {
            $sql = "insert into ldap_config (ldapserver,ldapport,ldaptree,ldapgtree,ldapinfo) values (?,?,?,?,?)";
            $q = $pdo->prepare($sql);
            $q->execute(array(htmlspecialchars($_POST['ldapserver']), htmlspecialchars($_POST['ldapport']), htmlspecialchars($_POST['ldaptree']), htmlspecialchars($_POST['ldapgtree']), htmlspecialchars($_POST['ldapinfo'])));
            $msg[] = "Ldap server added successfully";
        }
        if (isset($_POST['saveadm'])) {
            if (isset($_POST['budget_groups'])) {
                $budget_groups = array();
                $bgroups = explode(",", htmlspecialchars($_POST['budget_groups']));
                foreach ($bgroups as $val) {
                    $latname = textClass::cyr2lat($val);
                    $latname = textClass::strreplace($latname, "_");
                    $budget_groups[$latname] = $val;
                }
                $budget_groups = json_encode($budget_groups, true);
                $website["budget_groups"] = !empty($_POST['budget_groups']) ? $budget_groups : "";
            }
            foreach ($_POST as $key => $val) {
                if (substr($key, 0, 5) === "conf#") {
                    $key = explode("#", $key);
                    $website[$key[1]] = !empty($val) ? $val : "";
                }
            }

            $newSettings = array("website" => json_encode($website, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            textClass::change_config('controller/config.vars.php', $newSettings);
            $msg[] = "configuration updated";
        }
        if (!empty($env)) {
            $menudataenv = json_decode($env, true);
        } else {
            $menudataenv = array();
        }
        if (!empty($bsteps)) {
            $menudatabsteps = json_decode($bsteps, true);
        } else {
            $menudatabsteps = json_decode("[{}]", true);
        }

        include "public/modules/css.php";
        if ($thisarray['p1'] == "users" || $thisarray['p1'] == "groups") {?>
<link rel="stylesheet" type="text/css" href="/assets/css/jquery-ui.min.css">
<?php }
        if ($thisarray['p1'] == "modules") {?>
<link rel="stylesheet" type="text/css" href="/assets/js/datatables/dataTables.bootstrap5.min.css">
<link rel="stylesheet" type="text/css" href="/assets/js/datatables/responsive.dataTables.min.css">
<?php }
        if ($thisarray['p1'] == "env" || $thisarray['p1'] == "business") {?>
<link rel="stylesheet" type="text/css" href="/assets/css/nestablemenu.css">
<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap-datetimepicker.css">
<?php }
        echo '<link rel="stylesheet" type="text/css" href="/assets/css/tinyreset.css">
        </head><body class="fix-header card-no-border"><div id="main-wrapper">';
        $breadcrumb["text"] = "Midleo configuration";
        include "public/modules/headcontent.php";
        echo '<div class="page-wrapper"><div class="container-fluid">';
        $brarr=array();
        if($_SESSION["user_level"]>=3){
            $brenvarr=array(
                array(
                    "title"=>"View/Edit users",
                    "link"=>"/".$page."/users",
                    "midicon"=>"users",
                    "active"=>($thisarray['p1'] == "users")?"active":"",
                )
              );
              if (sessionClass::checkAcc($acclist, "odfiles")) {
                array_push($brenvarr,array(
                    "title"=>"View/Edit groups",
                    "link"=>"/".$page."/groups",
                    "midicon"=>"groups",
                    "active"=>($thisarray['p1'] == "groups")?"active":"",
                  ));
              }
              array_push($brenvarr,array(
                "title"=>"LDAP entries",
                "link"=>"/".$page."/ldap",
                "midicon"=>"ldap",
                "active"=>($thisarray['p1'] == "ldap")?"active":"",
              ),
              array(
                "title"=>"External connections",
                "link"=>"/".$page."/external",
                "midicon"=>"ext-config",
                "active"=>($thisarray['p1'] == "external")?"active":"",
              ),
              array(
                "title"=>"MAIL Configuration",
                "link"=>"/".$page."/mail",
                "midicon"=>"email-config",
                "active"=>($thisarray['p1'] == "mail")?"active":"",
              ),
              array(
                "title"=>"Core Configuration",
                "link"=>"/".$page."/main",
                "midicon"=>"core-config",
                "active"=>($thisarray['p1'] == "main")?"active":"",
              ),
              array(
                "title"=>"Core Modules",
                "link"=>"/".$page."/modules",
                "midicon"=>"modules",
                "active"=>($thisarray['p1'] == "modules")?"active":"",
              ),
              array(
                "title"=>"Business logic",
                "link"=>"/".$page."/business",
                "midicon"=>"b-logic",
                "active"=>($thisarray['p1'] == "business")?"active":"",
              ),
              array(
                "title"=>"Environment configuration",
                "link"=>"/".$page."/env",
                "midicon"=>"enviro",
                "active"=>($thisarray['p1'] == "env")?"active":"",
              )


            );

        }
        ?>
<div class="row pt-3">
    <div class="col-lg-2">
        <?php include "public/modules/sidebar.php"; ?>
    </div>
    <div class="col-lg-10">
        <div class="row ngctrl" id="ngApp" ng-app="ngApp" ng-controller="ngCtrl">
            <div class="col-md-9">
                <?php include "public/modules/breadcrumb.php"; ?><br>
                <?php if (file_exists(__DIR__ . "/app/" . $thisarray['p1'] . ".php")) {include "app/" . $thisarray['p1'] . ".php";} else {textClass::PageNotFound();}?>
            </div>
            <div class="col-md-3">
                <?php if(!in_array($thisarray['p1'], array("external","mail","main"))){?>
                    <?php include "public/modules/filterbar.php"; ?>
                <?php } ?>
                <?php include "public/modules/breadcrumbin.php"; ?>
            </div>
        </div>
    </div>
</div>
<?php
include "public/modules/footer.php";
        echo "</div></div>";
        include "public/modules/js.php";?>
<?php if ($thisarray['p1'] == "users" || $thisarray['p1'] == "groups") {?>
<script src="/assets/js/dirPagination.js" type="text/javascript"></script>
<script type="text/javascript" src="/assets/js/ng-controller.js"></script>
<?php }   if ($thisarray['p1'] == "external") {?>
<script type="text/javascript">
var app = angular.module('ngApp', []);

app.config(['$compileProvider',
    function($compileProvider) {
        $compileProvider.aHrefSanitizationWhitelist(/^\s*(https?|ftp|mailto|tel|file|blob):/);
    }
]);
app.controller('ngCtrl', function($scope, $http) {
    $scope.ext = [];
    $scope.ext.gittype = "<?php echo $website['gittype'];?>";
});
angular.bootstrap(document.getElementById("ngApp"), ['ngApp']);
</script>

<?php }  if ($thisarray['p1'] == "update") {?>
<script type="text/javascript">
function getChlist() {
    $(".loading").show();
    $thisdiv = $("#chlistinfo");
    $("#updinfo").empty();
    $thisdiv.empty();
    $thisdiv.append("<li class='list-group-item'>Getting info from the main server</li>");
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/server/listupdhistory",
        cache: false,
        success: function(data) {
            if (data.error) {
                $thisdiv.append("<li class='list-group-item list-group-item-danger'>" + data.errorlog +
                    "</li>");
                $(".loading").hide();
            } else if (data.resp.error) {
                $thisdiv.append("<li class='list-group-item list-group-item-danger'>" + data.resp.errorlog +
                    "</li>");
                $(".loading").hide();
            } else {
                $.each(data.resp.history, function(k, v) {
                    $thisdiv.append(
                        "<li class='list-group-item list-group-item-action flex-column align-items-start'>" +
                        "<div class='d-flex w-100 justify-content-between'><h5 class='mb-1'>" +
                        v.author_name + "</h5>" +
                        "<small class='text-muted'>" + moment(v.committed_date).format(
                            'DD.MM.YYYY-HH:mm') + "</small></div><p class='mb-1'>" + v.message +
                        "</p>" +
                        "<small class='text-muted'><a href='" + v.web_url +
                        "' target='_blank'>Commit #" + v.short_id + "</a></small></li>");
                });
                $(".loading").hide();
            }
        }
    });

}

function startUpdate() {
    $(".loading").show();
    $(".btnsrvupd").hide();
    $thisdiv = $("#updinfo");
    $thisdiv.empty();
    $("#chlistinfo").empty();
    $thisdiv.append("<li class='list-group-item'>Starting MIDLEO application update.</li>");
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/server/backupapp",
        cache: false,
        success: function(data) {
            if (data.success) {
                $thisdiv.append(data.resp);
                $thisdiv.append("<li class='list-group-item'>Backup finished.</li>");
                setTimeout(downloadupd(), 1000);
            }
        }
    });
}

function downloadupd() {
    $thisdiv = $("#updinfo");
    $thisdiv.append("<li class='list-group-item'>Тhe download of the update begins..</li>");
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/server/downloadupd",
        cache: false,
        success: function(data) {
            if (data.error) {
                $thisdiv.append("<li class='list-group-item list-group-item-danger'>" + data.errorlog +
                    "</li>");
                $(".loading").hide();
            } else if (data.resp.error) {
                $thisdiv.append("<li class='list-group-item list-group-item-danger'>" + data.resp.errorlog +
                    "</li>");
                $(".loading").hide();
            } else if (!data.resp) {
                $thisdiv.append(
                    "<li class='list-group-item list-group-item-danger'>Problem getting the update! Please try again.</li>"
                );
                $(".loading").hide();
            } else {
                $thisdiv.append("<li class='list-group-item'>" + data.resp + "</li>");
                restoreupd();
            }
        }
    });
}

function restoreupd() {
    $thisdiv = $("#updinfo");
    $thisdiv.append("<li class='list-group-item'>Restoring the backup..</li>");
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/server/restoreupd",
        cache: false,
        success: function(data) {
            if (data.success) {
                $thisdiv.append("<li class='list-group-item'>" + data.resp + "</li>");
                $thisdiv.append("<li class='list-group-item'>Update finished!</li>");
                saveupd();
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert("Status: " + textStatus);
            alert("Error: " + errorThrown);
            $(".loading").hide();
        }
    });
}

function saveupd() {
    $thisdiv = $("#updinfo");
    $thisdiv.append("<li class='list-group-item'>Updating server configuration..</li>");
    $.ajax({
        type: "POST",
        url: "/server/saveupd",
        data: '',
        cache: false,
        success: function(data) {
            if (data.success) {
                $thisdiv.append("<li class='list-group-item'>" + data.resp + "</li>");
                $thisdiv.append(
                    "<li class='list-group-item'>You can proceed working with MIDLEO. Thank you!</li>");
                $(".loading").hide();
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert("Status: " + textStatus);
            alert("Error: " + errorThrown);
            $(".loading").hide();
            $(".btnsrvupd").show();
        }
    });
}
</script>
<?php }?>
<?php if ($thisarray['p1'] == "modules") {?>
<script src="/assets/js/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/js/datatables/dataTables.responsive.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    let table = $('#data-table').DataTable({
        "oLanguage": {
            "sSearch": "",
            "sSearchPlaceholder": "Find a module",
        },
        dom: 'Bfrtip',
        //  responsive: true,
        columnDefs: [{
            targets: -1,
            "data": null,
            "render": function(data, type, row, meta) {
                return (row[2] == "*" ?
                    "<button type=\"button\" class=\"btn btn-light btn-sm\"><svg class='midico midico-outline'><use href='/assets/images/icon/midleoicons.svg#i-x' xlink:href='/assets/images/icon/midleoicons.svg#i-x' /></svg></button>" :
                    "<button type=\"button\" data-bs-toggle=\"tooltip\" data-bs-placement=\"top\" title=\"Delete module\" class=\"btn btn-light btn-sm command-delete\" data-row-id=\"" +
                    row[0] +
                    "\"><svg class='midico midico-outline'><use href='/assets/images/icon/midleoicons.svg#i-trash' xlink:href='/assets/images/icon/midleoicons.svg#i-trash' /></svg></button>"
                    );
            }
        }]
    });
    $('.dtfilter').keyup(function() {
        table.search($(this).val()).draw();
    });
    $('.command-delete').on('click', function() {
        var thisid = $(this).data("row-id");
        $("#mod" + thisid).hide();
        delmodule(thisid);
    });

});
</script>
<?php } ?><script src="/assets/js/tagsinput.min.js" type="text/javascript"></script><?php 
        include "public/modules/template_end.php";
        echo '</body></html>';
    }
}