<br>
<h4><i class="mdi mdi-gesture-double-tap"></i>&nbsp;Navigation</h4>
<br>
<nav class="sidebar-nav ">
    <ul id="sidebarnav">
        <?php
$sqlin = "SELECT catname, category FROM knowledge_categories" . (!empty($sactcat) ? " where (" . $sactcat.")" : "");
$qin = $pdo->prepare($sqlin);
$qin->execute();
$zobjin = $qin->fetchAll();
foreach ($zobjin as $val) {?>
        <li class="row">
            <a class="waves-effect waves-dark col-md-10" href="/info/category/<?php echo $val['category']; ?>"
                aria-expanded="true" style="width: 100%;">
                <i
                    class="mdi mdi-chevron-<?php echo ($val['category']==$keyws || $val['category']==$blogcategory)?"down":"right";?>"></i>&nbsp;<span
                    class="hide-menu"><?php echo $val['catname']; ?></span></a>
            <?php if($val['category']==$keyws || $val['category']==$blogcategory){
$sqlin="SELECT id,cat_latname,cat_name FROM knowledge_info where category=? ".(!empty($sactcat)?" and (".$sactcat.")":"");
$qin = $pdo->prepare($sqlin); 
$qin->execute(array($val['category']));
if($zobjin = $qin->rowCount()>0){
   $zobjin = $qin->fetchAll()
?>
            <ul aria-expanded="true" class="collapse show">
                <?php foreach($zobjin as $val) { ?>
                <li><a href="/info/posts/<?php echo $val['cat_latname'];?>" data-bs-toggle="tooltip"
                        data-bs-placement="top" alt="<?php echo $val['cat_name'];?>"
                        title="<?php echo $val['cat_name'];?>"><?php echo strip_tags($val['cat_name']);?></a>
                </li>
                <?php } ?>
            </ul>
            <?php } ?>
            <?php   }
   ?>
        </li>
        <?php }?>
    </ul>
</nav>