<?php
array_push($brarr,array(
  "title"=>"Add new step",
  "link"=>"#",
  "id"=>"add-nmitem",
  "icon"=>"mdi-plus",
  "active"=>true,
));
?>
<?php $reqRows = $pdo->query('select count(id) from requests')->fetchColumn(); ?>
<div class="row">
    <div class="col-md-10">
        <form method="post" action="">
            <?php if(isset($modulelist["sysnestable"]) && !empty($modulelist["sysnestable"])){ ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="card p-0">
                        <?php echo SysNestable::createMenu($menudataenv,$reqRows>0?"0":"1");   ?>
                        <input type="text" id="thistype" value="env" style="display:none;">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <blockquote class="alert alert-light">
                    <p>Environment cannot be changed if there are opened requests already.</p>
                </blockquote>
            </div>
            <?php } ?>
        </form>
    </div>
</div>