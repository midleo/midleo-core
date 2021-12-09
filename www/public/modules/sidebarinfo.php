<br>
<h4><i class="mdi mdi-gesture-double-tap"></i>&nbsp;Navigation</h4>
<br>
<div class="list-group">
    <?php
$sqlin = "SELECT catname, category FROM knowledge_categories" . (!empty($sactcat) ? " where (" . $sactcat.")" : "");
$qin = $pdo->prepare($sqlin);
$qin->execute();
$zobjin = $qin->fetchAll();
foreach ($zobjin as $val) {?>
    <a href="/info/category/<?php echo $val['category']; ?>"
        class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action <?php echo ($val['category']==$keyws || $val['category']==$blogcategory)?"active":"";?>"><?php echo $val['catname']; ?></a>
    <?php if($val['category']==$keyws || $val['category']==$blogcategory){
$sqlin="SELECT id,cat_latname,cat_name FROM knowledge_info where category=? ".(!empty($sactcat)?" and (".$sactcat.")":"");
$qin = $pdo->prepare($sqlin); 
$qin->execute(array($val['category']));
if($zobjin = $qin->rowCount()>0){
   $zobjin = $qin->fetchAll()
?>
    <?php foreach($zobjin as $val) { ?>
    <a href="/info/posts/<?php echo $val['cat_latname'];?>"
        class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action"
        data-bs-toggle="tooltip" data-bs-placement="top" alt="<?php echo $val['cat_name'];?>"
        title="<?php echo $val['cat_name'];?>">- <?php echo strip_tags($val['cat_name']);?></a>
    <?php } ?>
    <?php } ?>
    <?php   }
   ?>
    <?php }?>
</div>