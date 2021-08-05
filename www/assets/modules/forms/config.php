<?php
$modulelist["forms"]["name"]="Form templates";
class Tpl{
public static function queues($before='',$after='',$objects=null){ 
   return '<div class="form-group row" >
<label class="form-control-label text-lg-right col-md-3">Source Qmanager</label>
<div class="col-md-9"><input name="'.$before.'srcqmgr'.$after.'" class="form-control autocomplqm" data-srv="'.$before.'srcsrv'.$after.'" type="text" value="'.(!empty($objects)?$objects[str_replace("_","",$after)]["srcqmgr"]:"").'">
</div></div>
<input name="'.$before.'srcsrv'.$after.'" id="'.$before.'srcsrv'.$after.'" style="display:none;" type="text" value="'.(!empty($objects)?$objects[str_replace("_","",$after)]["srcsrv"]:"").'">
<div class="form-group row">
<label class="form-control-label text-lg-right col-md-3">Source Queue/s</label>
<div class="col-md-9"><input name="'.$before.'srcqueue'.$after.'" data-role="tagsinput" class="form-control " type="text" value="'.(!empty($objects)?$objects[str_replace("_","",$after)]["srcqueue"]:"").'">
</div></div>
<div class="form-group row" >
<label class="form-control-label text-lg-right col-md-3">Source CCSID</label>
<div class="col-md-9"><input name="'.$before.'srcccsid'.$after.'" class="form-control " type="text" value="'.(!empty($objects)?$objects[str_replace("_","",$after)]["srcccsid"]:"").'">
</div></div>
<div class="form-group row" >
<label class="form-control-label text-lg-right col-md-3">Destination Qmanager</label>
<div class="col-md-9"><input name="'.$before.'dstqmgr'.$after.'" class="form-control autocomplqm" data-srv="'.$before.'dstsrv'.$after.'" type="text" value="'.(!empty($objects)?$objects[str_replace("_","",$after)]["dstqmgr"]:"").'">
</div></div>
<input name="'.$before.'dstsrv'.$after.'" id="'.$before.'dstsrv'.$after.'" style="display:none;" type="text" value="'.(!empty($objects)?$objects[str_replace("_","",$after)]["dstsrv"]:"").'">
<div class="form-group row" >
<label class="form-control-label text-lg-right col-md-3">Destination Queue/s</label>
<div class="col-md-9"><input name="'.$before.'dstqueue'.$after.'" data-role="tagsinput" class="form-control " type="text" value="'.(!empty($objects)?$objects[str_replace("_","",$after)]["dstqueue"]:"").'">
</div></div>
<div class="form-group row" >
<label class="form-control-label text-lg-right col-md-3">Destination CCSID</label>
<div class="col-md-9"><input name="'.$before.'dstccsid'.$after.'" class="form-control " type="text" value="'.(!empty($objects)?$objects[str_replace("_","",$after)]["dstccsid"]:"").'">
</div></div>
<div class="form-group row" >
<label class="form-control-label text-lg-right col-md-3">Max message lenght</label>
<div class="col-md-9"><input name="'.$before.'maxmsgl'.$after.'" class="form-control " type="text" value="'.(!empty($objects)?$objects[str_replace("_","",$after)]["maxmsgl"]:"").'">
</div></div>
<div class="form-group row" >
<label class="form-control-label text-lg-right col-md-3">Max messages/day</label>
<div class="col-md-9"><input name="'.$before.'maxmsg'.$after.'" class="form-control " type="text" value="'.(!empty($objects)?$objects[str_replace("_","",$after)]["maxmsg"]:"").'">
</div></div>
<div class="form-group row" >
<label class="form-control-label text-lg-right col-md-3">Message order</label>
<div class="col-md-9"><select name="'.$before.'messordr'.$after.'" class="form-control " >
<option value="no" '.(!empty($objects) && $objects[str_replace("_","",$after)]["messordr"]=="no"?"selected":"").'>No</option><option value="yes" '.(!empty($objects) && $objects[str_replace("_","",$after)]["messordr"]=="yes"?"selected":"").'>Yes</option></select>
</div></div> 
';
}
  public static function fte($before='',$after='',$objects=null){ 
    $pdo = pdodb::connect();
    $data='
   <div class="form-group row">
<label class="form-control-label text-lg-right col-md-3" data-trigger="hover" data-bs-toggle="popover" data-bs-placement="right" data-html="true" data-content="There are three types of file transfers<br><br>File to File - source and destination are files<br>Queue to File - source is a queue and destination - file<br>File to Queue - source is a file and destination - Queue" title="" data-original-title="file transfer type" ng-class="{\'has-error\':!mqfte.mqftetype'.$after.'}">Type</label>
<div class="col-md-9"><select class="form-control " ng-model="mqfte.mqftetype'.$after.'" ng-init="mqfte.mqftetype'.$after.'=\''.(!empty($objects)?$objects[str_replace("_","",$after)]["mqftetype"]:"").'\'" name="'.$before.'mqftetype'.$after.'"><option value="">Please select</option><option value="f2f" '.(!empty($objects)?($objects[str_replace("_","",$after)]["mqftetype"]=="f2f"?" selected=\"selected\"":""):"").'>File to File</option><option value="f2q" '.(!empty($objects)?($objects[str_replace("_","",$after)]["mqftetype"]=="f2q"?" selected=\"selected\"":""):"").'>File to Queue</option><option value="q2f" '.(!empty($objects)?($objects[str_replace("_","",$after)]["mqftetype"]=="q2f"?" selected=\"selected\"":""):"").'>Queue to File</option></select>
</div></div>
 <div class="form-group row" ng-show="mqfte.mqftetype'.$after.'==\'f2f\' || mqfte.mqftetype'.$after.'==\'f2q\'">
  <label class="form-control-label text-lg-right col-md-3">Source Server</label>
  <div class="col-md-9">
  ';
      $sql="select serverdns,agentname from env_appservers where serv_type='fte'";
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
     if($zobjfte = $stmt->fetchAll()){
      $data.='<select name="'.$before.'srcsrv'.$after.'" class="form-control " ng-init="'.$before.'srcsrv'.$after.'=\''.(!empty($objects)?$objects[str_replace("_","",$after)]["srcsrv"]:"").'\'">';
	  $data.='<option value="">Please select</option>';
      foreach($zobjfte as $val) {
          $data.='<option value="'.$val['serverdns'].'" '.(!empty($objects)?($objects[str_replace("_","",$after)]["srcsrv"]==$val['serverdns']?" selected=\"selected\"":""):"").'>'.$val['agentname'].' ('.$val['serverdns'].')</option>';
      }
      $data.='</select>';
    } else {
       $data.='<input name="'.$before.'srcsrv'.$after.'" class="form-control " type="text" value="'.(!empty($objects)?$objects[str_replace("_","",$after)]["srcsrv"]:"").'">';
     }
     $data.='
</div></div>
 <div class="form-group row"  ng-show="mqfte.mqftetype'.$after.'==\'f2f\' || mqfte.mqftetype'.$after.'==\'f2q\'">
  <label class="form-control-label text-lg-right col-md-3">Source Filemask</label>
  <div class="col-md-9"><input name="'.$before.'srcfilemsk'.$after.'" class="form-control " type="text" value="'.(!empty($objects)?$objects[str_replace("_","",$after)]["srcfilemsk"]:"").'">
</div></div>
<div class="form-group row" ng-show="mqfte.mqftetype'.$after.'==\'q2f\'">
<label class="form-control-label text-lg-right col-md-3" data-trigger="hover" data-bs-toggle="popover" data-bs-placement="right" data-html="true" data-content="The queue name which will be monitored for messages<br>Note that queue should be visible from the source agent qmanager" title="" data-original-title="Monitor Queue" >Source Queue</label>
<div class="col-md-9"><input name="'.$before.'sourcequeue'.$after.'" type="text" class="form-control " value="'.(!empty($objects)?$objects[str_replace("_","",$after)]["sourcequeue"]:"").'">
</div></div>
<div class="form-group row" ng-show="mqfte.mqftetype'.$after.'==\'q2f\'">
<label class="form-control-label text-lg-right col-md-3">Source Qmanager</label>
<div class="col-md-9">
';
      $sql="select serverdns,qmname from env_appservers where serv_type='qm'";
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
     if($zobjqm = $stmt->fetchAll()){
      $data.='<select name="'.$before.'sourceqmgr'.$after.'" class="form-control " ng-init="'.$before.'sourceqmgr'.$after.'=\''.(!empty($objects)?$objects[str_replace("_","",$after)]["sourceqmgr"]:"").'\'">';
	  $data.='<option value="">Please select</option>';
      foreach($zobjqm as $val) {
          $data.='<option value="'.$val['qmname'].'" '.(!empty($objects)?($objects[str_replace("_","",$after)]["sourceqmgr"]==$val['qmname']?" selected=\"selected\"":""):"").'>'.$val['qmname'].' ('.$val['serverdns'].')</option>';
      }
      $data.='</select>';
    } else {
       $data.='<input name="'.$before.'sourceqmgr'.$after.'" type="text" class="form-control " value="'.(!empty($objects)?$objects[str_replace("_","",$after)]["sourceqmgr"]:"").'">';
     }
    $data.='
</div></div>
  <div class="form-group row" >
  <label class="form-control-label text-lg-right col-md-3">Source CCSID</label>
  <div class="col-md-9"> <input name="'.$before.'srcccsid'.$after.'" class="form-control " type="text" value="'.(!empty($objects)?$objects[str_replace("_","",$after)]["srcccsid"]:"").'">
</div></div>
<div class="form-group row" ng-show="mqfte.mqftetype'.$after.'==\'f2f\' || mqfte.mqftetype'.$after.'==\'q2f\'">
  <label class="form-control-label text-lg-right col-md-3">Destination Server</label>
  <div class="col-md-9">
  ';
     if(!empty($zobjfte)){
      $data.='<select name="'.$before.'dstsrv'.$after.'" class="form-control " ng-init="'.$before.'dstsrv'.$after.'=\''.(!empty($objects)?$objects[str_replace("_","",$after)]["dstsrv"]:"").'\'">';
	  $data.='<option value="">Please select</option>';
      foreach($zobjfte as $val) {
          $data.='<option value="'.$val['serverdns'].'" '.(!empty($objects)?($objects[str_replace("_","",$after)]["dstsrv"]==$val['serverdns']?" selected=\"selected\"":""):"").'>'.$val['agentname'].' ('.$val['serverdns'].')</option>';
      }
      $data.='</select>';
     } else {
       $data.='<input name="'.$before.'dstsrv'.$after.'" class="form-control " type="text" value="'.(!empty($objects)?$objects[str_replace("_","",$after)]["dstsrv"]:"").'">';
     }
    $data.='
</div></div>
 <div class="form-group row" ng-show="mqfte.mqftetype'.$after.'==\'f2f\' || mqfte.mqftetype'.$after.'==\'q2f\'">
  <label class="form-control-label text-lg-right col-md-3">Destination Filemask</label>
  <div class="col-md-9"> <input name="'.$before.'dstfilemsk'.$after.'" class="form-control " type="text" value="'.(!empty($objects)?$objects[str_replace("_","",$after)]["dstfilemsk"]:"").'">
</div></div>
<div class="form-group row" ng-show="mqfte.mqftetype'.$after.'==\'f2q\'">
<label class="form-control-label text-lg-right col-md-3" data-trigger="hover" data-bs-toggle="popover" data-bs-placement="right" data-html="true" data-content="The queue name to which messages will be transferred<br>Note that queue should be visible from the destination agent qmanager" title="" data-original-title="Destination Queue" >Destination Queue</label>
<div class="col-md-9"><input name="'.$before.'destqueue'.$after.'" type="text" class="form-control " value="'.(!empty($objects)?$objects[str_replace("_","",$after)]["destqueue"]:"").'">
</div></div>
  <div class="form-group row" ng-show="mqfte.mqftetype'.$after.'==\'f2q\'">
<label class="form-control-label text-lg-right col-md-3" >Destination Qmanager</label>
<div class="col-md-9">
';
    if(!empty($zobjqm)){
      $data.='<select name="'.$before.'destqmgr'.$after.'" class="form-control " ng-init="'.$before.'destqmgr'.$after.'=\''.(!empty($objects)?$objects[str_replace("_","",$after)]["destqmgr"]:"").'\'">';
	  $data.='<option value="">Please select</option>';
      foreach($zobjqm as $val) {
          $data.='<option value="'.$val['qmname'].'" '.(!empty($objects)?($objects[str_replace("_","",$after)]["destqmgr"]==$val['qmname']?" selected=\"selected\"":""):"").'>'.$val['qmname'].' ('.$val['serverdns'].')</option>';
      }
      $data.='</select>';
     } else {
       $data.='<input name="'.$before.'destqmgr'.$after.'" type="text" class="form-control " value="'.(!empty($objects)?$objects[str_replace("_","",$after)]["destqmgr"]:"").'">';
     }
    $data.='
</div></div>
  <div class="form-group row" >
  <label class="form-control-label text-lg-right col-md-3">Destination CCSID</label>
  <div class="col-md-9"> <input name="'.$before.'dstccsid'.$after.'" class="form-control " type="text" value="'.(!empty($objects)?$objects[str_replace("_","",$after)]["dstccsid"]:"").'">
</div></div>
  <div class="form-group row" ng-show="mqfte.mqftetype'.$after.'==\'f2f\' || mqfte.mqftetype'.$after.'==\'q2f\'">
  <label class="form-control-label text-lg-right col-md-3">Overwrite on destination</label>
  <div class="col-md-9"><select name="'.$before.'dstoverwrite'.$after.'" class="form-control " ng-init="'.$before.'dstoverwrite'.$after.'=\''.(!empty($objects)?$objects[str_replace("_","",$after)]["dstoverwrite"]:"").'\'"><option value="">Please select</option>
  <option value="yes" '.(!empty($objects)?($objects[str_replace("_","",$after)]["dstoverwrite"]=="yes"?" selected=\"selected\"":""):"").'>Overwrite</option>
  <option value="error" '.(!empty($objects)?($objects[str_replace("_","",$after)]["dstoverwrite"]=="error"?" selected=\"selected\"":""):"").'>Raise an error</option></select>
</div></div>
   <div class="form-group row" >
  <label class="form-control-label text-lg-right col-md-3">Notification on Error</label>
  <div class="col-md-9"><input name="'.$before.'notiferr'.$after.'" class="form-control " type="text" value="'.(!empty($objects)?$objects[str_replace("_","",$after)]["notiferr"]:"").'">
</div></div>
   <div class="form-group row" >
  <label class="form-control-label text-lg-right col-md-3">Notification on Success</label>
  <div class="col-md-9"> <input name="'.$before.'notifsucc'.$after.'" class="form-control " type="text" value="'.(!empty($objects)?$objects[str_replace("_","",$after)]["notifsucc"]:"").'">
</div></div>
   ';
    pdodb::disconnect();
    return $data;
  }
  public static function flow($before='',$after='',$objects=null){ 
   return '
   
   ';
  }
  public static function general($before='',$after='',$objects=null){ 
   return '
 <div class="form-group row" >
<label class="form-control-label text-lg-right col-md-3">Application</label>
<div class="col-md-9"><input name="'.$before.'applname'.$after.'" class="form-control " type="text" value="'.(!empty($objects)?$objects[str_replace("_","",$after)]["applname"]:"").'">
</div></div>
<div class="form-group row" >
<label class="form-control-label text-lg-right col-md-3">Software requirements</label>
<div class="col-md-9"><textarea name="'.$before.'softreq'.$after.'" class="form-control " rows="10">'.(!empty($objects)?$objects[str_replace("_","",$after)]["softreq"]:"").'</textarea>
</div></div>
   ';
  }
}