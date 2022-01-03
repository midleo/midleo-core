<script src="/<?php echo $website['corebase'];?>assets/js/jquery/jquery.min.js" type="text/javascript"></script>
<?php if(isset($_SESSION["user"])){?>
<script src="/<?php echo $website['corebase'];?>assets/js/jquery/jquery-ui.min.js" type="text/javascript"></script>
<?php } ?>
<script src="/<?php echo $website['corebase'];?>assets/js/bootstrap.min.js" type="text/javascript"></script>
<!--[if IE 9 ]>
<script src="/<?php echo $website['corebase'];?>assets/js/jquery/jquery.placeholder.min.js"></script>
<![endif]-->
<?php  if($thisarray["p0"]!="mlogin"){ ?>
<script src="/<?php echo $website['corebase'];?>assets/js/angular.min.js"></script>
<?php } ?>
<script src="/<?php echo $website['corebase'];?>assets/js/bootstrap-growl.min.js" type="text/javascript"></script>
<script src="/<?php echo $website['corebase'];?>assets/js/waves.min.js" type="text/javascript"></script>
<?php  if($thisarray["p0"]!="mlogin" || $thisarray["p0"]!="mregister"){ ?>
<script src="/<?php echo $website['corebase'];?>assets/js/sticky-kit.min.js" type="text/javascript"></script>
<script src="/<?php echo $website['corebase'];?>assets/js/sweet-alert.min.js" type="text/javascript"></script>
<?php } ?>
<?php if(isset($_SESSION["user"])){?>
<script src="/<?php echo $website['corebase'];?>assets/js/moment.min.js"></script>
<script src="/<?php echo $website['corebase'];?>assets/js/bootstrap-datetimepicker.min.js"></script>
<?php 
$arrtiny=array("/appconfig/mail","reqinfo","reqtasks","reqnew","cpinfo","reqinfocl","reqnewcl","projects","pjtemplates");
if(in_array($_SERVER['REQUEST_URI'],$arrtiny) || in_array($page,$arrtiny)){ ?><script type="text/javascript"
    src="/<?php echo $website['corebase'];?>assets/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="/<?php echo $website['corebase'];?>assets/js/tinymce/mentions.min.js"></script>
<?php } ?>
<?php } ?>
<script src="/<?php echo $website['corebase'];?>assets/js/custom.js?v=<?php echo filemtime('./'.$website['corebase'].'assets/js/custom.js');?>" type="text/javascript"></script>
<?php  if($thisarray["p0"]!="mlogin" || $thisarray["p0"]!="mregister"){ ?>
<script src="/<?php echo $website['corebase'];?>assets/js/autocomplete.js?v=<?php echo filemtime('./'.$website['corebase'].'assets/js/autocomplete.js');?>" type="text/javascript"></script>
<?php } ?>
<script type="text/javascript">
<?php if(!empty($msg)){ if(is_array($msg)){?> $(document).ready(function() {
    <?php foreach ($msg as $e) { if(!empty($e)){?>notify('<?php echo $e;?>', 'success');
    <?php }} ?>
});
<?php } else { ?> $(document).ready(function() {
    nalert('<?php echo $msg;?>', 'success');
});
<?php } } ?>
<?php if(!empty($err)){ if(is_array($err)){?> $(document).ready(function() {
    <?php foreach ($err as $e) { if(!empty($e)){?>notify('<?php echo $e;?>', 'danger');
    <?php }} ?>
});
<?php } else {?> $(document).ready(function() {
    nalert('<?php echo $err;?>', 'error');
});
<?php } } ?>
<?php if(isset($searchfind) && $searchfind=="1"){?>$(document).ready(function() {
    $('#modal-search-form').modal('show');
});
<?php } ?>
</script>