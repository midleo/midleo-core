<br>
<h4><i class="mdi mdi-gesture-double-tap"></i>&nbsp;Actions</h4>
<br>
<div class="list-group">
    <?php if($breadcrumb){  if($brarr){ foreach($brarr as $key=>$val){ ?>
    <a href="<?php echo $val["link"];?>" <?php if($val["tab"]){?>data-bs-toggle="tab" role="tab"<?php } ?>
    <?php if($val["modal"]){?>data-bs-toggle="modal"<?php } ?>
        title="<?php echo $val["title"];?>"
        class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action <?php echo $val["main"]?"border-arrow":"";?>"><?php if($val["midicon"]){?><svg
            class="midico midico-outline">
            <use href="/assets/images/icon/midleoicons.svg#i-<?php echo $val["midicon"];?>"
                xlink:href="/assets/images/icon/midleoicons.svg#i-<?php echo $val["midicon"];?>" />
        </svg>
        <?php } ?><?php if($val["icon"]){?><i
            class="mdi <?php echo $val["icon"];?>"></i><?php } ?><?php if($val["img"]){ ?><img
            src="<?php echo $val["img"];?>" width="22px"></a><?php } ?>&nbsp;<?php echo $val["title"];?></a>
    <?php }}} ?>
</div>