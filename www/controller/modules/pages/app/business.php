<?php
array_push($brarr,array(
    "title"=>"Add new step",
    "link"=>"javascript:void(0)",
    "id"=>"add-nmitem",
    "icon"=>"mdi-plus",
    "active"=>true,
  ));
  array_push($brarr,array(
    "title"=>"Save the configurtion",
    "link"=>"javascript:void(0)",
    "onclick"=>"document.getElementById('saveadm').click();",
    "midicon"=>"save",
    "active"=>true,
  ));
?>
<form method="post" action="">
    <?php if(isset($modulelist["budget"]) && !empty($modulelist["budget"])){ ?>
    <div class="row">
        <div class="col-md-6">
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
                <label class="form-control-label text-lg-right col-md-4">Working start time</label>
                <div class="col-md-8"> <input type="text" name="conf#working_start"
                        value="<?php echo isset($_POST['conf#working_start'])?$_POST['conf#working_start']:$website['working_start'];?>"
                        class="form-control time-picker" id="workstart" data-toggle="datetimepicker"
                        data-target="#workstart" placeholder="Plese select the Hour" /></div>
            </div>
            <div class="form-group row">
                <label class="form-control-label text-lg-right col-md-4">Working end time</label>
                <div class="col-md-8"> <input type="text" name="conf#working_end"
                        value="<?php echo isset($_POST['conf#working_end'])?$_POST['conf#working_end']:$website['working_end'];?>"
                        class="form-control time-picker" id="workend" data-toggle="datetimepicker"
                        data-target="#workend" placeholder="Please select the Hour" /></div>
            </div>
        </div>

    </div>
    <div class="form-group row">
        <label class="form-control-label text-lg-right col-md-2">Kanban workflow</label>
        <div class="col-md-6 ">
          <div class="card p-0">
            <?php echo SysNestable::createMenu($menudatabsteps,"1");   ?>
            <input type="text" id="thistype" value="bsteps" style="display:none;">
  </div>
        </div>
    </div>
    <?php } ?>
        <button type="submit" name="saveadm" id="saveadm" style="display:none;" >save</button>
</form>