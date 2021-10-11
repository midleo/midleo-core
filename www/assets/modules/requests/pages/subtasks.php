<?php 
  sessionClass::page_protect(base64_encode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
  $pdo = pdodb::connect();
  $msg=array();
  $data=sessionClass::getSessUserData(); foreach($data as $key=>$val){  ${$key}=$val;  }
 if(isset($_POST["submst"])){
   $sql="update requests_tasks set assigned=?, taskstatus=?, modified=? where reqid=? and id=?";
   $q = $pdo->prepare($sql);
   $q->execute(array($_SESSION["user"],htmlspecialchars($_POST["status"]),date("Y-m-d H:i:s"),$thisarray["p1"],$thisarray["p3"]));  
   gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("reqid"=>$thisarray["p1"],"appid"=>"system"), "Updated status of task <a href='/browse/req/".$thisarray["p1"]."'>".htmlspecialchars($_POST['taskname'])."</a>");
   $msg[]="Subtask updated"; 
 }
if(isset($_POST["addtask"])){
  $taskby=explode(":",htmlspecialchars($_POST["taskby"])); 
  $taskto=explode(":",htmlspecialchars($_POST["taskto"])); 
  $sql="insert into requests_tasks(reqid,taskname,info,deadline,taskby,taskto) values(?,?,?,?,?,?)";
  $q = $pdo->prepare($sql);
  $q->execute(array($thisarray["p1"],htmlspecialchars($_POST["taskname"]),$_POST["taskinfo"],htmlspecialchars($_POST["taskdeadline"]),($taskby[0].":".$taskby[1].":".$taskby[3]),($taskto[0].":".$taskto[1].":".$taskto[3])));
  gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("reqid"=>$thisarray["p1"],"appid"=>"system"), "Created new subtask <a href='/browse/req/".$thisarray["p1"]."'>".htmlspecialchars($_POST['taskname'])."</a>");
     send_mailfinal(
        $website['system_mail'],
        $taskto[2],
        "[MidlEO] ".$thisarray["p1"].": New subtask created",
        "Hello,<br>You have received new subtask!",
        "<br><br>Please check it "."<a href=\"https://".$_SERVER["HTTP_HOST"]."/reqtasks/".$thisarray["p1"]."\" target=\"_blank\">here</a>",
        $body=array(
          "Created by"=>$taskby[3],
          "Deadline"=>htmlspecialchars($_POST["taskdeadline"]),
          "Task"=>htmlspecialchars($_POST["taskname"]),
          "Task info"=>$_POST['taskinfo']
        ),
        "full"
      );
  header("Location:/reqtasks/".$thisarray["p1"]);
  $msg[]="Subtask created"; 
}
if(isset($_POST["delsubtask"])){
  $sql="delete from requests_tasks where reqid=? and id=?";
  $q = $pdo->prepare($sql);
  $q->execute(array($thisarray["p1"],$thisarray["p3"]));  
  header("Location:/reqtasks/".$thisarray["p1"]);
  $msg[]="Subtask deleted"; 
}
$q=gTable::read("requests","*"," where sname='".$thisarray["p1"]."'");
$zobj = $q->fetch(PDO::FETCH_ASSOC);
if(!empty($zobj["wid"])){
  $sql="select ".(DBTYPE=='oracle'?"to_char(wgroups) as wgroups":"wgroups")." from config_workflows where wid=?";
  $qin = $pdo->prepare($sql); 
  $qin->execute(array($zobj["wid"]));
  if($zobjin = $qin->fetch(PDO::FETCH_ASSOC)){ 
   $wfdatagroups=!empty($zobjin["wgroups"])?json_decode($zobjin["wgroups"],true):json_decode("[{}]",true);
  } 
  } else {
   $wfdatagroups=json_decode("[{}]",true); 
  }

include "public/modules/css.php"; ?>
<style type="text/css">.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td{vertical-align:middle;text-align: center;}</style>
</head><body class="fix-header card-no-border"><div id="main-wrapper">
<?php if(!empty($thisarray["p1"])){
 $breadcrumb["link"]="/requests"; $breadcrumb["text"]="Requests"; 
 if($thisarray["p1"]=="type"){
  $breadcrumb["text2"]=$thisarray["p3"];
 } else {
  $breadcrumb["link2"]="/reqinfo/".$thisarray["p1"];
  $breadcrumb["text2"]=$thisarray["p1"];
 }
} else {
  $breadcrumb["text"]="Requests"; 
}
  ?>
<?php include "public/modules/headcontent.php";?>
<div class="page-wrapper"><div class="container-fluid">

<?php include "public/modules/breadcrumb.php";
    echo '<div class="row"><div class="col-12">'; ?>
  
  
    
       
