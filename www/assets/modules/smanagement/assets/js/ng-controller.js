var app = angular.module('ngApp', ['angularUtils.directives.dirPagination','ui.tinymce']);
app.config(['$compileProvider',
  function ($compileProvider) {
    $compileProvider.aHrefSanitizationWhitelist(/^\s*(https?|ftp|mailto|tel|file|blob):/);
  }]);
app.controller('ngCtrl', function ($scope, $http, $location, $window, $sce, $anchorScroll) {
  $scope.selectedid = [];
  $scope.contentLoaded = false;
  $scope.contentpjLoaded = false;
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
  $scope.tinyOpts = {
    plugins: 'link image code',
    toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | code',
    height : "480"
  };
  $scope.exportData = function (what) { alasql('SELECT * INTO XLSX("MidleoData_' + what + '.xlsx",{sheetid:"' + what + '",headers:true}) FROM ?', [$scope.names]); };
  $scope.getAllservice = function (wid) {
    if ($('#contloaded')[0]) { angular.element(document.querySelector('#contloaded')).removeClass('hide'); }
    $http({
      method: 'POST',
      data: { 'wid': wid },
      url: '/reqworkflow/servicelist'
    }).then(function successCallback(response) {
      $scope.contentLoaded = true;
      if (response.data != "null") { $scope.names = response.data; } else { $scope.names = {}; }
    });
  };
  $scope.redirect = function (url, refresh) {
    if (refresh || $scope.$$phase) {
      $window.location.href = url;
    } else {
      $location.path(url);
      $scope.$apply();
    }
  };
  $scope.readrespusr = function (wid, sessuser) {
    $http({
      method: 'POST',
      data: { 'wid': wid, 'sessuser': sessuser },
      url: '/reqworkflow/groups/readone'
    }).then(function successCallback(response) {
      if (response.data != "") {
        $scope.respusers = response.data;
      } else {
        $scope.respusers = {};
      }
      $('#modal-user-form').modal('show');
    }, function errorCallback(response) {
      notify('Unable to retrieve users.', 'danger');
    });
  };
  $scope.addrespusr = function (wid, sessuser) {
    if (!$("#respusersselected").val()) {
      notify("Please write a correct user", "danger");
      return;
    }
    var groupuser = $("#respusersselected").val();
    var groupuserarray = groupuser.split('#');
    $http({
      method: 'POST',
      data: { 'wid': wid, 'sessuser': sessuser, 'utype': groupuserarray[0], 'uid': groupuserarray[1], 'uname': groupuserarray[2], 'uemail': groupuserarray[3] },
      url: '/reqworkflow/groups/addusr'
    }).then(function successCallback(response) {
      $scope.respusers[groupuserarray[1]] = {
        type: groupuserarray[0],
        uname: groupuserarray[2]
      };
      $("#autocomplete").val('');
      notify(response.data, 'success');
    }, function errorCallback(response) {
      notify('Unable to update group.', 'danger');
    });
  };
  $scope.delusrsel = function (wid, uid, utype, sessuser) {
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
          data: { 'wid': wid, 'userid': uid, 'utype': utype, 'sessuser': sessuser },
          url: '/reqworkflow/groups/delusr'
        }).then(function successCallback(response) {
          $(".usr_" + uid).hide();
          notify(response.data, 'success');
        });
      }
    })
  };
  $scope.showPJTempl = function (servicetype) {
    $scope.contentLoaded = false;
    $scope.templselected= false;
    $scope.names = [];
    $scope.pjinfo = [];
    if ($('#contloaded')[0]) { angular.element(document.querySelector('#contloaded')).removeClass('hide'); }
    $http({
      method: 'POST',
      data: { 'type': servicetype },
      url: '/projectapi/projtemplates'
    }).then(function successCallback(response) {
      $scope.contentLoaded = true;
     // $location.hash('templsel');
      $anchorScroll('templsel');
      if (response.data != "null") { $scope.names = response.data; } else { $scope.names = []; }
    });
  };
  $scope.showPJFinal = function (templid) {
    $scope.contentpjLoaded = false;
    $scope.templselected=templid;
    $scope.pjinfo = [];
    if ($('#contloaded')[0]) { angular.element(document.querySelector('#contloaded')).removeClass('hide'); }
    $http({
      method: 'POST',
      data: { 'templid': templid },
      url: '/projectapi/projtemplfinal'
    }).then(function successCallback(response) {
      $scope.contentpjLoaded = true;
     // $location.hash('projsubm');
      $anchorScroll('projsubm');
      if (response.data != "null") { 
        $scope.pjinfo = response.data;
        $scope.pjinfo.servinfo = JSON.parse(response.data.servinfo);
       } else { $scope.pjinfo = []; }
    });
  };
});
angular.bootstrap(document.getElementById("ngApp"), ['ngApp']);