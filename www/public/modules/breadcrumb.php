<?php if($breadcrumb){ ?>
<div class="row">
    <div class="col-lg-12 align-self-center" style="min-height:50px;">
        <?php if($brenvarr){?>
        <ul class="nav nav-tabs small customtab">
            <?php foreach($brenvarr as $key=>$val){ ?>
            <li class="nav-item <?php echo $val["main"]?"border-arrow":"";?>" data-bs-toggle="tooltip"
                title="<?php echo $val["title"];?>"><a <?php if($val["tab"]){?>data-bs-toggle="tab" role="tab" onclick="$('.hidden').show()"
                    <?php } ?> class="nav-link waves-effect <?php echo $val["disabled"];?> <?php echo $val["active"];?>"
                    href="<?php echo $val["link"];?>"><?php if($val["icon"]){?><i
                        class="mdi <?php echo $val["icon"];?>"></i><?php } ?><?php if($val["img"]){ ?><img
                        src="<?php echo $val["img"];?>"
                        width="22px"><?php } ?><?php echo isset($val["text"])?"&nbsp;".$val["text"]:"";?>
                </a><?php if($val["main"]){ if(!empty($thisarray['p2']) && $thisarray['p2']!="?type=new"){ ?>&nbsp;&nbsp;&nbsp;<a href="/env/apps"
                    style="top: 29%;position: absolute;z-index: 9;right:0px;" target="_parent" class="text-danger"><i
                        class="mdi mdi-close mdi-18px"></i></a><?php  }} ?></li>
            <?php  } ?>
        </ul>
        <?php } ?>
    </div>
    <?php if($showr || $breadcrumb["special"]){?>
    <div class="col-md-2 align-self-center d-none d-md-block">
        <div class="d-flex justify-content-end">
            <div class="text-end">
                <?php if($showr){?> <a href="<?php echo $breadcrumb["linkr2"];?>" data-bs-toggle="tooltip"
                    title="<?php echo $breadcrumb["textr2"];?>">
                    <?php echo $breadcrumb["textr"];?>
                </a><?php } ?>
                <?php echo $breadcrumb["special"]?$breadcrumb["special"]:"";?>
            </div>
        </div>
    </div>
    <?php } ?>
</div>
<?php } ?>