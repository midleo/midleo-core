<?php
class Class_cp
{
    public static function getPage($thisarray)
    {
        global $installedapp;
        global $website;
        global $maindir;
        global $modulelist;
        global $projcodes;
        global $lastupdate;
        if ($installedapp != "yes") {header("Location: /install");}
        sessionClass::page_protect(base64_encode("http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
        $err = array();
        $msg = array();
        $pdo = pdodb::connect();
        include "public/modules/css.php";
        $data = sessionClass::getSessUserData();foreach ($data as $key => $val) {${$key} = $val;}
        $breadcrumb["text"] = "Dashboard";
        $breadcrumb["midicon"] = "dashboard";
        $brarr = array();
        array_push($brarr, array(
            "title" => "Import documents",
            "link" => "#mnewimp",
            "midicon" => "add",
            "modal" => true,
            "active" => false,
        ));
        array_push($brarr, array(
            "title" => "Create/edit articles",
            "link" => "/cpinfo",
            "midicon" => "kn-b",
            "active" => ($page == "cpinfo") ? "active" : "",
        ));
        array_push($brarr, array(
            "title" => "LDAP configuration",
            "link" => "/" . $page . "/ldap",
            "midicon" => "ldap",
            "active" => ($thisarray['p1'] == "ldap") ? "active" : "",
        ), array(
            "title" => "External connections",
            "link" => "/" . $page . "/external",
            "midicon" => "ext-config",
            "active" => ($thisarray['p1'] == "external") ? "active" : "",
        ), array(
            "title" => "Core Configuration",
            "link" => "/" . $page . "/main",
            "midicon" => "core-config",
            "active" => ($thisarray['p1'] == "main") ? "active" : "",
        ));

        $page = "dashboard";
        echo '</head><body class="fix-header card-no-border"><div id="main-wrapper">';
        include "public/modules/headcontent.php";
        echo '<div class="page-wrapper"><div class="container-fluid">';

        ?>
<div class="row pt-3">
    <div class="col-lg-2">
        <?php include "public/modules/sidebar.php";?></div>
    <div class="col-lg-8">
        <div class="row" id="ngApp" ng-app="ngApp" ng-controller="ngCtrl">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Activity chart</h4>
                            </div>
                            <div class="card-body p-1">
                                <div class="chart-edge">
                                    <canvas id="line-chart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body p-0">
                        <table class="table table-vmiddle table-hover stylish-table mb-0">
                            <thead>
                                <tr>
                                    <th colspan="2" class="text-start" style="vertical-align:top;">
                                        <h4>Latest projects</h4>
                                    </th>
                                    <th colspan="2" class="text-end" style="vertical-align:top;"><a href="/projects">All
                                            Projects</a></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="display:none;">
                                    <td colspan="4"></td>
                                </tr>
                                <?php
if (empty($_SESSION["userdata"]["pjarr"])) {$_SESSION["userdata"]["pjarr"] = array();
            $argpjarr = 0;} else { $argpjarr = 1;}

        $sql = "select projcode,projname,projstatus,projduedate from config_projrequest where " . (!empty($_SESSION["userdata"]["pjarr"]) ? "( owner='" . $_SESSION["user"] . "' or serviceid in (" . str_repeat('?,', count($_SESSION["userdata"]["pjarr"]) - $argpjarr) . '?' . "))" : " requser=?");
        $q = $pdo->prepare($sql);
        if (!empty($_SESSION["userdata"]["pjarr"])) {
            $q->execute($_SESSION["userdata"]["pjarr"]);
        } else {
            $q->execute(array($_SESSION["user"]));
        }

        if ($zobj = $q->fetchAll()) {
            foreach ($zobj as $val) {?>
                                <tr>
                                    <td><?php echo $val["projname"]; ?></td>
                                    <td style="width:100px;" class="text-center"><span
                                            class="badge badge-<?php echo $projcodes[$val['projstatus']]["badge"]; ?>"><?php echo $projcodes[$val['projstatus']]["name"]; ?></span>
                                    </td>
                                    <td style="width:100px;" class="text-start">
                                        <?php echo date("d/m/Y", strtotime($val["projduedate"])); ?></td>
                                    <td style="width:50px;"><a
                                            href="/projects/?pjid=<?php echo $val["projcode"]; ?>"><svg
                                                class="midico midico-outline">
                                                <use href="/assets/images/icon/midleoicons.svg#i-right"
                                                    xlink:href="/assets/images/icon/midleoicons.svg#i-right" />
                                            </svg></a></td>
                                </tr>
                                <?php }
        }
        ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body p-0">
                        <table class="table table-vmiddle table-hover stylish-table mb-0">
                            <thead>
                                <tr>
                                    <th colspan="3">
                                        <h4>Recent activity</h4>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="display:none;">
                                    <td colspan="3"></td>
                                </tr>
                                <?php if (empty($_SESSION["userdata"]["apparr"])) {$_SESSION["userdata"]["apparr"] = array();
            $argapparr = 0;} else { $argapparr = 1;}
        if (empty($_SESSION["userdata"]["widarr"])) {$_SESSION["userdata"]["widarr"] = array();
            $argwidarr = 0;} else { $argwidarr = 1;}
        if ($_SESSION["user_level"] > 3) {
            if (!in_array("system", $_SESSION["userdata"]["apparr"])) {
                $_SESSION["userdata"]["apparr"][] = "system";
            }
        }
        if (empty($_SESSION["userdata"]["apparr"])) {
            $sql = "select * from tracking where whoid=? " . ($dbtype == "oracle" ? " and ROWNUM <= 5 order by id desc" : " order by id desc limit 5");
            $q = $pdo->prepare($sql);
            $q->execute(array($_SESSION["user"]));
        } else {
            $sql = "select * from tracking where whoid='" . $_SESSION["user"] . "' or appid in (" . str_repeat('?,', count($_SESSION["userdata"]["apparr"]) - $argapparr) . '?' . ") " . ($dbtype == "oracle" ? " and ROWNUM <= 5 order by id desc" : " order by id desc limit 5");
            $q = $pdo->prepare($sql);
            $q->execute($_SESSION["userdata"]["apparr"]);
        }
        if ($zobj = $q->fetchAll()) {
            foreach ($zobj as $val) {
                ?><tr>
                                    <td class="text-start"><a href="/browse/user/<?php echo $val['whoid']; ?>"
                                            target="_blank"><?php echo $val['who']; ?></a></td>
                                    <td class="text-start"><?php echo $val['what']; ?></td>
                                    <td class="text-end"><?php echo textClass::getTheDay($val['trackdate']); ?></td>
                                </tr>
                                <?php }} else {?>
                                <tr style="display:none;">
                                    <td colspan="3">No activity yet</td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body p-0">
                        <table class="table table-vmiddle table-hover stylish-table mb-0">
                            <thead>
                                <tr>
                                    <th class="text-start" style="vertical-align:top;">
                                        <h4>Recently used apps</h4>
                                    </th>
                                    <th colspan="2" class="text-end" style="vertical-align:top;"><a href="/env/apps">All
                                            Applications</a></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="display:none;">
                                    <td colspan="3"></td>
                                </tr>
                                <?php
$sql = "select " . (DBTYPE == 'oracle' ? "to_char(recentdata) as recentdata" : "recentdata") . " from users_recent where uuid=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($_SESSION["user_id"]));
        if ($zobj = $q->fetch(PDO::FETCH_ASSOC)) {
            foreach (json_decode($zobj["recentdata"], true) as $key => $val) {
                ?>
                                <tr style="cursor:pointer;"
                                    onclick="location.href='/env/apps/<?php echo $val["id"]; ?>'">
                                    <td class="text-start"><?php echo $val["name"]; ?></td>
                                    <td></td>
                                    <td style="width:50px" class="text-end"><a target="_parent"
                                            href="/env/apps/<?php echo $val["id"]; ?>"><svg
                                                class="midico midico-outline">
                                                <use href="/assets/images/icon/midleoicons.svg#i-right"
                                                    xlink:href="/assets/images/icon/midleoicons.svg#i-right" />
                                            </svg></a></td>
                                </tr>
                                <?php }
        }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-2">
        <?php include "public/modules/breadcrumbin.php";?>
    </div>
</div>
<?php
include "public/modules/footer.php";
        include "public/modules/js.php";?>
<script src="/assets/js/dirPagination.js"></script>
<script type="text/javascript" src="/assets/modules/requests/assets/js/ng-controller.js"></script>
<script src="/assets/js/chart.min.js" type="text/javascript"></script>
<script type="text/javascript">
var thiscolor = "#000";
var thiscolorreq = "rgb(255, 54, 54)";
var chdata = [<?php $thismonth = date('Y-m-d', strtotime('today -1 week'));
        if (DBTYPE == "oracle") {
            $sql = "SELECT * FROM (SELECT days.day as thisdate, count(id) as num
               FROM
               (select trunc(sysdate) as day from dual
               union select trunc(sysdate) - interval '1' day from dual
               union select trunc(sysdate) - interval '2' day from dual
               union select trunc(sysdate) - interval '3' day from dual
               union select trunc(sysdate) - interval '4' day from dual
               union select trunc(sysdate) - interval '5' day from dual
               union select trunc(sysdate) - interval '6' day from dual
               union select trunc(sysdate) - interval '7' day from dual
               union select trunc(sysdate) - interval '8' day from dual
               union select trunc(sysdate) - interval '9' day from dual) days
               left join tracking
               on days.day = to_char(trackdate, 'DD.MON.YYYY')" .
                (empty($_SESSION["userdata"]["apparr"]) ? " and whoid=?" : " and appid in (" . str_repeat('?,', count($_SESSION["userdata"]["apparr"]) - $argapparr) . "?" . ")") . "
               group by days.day
               order by days.day desc) WHERE rownum <= 10";
        } else if (DBTYPE == "postgresql") {
            $sql = "SELECT days.day as thisdate, count(id) as num
                FROM
               (select CURRENT_DATE as day
               union select CURRENT_DATE - interval '1' day
               union select CURRENT_DATE - interval '2' day
               union select CURRENT_DATE - interval '3' day
               union select CURRENT_DATE - interval '4' day
               union select CURRENT_DATE - interval '5' day
               union select CURRENT_DATE - interval '6' day
               union select CURRENT_DATE - interval '7' day
               union select CURRENT_DATE - interval '8' day
               union select CURRENT_DATE - interval '9' day) days
               left join tracking
               on days.day = DATE(trackdate)" .
                (empty($_SESSION["userdata"]["apparr"]) ? " where whoid=?" : " where appid in (" . str_repeat('?,', count($_SESSION["userdata"]["apparr"]) - $argapparr) . "?" . ")") . "
               group by days.day
               order by days.day desc limit 10";
        } else {
            $sql = "SELECT days.day as thisdate, count(id) as num
               FROM
               (select curdate() as day
               union select curdate() - interval 1 day
               union select curdate() - interval 2 day
               union select curdate() - interval 3 day
               union select curdate() - interval 4 day
               union select curdate() - interval 5 day
               union select curdate() - interval 6 day
               union select curdate() - interval 7 day
               union select curdate() - interval 8 day
               union select curdate() - interval 9 day) days
               left join tracking
               on days.day = DATE(trackdate)" .
                (empty($_SESSION["userdata"]["apparr"]) ? " and whoid=?" : " and appid in (" . str_repeat('?,', count($_SESSION["userdata"]["apparr"]) - $argapparr) . "?" . ")") . "
               group by days.day
               order by days.day desc limit 10";
        }
        $q = $pdo->prepare($sql);
        if (empty($_SESSION["userdata"]["apparr"])) {
            $q->execute(array($_SESSION["user"]));
        } else {
            $q->execute($_SESSION["userdata"]["apparr"]);
        }
        $zobj = $q->fetchAll();
        foreach ($zobj as $val) {echo "{ x: new Date('" . $val['thisdate'] . "'),y: " . $val['num'] . "},";}?>];
var reqdata = [<?php $thismonth = date('Y-m-d', strtotime('today -1 week'));
        if (DBTYPE == "oracle") {
            $sql = "SELECT * FROM (SELECT days.day as thisdate, count(id) as num
               FROM
               (select trunc(sysdate) as day from dual
               union select trunc(sysdate) - interval '1' day from dual
               union select trunc(sysdate) - interval '2' day from dual
               union select trunc(sysdate) - interval '3' day from dual
               union select trunc(sysdate) - interval '4' day from dual
               union select trunc(sysdate) - interval '5' day from dual
               union select trunc(sysdate) - interval '6' day from dual
               union select trunc(sysdate) - interval '7' day from dual
               union select trunc(sysdate) - interval '8' day from dual
               union select trunc(sysdate) - interval '9' day from dual) days
               left join requests
               on days.day = to_char(created, 'DD.MON.YYYY')" .
                (empty($_SESSION["userdata"]["widarr"]) ? " and requser=?" : " and wid in (" . str_repeat('?,', count($_SESSION["userdata"]["widarr"]) - $argwidarr) . "?" . ")") . "
               group by days.day
               order by days.day desc) WHERE rownum <= 10";
        } else if (DBTYPE == "postgresql") {
            $sql = "SELECT days.day as thisdate, count(id) as num
                FROM
                (select CURRENT_DATE as day
                union select CURRENT_DATE - interval '1' day
                union select CURRENT_DATE - interval '2' day
                union select CURRENT_DATE - interval '3' day
                union select CURRENT_DATE - interval '4' day
                union select CURRENT_DATE - interval '5' day
                union select CURRENT_DATE - interval '6' day
                union select CURRENT_DATE - interval '7' day
                union select CURRENT_DATE - interval '8' day
                union select CURRENT_DATE - interval '9' day) days
                left join requests
                on days.day = DATE(created)" .
                (empty($_SESSION["userdata"]["widarr"]) ? " and requser=?" : " and wid in (" . str_repeat('?,', count($_SESSION["userdata"]["widarr"]) - $argwidarr) . "?" . ")") . "
                group by days.day
                order by days.day desc limit 10";
        } else {
            $sql = "SELECT days.day as thisdate, count(id) as num
               FROM
               (select curdate() as day
               union select curdate() - interval 1 day
               union select curdate() - interval 2 day
               union select curdate() - interval 3 day
               union select curdate() - interval 4 day
               union select curdate() - interval 5 day
               union select curdate() - interval 6 day
               union select curdate() - interval 7 day
               union select curdate() - interval 8 day
               union select curdate() - interval 9 day) days
               left join requests
               on days.day = DATE(created)" .
                (empty($_SESSION["userdata"]["widarr"]) ? " and requser=?" : " and wid in (" . str_repeat('?,', count($_SESSION["userdata"]["widarr"]) - $argwidarr) . "?" . ")") . "
               group by days.day
               order by days.day desc limit 10";
        }
        $q = $pdo->prepare($sql);
        if (empty($_SESSION["userdata"]["widarr"])) {
            $q->execute(array($_SESSION["user"]));
        } else {
            $q->execute($_SESSION["userdata"]["widarr"]);
        }
        $zobj = $q->fetchAll();
        foreach ($zobj as $val) {echo "{ x: new Date('" . $val['thisdate'] . "'),y: " . $val['num'] . "},";}?>];
var color = Chart.helpers.color;
var config = {
    type: 'line',
    data: {
        datasets: [{
            label: 'changes per day',
            backgroundColor: color("#fff").rgbString(),
            borderColor: thiscolor,
            fill: false,
            data: chdata,
            pointRadius: 5,
            pointHoverRadius: 6,
        }, {
            label: 'requests per day',
            backgroundColor: color("#fff").rgbString(),
            borderColor: thiscolorreq,
            fill: false,
            data: reqdata,
            borderDash: [6, 4],
            pointRadius: 5,
            pointHoverRadius: 6,
        }]
    },
    options: {
        responsive: true,
        title: {
            display: false,
            /*	text: 'changes per day'*/
        },
        scales: {
            xAxes: [{
                type: 'time',
                time: {
                    unit: 'day'
                },
                display: true,
                scaleLabel: {
                    display: true,
                    labelString: 'Date'
                },
                ticks: {
                    major: {
                        fontStyle: 'bold',
                        fontColor: '#FF0000'
                    }
                }
            }],
            yAxes: [{
                display: true,
                scaleLabel: {
                    display: true,
                    labelString: 'number'
                }
            }]
        }
    }
};
window.onload = function() {
    var ctx = document.getElementById('line-chart').getContext('2d');
    window.myLine = new Chart(ctx, config);
};
</script><?php
include "public/modules/template_end.php";
        echo '</body></html>';
    }
}
class Class_cpinfo
{
    public static function getPage($thisarray)
    {
        global $installedapp;
        global $website;
        global $page;
        global $modulelist;
        global $maindir;
        if ($installedapp != "yes") {header("Location: /install");}
        sessionClass::page_protect(base64_encode("http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
        $err = array();
        $msg = array();
        $pdo = pdodb::connect();
        $data = sessionClass::getSessUserData();foreach ($data as $key => $val) {${$key} = $val;}
        if ($_SESSION['user_level'] < 5) {header("Location: /?");}
        if (isset($_POST['delcat'])) {
            $sql = "delete from knowledge_categories where id=?";
            $q = $pdo->prepare($sql);
            $q->execute(array(htmlspecialchars($_POST["catid"])));
            $msg[] = "Category was deleted.";
        }
        if (isset($_POST['addnewcat'])) {
            $newcat = htmlspecialchars($_POST["catname"]);
            $latname = textClass::cyr2lat($newcat);
            $latname = textClass::strreplace($latname);
            $sql = "insert into knowledge_categories (public,category,catname) values(?,?,?)";
            $q = $pdo->prepare($sql);
            $q->execute(array(htmlspecialchars($_POST['public']), $latname, $newcat));
            $msg[] = 'The category was added.';
        }
        if (isset($_POST['delpost'])) {
            $sql = "delete from knowledge_info where id=?";
            $q = $pdo->prepare($sql);
            $q->execute(array(htmlspecialchars($_POST["catid"])));
            if ($website['gittype']) {
                $sql = "select id,commitid,fileplace from env_gituploads where packuid=? order by id desc LIMIT 1";
                $q = $pdo->prepare($sql);
                $q->execute(array("knowledge_info:" . htmlspecialchars($_POST["catid"])));
                if ($zobj = $q->fetch(PDO::FETCH_ASSOC)) {
                    $return = vc::gitDelete($zobj["fileplace"], $zobj["commitid"]);
                    $sql = "update env_gituploads set steptype='delete' where id=?";
                    $q = $pdo->prepare($sql);
                    $q->execute(array($zobj["id"]));
                }
            }
            gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => "system"), "Deleted post:" . $_POST["postname"]);
            header("Location: /cpinfo");
            $msg[] = 'The post was deleted.';
        }
        if (isset($_POST['addpost'])) {
            $posttitle = htmlspecialchars($_POST["posttitle"]);
            $latname = textClass::cyr2lat($posttitle);
            $latname = textClass::strreplace($latname);
            $sql = "select count(id) as total from knowledge_info where cat_latname=?";
            $q = $pdo->prepare($sql);
            $q->execute(array($latname));
            if ($q->fetchColumn() > 0) {
                $err[] = "Post with such name already exist. Please specify a different one.";
            } else {
                $sql = "INSERT INTO knowledge_info (cat_latname,category,cat_name,public,tags,author, cattext,accgroups) VALUES (?,?,?,?,?,?,?,?) RETURNING id";
                $q = $pdo->prepare($sql);
                $q->execute(array($latname, htmlspecialchars($_POST["category"]), $posttitle, htmlspecialchars($_POST['public']), htmlspecialchars($_POST["tags"]), $_SESSION["user"], $_POST["postcontent"], (!empty($_POST["respgrsel"]) ? $_POST["respgrsel"] : "")));
                $tmp['catid'] = $q->fetch(PDO::FETCH_ASSOC)["id"];
                if ($website['gittype']) {
                    $shagit = "";
                    $return = vc::gitAdd("text", $_POST["postcontent"], "articles/posts/" . $latname . ".txt", false, $shagit, true);
                    if (empty($return["err"])) {
                        $tmp["gitupload"] = "articles/posts/" . $latname . ".txt";
                    } else {
                        $msg[] = $return["err"];
                        $tmp["gitupload"] = false;
                    }
                    if ($tmp["gitupload"]) {
                        $resp = vc::GetCommitID($tmp["gitupload"]);
                        $lastcommit = json_decode($resp, true)[0]["id"];
                        if ($lastcommit) {
                            $sql = "insert into env_gituploads (gittype,commitid,packuid,fileplace,steptype,stepuser) values (?,?,?,?,?,?)";
                            $q = $pdo->prepare($sql);
                            $q->execute(array($website['gittype'], $lastcommit, "knowledge_info:" . $tmp['catid'], "articles/posts/" . $latname . ".txt", "prepare", $_SESSION["user"]));
                        }
                    }
                }
                gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => "system"), "Created post:<a href='/info/posts/" . $latname . "'>" . $_POST["posttitle"] . "</a>");
                header("Location: /cpinfo/edit/" . $latname);
                $msg[] = 'You posted successfully the info.';
            }
        }
        if (isset($_POST['updpost'])) {
            $sql = "update knowledge_info set tags=?, gitprepared='1', category=?, cat_name=?, cattext=? , public=?, accgroups=? where cat_latname=?";
            $q = $pdo->prepare($sql);
            $q->execute(array(htmlspecialchars($_POST["tags"]), htmlspecialchars($_POST["category"]), htmlspecialchars($_POST["posttitle"]), $_POST["postcontent"], htmlspecialchars($_POST['public']), (!empty($_POST["respgrsel"]) ? $_POST["respgrsel"] : ""), $thisarray['p2']));
            textClass::replaceMentions($_POST["postcontent"], $_SERVER["HTTP_HOST"] . "/info/posts/" . $thisarray['p2']);
            if ($website['gittype']) {
                $shagit = "";
                if ($website['gittype'] == "github" && $_POST["gitprepared"] == 1) {
                    $resp = vc::gitTreelist("articles/posts/" . $thisarray['p2'] . ".txt");
                    $shagit = json_decode($resp, true)["sha"];
                }
                $return = vc::gitAdd("text", $_POST["postcontent"], "articles/posts/" . $thisarray['p2'] . ".txt", ($_POST["gitprepared"] == 1 ? true : false), $shagit, true);
                if (empty($return["err"])) {
                    $tmp["gitupload"] = "articles/posts/" . $thisarray['p2'] . ".txt";
                } else {
                    $msg[] = $return["err"];
                    $tmp["gitupload"] = false;
                }
                if ($tmp["gitupload"]) {
                    $resp = vc::GetCommitID($tmp["gitupload"]);
                    $lastcommit = json_decode($resp, true)[0]["id"];
                    if ($lastcommit) {
                        $sql = "insert into env_gituploads (gittype,commitid,packuid,fileplace,steptype,stepuser) values (?,?,?,?,?,?)";
                        $q = $pdo->prepare($sql);
                        $q->execute(array($website['gittype'], $lastcommit, "knowledge_info:" . $_POST['catid'], "articles/posts/" . $thisarray['p2'] . ".txt", "prepare", $_SESSION["user"]));
                    }
                }
            }
            gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => "system"), "Updated post:<a href='/info/posts/" . $thisarray['p2'] . "'>" . $_POST["posttitle"] . "</a>");
            $msg[] = 'The post was updated.';
        }
        include "public/modules/css.php";?>
