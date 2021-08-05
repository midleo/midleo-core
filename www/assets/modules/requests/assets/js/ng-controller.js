var app = angular.module('ngApp', ['angularUtils.directives.dirPagination']);
app.config(['$compileProvider',
  function ($compileProvider) {
    $compileProvider.aHrefSanitizationWhitelist(/^\s*(https?|ftp|mailto|tel|file|blob):/);
  }]);
app.controller('ngCtrl', function ($scope, $http, $location, $window, $sce) {
  $scope.selectedid = [];
  $scope.contentLoaded = false;
  $scope.textlimit = 20;
  $('.updinfo,.deplbut').hide();
  var efforts = $("#efforts").val();
  $scope.proj = {};
  $scope.proj.efforts = efforts;
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
  $scope.getAllreq = function (grid, type, own=null) {
    if ($('#contloaded')[0]) { angular.element(document.querySelector('#contloaded')).removeClass('hide'); }
    $http({
      method: 'POST',
      data: { 'grid': grid, 'type': type, 'own': own },
      url: '/reqapi/readureq'
    }).then(function successCallback(response) {
      $scope.contentLoaded = true;
      if (response.data != "null") { $scope.names = response.data; } else { $scope.names = {}; }
    });
  };
  $scope.readOneReq = function (reqid) {
    $('#requser').hide();
    $('#reqfiles').hide();
    $('.reqtype').hide();
    $('#btn-create-obj').hide();
    $('#btn-update-obj').show();
    $http({
      method: 'POST',
      data: { 'reqid': reqid },
      url: '/reqapi/readureq/one'
    }).then(function successCallback(response) {
      if (response.data != "null") {
        $scope.proj = response.data;
        if (response.data.requser != "") { $('#requser').show(); }
        if (response.data.files != "") { $('#reqfiles').show(); }
        $('#modal-obj-form').modal('show');
      } else {
        $scope.proj = [];
      }
    });
  };
  $scope.confirmreq = function (reqid, user, wfstep, usfullname, projid) {
    $http({
      method: 'POST',
      data: {
        'reqid': reqid,
        'user': user,
        'wfstep': wfstep,
        'fullname': usfullname,
        'project': projid
      },
      url: '/reqapi/confirmreq'
    }).then(function successCallback(response) {
      notify(response.data, 'success');
      $(".confirmreq" + reqid).hide();
    });
  };
  $scope.approvereq = function (reqid, user, wfstep, usfullname, projid) {
    $http({
      method: 'POST',
      data: {
        'reqid': reqid,
        'user': user,
        'wfstep': wfstep,
        'fullname': usfullname,
        'project': projid
      },
      url: '/reqapi/approvereq'
    }).then(function successCallback(response) {
      notify(response.data, 'success');
      $(".approvereq" + reqid).hide();
    });
  };
  $scope.deploybut = function (reqid, user, usfullname, projid) {
    var env = $scope.deployin;
    var pkgname = $scope.pkgname;
    $http({
      method: 'POST',
      data: {
        'reqid': reqid,
        'user': user,
        'fullname': usfullname,
        'project': projid,
        'pkgname': pkgname,
        'env': env
      },
      url: '/reqapi/deployreq'
    }).then(function successCallback(response) {
      notify(response.data, 'success');
    });
  };
  $scope.sendnext = function (nextid) {
    $scope.proj.nextstep = nextid;
    setTimeout(function () {
      $("#sendnext").click();
    }, 1);
  };
});
angular.bootstrap(document.getElementById("ngApp"), ['ngApp']);