<?php 
if(!empty($_GET["qid"])){ ?>
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body p-2">



            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-body p-1">
                <div class="chart-edge">
                    <canvas id="mqmess-chart"></canvas>
                </div>
            </div>
        </div>




    </div>
</div>

<?php }
else if(!empty($_GET["qmid"])){
    $sql="select ".(DBTYPE=='oracle'?"to_char(qminv) as qminv":"qminv")." from env_jobs_mq where qmgr=?";
    $q = $pdo->prepare($sql);
    $q->execute(array(htmlspecialchars($_GET["qmid"])));
    if($zobj = $q->fetch(PDO::FETCH_ASSOC)){ 
        $tmp["qminv"]=json_decode($zobj["qminv"],true);
    ?>
<div class="row pt-3">
    <div class="col-lg-2">
        <?php include "public/modules/sidebar.php"; ?>
    </div>
    <div class="col-lg-8">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body p-2">
                        <?php foreach($tmp["qminv"]["QMINFO"] as $key=>$val){ if($val){ ?>
                        <small><?php echo $key;?></small>
                        <h6 style="font-size: 0.8rem;margin-bottom: 0.3rem;"><?php echo $val;?></h6>
                        <?php }} ?>

                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-md-9">
                                <ul class="nav nav-tabs customtab" role="tablist">
                                    <li class="nav-item"> <a class="nav-link active" data-bs-toggle="tab" href="#ql"
                                            role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span
                                                class="hidden-xs-down">Queue List</span></a> </li>
                                    <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#chl"
                                            role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span
                                                class="hidden-xs-down">Channel List</span></a> </li>
                                </ul>
                            </div>
                            <div class="col-md-3">
                            <?php include "public/modules/filterbar.php"; ?>
                            </div>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane active" id="ql" role="tabpanel">
                                <table id="data-table" class="table table-hover stylish-table mb-0" aria-busy="false"
                                    style="margin-top:0px !important;">
                                    <thead>
                                        <tr>
                                            <th style="width:40px"></th>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Altered on</th>
                                            <th>MAXMSGL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($tmp["qminv"]["QUEUES"] as $key=>$val){ if($val["NAME"]){ ?>
                                        <tr>
                                            <td><a href="/webstat/ibmmq/?qid=<?php echo $key;?>"><i class="mdi mdi-magnify"></i></a></td>
                                            <td><?php echo $val["NAME"];?></td>
                                            <td><?php echo $val["TYPE"];?></td>
                                            <td><?php echo $val["ALTDATE"];?></td>
                                            <td><?php echo $val["MAXMSGL"];?></td>
                                        </tr>
                                        <?php }} ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="chl" role="tabpanel">
                                <table id="data-table2" class="table table-hover stylish-table mb-0" aria-busy="false"
                                    style="margin-top:0px !important;">
                                    <thead>
                                        <tr>
                                            <th style="width:40px"></th>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Altered on</th>
                                            <th>MAXMSGL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($tmp["qminv"]["CHANNELS"] as $key=>$val){ if($val["NAME"]){ ?>
                                        <tr>
                                            <td><a href="/webstat/ibmmq/?chlid=<?php echo $key;?>"><i class="mdi mdi-magnify"></i></a></td>
                                            <td><?php echo $val["NAME"];?></td>
                                            <td><?php echo $val["CHLTYPE"];?></td>
                                            <td><?php echo $val["ALTDATE"];?></td>
                                            <td><?php echo $val["MAXMSGL"];?></td>
                                        </tr>
                                        <?php }} ?>
                                    </tbody>
                                </table>


                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <?php include "public/modules/breadcrumbin.php"; ?>
    </div>
</div>
<?php } else { echo "<div class='alert alert-light'>No such Qmanager</div>";}} else { ?>
<div class="row pt-3">
    <div class="col-lg-2">
        <?php include "public/modules/sidebar.php"; ?>
    </div>
    <div class="col-lg-10">
        <div class="row" id="ngApp" ng-app="ngApp" ng-controller="ngCtrl">
            <div class="col-lg-9">


                <div class="card ngctrl p-0">
                    <table class="table table-vmiddle table-hover stylish-table mb-0">
                        <thead>
                            <tr>
                                <th class="text-center"></th>
                                <th class="text-center">Qmanager</th>
                                <th class="text-center">Application</th>
                                <th class="text-center">Server</th>
                                <th class="text-center">Repeat</th>
                                <th class="text-center" style="width:120px;">Last scan</th>
                            </tr>
                        </thead>
                        <tbody ng-init="getallMQINV()">
                            <tr ng-hide="contentLoaded">
                                <td colspan="6" style="text-align:center;font-size:1.1em;"><i
                                        class="mdi mdi-loading iconspin"></i>&nbsp;Loading...</td>
                            </tr>
                            <tr id="contloaded" class="hide"
                                dir-paginate="d in names | filter:search | orderBy:'lrun' | itemsPerPage:10"
                                pagination-id="prodx">
                                <td class="text-center"><a href="/webstat/ibmmq/?qmid={{d.qmgr}}"><i class="mdi mdi-magnify"></i></a></td>
                                <td class="text-center">{{ d.qmgr }}</td>
                                <td class="text-center">{{ d.proj }}</td>
                                <td class="text-center">{{ d.srv }}</td>
                                <td class="text-center">{{ d.repeat }}</td>
                                <td class="text-center">{{ d.lrun }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <dir-pagination-controls pagination-id="prodx" boundary-links="true"
                        on-page-change="pageChangeHandler(newPageNumber)"
                        template-url="/assets/templ/pagination.tpl.html">
                    </dir-pagination-controls>

                </div>
            </div>

            <div class="col-md-2">
                <?php include "public/modules/filterbar.php"; ?>
                <?php include "public/modules/breadcrumbin.php"; ?>
            </div>
        </div>
    </div>
    <?php } ?>

    <?php 
include "public/modules/footer.php";
include "public/modules/js.php"; ?>
    <script src="/assets/js/dirPagination.js"></script>
    <script type="text/javascript" src="/assets/js/dirPagination.js"></script>
    <script type="text/javascript" src="/assets/modules/automation/assets/js/ng-controller.js"></script>
    <?php if(!empty($_GET["qmid"]) && (empty($_GET["qid"]) || empty($_GET["chlid"]))){ ?>
    <script src="/assets/js/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/js/datatables/dataTables.responsive.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        let table = $('#data-table').DataTable({
            "oLanguage": {
                "sSearch": "",
                "sSearchPlaceholder": "Find an article"
            },
            dom: 'Bfrtip'
        });
        let table2 = $('#data-table2').DataTable({
            "oLanguage": {
                "sSearch": "",
                "sSearchPlaceholder": "Find an article"
            },
            dom: 'Bfrtip'
        });
        $('.dtfilter').keyup(function() {
            table.search($(this).val()).draw();
            table2.search($(this).val()).draw();
        });

    });
    </script><?php } ?>
    <?php if(!empty($_GET["qid"]) || !empty($_GET["chlid"])){?>
    <script src="/assets/js/chart.min.js" type="text/javascript"></script>
    <script type="text/javascript">
    var thiscolor = "#000";
    var thiscolorreq = "rgb(255, 54, 54)";
    var color = Chart.helpers.color;
    var messdata = [{
            x: new Date('<?php echo date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." -15 minutes"));?>'),
            y: <?php echo rand(0,100);?>
        },
        {
            x: new Date('<?php echo date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." -10 minutes"));?>'),
            y: <?php echo rand(0,100);?>
        },
        {
            x: new Date('<?php echo date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." -5 minutes"));?>'),
            y: <?php echo rand(0,100);?>
        },
    ];
    var config = {
        type: 'bar',
        data: {
            datasets: [{
                label: 'Number of messages',
                backgroundColor: color("#fff").rgbString(),
                borderColor: thiscolor,
                fill: false,
                data: messdata,
                pointRadius: 0,
                lineTension: 0,
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            title: {
                display: false,
            },
            scales: {
                xAxes: [{
                    type: 'time',
                    time: {
                        unit: 'minute'
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
                        display: false,
                        labelString: 'number'
                    }
                }]
            }
        }
    };

    window.onload = function() {
        var ctx = document.getElementById('mqmess-chart').getContext('2d');
        window.myLine = new Chart(ctx, config);
    };
    </script>
    <?php } ?>