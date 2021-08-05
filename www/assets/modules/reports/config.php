<?php
$modulelist["reports"]["name"] = "Reports";
class Class_reports
{
    public static function getPage($thisarray)
    {
        global $installedapp;
        global $website;
        global $page;
        global $bsteps;
        global $modulelist;
        global $typereq;
        global $maindir;
        global $env;
        if (!empty($env)) {  $menudataenv = json_decode($env, true); } else {  $menudataenv = array(); }
        if ($installedapp != "yes") {header("Location: /install");}
        sessionClass::page_protect(base64_encode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
        $err = array();
        $msg = array();
        $pdo = pdodb::connect();
        include "public/modules/css.php";   
   echo '<style type="text/css">@page {
    size: A4;
    margin: 0;
}html, body {
    width: 210mm;
    height: 297mm;        
}.page {
    margin: 0;
    border: initial;
    border-radius: initial;
    width: initial;
    min-height: initial;
    box-shadow: initial;
    background: initial;
    page-break-after: always;
    padding:1cm;
}.card-header + .card-body{padding-top:15px;}</style></head>';
   echo '<body class="fix-header card-no-border no-sidebar" style="background: #fff;"><div id="main-wrapper">';
    ?>
        <?php if(file_exists(__DIR__."/types/".$thisarray['p1'].".php")){ include "types/".$thisarray['p1'].".php";}   else { textClass::PageNotFound(); }?>
<?php include "public/modules/license.php";
    echo '</div>';
    include "public/modules/js.php"; ?>
    
<?php if($thisarray['p1']=="apps"){?>
<script src="/assets/js/chart.min.js" type="text/javascript"></script>
<script type="text/javascript">
    var coleff = "rgb(82,194,247)";

   var ticks_months = [ <?php for ($i = 1; $i <= 6; $i++) {$offset = 6 - $i;
            echo '[' . $i . ',"' . date('F', strtotime("-$offset months")) . '"],';}?>];
   var dataeff=[   <?php if (DBTYPE == "oracle") {
            $sql = "SELECT TO_CHAR(months.month,'YYYY-MM') as month, COALESCE(count(id),0) as requests
                 FROM
                 (select trunc(sysdate) as month from dual
                 union select trunc(sysdate) - interval '1' month as month from dual
                 union select trunc(sysdate) - interval '2' month as month from dual
                 union select trunc(sysdate) - interval '3' month as month from dual
                 union select trunc(sysdate) - interval '4' month as month from dual
                 union select trunc(sysdate) - interval '5' month as month from dual) months
                 left join requests
                 ON extract(MONTH from months.month) = EXTRACT(MONTH FROM created) AND EXTRACT(YEAR FROM months.month) = EXTRACT(YEAR FROM created)" . (!empty($_GET['app']) ? " and reqapp='" . htmlspecialchars($_GET['app']) . "'" : "") . "
                 GROUP BY months.month
                 order by months.month asc";
        } else {
            $sql = "SELECT DATE_FORMAT(months.month,'%Y-%m') as month, COALESCE(count(id),0) as request
                 FROM
                 (select curdate() as month
                 union select curdate() - interval 1 month as month
                 union select curdate() - interval 2 month as month
                 union select curdate() - interval 3 month as month
                 union select curdate() - interval 4 month as month
                 union select curdate() - interval 5 month as month) months
                 left join requests
                 ON MONTH(months.month) = MONTH(created) AND YEAR(months.month) = YEAR(created)" . (!empty($_GET['app']) ? " and reqapp='" . htmlspecialchars($_GET['app']) . "'" : "") . "
                 GROUP BY months.month
                 order by months.month asc";
        }
            $q = $pdo->prepare($sql);
            $q->execute();
            $zobj = $q->fetchAll();
            foreach ($zobj as $val) {echo "{ x: new Date('" . $val['month'] . "'),y: " . $val['request'] . "},";}?> ];
    var color = Chart.helpers.color;
    var config = {
			type: 'bar',
			data: {
				datasets: [{
					label: 'Monthly requests',
					backgroundColor: color(coleff).alpha(0.6).rgbString(),
					borderWidth: 1,
					fill: false,
					data: dataeff,
				}]
			},
			options: {
                legend: { display: false },
				responsive: true,
				title: {  display: false, /*	text: 'changes per day'*/	},
				scales: {
					xAxes: [{
            type: 'time',
            time: { unit: 'month' },
						display: true,
						scaleLabel: { display: false,labelString: 'Date'	},
						ticks: { major: {		fontColor: '#FF0000'	}}
					}],
					yAxes: [{
						display: true,
						scaleLabel: {	display: true,	labelString: 'number'}
					}]
				}
			}
		};
    window.onload = function() {
			var ctx = document.getElementById('bar-chart-eff').getContext('2d');
			window.myLine = new Chart(ctx, config);
    };

   </script>
   <?php } ?>
    <?php
    include "public/modules/template_end.php";
    if(!empty($text)){unset($text);}
     echo '</body></html>';
 }
}