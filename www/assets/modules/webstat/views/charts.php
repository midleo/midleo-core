<?php 
if (!empty($bsteps) && !empty($ugrarr) && !empty($widarr)) {
    $temp["bsteps"] = array();
    foreach (json_decode($bsteps, true) as $keyin => $valin) {
        $temp["bsteps"][$valin["nameshort"]]["name"] = $valin["name"];
        $temp["bsteps"][$valin["nameshort"]]["color"] = $valin["color"];
        $temp["bsteps"][$valin["nameshort"]]["wfnum"] = 0;
    }
    $sqlin = "
    SELECT COALESCE(count(id),0) as wfnum, wfbstep 
    FROM requests
    WHERE  requests.wfunit IN (" . str_repeat('?,', count($ugrarr) - 1) . '?' . ")
    AND requests.wid IN (" . str_repeat('?,', count($widarr) - 1) . '?' . ")
    group by wfbstep";
  $qin = $pdo->prepare($sqlin);
  $qin->execute(array_merge($ugrarr, $widarr));
  if ($zobjin = $qin->fetchAll()) {
    foreach ($zobjin as $valin) {
      $temp["bsteps"][$valin["wfbstep"]]["wfnum"]=$valin["wfnum"];
    }
   }
  } else { $temp["bsteps"] = array(); } ?>

