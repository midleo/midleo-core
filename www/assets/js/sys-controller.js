var sysapp = angular.module('ngSysApp', []);
sysapp.config(['$compileProvider',
    function ($compileProvider) {
        $compileProvider.aHrefSanitizationWhitelist(/^\s*(https?|ftp|mailto|tel|file|blob):/);
}]);
sysapp.controller('ngsysCtrl', function($scope, $http, $window) {
  $scope.sysLoaded = false;
  $scope.getAllnav = function(thispage,thismethod,thistype){
   if($('#sidebarnavload')[0]){ angular.element(document.querySelector('#sidebarnavload')).removeClass('hide');}
   if(thismethod=="reset"){
    $window.localStorage.removeItem("respdata");
    $window.localStorage.setItem("navtype","allnav");
    $(".navtop span.close").hide();
    $(".favstar").html('<svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-star-empty" xlink:href="/assets/images/icon/midleoicons.svg#i-star-empty"/></svg>');
    $scope.names = [];
   }
   if(thistype=="fav"){
    $window.localStorage.setItem("navtype","favnav");
    $(".navtop span.close").show();
    $(".favstar").html('<svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-star-full" xlink:href="/assets/images/icon/midleoicons.svg#i-star-full"/></svg>');
   } 
   let cachedata = $window.localStorage.getItem("respdata");
   let navtype = $window.localStorage.getItem("navtype");
   if(!navtype){
    $window.localStorage.setItem("navtype","allnav");
    navtype = "allnav";
   }
   $scope.navtype=navtype;
   if(cachedata){
     $scope.sysLoaded = true;
     $scope.names=angular.fromJson(cachedata);
     $scope.thispage = thispage;
   } else {
   $http({
     method: 'POST',
     data: {'thispage' : thispage, 'type' : thistype },
     url: '/pubapi/readnav'
   }).then(function successCallback(response) {
     $scope.sysLoaded = true;
     if (response.data!="null") {
       $scope.names = response.data;
       $scope.thispage = thispage;
       $window.localStorage.setItem("respdata",angular.toJson(response.data));
     } else {
       $scope.names = [];
     }
   });
  }
 };
 $scope.navfav = function(thisid,thistype){
  $http({
    method: 'POST',
    data: {'navid' : thisid, 'type': thistype },
    url: '/pubapi/favnav'
  }).then(function successCallback(response) {
    if (response.data!="null") {
      if(thistype=="rm"){
        $scope.getAllnav('','reset','fav');
      }
      notify("Favorite Menu updated","success");
    }
  });
 };
});

