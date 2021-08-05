<form method="post" action="" class="form-material">
    <div class="row" id="ngApp" ng-app="ngApp" ng-controller="ngCtrl">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-control-label">Template header</label>
                            <textarea name="conf#varheader" rows="5" class="textarea">
                                <?php echo isset($_POST['conf#varheader'])?$_POST['conf#varheader']:$website['varheader'];?>
                            </textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Template footer</label>
                            <textarea name="conf#varfooter" rows="5" class="textarea">
                                <?php echo isset($_POST['conf#varfooter'])?$_POST['conf#varfooter']:$website['varfooter'];?>
                            </textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-3">SMTP server</label>
                        <div class="col-md-9">
                            <input type="text" name="conf#smtp_host" value="<?php echo isset($_POST['conf#smtp_host'])?$_POST['conf#smtp_host']:$website['smtp_host'];?>" class="form-control" placeholder="eg. mail.domain.com"> </div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-3">SMTP port</label>
                        <div class="col-md-9">
                            <input type="text" name="conf#smtp_port" value="<?php echo isset($_POST['conf#smtp_port'])?$_POST['conf#smtp_port']:$website['smtp_port'];?>" class="form-control" placeholder="eg. 25"> </div>
                    </div>
                    <div style="z-index:9999;position:fixed;bottom:60px; right:24px;">
                        <button type="submit" name="saveadm" id="saveadm" data-bs-toggle="tooltip" data-bs-placement="top" title="Save the changes" class="waves-effect waves-light btn btn-primary btn-circle btnnm"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-save" xlink:href="/assets/images/icon/midleoicons.svg#i-save" /></svg></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>