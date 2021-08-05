<?php 
class Class_browse{
  public static function getPage($thisarray){
    global $installedapp;
    global $website;
	  global $page;
	  global $modulelist;
    global $maindir;
    global $typesrv;
    global $typeobj;
    if($installedapp!="yes"){ header("Location: /install"); }
    session_start();
    $err = array();
    $msg = array();
    $pdo = pdodb::connect();
    if(!empty($_SESSION["user"])){ $data=sessionClass::getSessUserData(); foreach($data as $key=>$val){  ${$key}=$val; }  } 
    if(!empty($_SESSION["requser"])){ $data=sessionClassreq::getSessUserData(); foreach($data as $key=>$val){  ${$key}=$val;  }  } 
   
    include "public/modules/css.php";
    if($thisarray['p1']=="server" || $thisarray['p1']=="appserver" || $thisarray['p1']=="serverlist"){?>
    <link rel="stylesheet" type="text/css" href="/assets/js/datatables/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/js/datatables/responsive.dataTables.min.css">
    <?php if($thisarray['p3']=="obj"){?>
    <script type="text/javascript" src="/assets/js/vis-network.min.js"></script>
    <?php } ?>
    <?php } 
    echo '</head><body class="fix-header card-no-border"><div id="main-wrapper">';
    if($thisarray['p1']=="req"){ $breadcrumb["text"]="Request info"; } 
          elseif($thisarray['p1']=="appserver"){ 
            $breadcrumb["text"]="Application server info"; 
            $showr=true;
            $breadcrumb["textr2"]="Back to application servers";
            $breadcrumb["linkr2"]="/env/appservers/".(!empty($_SESSION["userdata"]["lastappid"])?$_SESSION["userdata"]["lastappid"]:""); 
            $breadcrumb["textr"]=' <svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-app-srv" xlink:href="/assets/images/icon/midleoicons.svg#i-app-srv"/></svg>';
          } 
          elseif($thisarray['p1']=="server"){ 
            $breadcrumb["text"]="Server info"; 
            $showr=true;
            $breadcrumb["textr2"]="Back to servers";
            $breadcrumb["linkr2"]="/env/servers/".(!empty($_SESSION["userdata"]["lastappid"])?$_SESSION["userdata"]["lastappid"]:""); 
            $breadcrumb["textr"]=' <svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-server" xlink:href="/assets/images/icon/midleoicons.svg#i-server"/></svg>';
          } 
          elseif($thisarray['p1']=="draw"){ 
            $breadcrumb["text"]="Diagram info"; 
          } 
          elseif($thisarray['p1']=="serverlist"){ 
            $breadcrumb["text"]="Server list"; 
            $showr=true;
            $breadcrumb["textr2"]="Back to home";
            $breadcrumb["linkr2"]="//{$_SERVER['HTTP_HOST']}//p=welcome"; 
            $breadcrumb["textr"]=' <svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-dashboard" xlink:href="/assets/images/icon/midleoicons.svg#i-dashboard"/></svg>';
          } 
          else { $breadcrumb["text"]="User info"; } 
    include "public/modules/headcontent.php";
    ?>
    <div class="page-wrapper"><div class="container-fluid">
    <?php  include "public/modules/breadcrumb.php";?>
  <?php include "public/modules/license.php";?>
  <?php if(file_exists(__DIR__."/browse/".$thisarray['p1'].".php")){ include "browse/".$thisarray['p1'].".php";}   else { textClass::PageNotFound(); }?>
    </div>
</div>
<?php
    include "public/modules/footer.php";
    echo "</div></div>";
    include "public/modules/js.php"; 
    if($thisarray['p1']=="server" || $thisarray['p1']=="appserver" || $thisarray['p1']=="serverlist"){?>
     <script src="/assets/js/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/js/datatables/dataTables.responsive.min.js"></script>
    <script>
        let dtable=$('.datainfo').DataTable({
          "oLanguage": {
             "sSearch": ""
            },
            dom: 'Bfrtip',
            responsive: true,
            columnDefs: [
                { responsivePriority: 3, targets: 0 },
                { responsivePriority: 2, targets: -1 }
            ]
        });
        $('.dtfilter').keyup(function(){
          dtable.search($(this).val()).draw() ;
        });
    </script>
    <?php }  
    if($thisarray['p1']=="appserver" && $thisarray['p3']=="obj"){?>
<script type="text/javascript">
var nodesarr = null;
var edgesarr = null;
var network = null;
var EDGE_LENGTH_MAIN = 150;
var EDGE_LENGTH_SUB = 50;
var DIR = '/assets/images/icon/';
var options = {
        groups: {
          server: { shape: 'image', image: DIR + 'network.png'  },
          app: {    shape: 'image', image: DIR + 'app.png'      },
          code: {   shape: 'image', image: DIR + 'code.png'     },
          object: {   shape: 'image', image: DIR + 'object.png'     }
        }
      };
nodesarr = [];
edgesarr = [];
nodesarr.push({  id: '<?php echo $val["serverdns"];?>',    group: 'server',    label: '<?php echo $val["qmname"];?>' , link: "/env/appservers" });
<?php if($val["serv_type"]=="qm"){
  $temparr=array();
  $temparr["qm"]=array("qm","queues","channels","subs","topics");
  foreach($temparr["qm"] as $key){
    $sqlin="select id,proj,objname,objtype,qmgr from mqenv_mq_".$key." where qmgr=?";
    $qin = $pdo->prepare($sqlin);
    $qin->execute(array(htmlspecialchars($val["qmname"])));
    if($zobjin = $qin->fetchAll()){ ?>
    

<?php
      foreach($zobjin as $keyin=>$valin) {
       if($valin["proj"]){ $temparr["proj"][]=$valin["proj"];
      
      ?>nodesarr.push({  id: '<?php echo md5($valin["id"].$valin["objname"]);?>',    group: 'code',    label: '<?php echo $valin["objname"];?>' , link: "/" });
      edgesarr.push({from: '<?php echo $key.$valin["proj"];?>', to: '<?php echo md5($valin["id"].$valin["objname"]);?>', length: EDGE_LENGTH_SUB});
      <?php
       }
      }
    }
  

  }
  
  foreach(array_values(array_unique($temparr["proj"])) as $keyin){ ?>
    nodesarr.push({  id: '<?php echo $keyin;?>',    group: 'app',    label: '<?php echo $keyin;?>' , link: "/env/apps" });
    edgesarr.push({from: '<?php echo $val["serverdns"];?>', to: '<?php echo $keyin;?>', length: EDGE_LENGTH_MAIN});
    
    <?php foreach($temparr["qm"] as $key){ ?>
    nodesarr.push({  id: '<?php echo $key.$keyin;?>',    group: 'object',    label: '<?php echo $typeobj[$key];?>' , link: "/env/<?php echo $key;?>/<?php echo $keyin;?>" });
    edgesarr.push({from: '<?php echo $keyin;?>', to: '<?php echo $key.$keyin;?>', length: EDGE_LENGTH_SUB});
    <?php }
   }
  ?>



  <?php } ?>
  var container = document.getElementById('myobj');

  var nodes = new vis.DataSet(nodesarr);
  var edges = new vis.DataSet(edgesarr);

  var data = { nodes: nodes,  edges: edges };
  var network = new vis.Network(container, data, options);
  network.on( 'click', function(properties) {
    var ids = properties.nodes;
    var clickedNodes = nodes.get(ids);
    if(clickedNodes[0]){
      console.log('clicked nodes:', clickedNodes[0].label);
    }
  });
</script>

    <?php } 
    include "public/modules/template_end.php";
    echo '</body></html>'; 
  }
}