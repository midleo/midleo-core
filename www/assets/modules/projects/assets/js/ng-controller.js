var app = angular.module('ngApp', ['angularUtils.directives.dirPagination']);
app.config(['$compileProvider',
  function ($compileProvider) {
    $compileProvider.aHrefSanitizationWhitelist(/^\s*(https?|ftp|mailto|tel|file|blob):/);
  }]);
app.controller('ngCtrl', function ($scope, $http, $location, $window, $sce) {
  $scope.selectedid = [];
  $scope.contentLoaded = false;
  $scope.total = 0;
  $scope.finalobj = {};
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
  $scope.uploadXMLFile = function (thistype, appid) {
    $(".uplbut").hide();
    $(".uplwait").show();
    var formData = new FormData($('#XMLUpload').get(0));
    formData.append('file', $('#dfile')[0].files[0]);
    formData.append('appid', appid);
    $http({
      method: 'POST',
      data: formData,
      mimeType: "multipart/form-data",
      contentType: false,
      transformRequest: angular.identity,
      cache: false,
      processData: false,
      headers: { 'Content-Type': undefined },
      url: "/excelimportapi/" + thistype,
    }).then(function successCallback(response) {
      $('#modal-imp-form').modal('hide');
      $('#modal-response-form').modal('show');
      $(".uplbut").hide();
      $(".uplwait").hide();
      $scope.response = response.data;
      if (thistype == 'importibmmq') {
        $scope.getAllimp(appid);
      }
      if (thistype == 'importfw') {
        $scope.getAllfw(appid);
      }
     // if (thistype == 'importpj') {
     //   $scope.getAllproj();
     // }
    });
    // return false;        
  };
  $scope.exportData = function (what) { alasql('SELECT * INTO XLSX("MidleoData_' + what + '.xlsx",{sheetid:"' + what + '",headers:true}) FROM ?', [$scope.names]); };
  $scope.readpjgr = function (pjid, sessuser) {
    $http({
      method: 'POST',
      data: { 'pjid': pjid, 'sessuser': sessuser },
      url: '/projectapi/groups/readone'
    }).then(function successCallback(response) {
      if (response.data != "") {
        $scope.respusers = response.data;
      } else {
        $scope.respusers = {};
      }
    }, function errorCallback(response) {
      notify('Unable to retrieve users.', 'danger');
    });
  };
  $scope.addpjgr = function (pjid, sessuser) {
    if (!$("#respusersselected").val()) {
      notify("Please write a correct user", "danger");
      return;
    }
    var groupuser = $("#respusersselected").val();
    var groupuserarray = groupuser.split('#');
    $http({
      method: 'POST',
      data: { 'pjid': pjid, 'sessuser': sessuser, 'utype': groupuserarray[0], 'uid': groupuserarray[1], 'uname': groupuserarray[2], 'uemail': groupuserarray[3], 'utitle': groupuserarray[4], 'avatar': groupuserarray[5] },
      url: '/projectapi/groups/addusr'
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
  $scope.delpjgr = function (pjid, uid, utype, sessuser) {
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
          data: { 'pjid': pjid, 'userid': uid, 'utype': utype, 'sessuser': sessuser },
          url: '/projectapi/groups/delusr'
        }).then(function successCallback(response) {
          $(".usr_" + uid).hide();
          notify(response.data, 'success');
        });
      }
    })
  };
  $scope.getProjTempllist = function (thistype) {
    if ($('#contloaded')[0]) { angular.element(document.querySelector('#contloaded')).removeClass('hide'); }
    $http({
      method: 'POST',
      data: { 'type': thistype },
      url: '/projectapi/projtemplates'
    }).then(function successCallback(response) {
      $scope.contentLoaded = true;
      if (response.data != "null") { $scope.names = response.data; } else { $scope.names = []; }
    });
  };
  $scope.getAllproj = function (thistype) {
    if ($('#contloaded')[0]) { angular.element(document.querySelector('#contloaded')).removeClass('hide'); }
    $http({
      method: 'POST',
      data: { 'type': thistype },
      url: '/projectapi/projects'
    }).then(function successCallback(response) {
      $scope.contentLoaded = true;
      if (response.data != "null") { $scope.names = response.data; } else { $scope.names = []; }
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
  $scope.deleteproj = function (id, projcode, user, thistype) {
    Swal.fire({
      title: 'Delete this project?',
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
          data: { 'id': id, 'user': user, 'projcode': projcode, 'type': thistype },
          url: '/projectapi/projects/delete'
        }).then(function successCallback(response) {
          notify(response.data, 'success');
          $scope.getAllproj();
        });
      }
    })
  };
  $scope.addServtoPj = function (){
    if (!$("#serviceid").val()) {
      notify("Please write a search criteria", "danger");
      return;
    }
    let servid=$("#serviceid").val();
    let servname=$("#servicename").val();
    let servtype=$("#servicetype").val();
    let servform=$("#serviceformid").val();
    let servcost=$("#servicecost").val();
    let servcurcost=$("#servicecurcost").val();
    if(servid){ 
      if (typeof $scope.finalobj[servid] == "undefined" || Object.keys($scope.finalobj[servid]) === 0) {
        $scope.finalobj[servid] = {};
      }
      $scope.finalobj[servid].name=servname;
      $scope.finalobj[servid].type=servtype;
      $scope.finalobj[servid].formid=servform;
      $scope.finalobj[servid].cost=servcost;
      $scope.finalobj[servid].curcost=servcurcost;
      $scope.total=parseFloat($scope.total)+parseFloat(servcurcost);
    } 
    $scope.finaljsonobj=JSON.stringify($scope.finalobj);
    $(".autocomplservlist").val('');  
  };
  $scope.projstatus=function(thisid,thislink){

    $scope.redirect(thislink);
  };
});
angular.bootstrap(document.getElementById("ngApp"), ['ngApp']);