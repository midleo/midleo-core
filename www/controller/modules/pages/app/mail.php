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
        <div class="col-md-7">
            <div class="form-group">
                <textarea name="conf#varheader" rows="5" class="textarea" placeholder="Template header">
                                <?php echo isset($_POST['conf#varheader'])?$_POST['conf#varheader']:$website['varheader'];?>
                            </textarea>
            </div>
            <div class="form-group">
                <textarea name="conf#varfooter" rows="5" class="textarea" placeholder="Template footer">
                                <?php echo isset($_POST['conf#varfooter'])?$_POST['conf#varfooter']:$website['varfooter'];?>
                            </textarea>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <input type="text" placeholder="SMTP server; eg. mail.domain.com" name="conf#smtp_host"
                    value="<?php echo isset($_POST['conf#smtp_host'])?$_POST['conf#smtp_host']:$website['smtp_host'];?>"
                    class="form-control">
            </div>
            <div class="form-group">
                <input type="text" name="conf#smtp_port"
                    value="<?php echo isset($_POST['conf#smtp_port'])?$_POST['conf#smtp_port']:$website['smtp_port'];?>"
                    class="form-control" placeholder="SMTP port; eg. 25">
            </div>
            <button type="submit" name="saveadm" id="saveadm" style="display:none;" >save</button>
        </div>
    </div>
</form>