<?php $sql="select deployed,assigned,reqname,reqtype from requests where sname=?";
    $q = $pdo->prepare($sql);
  if($q->execute(array($thisarray["p1"]))){
          $zobj = $q->fetch(PDO::FETCH_ASSOC); 
            if(!is_array($zobj) and empty($zobj)){?>
<html><head><script language="JavaScript">function redirect(){ parent.location.href="/requests" }</script></head><body onLoad="redirect()"></body></html>
<?php } ?>
       
        <div class="row">
          <div class="col-md-8">
          <div class="card"  >
          <div class="card-body p-<?php echo ($thisarray["p2"]=="view"||$thisarray["p2"]=="new")?2:0;?>" >
            <?php if($thisarray["p2"]=="view"){
            $sql="select * from requests_tasks where reqid=? and id=?";
            $q = $pdo->prepare($sql);
            $q->execute(array($thisarray["p1"],$thisarray["p3"]));  
              if($zobjin = $q->fetch(PDO::FETCH_ASSOC)){ 
                 $taskby=explode(":",$zobjin["taskby"]); 
                 $taskto=explode(":",$zobjin["taskto"]); 
                $canassign=($taskto[1]==$_SESSION["user"]?true:false);
                if($taskto[0]=="group"){ $canassign=(in_array($taskto[1],$ugrarr)?true:false);  } 
            ?>
              
          <form action="" method="post" class="form-horizontal form-material">
                  <div class="form-group row">
                    <label class="form-control-label text-lg-right col-md-3"></label>
                    <div class="col-md-9">
                    <div class="btn-group  mb-2 mb-md-0 reqbtngroup" role="group" aria-label="Request buttons">
    <?php if($taskby[1]==$_SESSION["user"] && $zobjin["taskstatus"]=="new" && empty($zobjin["assigned"])){?>   <button type="submit" name="delsubtask" class="btn btn-danger btn-sm" ><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-x" xlink:href="/assets/images/icon/midleoicons.svg#i-x" /></svg>&nbsp;Delete</button><?php } ?>
    <?php if(!in_array($zobjin["taskstatus"], array("done","closed"))){?>
  <?php if($canassign && $zobjin["taskstatus"]!="assigned"){?>  <button type="button" onclick="$('#st').val('assigned');$('#submst').click();" class="btn btn-primary btn-sm">Assign</button><?php } ?>
   <?php if($canassign && $zobjin["taskstatus"]!="progress"){?>  <button type="button" onclick="$('#st').val('progress');$('#submst').click();" class="btn btn-default btn-sm">In progress</button><?php } ?>
  <?php if($canassign && $zobjin["taskstatus"]!="done"){?>    <button type="button" onclick="$('#st').val('done');$('#submst').click();" class="btn btn-success btn-sm">Done</button><?php } ?>
  <?php if($canassign && $zobjin["rejected"]!="assigned"){?>   <button type="button" onclick="$('#st').val('rejected');$('#submst').click();" class="btn btn-inverse btn-sm">Reject</button><?php } ?>
 <?php if($taskby[1]==$_SESSION["user"] || $canassign){?>    <button type="button" onclick="$('#st').val('closed');$('#submst').click();" class="btn btn-danger btn-sm">Close</button><?php } ?>
       <input type="text" name="status" id="st" style="display:none;">
    <input type="text" name="taskid" value="<?php echo $zobjin['id'];?>" style="display:none;">
    <input type="submit" id="submst" name="submst" style="display:none;">
     <?php } ?>
                      </div>
                      </div>
                      </div>
             <div class="form-group row">
                      <label class="form-control-label text-lg-right col-md-3">Name</label>
                      <div class="col-md-9"><input name="taskname" type="text" class="form-control" value="<?php echo $zobjin['taskname'];?>">
                      </div>    </div> 
                <div class="form-group row">
                      <label class="form-control-label text-lg-right col-md-3">Task Info</label>
                      <div class="col-md-9"><textarea rows="5" name="taskinfo" class="form-control textarea"><?php echo $zobjin['info'];?></textarea>
                    </div>   </div> 
                <div class="form-group row">
                      <label class="form-control-label text-lg-right col-md-3">Created on</label>
                      <div class="col-md-9"><input name="taskcreated" type="text" class="form-control" value="<?php echo $zobjin['created'];?>">
                    </div>    </div> 
                 <div class="form-group row">
                      <label class="form-control-label text-lg-right col-md-3">Deadline</label>
                      <div class="col-md-9"><input name="taskdeadline" type="text" class="form-control" value="<?php echo $zobjin['deadline'];?>">
                    </div>    </div> 

                 <div class="form-group row">
                      <label  class="form-control-label text-lg-right col-md-3" >Created by</label>
                      <div class="col-md-9"><?php echo "<a href='/browse/".$taskby[0]."/".$taskby[1]."' target='_parent'>".$taskby[2]."</a>";?></div>
                    </div> 
                   <div class="form-group row">
                      <label  class="form-control-label text-lg-right col-md-3" >Created for</label>
                      <div class="col-md-9"><?php  echo "<a href='/browse/".$taskto[0]."/".$taskto[1]."' target='_parent'>".$taskto[2]."</a>";?></div>
                    </div> 
                 <div class="form-group row">
                      <label  class="form-control-label text-lg-right col-md-3">Status</label>
                      <div class="col-md-9"><span class="badge badge-info"><?php echo $zobjin["taskstatus"];?></span></div>
                    </div> 
                
            </form>
         <?php   } else {
                textClass::PageNotFound(); 
              }
            } elseif($thisarray["p2"]=="new"){ ?>

                <form action="" method="post" class="form-horizontal form-material">
             <div class="form-group row">
                      <label class="form-control-label text-lg-right col-md-3">Name</label>
                      <div class="col-md-9"><input name="taskname" type="text" class="form-control" value="<?php echo $zobjin['taskname'];?>" required>
                    </div> </div>
                <div class="form-group row">
                      <label class="form-control-label text-lg-right col-md-3">Task Info</label>
                      <div class="col-md-9"> <textarea rows="5" name="taskinfo" class="form-control textarea"><?php echo $zobjin['info'];?></textarea>
                      </div></div>
              <div class="form-group row">
    <label class="form-control-label text-lg-right col-md-3">Assign to</label>
    <div class="col-md-9"><select class="form-control" name="taskto" required>

<?php if(!empty($wfdatagroups)){ echo '<option value="">Please select</option>';
foreach($wfdatagroups as $key=>$val){ ?>
<option value="<?php echo $val['type'];?>:<?php echo $key;?>:<?php echo $val['uemail'];?>:<?php echo $val['uname'];?>" ><?php echo $val['uname'];?> - (<?php echo $val['type'];?>)</option>
<?php 
}
} else {
  echo '<option value="">No users configured yet</option>';
}
?>
</select></div>
	 </div>
              <div class="form-group row">
                      <label class="form-control-label text-lg-right col-md-3">Deadline</label>
                      <div class="col-md-9"> <input name="taskdeadline" type="text" class="form-control date-picker" id="datepick" data-toggle="datetimepicker" data-target="#datepick" value="<?php echo $zobjin['deadline'];?>">
                    </div> </div>
            <div class="form-group row">
              <label class="form-control-label text-lg-right col-md-3"></label>
               <div class="col-md-9">
                 <input type="text" value="user:<?php echo $_SESSION["user"];?>:<?php echo $usemail;?>:<?php echo $usname;?>" name="taskby" style="display:none;">
                <button type="submit" name="addtask" class="waves-effect waves-light btn btn-light"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-add" xlink:href="/assets/images/icon/midleoicons.svg#i-add"/></svg>&nbsp;Create</button>
                </div> 
                </div> 
                  
            </form>
         <?php   } else { ?>
    <a  href="/reqtasks/<?php echo $thisarray["p1"];?>/new" target="_parent" data-bs-toggle="tooltip" data-bs-placement="top" title="Create new Subtask"  class="waves-effect waves-light btn btn-light" ><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-add" xlink:href="/assets/images/icon/midleoicons.svg#i-add" /></svg>&nbsp;Create new task</a>
         
         <?php
         $sql="select count(id) from requests_tasks where reqid=?";
         $q = $pdo->prepare($sql);
         $q->execute(array($thisarray["p1"]));
         if($q->fetchColumn()>0){  ?>
              
  <div class="table-responsive">
       <table class="table table-hover stylish-table mb-0">
        <thead><tr><th>Status</th><th>Task Name</th><th>From</th><th>To</th><th>Deadline</th></tr></thead>
       <tbody>
         <?php
            $sql="select * from requests_tasks where reqid=?";
         $q = $pdo->prepare($sql);
         $q->execute(array($thisarray["p1"])); 
         if($zobj = $q->fetchAll()){
	  foreach($zobj as $val) {
         ?>
           <tr>
             <td><font class="statusinfo <?php echo $val["taskstatus"];?>"><?php echo $val["taskstatus"];?></font></td>
            <td><a href="/reqtasks/<?php echo $thisarray["p1"];?>/view/<?php echo $val["id"];?>" target="_parent"><?php echo $val["taskname"];?></a></td>
            <td><?php $taskby=explode(":",$val["taskby"]); echo "<a href='/browse/".$taskby[0]."/".$taskby[1]."' target='_parent'>".$taskby[2]."</a>";?></td>
            <td><?php $taskto=explode(":",$val["taskto"]); echo "<a href='/browse/".$taskto[0]."/".$taskto[1]."' target='_parent'>".$taskto[2]."</a>";?></td>
            <td><?php echo date("d/m/y",strtotime($val["deadline"]));?></td>
          </tr>
        <?php }} ?>
      </tbody>
     </table>
   </div>
    
            
            <?php } else { ?>
            <div class="alert">There are no tasks yet.</div>
            
            <?php } ?>
         <?php } ?>   
         </div></div></div>
          <div class="col-md-4">
          <div class="card">
                            <div class="card-body  p-0">
                                <?php include "reqnav.php";?>
                            </div>
                        </div>
           
            </div>
          </div>
         </div>
      </div>
<?php  } else { textClass::PageNotFound(); } ?>        
    
    
                    <br>
    
    </div>
  

<?php include "public/modules/footer.php";?>
<?php include "public/modules/js.php";?>
<?php include "public/modules/template_end.php";?>
</body>
</html>