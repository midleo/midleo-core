<?php $reqRows = $pdo->query('select count(id) from requests')->fetchColumn(); ?>
<div class="row">
    <div class="col-md-10">
        <div class="card" id="ngApp" ng-app="ngApp" ng-controller="ngCtrl">
            <div class="card-body">
                <form method="post" action="">
                    <?php if(isset($modulelist["sysnestable"]) && !empty($modulelist["sysnestable"])){ ?>
                    <div class="row">
                        <div class="col-md-6">
                            <?php echo SysNestable::createMenu($menudataenv,$reqRows>0?"0":"1");   ?>
                            <input type="text" id="thistype" value="env" style="display:none;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <blockquote
                            class="alert alert-<?php if($reqRows>0){ ?>danger<?php } else { ?>warning<?php } ?>">
                            <p>Environment cannot be changed if there are opened requests already.</p>
                        </blockquote>
                    </div>
                    <?php } ?>
                </form>
            </div>
        </div>
    </div>
</div>