<?php if ($thisarray['p1'] == "charts") {?>
  <div class="row pt-3">
    <div class="col-lg-2">
        <?php include "public/modules/sidebar.php"; ?>
    </div>
    <div class="col-lg-8">
<?php if (isset($modulelist["budget"]) && !empty($modulelist["budget"])) {?>
<form method="post" action="">
    <div class="row">
        <div class="col-md-4 position-relative">
            <input type="text" id="applauto" class="form-control topsearch" required
                placeholder="Filter" />
            <span class="searchicon"><svg class="midico midico-outline">
                    <use href="/assets/images/icon/midleoicons.svg#i-search"
                        xlink:href="/assets/images/icon/midleoicons.svg#i-search" />
                </svg>
                <input type="text" id="appname" name="appname" style="display:none;" />
        </div>
        <div class="col-md-4">
            <button type="submit" name="getforms" class="btn btn-info"><svg class="midico midico-outline">
                    <use href="/assets/images/icon/midleoicons.svg#i-search"
                        xlink:href="/assets/images/icon/midleoicons.svg#i-search" />
                </svg>&nbsp;Show</button>&nbsp;
            <a href="/webstat/charts/?" class="btn btn-light">Reset</a>
        </div>
    </div>
</form><br>
<?php }?>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4>Overview</h4>
            </div>
            <div class="card-body">
                <div class="chart-edge">
                    <canvas id="bar-chart-eff"></canvas>
                </div>
            </div>
        </div>


    </div>
    <?php if (!empty($temp["bsteps"]) && count($temp["bsteps"])>0) {?>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4>Requests</h4>
            </div>
            <div class="card-body">
                <div>
                    <canvas id="req-pie-chart" height="150"></canvas>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>
</div>
    <div class="col-md-2">
        <?php include "public/modules/breadcrumbin.php"; ?>
    </div>
    </div>
<?php } else {?>
<div class="alert alert-info">Please open the correct menu</div>
<?php }?>
</div>

<?php 
include "public/modules/footer.php";
include "public/modules/js.php"; ?>
<script src="/assets/js/chart.min.js" type="text/javascript"></script>
<script type="text/javascript">
var coleff = "rgb(82,194,247)";
var coldepl = "rgb(0,200,83)";
var colnewreq = "rgb(69,90,100)";
var colapp = "rgb(98,0,234)";

var ticks_months = [<?php for ($i = 1; $i <= 6; $i++) {$offset = 6 - $i;
            echo '[' . $i . ',"' . date('F', strtotime("-$offset months")) . '"],';}?>];
var dataeff = [<?php if (DBTYPE == "oracle") {
            $sql = "SELECT TO_CHAR(months.month,'YYYY-MM') as month, COALESCE(SUM(effdays),0) as efforts
                 FROM
                 (select trunc(sysdate) as month from dual
                 union select trunc(sysdate) - interval '1' month as month from dual
                 union select trunc(sysdate) - interval '2' month as month from dual
                 union select trunc(sysdate) - interval '3' month as month from dual
                 union select trunc(sysdate) - interval '4' month as month from dual
                 union select trunc(sysdate) - interval '5' month as month from dual) months
                 left join requests_efforts
                 ON extract(MONTH from months.month) = EXTRACT(MONTH FROM effdate) AND EXTRACT(YEAR FROM months.month) = EXTRACT(YEAR FROM effdate)" . (!empty($_POST['appname']) ? " and reqapp='" . htmlspecialchars($_POST['appname']) . "'" : "") . "
                 GROUP BY months.month
                 order by months.month asc";
        } else if(DBTYPE=="postgresql"){  
          $sql = "SELECT TO_CHAR(months.month,'YYYY-MM') as month, COALESCE(SUM(CAST(effdays AS DECIMAL(10,2))),0) as efforts
          FROM
          (select CURRENT_DATE as month
          union select CURRENT_DATE - interval '1' month as month
          union select CURRENT_DATE - interval '2' month as month
          union select CURRENT_DATE - interval '3' month as month
          union select CURRENT_DATE - interval '4' month as month
          union select CURRENT_DATE - interval '5' month as month) months
          left join requests_efforts
          ON  extract(MONTH FROM months.month) =  extract(MONTH FROM effdate) AND  extract(YEAR FROM months.month) =  extract(YEAR FROM effdate)" . (!empty($_POST['appname']) ? " and reqapp='" . htmlspecialchars($_POST['appname']) . "'" : "") . "
          GROUP BY months.month
          order by months.month asc";
        } else {
            $sql = "SELECT DATE_FORMAT(months.month,'%Y-%m') as month, COALESCE(SUM(effdays),0) as efforts
                 FROM
                 (select curdate() as month
                 union select curdate() - interval 1 month as month
                 union select curdate() - interval 2 month as month
                 union select curdate() - interval 3 month as month
                 union select curdate() - interval 4 month as month
                 union select curdate() - interval 5 month as month) months
                 left join requests_efforts
                 ON MONTH(months.month) = MONTH(effdate) AND YEAR(months.month) = YEAR(effdate)" . (!empty($_POST['appname']) ? " and reqapp='" . htmlspecialchars($_POST['appname']) . "'" : "") . "
                 GROUP BY months.month
                 order by months.month asc";
        }
            $q = $pdo->prepare($sql);
            $q->execute();
            $zobj = $q->fetchAll();
            foreach ($zobj as $val) {echo "{ x: new Date('" . $val['month'] . "'),y: " . $val['efforts'] . "},";}?>];
var dataappr = [<?php if (DBTYPE == "oracle") {
                $sql = "SELECT TO_CHAR(months.month,'YYYY-MM') as month, COALESCE(count(id),0) as requests
                 FROM
                 (select trunc(sysdate) as month from dual
                 union select trunc(sysdate) - interval '1' month as month from dual
                 union select trunc(sysdate) - interval '2' month as month from dual
                 union select trunc(sysdate) - interval '3' month as month from dual
                 union select trunc(sysdate) - interval '4' month as month from dual
                 union select trunc(sysdate) - interval '5' month as month from dual) months
                 left join requests_approval
                 ON extract(MONTH from months.month) = EXTRACT(MONTH FROM TO_DATE(apprdate,'YYYY-MM-DD')) AND EXTRACT(YEAR FROM months.month) = EXTRACT(YEAR FROM TO_DATE(apprdate,'YYYY-MM-DD'))" . (!empty($_POST['appname']) ? " and reqapp='" . htmlspecialchars($_POST['appname']) . "'" : "") . "
                 GROUP BY months.month
                 order by months.month asc";
            } else if(DBTYPE=="postgresql"){ 
              $sql = "SELECT TO_CHAR(months.month,'YYYY-MM') as month, COALESCE(count(id),0) as requests
              FROM
              (select CURRENT_DATE as month
          union select CURRENT_DATE - interval '1' month as month
          union select CURRENT_DATE - interval '2' month as month
          union select CURRENT_DATE - interval '3' month as month
          union select CURRENT_DATE - interval '4' month as month
          union select CURRENT_DATE - interval '5' month as month) months
              left join requests_approval
              ON extract(MONTH from months.month) = EXTRACT(MONTH FROM apprdate) AND EXTRACT(YEAR FROM months.month) = EXTRACT(YEAR FROM apprdate)" . (!empty($_POST['appname']) ? " and reqapp='" . htmlspecialchars($_POST['appname']) . "'" : "") . "
              GROUP BY months.month
              order by months.month asc";              
            } else {
                $sql = "SELECT DATE_FORMAT(months.month,'%Y-%m') as month, COALESCE(count(id),0) as requests
                 FROM
                 (select curdate() as month
                 union select curdate() - interval 1 month as month
                 union select curdate() - interval 2 month as month
                 union select curdate() - interval 3 month as month
                 union select curdate() - interval 4 month as month
                 union select curdate() - interval 5 month as month) months
                 left join requests_approval
                 ON MONTH(months.month) = MONTH(apprdate) AND YEAR(months.month) = YEAR(apprdate)" . (!empty($_POST['appname']) ? " and reqapp='" . htmlspecialchars($_POST['appname']) . "'" : "") . "
                 GROUP BY months.month
                 order by months.month asc";
            }
            $q = $pdo->prepare($sql);
            $q->execute();
            $zobj = $q->fetchAll();
            foreach ($zobj as $val) {echo "{ x: new Date('" . $val['month'] . "'),y: " . $val['requests'] . "},";}?>];
var datadepl = [
    <?php if (DBTYPE == "oracle") {
                $sql = "SELECT TO_CHAR(months.month,'YYYY-MM') as month, COALESCE(count(id),0) as deployments
                 FROM
                 (select trunc(sysdate) as month from dual
                 union select trunc(sysdate) - interval '1' month as month from dual
                 union select trunc(sysdate) - interval '2' month as month from dual
                 union select trunc(sysdate) - interval '3' month as month from dual
                 union select trunc(sysdate) - interval '4' month as month from dual
                 union select trunc(sysdate) - interval '5' month as month from dual) months
                 left join requests_deployments
                 ON extract(MONTH from months.month) = EXTRACT(MONTH FROM TO_DATE(depldate,'YYYY-MM-DD')) AND EXTRACT(YEAR FROM months.month) = EXTRACT(YEAR FROM TO_DATE(depldate,'YYYY-MM-DD'))" . (!empty($_POST['appname']) ? " and reqapp='" . htmlspecialchars($_POST['appname']) . "'" : "") . "
                 GROUP BY months.month
                 order by months.month asc";
            } else if(DBTYPE=="postgresql"){ 
              $sql = "SELECT TO_CHAR(months.month,'YYYY-MM') as month, COALESCE(count(id),0) as deployments
              FROM
              (select CURRENT_DATE as month
          union select CURRENT_DATE - interval '1' month as month
          union select CURRENT_DATE - interval '2' month as month
          union select CURRENT_DATE - interval '3' month as month
          union select CURRENT_DATE - interval '4' month as month
          union select CURRENT_DATE - interval '5' month as month) months
              left join requests_deployments
              ON extract(MONTH from months.month) = EXTRACT(MONTH FROM depldate) AND EXTRACT(YEAR FROM months.month) = EXTRACT(YEAR FROM depldate)" . (!empty($_POST['appname']) ? " and reqapp='" . htmlspecialchars($_POST['appname']) . "'" : "") . "
              GROUP BY months.month
              order by months.month asc";              
            } else {
                $sql = "SELECT DATE_FORMAT(months.month,'%Y-%m') as month, COALESCE(count(id),0) as deployments
                 FROM
                 (select curdate() as month
                 union select curdate() - interval 1 month as month
                 union select curdate() - interval 2 month as month
                 union select curdate() - interval 3 month as month
                 union select curdate() - interval 4 month as month
                 union select curdate() - interval 5 month as month) months
                 left join requests_deployments
                 ON MONTH(months.month) = MONTH(depldate) AND YEAR(months.month) = YEAR(depldate)" . (!empty($_POST['appname']) ? " and reqapp='" . htmlspecialchars($_POST['appname']) . "'" : "") . "
                 GROUP BY months.month
                 order by months.month asc";
            }
            $q = $pdo->prepare($sql);
            $q->execute();
            $zobj = $q->fetchAll();
            foreach ($zobj as $val) {echo "{ x: new Date('" . $val['month'] . "'),y: " . $val['deployments'] . "},";}?>];
var datanewreq = [<?php if (DBTYPE == "oracle") {
                $sql = "SELECT TO_CHAR(months.month,'YYYY-MM') as month, COALESCE(count(id),0) as requests
                 FROM
                 (select trunc(sysdate) as month from dual
                 union select trunc(sysdate) - interval '1' month as month from dual
                 union select trunc(sysdate) - interval '2' month as month from dual
                 union select trunc(sysdate) - interval '3' month as month from dual
                 union select trunc(sysdate) - interval '4' month as month from dual
                 union select trunc(sysdate) - interval '5' month as month from dual) months
                 left join requests
                 ON extract(MONTH from months.month) = EXTRACT(MONTH FROM created) AND EXTRACT(YEAR FROM months.month) = EXTRACT(YEAR FROM created)" . (!empty($_POST['appname']) ? " and reqapp='" . htmlspecialchars($_POST['appname']) . "'" : "") . "
                 GROUP BY months.month
                 order by months.month asc";
            } else if(DBTYPE=="postgresql"){ 
              $sql = "SELECT TO_CHAR(months.month,'YYYY-MM') as month, COALESCE(count(id),0) as requests
              FROM
              (select CURRENT_DATE as month
          union select CURRENT_DATE - interval '1' month as month
          union select CURRENT_DATE - interval '2' month as month
          union select CURRENT_DATE - interval '3' month as month
          union select CURRENT_DATE - interval '4' month as month
          union select CURRENT_DATE - interval '5' month as month) months
              left join requests
              ON extract(MONTH from months.month) = EXTRACT(MONTH FROM created) AND EXTRACT(YEAR FROM months.month) = EXTRACT(YEAR FROM created)" . (!empty($_POST['appname']) ? " and reqapp='" . htmlspecialchars($_POST['appname']) . "'" : "") . "
              GROUP BY months.month
              order by months.month asc";
            } else {
                $sql = "SELECT DATE_FORMAT(months.month,'%Y-%m') as month, COALESCE(count(id),0) as requests
                 FROM
                 (select curdate() as month
                 union select curdate() - interval 1 month as month
                 union select curdate() - interval 2 month as month
                 union select curdate() - interval 3 month as month
                 union select curdate() - interval 4 month as month
                 union select curdate() - interval 5 month as month) months
                 left join requests
                 ON MONTH(months.month) = MONTH(created) AND YEAR(months.month) = YEAR(created)" . (!empty($_POST['appname']) ? " and reqapp='" . htmlspecialchars($_POST['appname']) . "'" : "") . "
                 GROUP BY months.month
                 order by months.month asc";
            }
            $q = $pdo->prepare($sql);
            $q->execute();
            $zobj = $q->fetchAll();
            foreach ($zobj as $val) {echo "{ x: new Date('" . $val['month'] . "'),y: " . $val['requests'] . "},";}?>];
var color = Chart.helpers.color;
var config = {
    type: 'line',
    data: {
        datasets: [{
            label: 'Efforts',
            backgroundColor: color(coleff).alpha(0.2).rgbString(),
            borderColor: coleff,
            fill: true,
            data: dataeff,
        }, {
            label: 'Deployments',
            backgroundColor: color(coldepl).alpha(0.2).rgbString(),
            borderColor: coldepl,
            fill: true,
            data: datadepl,
        }, {
            label: 'New requests',
            backgroundColor: color(colnewreq).alpha(0.2).rgbString(),
            borderColor: colnewreq,
            fill: true,
            data: datanewreq,
        }, {
            label: 'Approved requests',
            backgroundColor: color(colapp).alpha(0.2).rgbString(),
            borderColor: colapp,
            fill: true,
            data: dataappr,
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
                    unit: 'month'
                },
                display: true,
                scaleLabel: {
                    display: false,
                    labelString: 'Date'
                },
                ticks: {
                    major: {
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
    var ctx = document.getElementById('bar-chart-eff').getContext('2d');
    window.myLine = new Chart(ctx, config);
};
<?php if (!empty($temp["bsteps"]) && count($temp["bsteps"])>0) {?>
new Chart(document.getElementById("req-pie-chart"), {
    type: "pie",
    data: {
        labels: [<?php foreach ($temp["bsteps"] as $keyin => $valin) {echo "'" . $valin["name"] . "',";}?>],
        datasets: [{
            label: "",
            backgroundColor: [
                <?php foreach ($temp["bsteps"] as $keyin => $valin) {echo "'" . $valin["color"] . "',";}?>
            ],
            borderColor: "#ababab",
            data: [
                <?php foreach ($temp["bsteps"] as $keyin => $valin) {echo "'" . $valin["wfnum"] . "',";}?>],
        }, ],
    },
    options: {
        legend: {
            labels: {
                fontColor: Chart.helpers.color,
            },
        },
        title: {
            display: true,
            fontColor: Chart.helpers.color,
            text: "Current requests by business step",
        },
    }
});
<?php } ?>
</script>