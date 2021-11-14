var app = angular.module('ngApp', ['angularUtils.directives.dirPagination']);
app.config(['$compileProvider',
  function ($compileProvider) {
    $compileProvider.aHrefSanitizationWhitelist(/^\s*(https?|ftp|mailto|tel|file|blob):/);
  }]);
app.controller('ngCtrl', function ($scope, $http, $location, $window, $sce) {
  $scope.selectedid = [];
  $scope.contentLoaded = false;
  $scope.textlimit = 20;
  $scope.parJson = function (json) {
    if (json) { return JSON.parse(json); }
  };
  $scope.renderHtml = function (htmlCode) {
    return $sce.trustAsHtml(htmlCode);
  };
  $scope.decode64 = function (str) {
    return atob(str);
  };
  $scope.exportData = function (what) { alasql('SELECT * INTO XLSX("MidleoData_' + what + '.xlsx",{sheetid:"' + what + '",headers:true}) FROM ?', [$scope.names]); };
  $scope.redirect = function (url, refresh) {
    if (refresh || $scope.$$phase) {
      $window.location.href = url;
    } else {
      $location.path(url);
      $scope.$apply();
    }
  };
  $scope.getallCICD = function (thistype) {
    if ($('#contloaded')[0]) { angular.element(document.querySelector('#contloaded')).removeClass('hide'); }
    $http({
      method: 'POST',
      data: { 'type': thistype },
      url: '/autoapi/readcicd'
    }).then(function successCallback(response) {
      $scope.contentLoaded = true;
      if (response.data != "null") { $scope.names = response.data; } else { $scope.names = {}; }
    });
  };
  $scope.getallMQINV = function () {
    if ($('#contloaded')[0]) { angular.element(document.querySelector('#contloaded')).removeClass('hide'); }
    $http({
      method: 'POST',
      data: {},
      url: '/autoapi/readmqinv'
    }).then(function successCallback(response) {
      $scope.contentLoaded = true;
      if (response.data != "null") { $scope.names = response.data; } else { $scope.names = {}; }
    });
  };
  $scope.createmqjob = function () {
    if ($("#jobnextrun").val()) { $scope.depl.nextrun = $("#jobnextrun").val(); }
    $http({
      method: 'POST',
      data: { 'deplinfo': $scope.depl },
      url: '/autoapi/addmqinv'
    }).then(function successCallback(response) {
      notify('Job created successfully', 'success');
      $("#jobnextrun").val();
      $scope.depl = {};
      $('#modal-obj-form').modal('hide');
      $scope.getallMQINV();
    });
  };
  $scope.deletemqinv = function (invid, appid, qmgr, user) {
    Swal.fire({
      title: 'Delete this object?',
      icon: 'error',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Delete',
      customClass: {
        confirmButton: 'btn btn-success btn-sm',
        cancelButton: 'btn btn-danger btn-sm',
      }
    }).then((result) => {
      if (result.value) {
        $http({
          method: 'POST',
          data: { 'invid': invid, 'qmgr': qmgr, 'appid': appid, 'user': user },
          url: '/autoapi/delmqinv'
        }).then(function successCallback(response) {
          notify(response.data, 'success');
          $scope.getallMQINV();
        });
      }
    })
  };
});