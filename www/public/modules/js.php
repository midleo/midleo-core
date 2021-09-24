<script src="/assets/js/jquery/jquery.min.js" type="text/javascript"></script>
<?php if(isset($_SESSION["user"]) || isset($_SESSION["requser"])){?>
<script src="/assets/js/jquery/jquery-ui.min.js" type="text/javascript"></script>
<?php } ?>
<script src="/assets/js/bootstrap.min.js" type="text/javascript"></script>
<!--[if IE 9 ]>
<script src="/assets/js/jquery/jquery.placeholder.min.js"></script>
<![endif]-->
<script src="/assets/js/bootstrap-growl.min.js" type="text/javascript"></script>
<script src="/assets/js/waves.min.js" type="text/javascript"></script>
<script src="/assets/js/sticky-kit.min.js" type="text/javascript"></script>
<script src="/assets/js/sweet-alert.min.js" type="text/javascript"></script>
<?php if(isset($_SESSION["user"]) || isset($_SESSION["requser"])){?>
<script src="/assets/js/moment.min.js"></script>
<script src="/assets/js/bootstrap-datetimepicker.min.js"></script>
<?php 
$arrtiny=array("/appconfig/mail","reqinfo","reqtasks","reqnew","cpinfo","reqinfocl","reqnewcl","projects","pjtemplates");
if(in_array($_SERVER['REQUEST_URI'],$arrtiny) || in_array($page,$arrtiny)){ ?><script type="text/javascript" src="/assets/js/tinymce/tinymce.min.js"></script><script type="text/javascript" src="/assets/js/tinymce/mentions.min.js"></script><?php } ?>
<?php } ?>
<script src="/assets/js/custom.js?v=<?php echo filemtime('./assets/js/custom.js');?>" type="text/javascript"></script>
<script src="/assets/js/autocomplete.js?v=<?php echo filemtime('./assets/js/autocomplete.js');?>" type="text/javascript"></script>
<script type="text/javascript">
<?php if(!empty($msg)){ if(is_array($msg)){?> $(document).ready(function(){ <?php foreach ($msg as $e) { if(!empty($e)){?>notify('<?php echo $e;?>', 'success');<?php }} ?> });<?php } else { ?> $(document).ready(function(){ nalert('<?php echo $msg;?>', 'success'); });<?php } } ?>
<?php if(!empty($err)){ if(is_array($err)){?> $(document).ready(function(){ <?php foreach ($err as $e) { if(!empty($e)){?>notify('<?php echo $e;?>', 'danger');<?php }} ?> });<?php } else {?> $(document).ready(function(){ nalert('<?php echo $err;?>', 'error');});<?php } } ?>
<?php if(isset($searchfind) && $searchfind=="1"){?>$(document).ready(function(){ $('#modal-search-form').modal('show'); });<?php } ?>
<?php if(!empty($alert) && is_array($alert)){ ?>$(document).ready(function(){  $(".newmess").show(); $(".newmesssvg").addClass("anim-tada");<?php foreach ($alert as $k=>$v) {  ?>$( ".message-center" ).append('<a href="<?php echo $k;?>" class="border-bottom d-block text-decoration-none py-2 px-3"><div class="mail-contnet d-inline-block align-middle"><h5 class="my-1"><?php echo $v["head"];?></h5> <span class="mail-desc font-12 text-truncate overflow-hidden text-nowrap d-block"><?php echo $v["line1"];?></span> <span class="time font-12 mt-1 text-truncate overflow-hidden text-nowrap d-block"><?php echo $v["line2"];?></span></div></a>');<?php } ?> });<?php } ?>
</script>