<?php if ($thisarray['p1'] != "new") {?>
<link rel="stylesheet" type="text/css" href="/assets/js/datatables/dataTables.bootstrap5.min.css">
<link rel="stylesheet" type="text/css" href="/assets/js/datatables/responsive.dataTables.min.css">
<?php }?>
<link rel="stylesheet" type="text/css" href="/assets/css/jquery-ui.min.css">
<link rel="stylesheet" type="text/css" href="/assets/css/tinyreset.css">
<style type="text/css">
.bootstrap-tagsinput {
    border-radius: 0px;
}
</style>
<?php echo '</head><body class="fix-header card-no-border"><div id="main-wrapper">';
        $breadcrumb["text"] = "Knowledge base";
        $brarr = array();
        array_push($brarr, array(
            "title" => "New Page",
            "link" => "/cpinfo/new",
            "midicon" => "add",
            "active" => ($page == "cpinfo") ? "active" : "",
        ));
        array_push($brarr, array(
            "title" => "Import documents",
            "link" => "/docimport",
            "midicon" => "deploy",
            "active" => ($page == "docimport") ? "active" : "",
        ));
        if (sessionClass::checkAcc($acclist, "designer")) {
            array_push($brarr, array(
                "title" => "View/Edit diagrams",
                "link" => "/draw",
                "midicon" => "diagram",
                "active" => ($page == "draw") ? "active" : "",
            ));
        }
        if (sessionClass::checkAcc($acclist, "odfiles") && !empty($website['odappid'])) {
            array_push($brarr, array(
                "title" => "View/Map OneDrive files",
                "link" => "/onedrive",
                "midicon" => "onedrive",
                "active" => ($page == "onedrive") ? "active" : "",
            ));
        }
        if (sessionClass::checkAcc($acclist, "dbfiles") && !empty($website['dbclid'])) {
            array_push($brarr, array(
                "title" => "View/Map Dropbox files",
                "link" => "/dropbox",
                "midicon" => "dropbox",
                "active" => ($page == "dropbox") ? "active" : "",
            ));
        }

        // $breadcrumb["midicon"]="kn-b";
        include "public/modules/headcontent.php"; ?>
