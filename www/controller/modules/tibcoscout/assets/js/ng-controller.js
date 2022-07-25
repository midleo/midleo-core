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
  $scope.copyToClipboard = function (thistext) {
    navigator.clipboard.writeText(thistext);
    notify("Copied to clipboard", "success");
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
  $scope.getAllTib = function (what, projid) {
    if ($('#contloaded')[0]) { angular.element(document.querySelector('#contloaded')).removeClass('hide'); }
    $http({
      method: 'POST',
      data: { 'projid': projid },
      url: '/tibapi/readtibobj/' + what
    }).then(function successCallback(response) {
      $scope.contentLoaded = true;
      if (response.data != "null") { $scope.names = response.data; } else { $scope.names = {}; }
    });
  };
  $scope.readOneTib = function (what, objid, projid) {
    $('#btn-update-obj').show();
    $('#btn-create-obj').hide();
    $http({
      method: 'POST',
      data: { 'objid': objid, 'projid': projid },
      url: '/tibapi/readtibobj/' + what + '/one'
    }).then(function successCallback(response) {
      $scope.tibq = response.data;
      $("#tags").tagsinput('removeAll');
      $("#tags").tagsinput('add', $scope.tibq.tags);
      $('#modal-obj-form').modal('show');
    }, function errorCallback(response) {
      notify('Unable to retrieve record.', 'danger');
    });
  };
  $scope.showCreateFormTib = function () {
    $("#tags").tagsinput('removeAll');
    $scope.clearFormTib();
    $('#btn-update-obj').hide();
    $('#btn-create-obj').show();
  };
  $scope.clearFormTib = function () {
    $scope.tibq = {};
  };
  $scope.createtib = function (what, projid, user) {
    if ($("#tags").val()) { $scope.tibq.tags = $("#tags").val(); }
    var addToArray = true;
    for (var i = 0; i < $scope.names.length; i++) {
      if ($scope.names[i].name === $scope.tibq.name && $scope.names[i].srv === $scope.tibq.srv) {
        addToArray = false;
      }
    }
    if (addToArray == true) {
      $http({
        method: 'POST',
        data: { 'tibq': $scope.tibq, 'projid': projid, 'user': user },
        url: '/tibapi/add/' + what
      }).then(function successCallback(response) {
        notify(response.data, 'success');
        $('#modal-obj-form').modal('hide');
        $scope.clearFormTib();
        $scope.getAllTib(what, projid);
      });
    } else {
      notify("Object already exist!", 'warning');
    }
  };
  $scope.updatetib = function (what, projid, user) {
    if ($("#tags").val()) { $scope.tibq.tags = $("#tags").val(); }
    $http({
      method: 'POST',
      data: { 'tibq': $scope.tibq, 'projid': projid, 'user': user },
      url: '/tibapi/update/' + what
    }).then(function successCallback(response) {
      notify(response.data, 'success');
      $('#modal-obj-form').modal('hide');
      $scope.clearFormTib();
      $scope.getAllTib(what, projid);
    });
  };
  $scope.deletetib = function (what, tibid, tibqn, projid, user) {
    Swal.fire({
      title: 'Delete this object?',
      icon: 'error',
      showCancelButton: true,
      confirmButtonText: 'Delete',
      customClass: {
        confirmButton: 'btn btn-success btn-sm',
        cancelButton: 'btn btn-danger btn-sm',
      }
    }).then((result) => {
      if (result.value) {
        $http({
          method: 'POST',
          data: { 'id': tibid, 'tibqn': tibqn, 'projid': projid, 'user': user },
          url: '/tibapi/dell/' + what
        }).then(function successCallback(response) {
          notify(response.data, 'success');
          $scope.getAllTib(what, projid);
        });
      }
    })
  };
  $scope.deployTibSelected = function (thisapp, what) {
    $(".loading").show();
    $scope.responsedata = '';
    $scope.depl.selectedobj = [];
    $scope.depl.selectedobj = angular.fromJson($("#selectedobj").val());
    $http({
      method: 'POST',
      data: { 'env': $scope.depl.deplenv, 'type': what, 'srv': $scope.depl.srv, 'appl': thisapp, 'objects': $scope.depl.selectedobj },
      url: '/tibapi/deployselected'
    }).then(function successCallback(response) {
      $(".loading").hide();
      $('#modal-depl-form').modal('hide');
      $scope.responsedata = response.data;
      $('#modal-depl-response').modal('show');
      $scope.getAllTib(what, projid);
    });
  };
  $scope.getAllTibacl = function (thisapp) {
    if ($('#contloaded')[0]) { angular.element(document.querySelector('#contloaded')).removeClass('hide'); }
    $http({
      method: 'POST',
      data: { 'thisapp': thisapp },
      url: '/tibapi/readtibacl'
    }).then(function successCallback(response) {
      $scope.contentLoaded = true;
      if (response.data != "null") { $scope.names = response.data; } else { $scope.names = {}; }
    });
  };
  $scope.showCreateFormtibacl = function () {
    $("#tags").tagsinput('removeAll');
    $scope.clearFormtibacl();
    $('#btn-update-tibacl').hide();
    $('#btn-conf-tibacl').hide();
    $('#btn-create-tibacl').show();
  };
  $scope.clearFormtibacl = function () { $scope.tibacl = {}; };
  $scope.readOnetibacl = function (thisapp, id) {
    $('#btn-update-tibacl').show();
    $('#btn-conf-tibacl').show();
    $('#btn-create-tibacl').hide();
    $http({
      method: 'POST',
      data: { 'id': id, 'thisapp': thisapp },
      url: '/tibapi/readtibacl/one'
    }).then(function successCallback(response) {
      $scope.tibacl = response.data;
      $scope.selectedtibaclm = $scope.tibacl.selectedtibaclm;
      $("#tags").tagsinput('removeAll');
      $("#tags").tagsinput('add', $scope.tibacl.tags);
      $('#modal-tibacl-form').modal('show');
    }, function errorCallback(response) {
      notify('Unable to retrieve record.', 'danger');
    });
  };
  $scope.updatetibacl = function (thisapp, sessuser) {
    if ($("#tags").val()) { $scope.tibacl.tags = $("#tags").val(); }
    var perm = $("#selectedperm").val();
    $http({
      method: 'POST',
      data: { 'tibacl': $scope.tibacl, 'thisapp': thisapp, 'perm': perm, 'user': sessuser },
      url: '/tibapi/updatetibacl'
    }).then(function successCallback(response) {
      notify(response.data, 'success');
      $('#modal-tibacl-form').modal('hide');
      $scope.clearFormtibacl();
      $scope.getAllTibacl(thisapp);
    });
  };
  $scope.createtibacl = function (thisapp, sessuser) {
    if ($("#tags").val()) { $scope.tibacl.tags = $("#tags").val(); }
    var perm = $("#selectedperm").val();
    $http({
      method: 'POST',
      data: { 'tibacl': $scope.tibacl, 'thisapp': thisapp, 'perm': perm, 'user': sessuser },
      url: '/tibapi/createtibacl'
    }).then(function successCallback(response) {
      notify(response.data, 'success');
      $('#modal-tibacl-form').modal('hide');
      $scope.clearFormtibacl();
      $scope.getAllTibacl(thisapp);
    });
  };
  $scope.tibaclconf = function (thisapp, sessuser) {
    $http({
      method: 'POST',
      headers: { 'Content-type': 'text/plain;charset=utf-8' },
      data: { 'tibacl': $scope.tibacl, 'projid': thisapp, 'user': sessuser },
      cache: false,
      url: '/tibapi/acl'
    }).then(function successCallback(response, headers) {
      $('#modal-tibacl-form').modal('hide');
      $scope.response = response.data.resp;
      $('#modal-response-form').modal('show');
    });
  };
  $scope.delltibacl = function (thisapp, thisid, thisname, user) {
    Swal.fire({
      title: 'Delete this object?',
      icon: 'error',
      showCancelButton: true,
      confirmButtonText: 'Delete',
      customClass: {
        confirmButton: 'btn btn-success btn-sm',
        cancelButton: 'btn btn-danger btn-sm',
      }
    }).then((result) => {
      if (result.value) {
        $http({
          method: 'POST',
          data: { 'projid': thisapp, 'user': user, 'id': thisid, 'objname': thisname },
          url: '/tibapi/deltibacl'
        }).then(function successCallback(response) {
          notify(response.data, 'success');
          $scope.getAllTibacl(thisapp);
        });
      }
    })
  };
});