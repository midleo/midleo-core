<br>
<h4><i class="mdi mdi-gesture-double-tap"></i>&nbsp;Actions</h4>
<br>
<div class="list-group">
<a href="/draw" data-bs-toggle="tooltip" data-bs-placement="top" title="Create new Article" class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action" ><i class="mdi mdi-plus"></i>&nbsp;New Diagram</a>
<?php if($forumcase=="v"){?><a href="/draw/<?php echo $thisarray["last"];?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit this Article" type="button" class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action" ><i class="mdi mdi-pencil-outline"></i>&nbsp;Edit this diagram</a><?php } ?>
</div>
<?php if($clientkeyws){?>
  <br><br>
<h4><i class="mdi mdi-tag-outline"></i>&nbsp;Tags</h4>
<br>
<div class="nav tag-cloud">
<?php  
  $kt=explode(",",$clientkeyws);
  $kt=array_unique($kt);
  shuffle($kt);
  foreach($kt as $key=>$val){ if($val<>" " and strlen($val) < 60 and strlen($val) > 0){ 
    $val=ltrim($val, ' ');
    $val=rtrim($val, ' '); ?><a class="waves-effect btn btn-light btn-sm" style="margin: 0 4px 6px 0;" href="/diagrams/tags/<?php echo $val;?>"><i class="mdi mdi-pound"></i>&nbsp;<?php echo $val;?></a><?php }} ?>
</div>
</div>
<?php } ?>