<div class="page-wrapper">
    <div class="container-fluid">
        <!--diagrams modal-->
        <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="drlbl" aria-hidden="true">
            <div class="modal-dialog" style="width: auto;">
                <div class="modal-content">
                    <div class="modal-body">

                        <br><br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-info" onclick="selectDraw();">Insert</button>
                    </div>
                </div>
            </div>
        </div>
        <!--diagrams modal-->
        <?php if ($thisarray['p1'] == "new") {?>
        <form class="form-material" action="" method="post" enctype="multipart/form-data" name="frmUpload"
            onSubmit="return validateForm();">

            <div class="row pt-3">
                <div class="col-lg-2">
                    <?php include "public/modules/sidebar.php";?></div>
                <div class="col-lg-8">
                    <div class="card p-0">
                        <input type="text" placeholder="Title" name="posttitle" id="posttitle"
                            value="<?php echo $_POST["posttitle"]; ?>"
                            class="form-control br-0 form-control-lg  brtr-3 brtl-3 border-0" required>

                        <textarea name="postcontent" rows="10"
                            class="textarea"><?php echo $_POST["postcontent"]; ?></textarea>
                        <div class="card-footer">
                            <input placeholder="Tags" type="text" name="tags" id="tags"
                                value="<?php echo $_POST["tags"]; ?>" class="form-control br-0 validatefield"
                                data-role="tagsinput" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <br>
                    <h4><i class="mdi mdi-gesture-double-tap"></i>&nbsp;Actions</h4>
                    <br>
                    <div class="list-group">
                        <a href="/cpinfo"
                            class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action"><i
                                class="mdi mdi-history"></i>&nbsp;Back to Knowledge base</a>
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modlcat"
                            class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action"><i
                                class="mdi mdi-border-all"></i>&nbsp;Category</a>
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modlacc"
                            class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action"><i
                                class="mdi mdi-shield-lock-outline"></i>&nbsp;Permissions</a>
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modal-category-form"
                            class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action"><i
                                class="mdi mdi-plus"></i>&nbsp;New Category</a>
                        <a href="javascript:void(0)" onclick="document.getElementById('addpost').click();"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Create new Article"
                            class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action"><i
                                class="mdi mdi-content-save-outline"></i>&nbsp;Save</a>
                    </div>
                    <button type="submit" id="addpost" name="addpost" style="display:none;"></button>

                </div>
            </div>




            <!--cat modal-->
            <div class="modal fade" id="modlcat" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">

                            <select name="category" style="width:100%;" class="form-control" data-live-search="true">
                                <?php $sql = "SELECT * FROM knowledge_categories";
            $q = $pdo->prepare($sql);
            $q->execute();
            if ($zobj = $q->fetchAll()) {
                foreach ($zobj as $val) {?>
                                <option value="<?php echo $val['category']; ?>"><?php echo $val['catname']; ?></option>
                                <?php }} else {?>
                                <option value="demo">Please add category</option>
                                <?php }?>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Done</button>
                        </div>
                    </div>
                </div>
            </div>
            <!--cat modal-->
            <!--acc modal-->
            <div class="modal fade" id="modlacc" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="btn-group" role="group" aria-label="acc radios">
                                    <input type="radio" class="btn-check" name="public" id="public1" value="1"
                                        onclick="document.getElementById('accgroups').style.display = 'none';"
                                        autocomplete="off" checked>
                                    <label class="btn btn-outline-info btn-sm" for="public1">Public</label>
                                    <input type="radio" class="btn-check" name="public" id="public0" value="0"
                                        onclick="document.getElementById('accgroups').style.display = 'flex';"
                                        autocomplete="off">
                                    <label class="btn btn-outline-info btn-sm" for="public0">Private</label>
                                </div>
                            </div>
                            <div id="accgroups" class="row" style="display:none;">
                                <div class="form-group col-lg-6">
                                    <input name="users" id="autogroups" type="text" class="form-control"
                                        placeholder="Access groups">

                                    <input type="text" id="respgrsel" name="respgrsel" style="display:none;">
                                </div>
                                <div class="form-group col-lg-12">
                                    <input placeholder="Groups with access" type="text" id="tagsacc" disabled
                                        class="form-control br-0 brbr-3 brbl-3" data-role="tagsinput">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Done</button>
                        </div>
                    </div>
                </div>
            </div>
            <!--acc modal-->
        </form>
        <?php } else if ($thisarray['p1'] == "edit") {
            $sql = "SELECT id,cat_latname,cat_name,category,cattext,public,accgroups,tags,gitprepared FROM knowledge_info where cat_latname=?" . (!empty($sactive) ? " and" . $sactive : "");
            $q = $pdo->prepare($sql);
            $q->execute(array($thisarray['p2']));
            if ($zobj = $q->fetch(PDO::FETCH_ASSOC)) {

                ?><form action="" class="form-material" method="post">
            <div class="row pt-3">
                <div class="col-lg-2">
                    <?php include "public/modules/sidebar.php";?></div>
                <div class="col-lg-8">
                    <div class="card p-0">
                        <input type="text" placeholder="Title" name="posttitle" value="<?php echo $zobj['cat_name']; ?>"
                            class="form-control br-0 form-control-lg brtr-3 brtl-3 border-0" required>
                        <textarea name="postcontent" rows="10"
                            class="textarea"><?php echo $zobj['cattext']; ?></textarea>
                        <div class="card-footer"><input placeholder="Tags" type="text" name="tags" id="tags"
                                value="<?php echo $zobj['tags']; ?>"
                                class="form-control br-0 validatefield brbr-3 brbl-3" data-role="tagsinput">
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <br>
                    <h4><i class="mdi mdi-gesture-double-tap"></i>&nbsp;Actions</h4>
                    <br>
                    <div class="list-group">
                        <a href="/cpinfo"
                            class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action"><i
                                class="mdi mdi-history"></i>&nbsp;Back</a>
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modlcat"
                            class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action"><i
                                class="mdi mdi-border-all"></i>&nbsp;Category</a>
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modlacc"
                            class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action"><i
                                class="mdi mdi-shield-lock-outline"></i>&nbsp;Permissions</a>
                        <a href="javascript:void(0)"
                            onclick="getGITHistory('knowledge_info:<?php echo $zobj['id']; ?>');"
                            class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action"><i
                                class="mdi mdi-git"></i>&nbsp;Change History</a>
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modal-category-form"
                            class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action"><i
                                class="mdi mdi-plus"></i>&nbsp;New Category</a>
                        <a href="javascript:void(0)" onclick="document.getElementById('updpost').click();"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Create new Article"
                            class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action"><i
                                class="mdi mdi-content-save-outline"></i>&nbsp;Save</a>
                        <a href="javascript:void(0)" onclick="document.getElementById('delpost').click();"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Create new Article"
                            class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action"><i
                                class="mdi mdi-close"></i>&nbsp;Delete</a>

                    </div>
                    <input type="hidden" name="gitprepared" value="<?php echo $zobj['gitprepared']; ?>">
                    <button type="submit" id="updpost" name="updpost" style="display:none;"></button>
                    <button type="submit" id="delpost" name="delpost" style="display:none;"></button>

                </div>

            </div>
            <input type="hidden" name="catid" value="<?php echo $zobj['id']; ?>">
            <input type="hidden" name="postname" value="<?php echo $zobj['cat_latname']; ?>">


            <!--history modal-->
            <div class="modal fade" id="modal-hist" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" style="width:auto;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div style="display:block;">
                                <input type="text" ng-model="search" class="form-control topsearch dtfilter"
                                    placeholder="Filter">
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body pt-0">
                            <div class="table-responsive">
                                <table id="data-table-hist" class="table table-hover stylish-table mb-0"
                                    aria-busy="false" style="margin-top:0px !important;" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th data-column-id="id" data-identifier="true" data-visible="false"
                                                data-type="numeric">ID</th>
                                            <th data-column-id="commitid">Commit ID</th>
                                            <th data-column-id="filepl">File place</th>
                                            <th data-column-id="author">Author</th>
                                            <th data-column-id="commitdate">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!--history modal-->
            <!--cat modal-->
            <div class="modal fade" id="modlcat" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">

                            <select name="category" style="width:100%;" class="form-control" data-live-search="true">
                                <option value="<?php echo $zobj['category']; ?>"><?php echo $zobj['category']; ?>
                                </option>
                                <?php $sql = "SELECT * FROM knowledge_categories";
                $q = $pdo->prepare($sql);
                $q->execute();
                if ($zobjin = $q->fetchAll()) {
                    foreach ($zobjin as $val) {?>
                                <option value="<?php echo $val['category']; ?>"><?php echo $val['catname']; ?></option>
                                <?php }} else {?>
                                <option value="demo">Please add category</option>
                                <?php }?>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Done</button>
                        </div>
                    </div>
                </div>
            </div>
            <!--cat modal-->
            <!--acc modal-->
            <div class="modal fade" id="modlacc" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="btn-group" role="group" aria-label="acc radios">
                                    <input type="radio" class="btn-check" name="public" id="public1" value="1"
                                        onclick="document.getElementById('accgroups').style.display = 'none';"
                                        autocomplete="off" <?php echo $zobj['public']=="1"?"checked":""; ?>>
                                    <label class="btn btn-outline-info btn-sm" for="public1">Public</label>
                                    <input type="radio" class="btn-check" name="public" id="public0" value="0"
                                        onclick="document.getElementById('accgroups').style.display = 'flex';"
                                        autocomplete="off" <?php echo $zobj['public']=="0"?"checked":""; ?>>
                                    <label class="btn btn-outline-info btn-sm" for="public0">Private</label>
                                </div>

                            </div>
                            <div id="accgroups" class="row" style="display:<?php echo $zobj['public']=="1"?"none":"flex"; ?>;">
                                <div class="form-group col-lg-6">
                                    <input name="users" id="autogroups" type="text" class="form-control"
                                        placeholder="search for groups">
                                    <input style="display:none;" type="text" id="savedgr" value="<?php echo !empty($zobj['accgroups'])?implode(', ' , json_decode($zobj['accgroups'])):""; ?>">
                                    <input type="text" id="respgrsel" name="respgrsel" style="display:none;">

                                </div>
                                <div class="form-group col-lg-12">
                                    <input placeholder="Groups with access" type="text" id="tagsacc" disabled
                                        class="form-control br-0 brbr-3 brbl-3" data-role="tagsinput">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Done</button>
                        </div>
                    </div>
                </div>
            </div>
            <!--acc modal-->
        </form>
        <?php
} else {textClass::PageNotFound();}} else {?>
        <div class="row pt-3">
            <div class="col-lg-2">
                <?php include "public/modules/sidebar.php";?></div>
            <div class="col-lg-8">
                <div class="row">
                    <div class="card p-0">
                        <table id="data-table-ki" class="table table-hover stylish-table mb-0" aria-busy="false"
                            style="margin-top:0px !important;">
                            <thead>
                                <tr>
                                    <th data-column-id="id" data-identifier="true" data-visible="false"
                                        data-type="numeric">ID</th>
                                    <th data-column-id="article">Article</th>
                                    <th data-column-id="latname" data-visible="false"></th>
                                    <th data-column-id="category">Category</th>
                                    <th data-column-id="category">Views</th>
                                    <th data-column-id="category">Public</th>
                                    <th data-column-id="filedate">Date</th>
                                    <th data-column-id="commands" data-formatter="commands" data-sortable="false"
                                        data-align="center" data-width="100px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sql = "select id,cat_name,cat_latname,category,catdate,views,public from knowledge_info where author='" . $_SESSION["user"] . "' order by id desc";
            $q = $pdo->prepare($sql);
            $q->execute();
            if ($zobj = $q->fetchAll()) {?>

                                <?php foreach ($zobj as $val) {
                echo "<tr id='kb" . $val['id'] . "'><td >" . $val['id'] . "</td><td>" . $val['cat_name'] . "</td><td>" . $val['cat_latname'] . "</td><td >" . $val['category'] . "</td><td >" . $val['views'] . "</td><td >" . ($val['public'] == 0 ? "No" : "Yes") . "</td><td >" . date("d.m.Y", strtotime($val['catdate'])) . "</td><td></td></tr>";
            }?> <?php }?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <?php include "public/modules/filterbar.php";?>
                <?php include "public/modules/breadcrumbin.php";?>
            </div>
        </div>
        <br>
        <?php }?>
        <!--modal start -->
        <div class="modal fade" id="modal-category-form" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">

                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Category information</h4>
                    </div>
                    <form action="" method="post">
                        <div class="modal-body form-horizontal">
                            <div class="form-group row">
                                <label class="form-control-label text-lg-right col-md-3">Category name</label>
                                <div class="col-md-9"> <input name="catname" type="text" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="form-control-label text-lg-right col-md-3">Visibility</label>
                                <div class="col-md-9"> <select name="public" class="form-control">
                                        <option value="1">Public</option>
                                        <option value="0">Private</option>
                                    </select></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="addnewcat" class="btn btn-light btn-sm"><svg
                                    class="midico midico-outline">
                                    <use href="/assets/images/icon/midleoicons.svg#i-save"
                                        xlink:href="/assets/images/icon/midleoicons.svg#i-save" />
                                </svg>&nbsp;Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--modal end-->
    </div>
