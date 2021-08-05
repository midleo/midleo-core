<div class="btn-group " role="group" aria-label="Info btns">
<?php if($forumcase=="v"){?><a href="/draw/<?php echo $thisarray["last"];?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit this Article" type="button" class="waves-effect waves-light btn btn-w" >Edit</a><?php } ?>
<a href="/draw/new" data-bs-toggle="tooltip" data-bs-placement="top" title="Create new Article" type="button" class="waves-effect waves-light btn btn-w" >New</a>
</div>
<br><br>
<?php if($clientkeyws){?>
<div class="card cardtr" style="box-shadow:none;">
<div class="card-header" style="background-color:transparent;">
<span class="card-title"><i class="mdi mdi-tag-outline"></i>&nbsp;Tags</span>
</div>
<div style="padding:10px;">
<div class="nav tag-cloud">
<?php  
  $kt=explode(",",$clientkeyws);
  $kt=array_unique($kt);
  shuffle($kt);
  foreach($kt as $key=>$val){ if($val<>" " and strlen($val) < 60 and strlen($val) > 0){ 
    $val=ltrim($val, ' ');
    $val=rtrim($val, ' '); ?><a class="waves-effect btn btn-light btn-sm" style="margin: 0 4px 6px 0;" href="/diagrams/tags/<?php echo $val;?>"><i class="mdi mdi-tag"></i>&nbsp;<?php echo $val;?></a><?php }} ?>
</div>
</div>
</div>
<?php } ?>