<?php
$modulelist["sysnestable"]["name"]="Nestable module";
class SysNestable{
public static function createMenu($data,$reqrows=1){ 
    $return='';
    if($reqrows==1){ $return.='<div style="z-index:9999;position:fixed;bottom:60px; right:87px;" data-bs-toggle="tooltip" data-bs-placement="top" title="Add new step"><button id="add-nmitem" class="waves-effect waves-light btn btn-primary btn-circle btnnm" type="button"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-add" xlink:href="/assets/images/icon/midleoicons.svg#i-add" /></svg></button>
</div>'; }
    $return.='<ul id="listserial" class="list-group list-group-flush">';
    $return.=SysNestable::parseMenu($data,"",$reqrows);
    $return.='</ul></div>';
    $return.='<div class="modal fade" id="nmModal" tabindex="-1" role="dialog" aria-labelledby="nmmodallbl">
      <div class="modal-dialog" role="document">
      <div class="modal-content">
      <div class="modal-body">
      <div class="form-group row">
      <label for="nmname" class="form-control-label text-lg-right col-md-4">Name of the step</label>
      <div class="col-md-8"><input type="text" class="form-control" id="nmname"></div>
      </div>
      <div class="form-group row">
      <label class="form-control-label text-lg-right col-md-4">Color</label>
      <div class="col-md-8">
      <input type="color" id="nmcolor" class="form-control" style="height:revert;"  />
      </div>
      <input type="hidden" id="nmid" value="">
      </div>
      
      </div>
      <div class="modal-footer">
      <button type="button" onclick="savethisnm()" class="btn btn-light btn-sm">Save changes</button>
      <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
      </div>
      </div>
      </div>
      </div>
      ';
    return $return;
  }
  public static function parseMenu($jsonArray, $parentID = 0,$reqrows=1) {
    $return = "";
    if(count($jsonArray) != 0){ 
    foreach ($jsonArray as $subArraykey=>$subArray) { 
      $return.='<li id="'.$subArray['id'].'" class="list-group-item border-bottom ni'.$subArray['id'].'" data-name="'.$subArray['name'].'" data-nameshort="'.$subArray['nameshort'].'" data-color="'.$subArray['color'].'" data-id="'.$subArray['id'].'">';
      $return.='<a class="nml'.$subArray['id'].'">'.$subArray["name"].'</a>';
      if($reqrows==1){ $return.='<div class="float-end"><a class="button-edit'.$subArray['id'].' text-info" onclick="showthisnm(\''.$subArray['id'].'\',\''.$subArray['name'].'\',\''.$subArray['color'].'\')" style="cursor:pointer;"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-edit" xlink:href="/assets/images/icon/midleoicons.svg#i-edit" /></svg></a>&nbsp;<a class="text-danger" onclick="rmthisnm(\'ni'.$subArray['id'].'\')" style="cursor:pointer;"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-x" xlink:href="/assets/images/icon/midleoicons.svg#i-x" /></svg></a></div>';}
      $return.='';
      if (isset($subArray['children'])) {
        $return.='<ul class="list-group list-group-flush">';
        $return.=SysNestable::parseMenu($subArray['children'], $subArray['id'],$reqrows);
        $return.='</ul>';
      }
      $return.='</li>';
    }
    }
    return $return;
  }
  public static function createMenuIcon($data,$reqrows=1){ 
    $return='';
    $return.='<ul id="listserial" class="list-group list-group-flush">';
    $return.=SysNestable::parseMenuIcon($data,"",$reqrows);
    $return.='</ul></div>';
    $return.='<div class="modal fade" id="nmModal" tabindex="-1" role="dialog" aria-labelledby="nmmodallbl">
      <div class="modal-dialog" role="document">
      <div class="modal-content">
      <div class="modal-body">
      <div class="form-group row">
      <label for="nmname" class="form-control-label text-lg-right col-md-2">Name</label>
      <div class="col-md-10"><input type="text" class="form-control" id="nmname"></div>
      </div>
      <div class="form-group row">
      <label class="form-control-label text-lg-right col-md-2">Icon</label>
      <div class="col-md-10" id="iconlist">
      </div>
      <input type="hidden" id="nmid" value="">
      </div>
      </div>
      <div class="modal-footer">
      <button type="button" onclick="savethisnmicon()" class="btn btn-light btn-sm">Save changes</button>
      <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
      </div>
      </div>
      ';
    return $return;
  }
  public static function parseMenuIcon($jsonArray, $parentID = 0,$reqrows=1) {
    $return = "";
    if(count($jsonArray) != 0){ 
    foreach ($jsonArray as $subArraykey=>$subArray) { 
      $return.='<li id="'.$subArray['id'].'" class="list-group-item border-bottom ni'.$subArray['id'].'" data-name="'.$subArray['name'].'" data-nameshort="'.$subArray['nameshort'].'" data-icon="'.$subArray['icon'].'" data-id="'.$subArray['id'].'">';
      $return.='<a class="nml'.$subArray['id'].'">'.$subArray["name"].'</a>';
      if($reqrows==1){ $return.='<div class="float-end"><a class="button-edit'.$subArray['id'].' text-info" onclick="showthisnmicon(\''.$subArray['id'].'\',\''.$subArray['name'].'\',\''.$subArray['icon'].'\')" style="cursor:pointer;"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-edit" xlink:href="/assets/images/icon/midleoicons.svg#i-edit" /></svg></a>&nbsp;<a class="text-danger" onclick="rmthisnm(\'ni'.$subArray['id'].'\')" style="cursor:pointer;"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-x" xlink:href="/assets/images/icon/midleoicons.svg#i-x" /></svg></a></div>';}
      $return.='';
      if (isset($subArray['children'])) {
        $return.='<ul class="list-group list-group-flush">';
        $return.=SysNestable::parseMenuIcon($subArray['children'], $subArray['id'],$reqrows);
        $return.='</ul>';
      }
      $return.='</li>';
    }
    }
    return $return;
  }
}