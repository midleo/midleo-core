<br><div class="sticky-top" style="top:70px;">
<h4><i class="mdi mdi-gesture-double-tap"></i>&nbsp;Navigation</h4>
<br>
<ul class="list-unstyled fw-normal pb-2 small">
    <?php
$sqlin = "SELECT catname, category FROM knowledge_categories" . (!empty($sactcat) ? " where (" . $sactcat.")" : "");
$qin = $pdo->prepare($sqlin);
$qin->execute();
$zobjin = $qin->fetchAll();
foreach ($zobjin as $val) {?>
    <li>
        <a href="/info/category/<?php echo $val['category']; ?>"
            class="btn  btn-sm btn-light <?php echo ($val['category']==$keyws || $val['category']==$blogcategory)?"active":"";?>"><?php echo $val['catname']; ?></a>
        <?php if($val['category']==$keyws || $val['category']==$blogcategory){
$sqlin="SELECT id,cat_latname,cat_name FROM knowledge_info where category=? ".(!empty($sactcat)?" and (".$sactcat.")":"");
$qin = $pdo->prepare($sqlin); 
$qin->execute(array($val['category']));
if($zobjin = $qin->rowCount()>0){
   $zobjin = $qin->fetchAll()
?>
        <?php foreach($zobjin as $val) { ?>
    <li>
        <a href="/info/posts/<?php echo $val['cat_latname'];?>" class="btn btn-sm btn-light ps-1 text-start"
            data-bs-toggle="tooltip" data-bs-placement="top" alt="<?php echo $val['cat_name'];?>"
            title="<?php echo $val['cat_name'];?>"><?php echo strip_tags($val['cat_name']);?></a>
    </li>
    <?php } ?>
    <?php } ?>
    <?php   }
   ?></li>
    <?php }?>
</ul>
        </div>