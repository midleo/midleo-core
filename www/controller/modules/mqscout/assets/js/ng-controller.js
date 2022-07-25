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
  //end main functions 
  $scope.getAll = function (what, projid, type, qm=null) {
    if ($('#contloaded')[0]) { angular.element(document.querySelector('#contloaded')).removeClass('hide'); }
    $http({
      method: 'POST',
      data: { 'projid': projid, 'type': type , 'qm': qm},
      url: '/mqapi/read/' + what
    }).then(function successCallback(response) {
      $scope.contentLoaded = true;
      if (response.data != "null") { $scope.names = response.data; } else { $scope.names = {}; }
    });
  };
  $scope.getAllfte = function (projid, type) {
    if ($('#contloaded')[0]) { angular.element(document.querySelector('#contloaded')).removeClass('hide'); }
    $http({
      method: 'POST',
      data: { 'projid': projid, 'type': type },
      url: '/mqapi/readfte'
    }).then(function successCallback(response) {
      $scope.contentLoaded = true;
      if (response.data != "null") { $scope.names = response.data; } else { $scope.names = {}; }
    });
  };
  $scope.delete = function (what, qid, qmid, projid, user, type) {
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
          data: { 'qid': qid, 'qmid': qmid, 'projid': projid, 'user': user, 'type': type },
          url: '/mqapi/dell/' + what
        }).then(function successCallback(response) {
          notify(response.data, 'success');
          $scope.getAll(what, projid, type);
        });
      }
    })
  };
  $scope.duplicate = function (what, qid, qmid, user, appid) {
    Swal.fire({
      title: 'Copy this object',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Copy',
      customClass: {
        confirmButton: 'btn btn-primary btn-sm',
        cancelButton: 'btn btn-danger btn-sm',
      }
    }).then((result) => {
      if (result.value) {
        $http({
          method: 'POST',
          data: { 'qid': qid, 'qmid': qmid, 'appid': appid, 'user': user },
          url: '/mqapi/duplicate/' + what
        }).then(function successCallback(response) {
          notify(response.data, 'success');
          if (what == "tibco") {
            $scope.getAllTib(qid, appid);
          } else {
            $scope.getAll(what, appid, 'env');
          }
        });
      }
    })
  };
  $scope.deletefte = function (projid, fteid, user, type, bstep) {
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
          data: { 'projid': projid, 'user': user, 'fteid': fteid, 'type': type, 'bstep': bstep },
          url: '/mqapi/dellfte/'
        }).then(function successCallback(response) {
          notify(response.data, 'success');
          $scope.getAllfte(projid, type);
        });
      }
    })
  };
  $scope.deleteflows = function (projid, flowid, user, type, flowname) {
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
          data: { 'projid': projid, 'user': user, 'flowid': flowid, 'type': type, 'flowname': flowname },
          url: '/mqapi/dellflows'
        }).then(function successCallback(response) {
          notify(response.data, 'success');
          $scope.getAllflows(projid, type);
        });
      }
    })
  };
  $scope.deleteflow = function (projid, user, flowid, file, type, flowname) {
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
          data: { 'projid': projid, 'user': user, 'flowid': flowid, 'file': file, 'type': type, 'flowname': flowname },
          url: '/mqapi/dellflow'
        }).then(function successCallback(response) {
          notify(response.data, 'success');
          $scope.getflow(flowid, type);
        });
      }
    })
  };
  $scope.update = function (what, projid, user, type) {
    if ($("#tags").val()) { $scope.mq.tags = $("#tags").val(); }
    $http({
      method: 'POST',
      data: { 'mq': $scope.mq, 'projid': projid, 'user': user, 'type': type },
      url: '/mqapi/update/' + what
    }).then(function successCallback(response) {
      notify(response.data, 'success');
      $('#modal-obj-form').modal('hide');
      $scope.clearForm();
      $scope.getAll(what, projid, type);
    });
  };
  $scope.updatefte = function (projid, user, type, bstep) {
    if ($("#tags").val()) { $scope.mqfte.tags = $("#tags").val(); }
    $scope.mqfte.mqftetype = $scope.mqfte.type;
    $http({
      method: 'POST',
      data: { 'mqfte': $scope.mqfte, 'user': user, 'projid': projid, 'type': type, 'bstep': bstep },
      url: '/mqapi/updatefte/'
    }).then(function successCallback(response) {
      notify(response.data, 'success');
      $('#modal-fte-form').modal('hide');
      $scope.clearFormfte();
      $scope.getAllfte(projid, type);
    });
  };
  $scope.readOne = function (what, qid, qmid, projid, type) {
    $('#btn-update-obj').show();
    $('#btn-mqsc-obj').show();
    $('#btn-create-obj').hide();
    $http({
      method: 'POST',
      data: { 'qid': qid, 'qmid': qmid, 'projid': projid, 'type': type },
      url: '/mqapi/read/' + what + '/one'
    }).then(function successCallback(response) {
      $scope.mq = response.data;
      $("#tags").tagsinput('removeAll');
      $("#tags").tagsinput('add', $scope.mq.tags);
      $('#modal-obj-form').modal('show');
    }, function errorCallback(response) {
      notify('Unable to retrieve record.', 'danger');
    });
  };
  $scope.readOnefte = function (projid, fteid, type) {
    $('#btn-update-fte').show();
    $('#btn-conf-fte').show();
    $('#btn-create-fte').hide();
    $http({
      method: 'POST',
      data: { 'fteid': fteid, 'projid': projid, 'type': type },
      url: '/mqapi/readfte/one'
    }).then(function successCallback(response) {
      $scope.mqfte = response.data;
      $("#tags").tagsinput('removeAll');
      $("#tags").tagsinput('add', $scope.mqfte.tags);
      $('#modal-fte-form').modal('show');
    }, function errorCallback(response) {
      notify('Unable to retrieve record.', 'danger');
    });
  };
  $scope.showCreateForm = function () {
    $("#tags").tagsinput('removeAll');
    $scope.clearForm();
    $('#btn-update-obj').hide();
    $('#btn-mqsc-obj').hide();
    $('#btn-create-obj').show();
  };
  $scope.resetDeplForm = function () {
    $scope.depl = {};
    $(".loading").hide();
  };
  $scope.showDeployForm = function () {
    if (typeof $scope.depl != "undefined") {
      $scope.depl.qm = "";
      $scope.depl.deplenv = "";
    }
    $("#jobnextrun").val();
    $("#reqname").val();
    $("reqauto").val();
  };
  $scope.createjob = function (appcode, objenv, objplace, what, type) {
    $scope.depl.selectedobj = [];
    $scope.depl.selectedobj = angular.fromJson($("#selectedobj").val());
    if ($("#reqname").val()) { $scope.depl.reqid = $("#reqname").val(); } else { $scope.depl.reqid = "unknown"; }
    if ($("#jobnextrun").val()) { $scope.depl.nextrun = $("#jobnextrun").val(); }
    $http({
      method: 'POST',
      data: { 'appcode': appcode, 'deplinfo': $scope.depl, 'objenv': objenv, 'objplace': objplace, 'what': what },
      url: '/autoapi/addjob'
    }).then(function successCallback(response) {
      notify('Job created successfully', 'success');
      if (objenv == "fte") {
        $scope.getAllfte(appcode, 'env');
      } else if (objenv == "tibems") {
        $scope.getAllTib(what, appcode);
      } else {
        $scope.getAll(what, appcode, type);
      }
    }, function errorCallback(response) {
      notify('Unable to create this job.', 'danger');
    });
    $('#modal-depl-form').modal('hide');
  };
  $scope.showCreateFormfte = function () {
    $("#tags").tagsinput('removeAll');
    $scope.clearFormfte();
    $('#btn-update-fte').hide();
    $('#btn-conf-fte').hide();
    $('#btn-create-fte').show();
  };
  $scope.showCreateFormflow = function () { $("#tags").tagsinput('removeAll'); $scope.clearFormflow(); };
  $scope.clearForm = function () {
    if ($scope.mq) {
      var type = $scope.mq.type;
      var active = $scope.mq.active;
      var projname = $scope.mq.projname;
      $scope.mq = {};
      $scope.mq.type = type;
      $scope.mq.active = active;
      $scope.mq.projname = projname;
      $('.autocomplsrv').val();
      $('#server').val();
      $('#serverip').val();
      $('#serverid').val();
    }
  };
  $scope.create = function (what, projid, user, type) {
    if ($("#tags").val()) { $scope.mq.tags = $("#tags").val(); }
    var addToArray = true;
    for (var i = 0; i < $scope.names.length; i++) {
      if ($scope.names[i].name === $scope.mq.name && $scope.names[i].qm === $scope.mq.qm) {
        addToArray = false;
      }
    }
    if (addToArray == true) {
      $http({
        method: 'POST',
        data: { 'mq': $scope.mq, 'projid': projid, 'user': user, 'type': type },
        url: '/mqapi/add/' + what
      }).then(function successCallback(response) {
        notify(response.data, 'success');
        $('#modal-obj-form').modal('hide');
        $scope.clearForm();
        $scope.getAll(what, projid, type);
      });
    } else {
      notify("Object already exist!", 'warning');
    }
  };
  $scope.createfte = function (projid, user, type, bstep) {
    if ($("#tags").val()) { $scope.mqfte.tags = $("#tags").val(); }
    $http({
      method: 'POST',
      data: { 'mqfte': $scope.mqfte, 'user': user, 'projid': projid, 'type': type, 'bstep': bstep },
      url: '/mqapi/addfte/'
    }).then(function successCallback(response) {
      notify(response.data, 'success');
      $('#modal-fte-form').modal('hide');
      $scope.clearFormfte();
      $scope.getAllfte(projid, type);
    });
  };
  $scope.createflow = function (projid, user, type) {
    if ($("#tags").val()) { $scope.flow.tags = $("#tags").val(); }
    $http({
      method: 'POST',
      data: { 'flow': $scope.flow, 'user': user, 'projid': projid, 'type': type },
      url: '/mqapi/addflow'
    }).then(function successCallback(response) {
      notify(response.data, 'success');
      $('#modal-flow-form').modal('hide');
      $scope.clearFormflow();
      $scope.getAllflows(projid, type);
    });
  };
  $scope.mqsc = function (what, projid, user, type) {
    $http({
      method: 'POST',
      headers: { 'Content-type': 'text/html;charset=utf-8' },
      data: { 'mq': $scope.mq, 'user': user, 'what': what, 'type': type },
      cache: false,
      url: '/mqapi/mqsc/one/' + projid
    }).then(function successCallback(response, headers) {
      $('#modal-obj-form').modal('hide');
      $scope.response = response.data.resp;
      $('#modal-response-form').modal('show');
    });
  };
  $scope.dlqh = function (what, projid, user, type) {
    $http({
      method: 'POST',
      headers: { 'Content-type': 'text/html;charset=utf-8' },
      data: { 'mq': $scope.mq, 'user': user, 'what': what, 'type': type },
      cache: false,
      url: '/mqapi/dlqh/' + projid
    }).then(function successCallback(response, headers) {
      $('#modal-obj-form').modal('hide');
      $scope.response = response.data.resp;
      $('#modal-response-form').modal('show');
    });
  };
  $scope.deployMQftesel = function (appcode) {
    $(".loading").show();
    $scope.depl.selectedobj = [];
    $scope.responsedataftemess = [];
    $scope.depl.selectedobj = angular.fromJson($("#selectedobj").val());
    $http({
      method: 'POST',
      data: { 'appcode': appcode, 'fteids': $scope.depl.selectedobj, 'env': $scope.depl.deplenv },
      url: '/deplapi/deployfte'
    }).then(function successCallback(response) {
      $('#modal-depl-form').modal('hide');
      $(".loading").hide();
      $scope.responsedatafte = JSON.parse(response.data.resp);
      $scope.responsedataftemess = JSON.parse(response.data.respreply);
      /*   angular.forEach($scope.responsedatafte.messages, function(value, key) {
           $scope.responsedataftemess.push(atob(value.message));
         }); */
      $('#modal-deplfte-response').modal('show');
      /*   if(response.data.success){
           notify(response.data.resp, 'success');
         } else {
           notify(response.data.resp, 'danger');
         } */
      // $scope.deployMQfteselreply(response.data.replyconnd);
      $scope.getAllfte(appcode, 'env');
    });
  };
  /* $scope.deployMQfteselreply=function(thisdata){
     $scope.responsedataftemess = [];
   };*/
  $scope.fteconf = function (projid, type) {
    $http({
      method: 'POST',
      headers: { 'Content-type': 'text/plain;charset=utf-8' },
      data: { 'mqfte': $scope.mqfte, 'type': type },
      cache: false,
      url: '/mqapi/fte/one/' + projid
    }).then(function successCallback(response, headers) {
      $('#modal-fte-form').modal('hide');
      $scope.response = response.data.resp;
      $('#modal-response-form').modal('show');
    });
  };
  $scope.auth = function (projid, user, type) {
    $http({
      method: 'POST',
      headers: { 'Content-type': 'text/html;charset=utf-8' },
      data: { 'mq': $scope.mq, 'user': user, 'type': type },
      cache: false,
      url: '/mqapi/auth/one/' + projid
    }).then(function successCallback(response, headers) {
      $('#modal-obj-form').modal('hide');
      $scope.response = response.data.resp;
      $('#modal-response-form').modal('show');
    });
  };
  $scope.getAllflows = function (projid, type) {
    if ($('#contloaded')[0]) { angular.element(document.querySelector('#contloaded')).removeClass('hide'); }
    $http({
      method: 'POST',
      data: { 'projid': projid, 'type': type },
      url: '/mqapi/readflows'
    }).then(function successCallback(response) {
      $scope.contentLoaded = true;
      if (response.data != "null") { $scope.names = response.data; } else { $scope.names = []; }
    });
  };
  $scope.getflow = function (flowid, type) {
    if ($('#contloaded')[0]) { angular.element(document.querySelector('#contloaded')).removeClass('hide'); }
    $http({
      method: 'POST',
      data: { 'flowid': flowid, 'type': type },
      url: '/mqapi/readflow'
    }).then(function successCallback(response) {
      $scope.contentLoaded = true;
      if (response.data != "null") { $scope.names = response.data; } else { $scope.names = []; }
    });
  };
  $scope.getAllpack = function (proj) {
    if ($('#contloaded')[0]) { angular.element(document.querySelector('#contloaded')).removeClass('hide'); }
    $http({
      method: 'POST',
      data: { 'proj': proj },
      url: '/mqapi/readpack'
    }).then(function successCallback(response) {
      $scope.contentLoaded = true;
      if (response.data != "null") { $scope.names = response.data; } else { $scope.names = []; }
    });
  };
  $scope.deletepack = function (appcode, packid, packuid) {
    Swal.fire({
      title: 'Delete this package?',
      icon: 'error',
      showCancelButton: true,
      confirmButtonText: 'Delete',
      customClass: {
        confirmButton: 'btn btn-success btn-sm',
        cancelButton: 'btn btn-danger btn-sm',
      }
    }).then((result) => {
      if (result.value) {
        $(".delb" + packid).html('<i class="mdi mdi-loading iconspin"></i>');
        $(".delb" + packid).prop("disabled", true);
        $(".delb" + packid).removeClass('btn-light').addClass('btn-secondary');
        $http({
          method: 'POST',
          data: { 'proj': appcode, 'packid': packid, 'packuid': packuid },
          url: '/deplapi/dellpack'
        }).then(function successCallback(response) {
          notify(response.data, 'success');
          $scope.getAllpack(appcode);
        });
      }
    })
  };
  $scope.getAllimp = function (appid) {
    if ($('#contloaded')[0]) { angular.element(document.querySelector('#contloaded')).removeClass('hide'); }
    $http({
      method: 'POST',
      data: { 'appid': appid },
      url: '/mqapi/readimp'
    }).then(function successCallback(response) {
      $scope.contentLoaded = true;
      if (response.data != "null") { $scope.names = response.data; } else { $scope.names = []; }
    });
  };
  $scope.getAlldepl = function (thisapp) {
    if ($('#contloaded')[0]) { angular.element(document.querySelector('#contloaded')).removeClass('hide'); }
    $http({
      method: 'POST',
      data: { 'appid': thisapp },
      url: '/mqapi/readdepl'
    }).then(function successCallback(response) {
      $scope.contentLoaded = true;
      if (response.data != "null") { $scope.names = response.data; } else { $scope.names = []; }
    });
  };
  $scope.showCreateFormpack = function () {
    $scope.clearFormpack();
    $("#tags").tagsinput('removeAll');
    $('#btn-create-obj').show();
  };
  $scope.clearFormfte = function () { $scope.mqfte = {}; };
  $scope.clearFormflow = function () { $scope.flow = {}; };
  $scope.clearFormpack = function () { $scope.package = {}; };
  $scope.deployPKG = function (thisapp) {
    if ($("#deplpkgid").val()) { $scope.depl.pkgid = $("#deplpkgid").val(); }
    if ($("#reqname").val()) { $scope.depl.reqid = $("#reqname").val(); }
    if ($("#srvid").val()) { $scope.depl.srvid = $("#srvid").val(); }
    $('.loading').show();
    $('.deplbutns').hide();
    $scope.responsedata = '';
    $http({
      method: 'POST',
      data: { 'deplinfo': $scope.depl, 'appid': thisapp },
      url: '/deplapi/deployall'
    }).then(function successCallback(response) {
      $scope.responsedata = response.data;
      $('#modal-depl-form').modal('hide');
      $('#modal-depl-response').modal('show');
      $('.loader').hide();
      $scope.getAlldepl(thisapp);
    });
  };
  $scope.deployMQSelected = function (thistype, thisapp) {
    if ($("#reqname").val()) { $scope.depl.reqid = $("#reqname").val(); }
    $(".loading").show();
    $scope.responsedata = '';
    $scope.depl.selectedobj = [];
    $scope.depl.selectedobj = angular.fromJson($("#selectedobj").val());
    $http({
      method: 'POST',
      data: { 'deplinfo': $scope.depl, 'appl': thisapp, 'type': thistype },
      url: '/deplapi/deployselected'
    }).then(function successCallback(response) {
      $(".loading").hide();
      $('#modal-depl-form').modal('hide');
      $scope.responsedata = response.data;
      $('#modal-depl-response').modal('show');
      $scope.getAll(thistype, thisapp, 'env');
    });
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
    });
  };
  $scope.getpkgapps = function () {
    let objtype = $scope.package.objtypes;
    if ($("#appname").val()) { $scope.package.appname = $("#appname").val(); }
    if (objtype) {
      objtype = objtype.split('#');
      if (objtype[0] == "qm") {
        $scope.names = {};
        $scope.getAll(objtype[1], $scope.package.appname, 'env');
      } else if (objtype[0] == "fte") {
        $scope.names = {};
        $scope.getAllfte($scope.package.appname, 'env');
      } else if (objtype[0] == "tibems") {
        $scope.names = {};
        $scope.getAllTib(objtype[1], $scope.package.appname);
      }
    }
  };
  $scope.addtofinal = function () {
    let objtype = $scope.package.objtypes;
    objtype = objtype.split('#');
    if ($("#appname").val()) { $scope.package.appname = $("#appname").val(); }
    if (typeof $scope.finalobj[$scope.package.appname] == "undefined" || Object.keys($scope.finalobj[$scope.package.appname]) === 0) {
      $scope.finalobj[$scope.package.appname] = {};
    }
    angular.forEach($scope.selectedid, function (value, key) {
      if (typeof $scope.finalobj[$scope.package.appname][$("#pkgapsrvid" + value).val()] == "undefined" || Object.keys($scope.finalobj[$scope.package.appname][$("#pkgapsrvid" + value).val()]) === 0) {
        $scope.finalobj[$scope.package.appname][$("#pkgapsrvid" + value).val()] = {};
      }
      if (typeof $scope.finalobjnames[objtype[1] + value] == "undefined" || Object.keys($scope.finalobjnames[objtype[1] + value]) === 0) {
        $scope.finalobjnames[objtype[1] + value] = {};
        $scope.finalobjnames[objtype[1] + value].name = $("#pkgnid" + value).val();
        $scope.finalobjnames[objtype[1] + value].type = objtype[1];
      } else {
        $scope.finalobjnames[objtype[1] + value].name = $("#pkgnid" + value).val();
        $scope.finalobjnames[objtype[1] + value].type = objtype[1];
      }
      if (angular.isArray($scope.finalobj[$scope.package.appname][$("#pkgapsrvid" + value).val()][objtype[1]])) {
        $scope.finalobj[$scope.package.appname][$("#pkgapsrvid" + value).val()][objtype[1]].push(value);
      } else {
        $scope.finalobj[$scope.package.appname][$("#pkgapsrvid" + value).val()][objtype[1]] = [];
        $scope.finalobj[$scope.package.appname][$("#pkgapsrvid" + value).val()][objtype[1]].push(value);
      }
    });
    $scope.selectedid = [];
  };
  $scope.preparepack = function (appcode, packid) {
    notify("Please wait. Package preparation begin.", "info");
    $(".prepb" + packid).html('<i class="mdi mdi-loading iconspin"></i>');
    $(".prepb" + packid).prop("disabled", true);
    $(".prepb" + packid).removeClass('btn-light').addClass('btn-secondary');
    $http({
      method: 'POST',
      data: { 'proj': appcode, 'packid': packid },
      url: '/deplapi/packprepare'
    }).then(function successCallback(response) {
      notify(response.data, 'success');
      $scope.getAllpack(appcode);
    });
  };
  $scope.getQMlist = function (thisapp, thispkg, thisid) {
    $(".mql" + thisid).html('<i class="mdi mdi-loading iconspin"></i>');
    $(".mql" + thisid).prop("disabled", true);
    $(".mql" + thisid).removeClass('btn-light').addClass('btn-secondary');
    $http({
      method: 'POST',
      data: { 'appl': thisapp, 'pkg': thispkg },
      url: '/mqapi/applications/qmlist'
    }).then(function successCallback(response) {
      $scope.pkgid = thispkg;
      $(".mql" + thisid).html('<i class="mdi mdi-eye-outline"></i>');
      $(".mql" + thisid).prop("disabled", false);
      $(".mql" + thisid).removeClass('btn-secondary').addClass('btn-light');
      if (response.data != "null") { $scope.qmlist = response.data; } else { $scope.qmlist = []; }
      $('#modal-obj-form').modal('show');
    });
  }
  $scope.getGitInfo = function (packuid, thisid) {
    $(".gl" + thisid).html('<i class="mdi mdi-loading iconspin"></i>');
    $(".gl" + thisid).prop("disabled", true);
    $(".gl" + thisid).removeClass('btn-light').addClass('btn-secondary');
    $http({
      method: 'POST',
      data: { 'pkg': packuid },
      url: '/vcapi/gitlist/commits'
    }).then(function successCallback(response) {
      $(".gl" + thisid).html('<i class="mdi mdi-git"></i>');
      $(".gl" + thisid).prop("disabled", false);
      $(".gl" + thisid).removeClass('btn-secondary').addClass('btn-light');
      if (response.data != "null") { $scope.gitlist = response.data; } else { $scope.gitlist = []; }
      $('#modal-git-form').modal('show');
    });
  }
  $scope.mqpreview = function (thisapp) {
    let thispkg = $("#pkgid").val();
    let thisenv = $("#pkgenv").val();
    $(".loading").show();
    $http({
      method: 'POST',
      data: { 'thisapp': thisapp, 'thispkg': thispkg, 'qm': $scope.thisqm, 'env': thisenv },
      url: '/deplapi/mqpreview'
    }).then(function successCallback(response) {
      $(".loading").hide();
      $('#modal-obj-form').modal('hide');
      $scope.response = response.data;
      $('#modal-resp-form').modal('show');
    });
  };
  $scope.createvar = function (user, projid) {
    if ($("#tags").val()) { $scope.mq.tags = $("#tags").val(); }
    $http({
      method: 'POST',
      data: { 'mq': $scope.mq, 'user': user, 'projid': projid },
      url: '/mqapi/addvar'
    }).then(function successCallback(response) {
      notify(response.data, 'success');
      $('#modal-obj-form').modal('hide');
      $scope.clearForm();
      $scope.getAll('vars', projid, 'mqenv');
    });
  };
  $scope.updatevar = function (user, projid) {
    if ($("#tags").val()) { $scope.mq.tags = $("#tags").val(); }
    $http({
      method: 'POST',
      data: { 'mq': $scope.mq, 'user': user, 'projid': projid },
      url: '/mqapi/updatevar'
    }).then(function successCallback(response) {
      notify(response.data, 'success');
      $('#modal-obj-form').modal('hide');
      $scope.clearForm();
      $scope.getAll('vars', projid, 'mqenv');
    });
  };
  $scope.deletevar = function (varname, varid, user, projid) {
    $http({
      method: 'POST',
      data: { 'varname': varname, 'user': user, 'varid': varid, 'projid': projid },
      url: '/mqapi/delvar'
    }).then(function successCallback(response) {
      notify(response.data, 'success');
      $('#modal-obj-form').modal('hide');
      $scope.clearForm();
      $scope.getAll('vars', '', 'mqenv');
    });
  };
});
