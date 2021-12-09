<?php
  array_push($brarr,array(
    "title"=>"Save the configurtion",
    "link"=>"javascript:void(0)",
    "onclick"=>"document.getElementById('saveadm').click();",
    "icon"=>"mdi-content-save-outline",
    "active"=>true,
  ));
?>
<form method="post" action="" class="form-material">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">General</div>
                <div class="card-body">
                    <div class="form-group">
                    <label for="env_name" class="form-label">Website name, eg. My application</label>
                        <input type="text" name="conf#env_name" id="env_name"
                            value="<?php echo isset($_POST['conf#env_name'])?$_POST['conf#env_name']:$website['env_name'];?>"
                            class="form-control" />
                    </div>
                    <div class="form-group">
                    <label for="datetime" class="form-label">Timezone</label>
                        <select name="conf#datetime" class="form-control" id="datetime">
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
                    <label for="proxy_host" class="form-label">Proxy server, eg. proxy.domain.com</label>
                        <input type="text" name="conf#proxy_host" id="proxy_host"
                            value="<?php echo isset($_POST['conf#proxy_host'])?$_POST['conf#proxy_host']:$website['proxy_host'];?>"
                            class="form-control" />
                    </div>
                    <div class="form-group">
                    <label for="proxy_port" class="form-label">Proxy port, eg. 8080</label>
                        <input type="number" name="conf#proxy_port" id="proxy_port"
                            value="<?php echo isset($_POST['conf#proxy_port'])?$_POST['conf#proxy_port']:$website['proxy_port'];?>"
                            class="form-control" />
                    </div>
                    <div class="form-group">
                    <label for="system_mail" class="form-label">System email, eg. noreply@myhost</label>
                        <input type="text" name="conf#system_mail" id="system_mail"
                            value="<?php echo isset($_POST['conf#system_mail'])?$_POST['conf#system_mail']:$website['system_mail'];?>"
                            class="form-control" />
                    </div>
                    <button type="submit" name="saveadm" id="saveadm" style="display:none;">save</button>

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
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Color pallete</div>
                <div class="card-body" id="styleradio">
<?php 
foreach($arrcolor as $key=>$val){?>
                <input name="conf#color" type="radio" id="r<?php echo $val;?>" class="radio-<?php echo $val;?>" value="<?php echo $val;?>" <?php if($website['color']==$val){?>checked="checked" <?php } ?> />
                <label for="r<?php echo $val;?>"></label>
<?php } ?>
                </div>
            </div>
        </div>
    </div>
</form>