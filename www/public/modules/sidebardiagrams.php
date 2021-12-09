<br>
<h4><i class="mdi mdi-gesture-double-tap"></i>&nbsp;Navigation</h4>
<br>
<div class="list-group">
    <?php
$sqlin = "SELECT catname, category FROM knowledge_categories" . (!empty($sactcat) ? " where" . $sactcat : ""); 
$qin = $pdo->prepare($sqlin);
$qin->execute();
$zobjin = $qin->fetchAll();
foreach ($zobjin as $val) {?>
<a href="/diagrams/category/<?php echo $val['category']; ?>"
        class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action <?php echo ($val['category']==$keyws || $val['category']==$blogcategory)?"active":"";?>"><?php echo $val['catname']; ?></a>
        <?php if($val['catname']==$keyws || $val['catname']==$blogcategory){
$sqlin="SELECT id,desid,desname FROM config_diagrams where category=? ".(!empty($sactcat)?" and ".$sactcat:"");
$qin = $pdo->prepare($sqlin); 
$qin->execute(array($val['catname']));
if($zobjin = $qin->rowCount()>0){
   $zobjin = $qin->fetchAll()
?>
            <?php foreach($zobjin as $val) { ?>
                <a href="/diagrams/v/<?php echo $val['desid'];?>"
        class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action"
        data-bs-toggle="tooltip" data-bs-placement="top" alt="<?php echo $val['desname'];?>"
        title="<?php echo $val['desname'];?>">- <?php echo strip_tags(textClass::word_limiter($val['desname'],15,25));?></a>
            <?php } ?>
        <?php } ?>
        <?php   }
   ?>
    <?php }?>
</div>