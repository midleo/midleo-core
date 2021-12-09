<div class="sticky-top p-2 bg-light br-4">
<h4><i class="mdi mdi-gesture-double-tap"></i>&nbsp;Actions</h4>
<br>
<div class="list-group">
<form method='post' action='/docapi/export'>
<a href="/draw" data-bs-toggle="tooltip" data-bs-placement="top" title="Create new Article" class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action" ><i class="mdi mdi-plus"></i>&nbsp;New Diagram</a>
<?php if($forumcase=="v"){?>
  <a data-bs-toggle="tooltip" href="javascript:void(0)" onclick="document.getElementById('pdfexport').click();"
        data-bs-placement="top" title="Export in PDF"
        class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action"><i
            class="mdi mdi-file-pdf-box"></i>&nbsp;Export in PDF</a>
  <a href="/draw/<?php echo $thisarray["last"];?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit this Article" type="button" class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action" ><i class="mdi mdi-pencil-outline"></i>&nbsp;Edit this diagram</a><?php } ?>
  <input type="submit" id="pdfexport" name="pdfexport" style="display:none;">
    <input type='hidden' name='thisid' value='<?php echo $thisarray["last"];?>'>
    <input type='hidden' name='thisuid' value='<?php echo $zobj['id']?$zobj['id']:"";?>'>
    <input type='hidden' name='thistype' value='diagrams'>
</form>
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
</div>