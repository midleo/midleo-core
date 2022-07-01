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
        $arrcolor=array("40BC86","1ABC9C","27AE60","00D717","F31D2F","EC555C","FCB410","B17E22","F24D16","FF8600","EC6625","2980B9","3498DB","528CCB","0918EC","199EC7","03A2FD","7b68ee","BF4ACC","074354","34495E","181D21");
        if (!is_array($website)) {$website = json_decode($website, true);}
        if (!sessionClass::checkAcc($acclist, "appconfig")) { header("Location:/cp/?");}
        if ($thisarray['p1']!="groups" && $_SESSION["user_level"]<3){ header("Location:/cp/?"); }
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

        include $website['corebase']."public/modules/css.php";
        if ($thisarray['p1'] == "users" || $thisarray['p1'] == "groups") {?>
<link rel="stylesheet" type="text/css" href="/<?php echo $website['corebase'];?>assets/css/jquery-ui.min.css">
<?php }
        if ($thisarray['p1'] == "modules") {?>
<?php }
        if ($thisarray['p1'] == "env" || $thisarray['p1'] == "business") {?>
<link rel="stylesheet" type="text/css" href="/<?php echo $website['corebase'];?>assets/css/nestablemenu.css">
<link rel="stylesheet" type="text/css" href="/<?php echo $website['corebase'];?>assets/css/bootstrap-datetimepicker.css">
<?php }
        echo '<link rel="stylesheet" type="text/css" href="/'.$website['corebase'].'assets/css/tinyreset.css">';
        echo '<style type="text/css">';
        foreach($arrcolor as $key=>$val){ echo '#styleradio [type="radio"].radio-'.$val.' + label:after{background-color:#'.$val.';border-color:#'.$val.';animation:ripple .2s linear forwards}'; }
        echo '.tox-tinymce {height: 300px !important;}</style>';
        echo '</head><body class="fix-header card-no-border"><div id="main-wrapper">';
        $breadcrumb["text"] = "Midleo configuration";
        include $website['corebase']."public/modules/headcontent.php";
        echo '<div class="page-wrapper"><div class="container-fluid">';
        $brarr=array();
        if($_SESSION["user_level"]>=3){
            $brenvarr=array(
                array(
                    "title"=>"View/Edit users",
                    "link"=>"/".$page."/users",
                    "icon"=>"mdi-account-multiple mdi-18px",
                    "active"=>($thisarray['p1'] == "users")?"active":"",
                )
              );
              if (sessionClass::checkAcc($acclist, "odfiles")) {
                array_push($brenvarr,array(
                    "title"=>"View/Edit groups",
                    "link"=>"/".$page."/groups",
                    "icon"=>"mdi-account-group-outline mdi-18px",
                    "active"=>($thisarray['p1'] == "groups")?"active":"",
                  ));
              }
              array_push($brenvarr,array(
                "title"=>"LDAP entries",
                "link"=>"/".$page."/ldap",
                "icon"=>"mdi-file-tree-outline mdi-18px",
                "active"=>($thisarray['p1'] == "ldap")?"active":"",
              ),
              array(
                "title"=>"External connections",
                "link"=>"/".$page."/external",
                "icon"=>"mdi-open-in-new mdi-18px",
                "active"=>($thisarray['p1'] == "external")?"active":"",
              ),
              array(
                "title"=>"MAIL Configuration",
                "link"=>"/".$page."/mail",
                "icon"=>"mdi-email-edit-outline mdi-18px",
                "active"=>($thisarray['p1'] == "mail")?"active":"",
              ),
              array(
                "title"=>"Core Configuration",
                "link"=>"/".$page."/main",
                "icon"=>"mdi-application-cog-outline mdi-18px",
                "active"=>($thisarray['p1'] == "main")?"active":"",
              ),
              array(
                "title"=>"Core Modules",
                "link"=>"/".$page."/modules",
                "icon"=>"mdi-cards mdi-18px",
                "active"=>($thisarray['p1'] == "modules")?"active":"",
              ),
              array(
                "title"=>"Business logic",
                "link"=>"/".$page."/business",
                "icon"=>"mdi-head-cog-outline mdi-18px",
                "active"=>($thisarray['p1'] == "business")?"active":"",
              ),
              array(
                "title"=>"Environment configuration",
                "link"=>"/".$page."/env",
                "icon"=>"mdi-gate-xnor mdi-18px",
                "active"=>($thisarray['p1'] == "env")?"active":"",
              )


            );

        }
        ?>
<div class="row pt-3">
    <div class="col-lg-2 bg-white leftsidebar">
        <?php include "public/modules/sidebar.php"; ?>
    </div>
    <div class="col-lg-10">
    <?php if(in_array($thisarray['p1'], array("external","users","groups"))){?>
        <div class="row ngctrl" id="ngApp" ng-app="ngApp" ng-controller="ngCtrl">
            <?php } else { ?><div class="row"><?php } ?>
            <div class="col-md-9">
                <?php include $website['corebase']."public/modules/breadcrumb.php"; ?><br>
                <?php if (file_exists(__DIR__ . "/app/" . $thisarray['p1'] . ".php")) {include "app/" . $thisarray['p1'] . ".php";} else {textClass::PageNotFound();}?>
            </div>
            <div class="col-md-3">
                <?php if(!in_array($thisarray['p1'], array("external","mail","main"))){?>
                    <?php include $website['corebase']."public/modules/filterbar.php"; ?>
                <?php } ?>
                <?php include $website['corebase']."public/modules/breadcrumbin.php"; ?>
            </div>
        </div>
    </div>
</div>
<?php
include $website['corebase']."public/modules/footer.php";
        echo "</div></div>";
        include $website['corebase']."public/modules/js.php";?>
<?php if ($thisarray['p1'] == "users" || $thisarray['p1'] == "groups") {?>
<script src="/<?php echo $website['corebase'];?>assets/js/dirPagination.js" type="text/javascript"></script>
<script type="text/javascript" src="/<?php echo $website['corebase'];?>assets/js/ng-controller.js"></script>
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
</script>
<?php }  ?>
<?php if ($thisarray['p1'] == "modules") {?>
<script src="/<?php echo $website['corebase'];?>assets/js/datatables/jquery.dataTables.min.js"></script>
<script src="/<?php echo $website['corebase'];?>assets/js/datatables/dataTables.responsive.min.js"></script>
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
                    "<button type=\"button\" class=\"btn btn-light btn-sm\" disabled><i class='mdi mdi-close'></i></button>" :
                    "<button type=\"button\" data-bs-toggle=\"tooltip\" data-bs-placement=\"top\" title=\"Delete module\" class=\"btn btn-light btn-sm command-delete\" data-row-id=\"" +
                    row[0] +
                    "\"><i class='mdi mdi-close'></i></button>"
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
<?php } ?><script src="/<?php echo $website['corebase'];?>assets/js/tagsinput.min.js" type="text/javascript"></script><?php 
        include $website['corebase']."public/modules/template_end.php";
        echo '</body></html>';
    }
}