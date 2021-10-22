<?php
  array_push($brarr,array(
    "title"=>"Save the configurtion",
    "link"=>"javascript:void(0)",
    "onclick"=>"document.getElementById('saveadm').click();",
    "midicon"=>"save",
    "active"=>true,
  ));
?>
<form method="post" action="" class="form-material">
    <div class="row">
        <div class="col-md-6">
        <div class="card">
        <div class="card-body">
            <div class="form-group">
                <input type="text" name="conf#env_name"
                    value="<?php echo isset($_POST['conf#env_name'])?$_POST['conf#env_name']:$website['env_name'];?>"
                    class="form-control" placeholder="Website name; eg. My application" />
            </div>
            <div class="form-group">
                <select name="conf#datetime" class="form-control">
                    <option value="">Timezone</option>
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

            <!--<div class="form-group">
<label class="form-control-label text-lg-right col-md-4">Website logo</label>

  <input type="text" name="conf#env_logo" value="<?php echo isset($_POST['conf#env_logo'])?$_POST['conf#env_logo']:$website['env_logo'];?>" class="form-control" placeholder="eg. /assets/logo.png"/>
  </div> -->

            <div class="form-group">
                <input type="text" name="conf#proxy_host"
                    value="<?php echo isset($_POST['conf#proxy_host'])?$_POST['conf#proxy_host']:$website['proxy_host'];?>"
                    class="form-control" placeholder="Proxy server; eg. proxy.domain.com" />
            </div>
            <div class="form-group">
                <input type="number" name="conf#proxy_port"
                    value="<?php echo isset($_POST['conf#proxy_port'])?$_POST['conf#proxy_port']:$website['proxy_port'];?>"
                    class="form-control" placeholder="Proxy port; eg. 8080" />
            </div>
            <div class="form-group">
                <input type="text" name="conf#system_mail"
                    value="<?php echo isset($_POST['conf#system_mail'])?$_POST['conf#system_mail']:$website['system_mail'];?>"
                    class="form-control" placeholder="System email; eg. noreply@myhost" />
            </div>
            <button type="submit" name="saveadm" id="saveadm" style="display:none;" >save</button>

<br>
            <div class="form-group">
                <input type='hidden' value='0' name='conf#registration'>
                <input type="checkbox" value="1" name="conf#registration" id="registration"
                    class="material-inputs filled-in chk-col-blue"
                    <?php if($website['registration']==1){?>checked="checked" <?php } ?> />
                <label for="registration">Enable user registration</label>
            </div>
            </div>
            </div>
        </div>
    </div>
</form>