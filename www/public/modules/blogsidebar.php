<br>
<h4><i class="mdi mdi-gesture-double-tap"></i>&nbsp;Actions</h4>
<br>
<div class="list-group">
<form method='post' action='/docapi/export'>
    <a href="/cpinfo/new" data-bs-toggle="tooltip" data-bs-placement="top" title="Create new Article"
        class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action"><i
            class="mdi mdi-plus"></i>&nbsp;New Page</a>
    <?php if($forumcase=="posts"){?>
      <a data-bs-toggle="tooltip" href="javascript:void(0)" onclick="document.getElementById('pdfexport').click();"
        data-bs-placement="top" title="Export in PDF"
        class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action"><i
            class="mdi mdi-file-pdf-box"></i>&nbsp;Export in PDF</a>
      <a data-bs-toggle="tooltip" href="/cpinfo/edit/<?php echo $keyws;?>"
        data-bs-placement="top" title="Edit this Article"
        class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action"><i
            class="mdi mdi-pencil-outline"></i>&nbsp;Edit</a>
    <a href="javascript:void(0)" onclick="getGITHistory('knowledge_info:<?php echo $zobj['id']; ?>');"
        class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action"><i
            class="mdi mdi-git"></i>&nbsp;Change History</a>

    <?php } ?>
    <?php if($breadcrumb){  if($brarr){ foreach($brarr as $key=>$val){ ?>
    <a href="<?php echo $val["link"];?>" data-bs-toggle="tooltip" data-bs-placement="top"
        title="<?php echo $val["title"];?>"
        class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action <?php echo $val["main"]?"border-arrow":"";?>"><?php if($val["icon"]){?><i
            class="mdi <?php echo $val["icon"];?>"></i><?php } ?><?php if($val["img"]){ ?><img
            src="<?php echo $val["img"];?>" width="22px"></a><?php } ?>&nbsp;<?php echo $val["title"];?></a>
    <?php }}} ?>
    <input type="submit" id="pdfexport" name="pdfexport" style="display:none;">
    <input type='hidden' name='thisid' value='<?php echo $keyws;?>'>
    <input type='hidden' name='thisuid' value='<?php echo $zobj['id']?$zobj['id']:"";?>'>
    <input type='hidden' name='thistype' value='kbase'>
            </form>
</div>
<?php  $clientkeyws="";
if(DBTYPE=='postgresql'){
  $sql = "SELECT tags FROM knowledge_info where ".$sactive." order by random() limit 5";
} else {
  $sql = "SELECT tags FROM knowledge_info where ".$sactive." order by rand() limit 5";
}
  $q = $pdo->prepare($sql);
  $q->execute();
  if($zobj = $q->fetchAll()){ ?>
<br><br>
<h4><i class="mdi mdi-tag-outline"></i>&nbsp;Tag cloud</h4>
<br>
<div class="nav tag-cloud"><?php
    foreach($zobj as $val) { $clientkeyws.=$val['tags'].",";  }
    $kt=explode(",",$clientkeyws);
    $kt=array_unique($kt);
    shuffle($kt);
    foreach($kt as $key=>$val){ if($val<>" " and strlen($val) < 60 and strlen($val) > 0){ 
      $val=ltrim($val, ' ');
      $val=rtrim($val, ' '); ?><a class="waves-effect btn btn-light btn-sm" style="margin: 0 4px 6px 0;"
        href="/info/tags/<?php echo $val;?>"><i class="mdi mdi-pound"></i>&nbsp;<?php echo $val;?></a><?php }}
  
  ?></div>
<?php
    }
  ?>
<div class="h2menu">
    <br>
    <h4><i class="mdi mdi-post-outline"></i>&nbsp;Content</h4>
    <br>
    <div class="list-group h2-menu">
    </div>
</div>
</div>