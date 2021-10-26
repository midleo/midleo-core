<div class="modal" id="modal-response-form" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" style="width:80%;min-width:600px;">
    <div class="modal-content">

      <div class="modal-body" style="" ng-hide="response.resperr || response.respok">
        <?php if(count($menudataenv)>0 && is_array($menudataenv)){  ?>
        <div role="tabpanel"> 
          <ul class="nav nav-tabs customtab" role="tablist">
          <?php $ienv=0;  foreach($menudataenv as $keyenv=>$valenv){  ?>
           <li class="nav-item"><a class="nav-link<?php echo $ienv==0?" active":"";?>" href="#<?php echo $valenv['nameshort'];?>" aria-controls="<?php echo $valenv['nameshort'];?>" role="tab" data-bs-toggle="tab"><?php echo $valenv['name'];?></a></li><?php 
             $ienv++;    }   ?>
          </ul><br>
          <div class="tab-content form-horizontal" style="width:100%;min-height:300px;max-height:500px;overflow-x:hidden;overflow-y:scroll;">
              <?php $ienv=0;  foreach($menudataenv as $keyenv=>$valenv){  ?>
            <div role="tabpanel" class="tab-pane <?php echo $ienv==0?'active':"";?>" id="<?php echo $valenv['nameshort'];?>" >
              <pre>{{response.<?php echo $valenv['nameshort'];?>}}</pre>  
            </div>
              <?php $ienv++;    }   ?>    
          </div>
        </div>
        <?php } else { ?>
        <div style="width:100%;min-height:200px;max-height:500px;overflow-x:hidden;overflow-y:scroll;"><pre>{{response}}</pre></div>
        <?php } ?>
        </div>

        <div class="modal-body" style="" ng-show="response.respok || response.resperr">
        <div ng-show="response.respok" style="width:100%;min-height:200px;max-height:500px;overflow-x:hidden;overflow-y:scroll;">
          <div class="alert alert-success" style="width:100%;overflow-wrap: break-word;word-wrap: break-word;-webkit-hyphens: auto;-ms-hyphens: auto; -moz-hyphens: auto; hyphens: auto;" ng-bind-html="renderHtml(response.respok)"></div>
        </div>
          <div ng-show="response.resperr" style="width:100%;min-height:200px;max-height:500px;overflow-x:hidden;overflow-y:scroll;">
          <div class="alert alert-danger" style="width:100%;overflow-wrap: break-word;word-wrap: break-word;-webkit-hyphens: auto;-ms-hyphens: auto; -moz-hyphens: auto; hyphens: auto;" ng-bind-html="renderHtml(response.resperr)"></div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="mdi mdi-close"></i>&nbsp;Close</button>
      </div>
    </div>
  </div>
</div>