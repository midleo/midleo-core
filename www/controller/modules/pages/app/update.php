<div class="row">
<div class="col-md-8">
<div class="card" id="ngApp" ng-app="ngApp" ng-controller="ngCtrl">
<div class="card-header"><h4>Update log</h4></div>
<div class="card-body p-0"> 
<ul id="chlistinfo" class="list-group list-group-flush"></ul>
<ul id="updinfo" class="list-group list-group-flush"></ul>
<div style="display:block;" class="text-center"><div class="loading"><svg class="circular" viewBox="25 25 50 50">
<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
</div>
</div>
</div></div></div>
<div class="col-md-4">
  <button type="button" onclick="getChlist()" class="btn btn-secondary"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-ldap" xlink:href="/assets/images/icon/midleoicons.svg#i-ldap" /></svg>&nbsp;Change list</button>
  <button type="button" onclick="startUpdate()" class="btn btn-info"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-update" xlink:href="/assets/images/icon/midleoicons.svg#i-update" /></svg>&nbsp;Start update</button>
</div>
</div>