<div class="d-grid btnbox" role="group" >
     <?php if(!empty($zobj['reqtype'])){?><button type="button" onclick="location.href='/requests/type/<?php echo $zobj['reqtype'];?>/<?php echo in_array($thisarray["p2"], array("new","view"))?$thisarray["p1"]:$thisarray["last"];?>'" class="btn btn-light waves-effect"><i class="mdi mdi-cogs"></i>&nbsp;Input form: <?php echo $typereq[$zobj['reqtype']];?></button><?php } ?>
     <button type="button" onclick="location.href='/requests/log/<?php echo in_array($thisarray["p2"], array("new","view"))?$thisarray["p1"]:$thisarray["last"];?>'" class="btn btn-light waves-effect"><i class="mdi mdi-history"></i>&nbsp;History</button>
     <button type="button" onclick="location.href='/requests/files/<?php echo in_array($thisarray["p2"], array("new","view"))?$thisarray["p1"]:$thisarray["last"];?>'" class="btn btn-light waves-effect"><span class="float-end"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-documents" xlink:href="/assets/images/icon/midleoicons.svg#i-documents" /></svg></span>&nbsp;Documentation</button> 
     <button class="btn btn-light" type="button" id="reqidbut"><span class="float-end"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-diagram" xlink:href="/assets/images/icon/midleoicons.svg#i-diagram" /></svg></span>&nbsp;Service workflow</button>
     <button type="button" onclick="location.href='/reqtasks/<?php echo in_array($thisarray["p2"], array("new","view"))?$thisarray["p1"]:$thisarray["last"];?>'" class="btn btn-light waves-effect"><span class="float-end"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-tasks" xlink:href="/assets/images/icon/midleoicons.svg#i-tasks" /></svg></span>&nbsp;Subtasks</button> 
     <button type="button" onclick="location.href='/reqcomm/<?php echo in_array($thisarray["p2"], array("new","view"))?$thisarray["p1"]:$thisarray["last"];?>'" class="btn btn-light waves-effect"><i class="mdi mdi-comment-multiple-outline"></i>&nbsp;Comments</button> 
     <button type="button" onclick="location.href='/reqinfo/<?php echo in_array($thisarray["p2"], array("new","view"))?$thisarray["p1"]:$thisarray["last"];?>'" class="btn btn-light waves-effect"><i class="mdi mdi-history"></i>&nbsp;Back to the request</button> 
</div>
<div class="modal" id="modal-hist" tabindex="-1" role="dialog" aria-hidden="true" >
    <div class="modal-dialog" style="min-width:90%;">
      <div class="modal-content">
          <div class="modal-header">
            Service workflow
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body"></div>
          <input type="hidden" id="reqid" value="<?php echo in_array($thisarray["p2"], array("new","view"))?$thisarray["p1"]:$thisarray["last"];?>">
      </div>
    </div>
  </div>