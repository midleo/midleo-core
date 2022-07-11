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
                <div class="card-header border-bottom">General</div>
                <div class="card-body">
                    <div class="form-group row">
                        <label for="env_name" class="form-control-label text-lg-right col-md-4">Website name</label>
                        <div class="col-md-8"><input type="text" name="conf#env_name" id="env_name"
                            value="<?php echo isset($_POST['conf#env_name'])?$_POST['conf#env_name']:$website['env_name'];?>"
                            class="form-control" />
</div>
                    </div>
                    <div class="form-group row">
                        <label for="datetime" class="form-control-label text-lg-right col-md-4">Timezone</label>
                        <div class="col-md-8"><select name="conf#datetime" class="form-control" id="datetime">
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
                        <label for="proxy_host" class="form-control-label text-lg-right col-md-4">Proxy server</label>
                        <div class="col-md-8"><input type="text" name="conf#proxy_host" id="proxy_host"
                            value="<?php echo isset($_POST['conf#proxy_host'])?$_POST['conf#proxy_host']:$website['proxy_host'];?>"
                            class="form-control" />
</div>
                    </div>
                    <div class="form-group row">
                        <label for="proxy_port" class="form-control-label text-lg-right col-md-4">Proxy port</label>
                        <div class="col-md-8"><input type="number" name="conf#proxy_port" id="proxy_port"
                            value="<?php echo isset($_POST['conf#proxy_port'])?$_POST['conf#proxy_port']:$website['proxy_port'];?>"
                            class="form-control" />
</div>
                    </div>
                    <div class="form-group row">
                        <label for="system_mail" class="form-control-label text-lg-right col-md-4">System email</label>
                        <div class="col-md-8"><input type="text" name="conf#system_mail" id="system_mail"
                            value="<?php echo isset($_POST['conf#system_mail'])?$_POST['conf#system_mail']:$website['system_mail'];?>"
                            class="form-control" />
</div>
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
            <div class="card">
                <div class="card-header border-bottom">Environments</div>
                <div class="card-body">

                </div>
            </div> 
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header border-bottom">Color pallete</div>
                <div class="card-body" id="styleradio">
                    <?php 
foreach($arrcolor as $key=>$val){?>
                    <input name="conf#color" type="radio" id="r<?php echo $val;?>" class="radio-<?php echo $val;?>"
                        value="<?php echo $val;?>" <?php if($website['color']==$val){?>checked="checked" <?php } ?> />
                    <label for="r<?php echo $val;?>"></label>
                    <?php } ?>
                </div>
            </div>
            <div class="card">
                <div class="card-header border-bottom">Business configuration</div>
                <div class="card-body">

                    <?php if(isset($modulelist["budget"]) && !empty($modulelist["budget"])){ ?>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-4">Efforts Unit</label>
                        <div class="col-md-8"> <input type="text" name="conf#effort_unit"
                                value="<?php echo isset($_POST['conf#effort_unit'])?$_POST['conf#effort_unit']:$website['effort_unit'];?>"
                                class="form-control" placeholder="Man Days/Man Hours/Days/Hours" /></div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-4">Currency</label>
                        <div class="col-md-8"><select name="conf#currency_unit" class="form-control">
                                <?php $currency_unit=isset($_POST['conf#currency_unit'])?$_POST['conf#currency_unit']:$website['currency_unit'];
  if(!empty($currency_unit)){ ?><option value="<?php echo $currency_unit;?>" selected="selected">
                                    <?php echo $currency_unit; ?></option><?php } ?>
                                <?php echo BAC::createCurrency();?>
                            </select></div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-4">Start time</label>
                        <div class="col-md-8"> <input type="text" name="conf#working_start"
                                value="<?php echo isset($_POST['conf#working_start'])?$_POST['conf#working_start']:$website['working_start'];?>"
                                class="form-control time-picker" id="workstart" data-toggle="datetimepicker"
                                data-target="#workstart" placeholder="Plese select the Hour" /></div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-4">End time</label>
                        <div class="col-md-8"> <input type="text" name="conf#working_end"
                                value="<?php echo isset($_POST['conf#working_end'])?$_POST['conf#working_end']:$website['working_end'];?>"
                                class="form-control time-picker" id="workend" data-toggle="datetimepicker"
                                data-target="#workend" placeholder="Please select the Hour" /></div>
                    </div>

                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-8">Kanban workflow</label>
                        <div class="col-md-4">
                            <button id="add-nmitem" type="button" class="btn btn-sm btn-light">Add new step</button>
  </div>
                    </div>
                    <div class="row">
                    <div class="col-md-12">
                    <?php echo SysNestable::createMenu($menudatabsteps,"1");   ?>
                                <input type="text" id="thistype" value="bsteps" style="display:none;">
                                </div> </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</form>