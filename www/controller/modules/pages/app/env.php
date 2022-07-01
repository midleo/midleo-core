<?php $reqRows = $pdo->query('select count(id) from requests')->fetchColumn(); ?>
<?php
if($reqRows==0){
array_push($brarr,array(
  "title"=>"Add new step",
  "link"=>"#",
  "id"=>"add-nmitem",
  "icon"=>"mdi-plus",
  "active"=>true,
));
}
?>
<form method="post" action="">
    <?php if(isset($modulelist["sysnestable"]) && !empty($modulelist["sysnestable"])){ ?>
    <div class="row">
        <div class="col-md-7">
            <div class="card p-0">
                <?php echo SysNestable::createMenu($menudataenv,$reqRows>0?"0":"1");   ?>
                <input type="text" id="thistype" value="env" style="display:none;">
            </div>
        </div>

        <div class="col-md-5 text-end">
            <div class="alert text-dark">
                Environment cannot be changed if there are opened requests already.
            </div>
        </div>
    </div>
    <?php } ?>
</form>