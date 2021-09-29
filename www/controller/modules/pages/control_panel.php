<?php
class Class_cp{
  public static function getPage($thisarray){
    global $installedapp;
    global $website;
    global $maindir;
    global $modulelist;
    global $appver;
    global $projcodes;
    global $lastupdate;
    if($installedapp!="yes"){ header("Location: /install"); }
    sessionClass::page_protect(base64_encode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
    $err = array();
    $msg = array();
    $pdo = pdodb::connect(); 
    include "public/modules/css.php"; 
    if(empty($_SESSION['appnewver']) && $website['check_new_ver']==1){
    if(!empty($website['proxy_host'])){
      $checkupdv=json_decode(notiffClass::checkUpdate($website['proxy_host'].":".$website['proxy_port'],$_SESSION['user'].":".documentClass::decryptIt($_SESSION['usrpwd'])),true);
    } else {   
      $checkupdv=json_decode(notiffClass::checkUpdate(),true);  
    }
    if(bccomp($checkupdv['version'], $appver, 2)==1){ $_SESSION['lastupdate']=$lastupdate; $_SESSION['appnewver']=$checkupdv['version']; $_SESSION['appnewvernum']=$checkupdv['version']; } else { $_SESSION['appnewver']="no"; $_SESSION['appnewvernum']="0"; }
    $err[]=$checkupdv['errorlog']; 
  }
    $data=sessionClass::getSessUserData(); foreach($data as $key=>$val){  ${$key}=$val; } 
    $breadcrumb["text"]="Dashboard";
    $breadcrumb["midicon"]="dashboard";
    $page="dashboard";
    echo '</head><body class="fix-header card-no-border"><div id="main-wrapper">';
    include "public/modules/headcontent.php";
    echo '<div class="page-wrapper"><div class="container-fluid">';
    include "public/modules/breadcrumb.php";

    ?>
<div id="ngApp" ng-app="ngApp" ng-controller="ngCtrl">
<div class="row">
<div class="col-md-6">
<div class="card">
          <div class="card-body p-0">
          <table class="table table-vmiddle table-hover stylish-table mb-0">
<thead><tr><th colspan="2" class="text-start" style="vertical-align:top;"><h4>Latest projects</h4></th><th colspan="2" class="text-end" style="vertical-align:top;"><a href="/projects">All Projects</a></th></tr></thead>
<tbody>
<tr style="display:none;"><td colspan="4"></td></tr>
<?php 
if(empty($_SESSION["userdata"]["pjarr"])){ $_SESSION["userdata"]["pjarr"] = array(); $argpjarr=0; } else { $argpjarr=1;}

$sql = "select projcode,projname,projstatus,projduedate from config_projrequest where ".(!empty($_SESSION["userdata"]["pjarr"])?"( owner='".$_SESSION["user"]."' or serviceid in (" . str_repeat('?,', count($_SESSION["userdata"]["pjarr"]) - $argpjarr) . '?' . "))":" requser=?");
$q = $pdo->prepare($sql);
if(!empty($_SESSION["userdata"]["pjarr"])){
  $q->execute($_SESSION["userdata"]["pjarr"]);
} else {
  $q->execute(array($_SESSION["user"])); 
}


if($zobj = $q->fetchAll()){
  foreach($zobj as $val) { ?>
<tr><td><?php echo $val["projname"];?></td><td style="width:100px;" class="text-center"><span class="badge badge-<?php echo $projcodes[$val['projstatus']]["badge"];?>"><?php echo $projcodes[$val['projstatus']]["name"];?></span></td><td style="width:100px;" class="text-start"><?php echo date("d/m/Y",strtotime($val["projduedate"]));?></td><td style="width:50px;"><a href="/projects/?pjid=<?php echo $val["projcode"];?>"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-right" xlink:href="/assets/images/icon/midleoicons.svg#i-right"/></svg></a></td></tr>
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
<thead><tr><th colspan="2" class="text-start" style="vertical-align:top;"><h4>Latest requests</h4></th><th colspan="2" class="text-end" style="vertical-align:top;"><a href="/requests">All Requests</a></th></tr></thead>
<tbody ng-init="getAllreq('<?php echo $ugr;?>','')">
<tr ng-hide="contentLoaded"><td colspan="4" style="text-align:center;font-size:1.1em;"><i class="mdi mdi-loading iconspin"></i>&nbsp;Loading...</td></tr>
<tr id="contloaded" dir-paginate="d in names | filter:search | orderBy:'deadline' | orderBy:'status':reverse| orderBy:'-priorityval' | itemsPerPage:5"  ng-class="d.reqactive==1 ? 'hide active' : 'hide none'" pagination-id="prodx">
<td class="text-start">{{ d.name }}</td>
<td class="text-center" style="width:80px;">{{ d.deadline }}</td>
<td class="text-end"><span class="badge badge-{{ d.statusinfo }}">{{ d.statusinfotxt }}</span></td>
<td style="width:50px;"><a href="/reqinfo/{{ d.sname }}" target="_parent"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-right" xlink:href="/assets/images/icon/midleoicons.svg#i-right"/></svg></a></td>
</tr>
</tbody>
</table>

          </div>
          </div>

</div>
</div>
      <div class="row"><div class="col-md-6"> 
        
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
      </div>
      <div class="col-md-6">
      <div class="card">
          <div class="card-body p-0">
        
          <table class="table table-vmiddle table-hover stylish-table mb-0">
<thead><tr><th  class="text-start" style="vertical-align:top;"><h4>Recently used apps</h4></th><th colspan="2" class="text-end" style="vertical-align:top;"><a href="/env/apps">All Applications</a></th></tr></thead>
<tbody>
<tr style="display:none;"><td colspan="3"></td></tr>
<?php
$sql="select ".(DBTYPE=='oracle'?"to_char(recentdata) as recentdata":"recentdata")." from users_recent where uuid=?";
 $q = $pdo->prepare($sql); 
 $q->execute(array($_SESSION["user_id"]));
 if($zobj = $q->fetch(PDO::FETCH_ASSOC)){ 
  foreach(json_decode($zobj["recentdata"],true) as $key=>$val){
?>
<tr><td class="text-start"><?php echo $val["name"];?></td><td></td><td style="width:50px" class="text-end"><a target="_parent" href="/env/apps/<?php echo $val["id"];?>"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-right" xlink:href="/assets/images/icon/midleoicons.svg#i-right"/></svg></a></td></tr>
<?php }
}?>
</tbody>
</table>

          </div>
          </div>
      </div>
    </div>
    <div class="row">
    <div class="col-md-6">
    <div class="card">
          <div class="card-body p-0">
          <table class="table table-vmiddle table-hover stylish-table mb-0">
<thead><tr><th colspan="3"><h4>Recent activity</h4></th></tr></thead>
<tbody>
<tr style="display:none;"><td colspan="3"></td></tr>
<?php if(empty($_SESSION["userdata"]["apparr"])){ $_SESSION["userdata"]["apparr"] = array(); $argapparr=0; } else { $argapparr=1;} 
      if(empty($_SESSION["userdata"]["widarr"])){ $_SESSION["userdata"]["widarr"] = array(); $argwidarr=0; } else { $argwidarr=1;} 
              if($_SESSION["user_level"]>3){
                if(!in_array("system",$_SESSION["userdata"]["apparr"])){
                  $_SESSION["userdata"]["apparr"][]="system";
                }
              } 
              if(empty($_SESSION["userdata"]["apparr"])){
                $sql = "select * from tracking where whoid=? ".($dbtype=="oracle"?" and ROWNUM <= 5 order by id desc":" order by id desc limit 5");
                $q = $pdo->prepare($sql); 
                $q->execute(array($_SESSION["user"]));
              } else {
                $sql = "select * from tracking where whoid='".$_SESSION["user"]."' or appid in (" . str_repeat('?,', count($_SESSION["userdata"]["apparr"]) - $argapparr) . '?' . ") ".($dbtype=="oracle"?" and ROWNUM <= 5 order by id desc":" order by id desc limit 5");
                $q = $pdo->prepare($sql); 
                $q->execute($_SESSION["userdata"]["apparr"]);
              }
  if($zobj = $q->fetchAll()){ 
    foreach($zobj as $val) {
      ?><tr><td class="text-start"><a href="/browse/user/<?php echo $val['whoid'];?>" target="_blank"><?php echo $val['who'];?></a></td>
      <td class="text-start"><?php echo $val['what'];?></td>
      <td class="text-end"><?php echo textClass::getTheDay($val['trackdate']);?></td>
                 </tr>
                 <?php }} else { ?>  
                  <tr style="display:none;"><td colspan="3">No activity yet</td></tr>
              <?php } ?>
</tbody>
             </table>
          </div>
        </div>
        </div>
        <div class="col-md-6">
        
        </div>
        </div>
        </div>
<?php
    include "public/modules/footer.php";
    include "public/modules/js.php"; ?>
    <script src="/assets/js/dirPagination.js"></script>
<script type="text/javascript" src="/assets/modules/requests/assets/js/ng-controller.js"></script>
<script src="/assets/js/chart.min.js" type="text/javascript"></script>
<script type="text/javascript">
    var thiscolor = "#000";
    var thiscolorreq = "rgb(255, 54, 54)";
    var chdata=[ <?php $thismonth=date('Y-m-d',strtotime('today -1 week')); 
               if(DBTYPE=="oracle"){
               $sql="SELECT * FROM (SELECT days.day as thisdate, count(id) as num
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
               on days.day = to_char(trackdate, 'DD.MON.YYYY')".
              (empty($_SESSION["userdata"]["apparr"])?" and whoid=?":" and appid in (" . str_repeat('?,', count($_SESSION["userdata"]["apparr"]) - $argapparr) . "?" . ")")."
               group by days.day
               order by days.day desc) WHERE rownum <= 10";
               } else if(DBTYPE=="postgresql"){
                $sql="SELECT days.day as thisdate, count(id) as num
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
               on days.day = DATE(trackdate)".
              (empty($_SESSION["userdata"]["apparr"])?" where whoid=?":" where appid in (" . str_repeat('?,', count($_SESSION["userdata"]["apparr"]) - $argapparr) . "?" . ")")."
               group by days.day
               order by days.day desc limit 10";
               } else {           
               $sql="SELECT days.day as thisdate, count(id) as num
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
               on days.day = DATE(trackdate)".
              (empty($_SESSION["userdata"]["apparr"])?" and whoid=?":" and appid in (" . str_repeat('?,', count($_SESSION["userdata"]["apparr"]) - $argapparr) . "?" . ")")."
               group by days.day
               order by days.day desc limit 10";
               }
               $q = $pdo->prepare($sql);
               if(empty($_SESSION["userdata"]["apparr"])){
                $q->execute(array($_SESSION["user"]));
               } else {
                $q->execute($_SESSION["userdata"]["apparr"]);
               }
               $zobj = $q->fetchAll();
               foreach($zobj as $val) { echo "{ x: new Date('".$val['thisdate']."'),y: ".$val['num']."},";} ?> ];
	var reqdata=[ <?php $thismonth=date('Y-m-d',strtotime('today -1 week')); 
               if(DBTYPE=="oracle"){
               $sql="SELECT * FROM (SELECT days.day as thisdate, count(id) as num
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
               on days.day = to_char(created, 'DD.MON.YYYY')".
              (empty($_SESSION["userdata"]["widarr"])?" and requser=?":" and wid in (" . str_repeat('?,', count($_SESSION["userdata"]["widarr"]) - $argwidarr) . "?" . ")")."
               group by days.day
               order by days.day desc) WHERE rownum <= 10";
               } else if(DBTYPE=="postgresql"){ 
                $sql="SELECT days.day as thisdate, count(id) as num
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
                on days.day = DATE(created)".
                (empty($_SESSION["userdata"]["widarr"])?" and requser=?":" and wid in (" . str_repeat('?,', count($_SESSION["userdata"]["widarr"]) - $argwidarr) . "?" . ")")."
                group by days.day
                order by days.day desc limit 10";
               } else {           
               $sql="SELECT days.day as thisdate, count(id) as num
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
               on days.day = DATE(created)".
              (empty($_SESSION["userdata"]["widarr"])?" and requser=?":" and wid in (" . str_repeat('?,', count($_SESSION["userdata"]["widarr"]) - $argwidarr) . "?" . ")")."
               group by days.day
               order by days.day desc limit 10";
               }
               $q = $pdo->prepare($sql);
               if(empty($_SESSION["userdata"]["widarr"])){
                $q->execute(array($_SESSION["user"]));
               } else {
                $q->execute($_SESSION["userdata"]["widarr"]);
               }
               $zobj = $q->fetchAll();
               foreach($zobj as $val) { echo "{ x: new Date('".$val['thisdate']."'),y: ".$val['num']."},";} ?> ];
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
				},{
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
				title: {  display: false, /*	text: 'changes per day'*/	},
				scales: {
					xAxes: [{
            type: 'time',
            time: { unit: 'day' },
						display: true,
						scaleLabel: { display: true,labelString: 'Date'	},
						ticks: { major: {	fontStyle: 'bold',	fontColor: '#FF0000'	}}
					}],
					yAxes: [{
						display: true,
						scaleLabel: {	display: true,	labelString: 'number'}
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
class Class_cpinfo{
  public static function getPage($thisarray){
    global $installedapp;
    global $website;
    global $page;
	  global $modulelist;
    global $maindir;
    if($installedapp!="yes"){ header("Location: /install"); }
    sessionClass::page_protect(base64_encode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
    $err = array();
    $msg = array();
    $pdo = pdodb::connect(); 
    $data=sessionClass::getSessUserData(); foreach($data as $key=>$val){  ${$key}=$val; } 
   // if (!sessionClass::checkAcc($acclist, "knowledge")) { header("Location:/?"); }
    if($_SESSION['user_level']<5){ header("Location: /?"); } 
    if(isset($_POST['delcat'])){
     $sql="delete from knowledge_categories where id=?";
     $q = $pdo->prepare($sql);
     $q->execute(array(htmlspecialchars($_POST["catid"])));
     $msg[]="Category was deleted.";
    }
  if(isset($_POST['addnewcat'])) {
    $newcat = htmlspecialchars($_POST["catname"]);
    $latname = textClass::cyr2lat($newcat);
    $latname = textClass::strreplace($latname);
    $sql="insert into knowledge_categories (cattype,category,catname) values(?,?,?)";
    $q = $pdo->prepare($sql);
    $q->execute(array(htmlspecialchars($_POST['cattype']),$latname,$newcat));
    $msg[]='The category was added.';
  }
    if(isset($_POST['delpost'])) {
    $sql="delete from knowledge_info where id=?";
    $q = $pdo->prepare($sql);
    $q->execute(array(htmlspecialchars($_POST["catid"])));
    header("Location: /cpinfo");
    $msg[]='The post was deleted.';
  }
   if(isset($_POST['addpost'])) {
    $posttitle = htmlspecialchars($_POST["posttitle"]);
    $latname = textClass::cyr2lat($posttitle);
    $latname = textClass::strreplace($latname);
    $sql = "select count(id) as total from knowledge_info where cat_latname=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($latname));
    if ($q->fetchColumn() > 0) {
      $err[]="Post with such name already exist. Please specify a different one.";
    } else {
      $sql="INSERT INTO knowledge_info (cat_latname,category,cat_name,public,tags,author, cattext,accgroups) VALUES (?,?,?,?,?,?,?,?)";
      $q = $pdo->prepare($sql);
      $q->execute(array($latname,htmlspecialchars($_POST["category"]),$posttitle,htmlspecialchars($_POST['cattype']),htmlspecialchars($_POST["tags"]),$_SESSION["user"],$_POST["postcontent"],(!empty($_POST["respgrsel"])?$_POST["respgrsel"]:"")));
      header("Location: /cpinfo");
      $msg[]='You posted successfully the info.';
    }
  }
     if(isset($_POST['updpost'])) {
       $sql="update knowledge_info set tags=?, category=?, cat_name=?, cattext=? , public=?, accgroups=? where cat_latname=?";
       $q = $pdo->prepare($sql);
       $q->execute(array(htmlspecialchars($_POST["tags"]),htmlspecialchars($_POST["category"]),htmlspecialchars($_POST["posttitle"]),$_POST["postcontent"],htmlspecialchars($_POST['cattype']),(!empty($_POST["respgrsel"])?$_POST["respgrsel"]:""),$thisarray['p2']));
       textClass::replaceMentions($_POST["postcontent"],$_SERVER["HTTP_HOST"]."/info/posts/".$thisarray['p2']);
       $msg[]='The post was updated.';
     }
     include "public/modules/css.php"; ?>
     <link rel="stylesheet" type="text/css" href="/assets/js/datatables/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/js/datatables/responsive.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/jquery-ui.min.css">
    <style type="text/css">
    .tox-tinymce {
    height: 700px!important;
    }
   
</style>
     <?php echo '</head><body class="fix-header card-no-border"><div id="main-wrapper">';
     $breadcrumb["text"]="Knowledge base";
     $brarr=array();
     if (sessionClass::checkAcc($acclist, "knowledge")) {
      array_push($brarr,array(
        "title"=>"Create/edit articles",
        "link"=>"/cpinfo",
        "midicon"=>"kn-b",
        "active"=>($page=="cpinfo")?"active":"",
      ));
      array_push($brarr,array(
        "title"=>"Import/View PDF",
        "link"=>"/pdf",
        "midicon"=>"documents",
        "active"=>($page=="pdf")?"active":"",
      ));
      array_push($brarr,array(
        "title"=>"Import Word documents",
        "link"=>"/word",
        "midicon"=>"documents",
        "active"=>($page=="word")?"active":"",
      ));
   }
   if (sessionClass::checkAcc($acclist, "designer")) {
    array_push($brarr,array(
      "title"=>"View/Edit diagrams",
      "link"=>"/draw",
      "midicon"=>"diagram",
      "active"=>($page=="draw")?"active":"",
    ));
  }
    if (sessionClass::checkAcc($acclist, "odfiles")) {
      array_push($brarr,array(
          "title"=>"View/Map OneDrive files",
        "link"=>"/onedrive",
        "midicon"=>"onedrive",
        "active"=>($page=="onedrive")?"active":"",
      ));
    }
    if (sessionClass::checkAcc($acclist, "dbfiles")) {
      array_push($brarr,array(
          "title"=>"View/Map Dropbox files",
        "link"=>"/dropbox",
        "midicon"=>"dropbox",
        "active"=>($page=="dropbox")?"active":"",
      ));
    }
     

    // $breadcrumb["midicon"]="kn-b"; 
    include "public/modules/headcontent.php"; ?>
    <div class="page-wrapper"><div class="container-fluid">
<?php include "public/modules/breadcrumb.php"; ?>

    <?php if($thisarray['p1']=="new"){ ?>
      <form class="form-horizontal form-material" action="" method="post" enctype="multipart/form-data" name="frmUpload" onSubmit="return validateForm();">

      <div class="row"><div class="col-md-9">
      
               <textarea name="postcontent"  rows="10" class="textarea"><?php echo $_POST["postcontent"];?></textarea>
                 
              </div>
              <div class="col-md-3">
              
     <div class="form-group">
              <input type="text" placeholder="Title" name="posttitle" id="posttitle" value="<?php echo $_POST["posttitle"];?>" class="form-control" required><span class="form-control-feedback"></span>
              </div>
              <div class="form-group">
                   <input placeholder="Tags" type="text" name="tags" id="tags" value="<?php echo $_POST["tags"];?>" class="form-control validatefield" data-role="tagsinput" required><span class="form-control-feedback"></span>
                  </div>
              <div class="form-group">
                <select name="cattype" class="form-control">
                      <option value="1" onclick="document.getElementById('accgroups').style.display = 'none';">Visible to Public</option>
                      <option value="0" onclick="document.getElementById('accgroups').style.display = 'flex';">Private</option>
                    </select>
                  </div>  
                  <div class="form-group" id="accgroups" style="display:none;">
                          <input name="users" id="autogroups" type="text" class="form-control" placeholder="Access groups">
                          <input type="text" id="respgrsel" name="respgrsel" style="display:none;">
                  </div>
                  

                  <div class="form-group row">
                    <div class="col-md-9"><select name="category" style="width:100%;" class="form-control" data-live-search="true">
                      <?php $sql="SELECT * FROM knowledge_categories";
  $q = $pdo->prepare($sql);
  $q->execute();
  if($zobj = $q->fetchAll()){
    foreach($zobj as $val) {  ?>
                      <option value="<?php echo $val['category'];?>"><?php echo $val['catname'];?></option><?php }} else { ?>
                      <option value="demo">Please add category</option>
                      <?php } ?>
                    </select>
                    </div>
                    <div class="col-md-3 text-start">
                        <button type="button" data-bs-toggle="modal" class="waves-effect btn btn-light btn-sm" href="#modal-category-form"><svg class='midico midico-outline'><use href='/assets/images/icon/midleoicons.svg#i-add' xlink:href='/assets/images/icon/midleoicons.svg#i-add' /></svg></button>
                    </div>
                    </div><br>
                    <div class="form-group text-start">
              <button type="submit" data-bs-toggle="tooltip" data-bs-placement="top" title="Post this content" name="addpost" class="btn btn-light"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-save" xlink:href="/assets/images/icon/midleoicons.svg#i-save"/></svg></button>
     </div>


              </div>
              </div>
              </form>
    <?php } else if($thisarray['p1']=="edit"){ 
      $sql="SELECT id,cat_latname,cat_name,category,cattext,tags FROM knowledge_info where cat_latname=?".(!empty($sactive)?" and".$sactive:"");
  $q = $pdo->prepare($sql);
  $q->execute(array($thisarray['p2']));
  if($zobj = $q->fetch(PDO::FETCH_ASSOC)){

    ?><form action="" class="form-horizontal form-material" method="post">
    <div class="row"><div class="col-md-9">
  
    <textarea name="postcontent"  rows="10" class="textarea"><?php echo $zobj['cattext'];?></textarea>
       </div>
       <div class="col-md-3">
       
       <div class="form-group">
               <input type="text" placeholder="Title" name="posttitle" value="<?php echo $zobj['cat_name'];?>" class="form-control" required><span class="form-control-feedback"></span>
         </div>
         <div class="form-group">
             <input type="text" placeholder="Tags" name="tags" value="<?php echo $zobj['tags'];?>" class="form-control" data-role="tagsinput"><span class="form-control-feedback"></span>
         </div>
         <div class="form-group">
                <select name="cattype" class="form-control">
                      <option value="1" onclick="document.getElementById('accgroups').style.display = 'none';">Visible to Public</option>
                      <option value="0" onclick="document.getElementById('accgroups').style.display = 'flex';">Private</option>
                    </select>
                  </div>  
                  <div class="form-group" id="accgroups" style="display:none;">
                          <input name="users" id="autogroups" type="text" class="form-control" placeholder="Access groups">
                          <input type="text" id="respgrsel" name="respgrsel" style="display:none;">
                  </div>
         <div class="form-group row">
          <div class="col-md-9"><select name="category" class="form-control">
                        <option value="<?php echo $zobj['category'];?>"><?php echo $zobj['category'];?></option>
                        <?php $sqlin="SELECT * FROM knowledge_categories";
  $qin = $pdo->prepare($sqlin);
  $qin->execute();
  $zobjin = $qin->fetchAll();
  foreach($zobjin as $val) { ?><option value="<?php echo $val['category'];?>"><?php echo $val['catname'];?></option><?php } ?>
                        </select>
                        </div>
                        <div class="col-md-3">
                        <button type="button" data-bs-toggle="modal" class="waves-effect btn btn-light btn-sm" href="#modal-category-form"><svg class='midico midico-outline'><use href='/assets/images/icon/midleoicons.svg#i-add' xlink:href='/assets/images/icon/midleoicons.svg#i-add' /></svg></button>
                        </div>
                  </div><br>
                  <div class="text-start d-grid gap-2 d-md-block">
        <button type="submit" data-bs-toggle="tooltip" data-bs-placement="top" title="Update" name="updpost" class="btn btn-light"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-save" xlink:href="/assets/images/icon/midleoicons.svg#i-save"/></svg></button>
        <button type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="Back" onclick="location.href='/cpinfo'" class="btn btn-light"><i class="mdi mdi-history"></i></button>
       <button type="submit" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete" name="delpost" class="btn btn-danger"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-x" xlink:href="/assets/images/icon/midleoicons.svg#i-x"/></svg></button>
         </div>
       </div>
       </div>
       <input type="hidden" name="catid" value="<?php echo $zobj['id'];?>">
       </form>
    <?php 
    } else { textClass::PageNotFound(); }} else { ?>
     <div class="row">
          <div class="col-md-3 position-relative">
              <input type="text" ng-model="search" class="form-control topsearch dtfilter" placeholder="Find an article">
              <span class="searchicon"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-search" xlink:href="/assets/images/icon/midleoicons.svg#i-search"/></svg>
          </div>
          <div class="col-md-9 text-end">
                  <a class="waves-effect waves-light btn btn-info" rel="tooltip" data-bs-placement="top" title="Create new article" href="/cpinfo/new" target="_parent"><svg class='midico midico-outline'><use href='/assets/images/icon/midleoicons.svg#i-add' xlink:href='/assets/images/icon/midleoicons.svg#i-add' /></svg>&nbsp;New</a>
          </div>
  </div><br>
   <div class="card">

            <table id="data-table-ki" class="table table-hover stylish-table" aria-busy="false" style="margin-top:0px !important;">
    <thead>
      <tr>
	  <th data-column-id="id" data-identifier="true" data-visible="false" data-type="numeric">ID</th>
       <th data-column-id="article">Article</th>
        <th data-column-id="latname" data-visible="false"></th>
        <th data-column-id="category">Category</th>
        <th data-column-id="category">Views</th>
        <th data-column-id="category">Public</th>
          <th data-column-id="filedate">Date</th>
          <th data-column-id="commands" data-formatter="commands" data-sortable="false" data-align="center" data-width="100px">Action</th>
      </tr>
    </thead>
    <tbody>
            
            
          <?php $sql="select id,cat_name,cat_latname,category,catdate,views,public from knowledge_info where author='".$_SESSION["user"]."' order by id desc";
$q = $pdo->prepare($sql);
  $q->execute();
  if($zobj = $q->fetchAll()){     ?>
         
<?php          foreach($zobj as $val) { 
    echo "<tr id='kb".$val['id']."'><td >".$val['id']."</td><td>".$val['cat_name']."</td><td>".$val['cat_latname']."</td><td >".$val['category']."</td><td >".$val['views']."</td><td >".($val['public']==0?"No":"Yes")."</td><td >".date("d.m.Y",strtotime($val['catdate']))."</td><td></td></tr>";
  } ?> <?php }?>
  
  </tbody>
  </table>
  
  
         
      </div>
        <br>
    <?php } ?>
    <!--modal start -->
    <div class="modal" id="modal-category-form" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                    
                      <div class="modal-content">
                      <div class="modal-header"><h4>Category information</h4></div>
                <form action="" method="post">
                          <div class="modal-body form-horizontal">
                  <div class="form-group row">
                  <label class="form-control-label text-lg-right col-md-3">Category name</label>
                        <div class="col-md-9"> <input name="catname" type="text" class="form-control" required></div>
                  </div>  
                  <div class="form-group row">
                  <label class="form-control-label text-lg-right col-md-3">Visibility</label>
                  <div class="col-md-9"> <select name="cattype" class="form-control">
                      <option value="1">Public</option>
                      <option value="0">Private</option>
                    </select></div>
                  </div>  
				              </div>
                          <div class="modal-footer">
                  <button type="submit" name="addnewcat" class="btn btn-light btn-sm"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-save" xlink:href="/assets/images/icon/midleoicons.svg#i-save"/></svg>&nbsp;Add</button>
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
    echo '<script src="/assets/js/tagsinput.min.js" type="text/javascript"></script>'; ?>
     <script src="/assets/js/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/js/datatables/dataTables.responsive.min.js"></script>
    <script type="text/javascript">
   $(document).ready(function(){
    
  let table=$('#data-table-ki').DataTable({
    "oLanguage": {
             "sSearch": "",
             "sSearchPlaceholder": "Find an article"
            },
            dom: 'Bfrtip',
          //  responsive: true,
            columnDefs: [
                { targets: -1,
                  "data": null,
                  "render": function ( data, type, row, meta ) {
                    return "<div class=\"btn-group\"><button type='button' onclick=\"location.href='/cpinfo/edit/"+row[2]+"'\" class=\"btn btn-sm btn-light\"><svg class='midico midico-outline'><use href='/assets/images/icon/midleoicons.svg#i-edit' xlink:href='/assets/images/icon/midleoicons.svg#i-edit'/></svg></button><button type=\"button\" class=\"btn btn-sm btn-light command-delete\"><svg class='midico midico-outline'><use href='/assets/images/icon/midleoicons.svg#i-trash' xlink:href='/assets/images/icon/midleoicons.svg#i-trash'/></svg></button></div>" 
                }
              }
            ]
        });
        $('.dtfilter').keyup(function(){
          table.search($(this).val()).draw() ;
        });
        $('.command-delete').on( 'click', function () {
        var data = table.row( $(this).parents('tr') ).data(); 
        var dataString = 'thisid='+ data[0]+'&thisusr=<?php echo $_SESSION["user"];?>';
        $.ajax({
          type:"POST",
          url:"/api/delkni",
          data: dataString,
          success:function(html){ $("#kb"+data[0]).hide(); notify('Article deleted!', 'error'); }
        });
       });

});
</script>
  <?php  include "public/modules/template_end.php";
    echo '</body></html>';
  }
}