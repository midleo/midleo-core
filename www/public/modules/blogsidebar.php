    <br><h4><i class="mdi mdi-gesture-double-tap"></i>&nbsp;Actions</h4>
    <br>
    <form method='post' action='/docapi/export'>
    <ul class="list">
    <li class="list-item">
            <a href="/cpinfo/new" data-bs-toggle="tooltip" data-bs-placement="top" title="Create new Article"
                class="list-link"><i
                    class="mdi mdi-plus"></i>&nbsp;New Page</a>
  </li>
            <?php if($forumcase=="posts"){?>
              <li class="list-item">
            <a data-bs-toggle="tooltip" href="javascript:void(0)"
                onclick="document.getElementById('pdfexport').click();" data-bs-placement="top" title="Export in PDF"
                class="list-link"><i
                    class="mdi mdi-file-pdf-box"></i>&nbsp;Export in PDF</a>
            </li>
            <li class="list-item">
            <a data-bs-toggle="tooltip" href="/cpinfo/edit/<?php echo $keyws;?>" data-bs-placement="top"
                title="Edit this Article"
                class="list-link"><i
                    class="mdi mdi-pencil-outline"></i>&nbsp;Edit</a>
            </li>
            <li class="list-item">
            <a href="javascript:void(0)" onclick="getGITHistory('knowledge_info:<?php echo $zobj['id']; ?>');"
                class="list-link"><i
                    class="mdi mdi-git"></i>&nbsp;Change History</a>
            </li>
            <?php } ?>
            <?php if($breadcrumb){  if($brarr){ foreach($brarr as $key=>$val){ ?>
              <li class="list-item">
            <a href="<?php echo $val["link"];?>" data-bs-toggle="tooltip" data-bs-placement="top"
                title="<?php echo $val["title"];?>"
                class="list-link <?php echo $val["main"]?"border-arrow":"";?>"><?php if($val["icon"]){?><i
                    class="mdi <?php echo $val["icon"];?>"></i><?php } ?><?php if($val["img"]){ ?><img
                    src="<?php echo $val["img"];?>" width="22px"></a><?php } ?>&nbsp;<?php echo $val["title"];?></a>
                    </li>
            <?php }}} ?>
            <input type="submit" id="pdfexport" name="pdfexport" style="display:none;">
            <input type='hidden' name='thisid' value='<?php echo $keyws;?>'>
            <input type='hidden' name='thisuid' value='<?php echo $zobj['id']?$zobj['id']:"";?>'>
            <input type='hidden' name='thistype' value='kbase'>
        
    </ul>
    </form>
    <?php  $clientkeyws="";
if(DBTYPE=='postgresql'){
  $sql = "SELECT tags FROM knowledge_info order by random() limit 5";
} else {
  $sql = "SELECT tags FROM knowledge_info order by rand() limit 5";
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
        <ul class="list h2-menu">
  </ul>
    </div>
</div>