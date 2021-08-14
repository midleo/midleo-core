<form method="post" action="" class="form-material">
    <div class="row" id="ngApp" ng-app="ngApp" ng-controller="ngCtrl">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-4">Website name</label>

                        <div class="col-md-8">
                            <input type="text" name="conf#env_name" value="<?php echo isset($_POST['conf#env_name'])?$_POST['conf#env_name']:$website['env_name'];?>" class="form-control" placeholder="eg. My application" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-4">Timezone</label>
                        <div class="col-md-8">
                            <select name="conf#datetime" class="form-control">
                                <?php $datetime=isset($_POST['conf#datetime'])?$_POST['conf#datetime']:$website['datetime'];
  if(!empty($datetime)){ ?>
                                    <option value="<?php echo $datetime;?>">
                                        <?php echo $datetime; ?>
                                    </option>
                                    <?php }
  foreach(timezone_abbreviations_list() as $abbr => $timezone){
foreach($timezone as $val){
  if(isset($val['timezone_id'])){
echo "<option value='".$val['timezone_id']."'>".$val['timezone_id']."</option>";
  }
}
  }?>
                            </select>
                        </div>
                    </div>

                    <!--<div class="form-group">
<label class="form-control-label text-lg-right col-md-4">Website logo</label>

  <input type="text" name="conf#env_logo" value="<?php echo isset($_POST['conf#env_logo'])?$_POST['conf#env_logo']:$website['env_logo'];?>" class="form-control" placeholder="eg. /assets/logo.png"/>
  </div> -->

                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-4">Proxy server</label>
                        <div class="col-md-8">
                            <input type="text" name="conf#proxy_host" value="<?php echo isset($_POST['conf#proxy_host'])?$_POST['conf#proxy_host']:$website['proxy_host'];?>" class="form-control" placeholder="eg. proxy.domain.com" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-4">Proxy port</label>
                        <div class="col-md-8">
                            <input type="number" name="conf#proxy_port" value="<?php echo isset($_POST['conf#proxy_port'])?$_POST['conf#proxy_port']:$website['proxy_port'];?>" class="form-control" placeholder="eg. 8080" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-4">System email</label>
                        <div class="col-md-8">
                            <input type="text" name="conf#system_mail" value="<?php echo isset($_POST['conf#system_mail'])?$_POST['conf#system_mail']:$website['system_mail'];?>" class="form-control" placeholder="eg. noreply@myhost" />
                        </div>
                    </div>
                    <div style="z-index:9999;position:fixed;bottom:60px; right:24px;">
                        <button type="submit" name="saveadm" id="saveadm" data-bs-toggle="tooltip" data-bs-placement="top" title="Save the changes" class="waves-effect waves-light btn btn-primary btn-circle btnnm"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-save" xlink:href="/assets/images/icon/midleoicons.svg#i-save" /></svg></button>
                    </div>
                    <?php if(!empty($_SESSION['appnewver']) && $_SESSION['appnewver']!="no"){ ?>
                        <div class="divnewbut" style="z-index:9999;position:fixed;bottom:60px; right:84px;">
                            <button type="submit" name="updapp" data-bs-toggle="tooltip" data-bs-placement="top" title="Update the application" class="waves-effect waves-light btn btn-primary btn-circle btnnm"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-deploy" xlink:href="/assets/images/icon/midleoicons.svg#i-deploy" /></svg></button>
                        </div>
                        <?php } ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                <div class="form-group row">
                        <div class="col-md-12">
                        <input type='hidden' value='0' name='conf#registration'>
                        <input type="checkbox" value="1" name="conf#registration" id="registration" class="material-inputs filled-in chk-col-blue"  <?php if($website['registration']==1){?>checked="checked"<?php } ?> />
<label for="registration">Enable user registration</label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                        <input type='hidden' value='0' name='conf#check_new_ver'>
                        <input type="checkbox" value="1" name="conf#check_new_ver" id="check_new_ver" class="material-inputs filled-in chk-col-blue"  <?php if($website['check_new_ver']==1){?>checked="checked"<?php } ?> />
<label for="check_new_ver">Check for new version</label>
                        </div>
                    </div>
                    <div class="form-group row" style="margin-bottom:0px;">
                        <div class="col-md-12">
                            <label for="latestv">Latest version
                                <?php echo $appver; ?>
                            </label>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</form>