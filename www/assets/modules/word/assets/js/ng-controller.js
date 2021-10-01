var app = angular.module('ngApp', ['angularUtils.directives.dirPagination']);
app.config(['$compileProvider',
  function ($compileProvider) {
    $compileProvider.aHrefSanitizationWhitelist(/^\s*(https?|ftp|mailto|tel|file|blob):/);
  }]);
app.controller('ngCtrl', function ($scope, $http, $location, $window, $sce) {
  $scope.selectedid = [];
  $scope.finalobj = {};
  $scope.finalobjnames = {};
  $scope.selectedtibaclm = [];
  $('.updinfo').hide();
  $scope.contentLoaded = false;
  $scope.textlimit = 20;
//start main functions
  $scope.parJson = function (json) {
    if (json) { return JSON.parse(json); }
  };
  $scope.renderHtml = function (htmlCode) {
    return $sce.trustAsHtml(htmlCode);
  };
  $scope.decode64 = function (str) {
    return atob(str);
  };
  $scope.copyToClipboard = function(thistext){ 
    navigator.clipboard.writeText(thistext);
    notify("Copied to clipboard","success");
  };
  $scope.toggle = function (item, list) {
    var idx = list.indexOf(item);
    if (idx > -1) { list.splice(idx, 1); } else { list.push(item); }
  };
  $scope.addexist = function (item, list) {
    var idx = list.indexOf(item);
    if (idx > -1) { } else { list.push(item); }
  };
  $scope.exists = function (item, list) { return list.indexOf(item) > -1; };
  $scope.redirect = function (url, refresh) {
    if (refresh || $scope.$$phase) {
      $window.location.href = url;
    } else {
      $location.path(url);
      $scope.$apply();
    }
  };
  $scope.exportData = function (what) { alasql('SELECT * INTO XLSX("MidleoData_' + what + '.xlsx",{sheetid:"' + what + '",headers:true}) FROM ?', [$scope.names]); };
//end main functions 
  $scope.getAllword = function () {
    if ($('#contloaded')[0]) { angular.element(document.querySelector('#contloaded')).removeClass('hide'); }
    $http({
      method: 'POST',
      data: { },
      url: '/wordapi/readimp'
    }).then(function successCallback(response) {
      $scope.contentLoaded = true;
      if (response.data != "null") { $scope.names = response.data; } else { $scope.names = []; }
    });
  };
  $scope.clearFormdoc = function () { $scope.doc = {}; };
  $scope.scandir = function(){
    $scope.doclist = [];
    delete $scope.tempkey;
    $http({
      method: 'POST',
      data: { 'dir': $scope.doc.fileloc, 'ext': $scope.doc.filetype },
      url: '/wordapi/getdoclist'
    }).then(function successCallback(response) {
     // $scope.contentLoaded = true;
      if (response.data != "null") { 
        if(response.data.error){
          nalert(response.data.errorlog,"warning");
          $scope.doclist = [];
        } else {
          $scope.doclist = response.data;
        }
      }
    });
  };
  $scope.startImport = function(){
    if(!$scope.tempkey){
      $scope.tempkey=0;
      angular.forEach($scope.doclist, function(value, key) {
        $http({
          method: 'POST',
          data: { 'filename': value, 'ext': $scope.doc.filetype },
          url: '/wordapi/import'
        }).then(function successCallback(response) {
          if (response.data != "null") { 
            if(response.data.error){
              nalert(response.data.errorlog,"warning");
            } else {
              console.log(response.data.resp+";key:"+key+";tempkey:"+$scope.tempkey);
              $scope.tempkey=$scope.tempkey+1; 
            }
          } else { return; }
        });
      });
    } else {
      nalert("This step is already done !","warning");
    }
    $scope.getAllword();
  };
});
angular.bootstrap(document.getElementById("ngApp"), ['ngApp']);