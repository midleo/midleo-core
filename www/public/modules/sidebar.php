<br>
<h4><i class="mdi mdi-gesture-double-tap"></i>&nbsp;Navigation</h4>
<br>
<nav class="sidebar-nav " ng-app="ngSysApp" ng-controller="ngsysCtrl">
 <div class="navtop hide-menu">
 <a  href="" ng-click="getAllnav('<?php echo $page."/".(($thisarray["last"]!=$page && $thisarray["last"]!="?")?$thisarray["last"]:"");?>','reset','fav')"><span class="favstar"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-star-empty" xlink:href="/assets/images/icon/midleoicons.svg#i-star-empty" /></svg></span>&nbsp;<span>Favorites</span></a>
 <span class="close"><a href="" ng-click="getAllnav('<?php echo $page."/".(($thisarray["last"]!=$page && $thisarray["last"]!="?")?$thisarray["last"]:"");?>','reset','')" ><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-x" xlink:href="/assets/images/icon/midleoicons.svg#i-x"/></svg></a></span>
 </div>
  <ul id="sidebarnav" ng-show="sysLoaded" ng-init="getAllnav('<?php echo $page."/".(($thisarray["last"]!=$page && $thisarray["last"]!="?")?$thisarray["last"]:"");?>','','')">
  <li  ng-repeat="d in names" id="nav{{d.navid}}" class="row" ng-class="{ 'active': d.navcond == '<?php echo $page;?>' }">
    <a ng-class="{ 'active': d.navcond == '<?php echo $page;?>' }"  class="waves-effect waves-dark col-md-10" href="{{d.navlink}}"><svg class="midico midico-outline me-2 mt-0" ng-class="{ 'active': d.navcond == '<?php echo $page;?>' }"><use href="{{d.navicon}}" xlink:href="{{d.navicon}}"/></svg><span class="hide-menu">{{d.navname}} </span></a>
    <div ng-show="navtype=='allnav'" class="favnav col-md-2" ng-click="navfav(d.navid,'add')" data-bs-toggle="tooltip" title="Add to favorites"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-star-full" xlink:href="/assets/images/icon/midleoicons.svg#i-star-full" /></svg></div>
    <div ng-show="navtype=='favnav'" class="favnav col-md-2" ng-click="navfav(d.navid,'rm')" data-bs-toggle="tooltip" title="Remove from favorites"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-star-empty" xlink:href="/assets/images/icon/midleoicons.svg#i-star-empty" /></svg></div>
   </li>
  </ul>
   <div id="sidebarnavload" ng-hide="sysLoaded" class="text-center" style="margin-top:50px;"><i class="mdi mdi-loading mdi-24px iconspin"></i></div>
  </nav>
