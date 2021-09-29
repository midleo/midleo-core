<?php 
  sessionClass::page_protect(base64_encode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
  $pdo = pdodb::connect();
  $msg=array();
  $data=sessionClass::getSessUserData(); foreach($data as $key=>$val){  ${$key}=$val;  } 
  
    include "public/modules/css.php"; ?>
</head><body class="fix-header card-no-border"><div id="main-wrapper">
<?php $breadcrumb["text"]="Search requests"; $breadcrumb["link"]="";
 include "public/modules/headcontent.php";?>
<div class="page-wrapper"><div class="container-fluid">
<?php  
include "public/modules/breadcrumb.php";?>

    
       
        <form name="form" action="" enctype="multipart/form-data" method="post" class="form-horizontal">
<br>
          <div class="row">
          <div class="col-md-6">
          <div class="card" >
      <div class="card-header"><h4>Search for specific request</h4></div> 
       <div class="card-body card-padding" >
       <div class="form-group row">
                      <label class="form-control-label text-lg-right col-md-3">Name</label>
                      <div class="col-md-9"><input name="reqname" type="text" class="form-control" value="<?php echo !empty($_POST['reqname'])?htmlspecialchars($_POST['reqname']):"";?>"></div>
                    </div>
            <div class="form-group row">
                      <label class="form-control-label text-lg-right col-md-3">Request ID</label>
                      <div class="col-md-9"><input name="reqid" type="text" class="form-control" value="<?php echo !empty($_POST['reqid'])?htmlspecialchars($_POST['reqid']):"";?>"></div>
                    </div>
          <div class="form-group row">
                      <label class="form-control-label text-lg-right col-md-3" >Project</label>
                      <div class="col-md-9"><input ng-maxlength="20" value="<?php echo !empty($_POST['projname'])?htmlspecialchars($_POST['projname']):"";?>" name="projname" type="text" class="form-control"></div>
            </div>
             <div class="form-group row">
                      <label class="form-control-label text-lg-right col-md-3">Request Info</label>
                      <div class="col-md-9"><input name="reqinfo" type="text" class="form-control" value="<?php echo !empty($_POST['reqinfo'])?htmlspecialchars($_POST['reqinfo']):"";?>"></div>
                    </div>
            <div class="form-group row">
                      <label class="form-control-label text-lg-right col-md-3">Requested by</label>
                      <div class="col-md-9"><input name="reqby" type="text" class="form-control" value="<?php echo !empty($_POST['reqby'])?htmlspecialchars($_POST['reqby']):"";?>"></div>
                    </div>
            <div class="form-group row">
                      <label class="form-control-label text-lg-right col-md-3">Requested date</label>
                      <div class="col-md-9"><input name="reqdate" value="<?php echo !empty($_POST['reqdate'])?htmlspecialchars($_POST['reqdate']):"";?>" class="form-control date-picker-unl" id="reqdate" data-toggle="datetimepicker" data-target="#reqdate" type="text"></div>
                    </div>
          <?php if (method_exists("Class_draw", "getPage") && is_callable(array("Class_draw", "getPage"))){?>
       <div class="form-group row">
                      <label class="form-control-label text-lg-right col-md-3">Diagram content</label>
                      <div class="col-md-9"><input name="desinfo" type="text" class="form-control" value="<?php echo !empty($_POST['desinfo'])?htmlspecialchars($_POST['desinfo']):"";?>"></div>
                    </div>
        <?php } ?>
            
            
          <div class="form-group row">
                    <div class="col-md-3"></div>
                   <div class="col-md-9"><button class="btn btn-secondary" name="searchreq" type="submit"><i class="mdi mdi-magnify"></i>&nbsp;Search</button>
                    </div>
                  </div>


                  </div>
                  </div>
            </div>


            <div class="col-md-6" >
            <div class="card" >
      <div class="card-header"><h4>Search result</h4></div> 
       <div class="card-body card-padding" >

    <?php     if(isset($_POST['searchreq'])){
  if(!empty($_POST["desinfo"])){
    $sql="select reqid,desname from config_diagrams where xmldata like ?";
    $q = $pdo->prepare($sql);
    $q->execute(array("%".htmlspecialchars($_POST["desinfo"])."%"));
    $zobj = $q->fetchAll();
  if(is_array($zobj) && !empty($zobj)){ ?>
              <h4><b>found diagrams</b></h4><br>
<div class="btn-group-vertical btn-block" role="group" >
<?php
    foreach($zobj as $val) {  echo "<a class='btn btn-default btn-block waves-effect' href='/reqinfo/".$val['reqid']."'>".$val['desname']."</a>"; }
  ?></div><?php 
  } else {
    echo '<div class="alert alert-info">No info found</div>';
  }
    
  } else { $temoval=""; foreach ($_POST as $key=>$val){  $tempval.=$val; }
    if(!empty($tempval)){
    $sql="select * from requests where 1=1";
    $sql.=!empty($_POST['reqname'])?" and reqname like '%".htmlspecialchars($_POST['reqname'])."%'":"";
    $sql.=!empty($_POST['reqid'])?" and sname='".htmlspecialchars($_POST['reqid'])."'":"";
    $sql.=!empty($_POST['projname'])?" and projnum like '%".htmlspecialchars($_POST['projname'])."%'":"";
    $sql.=!empty($_POST['reqinfo'])?" and info like '%".htmlspecialchars($_POST['reqinfo'])."%'":"";
    $sql.=!empty($_POST['reqby'])?" and requser='".htmlspecialchars($_POST['reqby'])."'":"";
    $sql.=!empty($_POST['reqdate'])?" and date_format(created, '%Y-%m-%d')='".htmlspecialchars($_POST['reqdate'])."'":"";
    $q = $pdo->prepare($sql);
    $q->execute();
    $zobj = $q->fetchAll();
  if(is_array($zobj) && !empty($zobj)){ ?>
<div class="btn-group-vertical btn-block" role="group" >
<?php
    foreach($zobj as $val) {  echo "<a class='btn btn-light btn-block waves-effect  text-start' href='/reqinfo/".$val['sname']."'>".$val['reqname']."</a>"; }
  ?></div><?php 
  } else {
    echo '<div class="alert alert-info">No info found</div>';
  }
      } else { echo "<div class='alert alert-warning'>Did you forget something?<br>please write search criteria.</div>";}
    } 
  } ?>
            </div>
            </div>
            </div>
          </div>
         </form>
       </div>
    </div>
    </div>
  </div>
<?php include "public/modules/footer.php";?></div>
<?php include "public/modules/js.php";?>
<?php include "public/modules/template_end.php";?>
</body>
</html>