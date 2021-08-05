<div class="scroll-sidebar">
    <nav class="sidebar-nav ">
        <a class="navbar-brand" href="//<?php echo $_SERVER['HTTP_HOST']; ?>//p=welcome">
        <img data-bs-toggle="tooltip" src="/assets/images/midleo-logo-white.svg" alt="Midleo CORE"
                title="Midleo CORE" class="mainicon" />
         <img data-bs-toggle="tooltip" src="/assets/images/midleo-icon-logo-white.svg" alt="Midleo CORE"
                title="Midleo CORE" class="sqicon" />
        </a>
        <ul id="sidebarnav" class="sidebarinfo">
            <?php
$sqlin = "SELECT catname, category FROM knowledge_categories" . (!empty($sactcat) ? " where" . $sactcat : "");
$qin = $pdo->prepare($sqlin);
$qin->execute();
$zobjin = $qin->fetchAll();
foreach ($zobjin as $val) {?>
            <li class="row">
                <a class="waves-effect waves-dark col-md-10" href="/diagrams/category/<?php echo $val['catname']; ?>"
                    aria-expanded="true" style="width: 100%;">
                    <i
                        class="mdi mdi-chevron-<?php echo ($val['catname']==$keyws || $val['catname']==$blogcategory)?"down":"right";?>"></i>&nbsp;<span
                        class="hide-menu"><?php echo $val['catname']; ?></span></a>
                <?php if($val['catname']==$keyws || $val['catname']==$blogcategory){
$sqlin="SELECT id,desid,desname FROM config_diagrams where category=? ".(!empty($sactcat)?" and ".$sactcat:"");
$qin = $pdo->prepare($sqlin); 
$qin->execute(array($val['catname']));
if($zobjin = $qin->rowCount()>0){
   $zobjin = $qin->fetchAll()
?>
                <ul aria-expanded="true" class="collapse show">
                    <?php foreach($zobjin as $val) { ?>
                    <li><a href="/diagrams/v/<?php echo $val['desid'];?>" data-bs-toggle="tooltip"
                            data-bs-placement="top" alt="<?php echo $val['desname'];?>"
                            title="<?php echo $val['desname'];?>"><?php echo strip_tags(textClass::word_limiter($val['desname'],15,25));?></a>
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
</div>