</div>
</div>


</div>
<?php
include "public/modules/footer.php";
        echo "</div></div>";
        include "public/modules/js.php";
        echo '<script src="/assets/js/tagsinput.min.js" type="text/javascript"></script>';?>
<?php if ($thisarray['p1'] != "new") {?>
<script src="/assets/js/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/js/datatables/dataTables.responsive.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    <?php if ($thisarray['p1'] != "edit") {?>
    let table = $('#data-table-ki').DataTable({
        "oLanguage": {
            "sSearch": "",
            "sSearchPlaceholder": "Find an article"
        },
        dom: 'Bfrtip',
        //  responsive: true,
        columnDefs: [{
            targets: -1,
            "data": null,
            "render": function(data, type, row, meta) {
                return "<div class=\"btn-group\"><button type='button' onclick=\"window.open('/info/posts/" +
                    row[2] +
                    "')\" class=\"btn btn-sm btn-light\"><svg class='midico midico-outline'><use href='/assets/images/icon/midleoicons.svg#i-search' xlink:href='/assets/images/icon/midleoicons.svg#i-search'/></svg></button><button type='button' onclick=\"location.href='/cpinfo/edit/" +
                    row[2] +
                    "'\" class=\"btn btn-sm btn-light\"><svg class='midico midico-outline'><use href='/assets/images/icon/midleoicons.svg#i-edit' xlink:href='/assets/images/icon/midleoicons.svg#i-edit'/></svg></button><button type=\"button\" class=\"btn btn-sm btn-light command-delete\"><svg class='midico midico-outline'><use href='/assets/images/icon/midleoicons.svg#i-trash' xlink:href='/assets/images/icon/midleoicons.svg#i-trash'/></svg></button></div>"
            }
        }]
    });
    $('.dtfilter').keyup(function() {
        table.search($(this).val()).draw();
    });
    $('.command-delete').on('click', function() {
        var data = table.row($(this).parents('tr')).data();
        var dataString = 'thisid=' + data[0] + '&thisname=' + data[1] +
            '&thisusr=<?php echo $_SESSION["user"]; ?>';
        $.ajax({
            type: "POST",
            url: "/api/delkni",
            data: dataString,
            success: function(html) {
                $("#kb" + data[0]).hide();
                notify('Article deleted!', 'danger');
            }
        });
    });


    <?php }?>
});
</script>
<?php }?>
<?php include "public/modules/template_end.php";
        echo '</body></html>';
    }
}