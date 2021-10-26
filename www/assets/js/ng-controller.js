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
  $scope.getAll = function (what, projid, type) {
    if ($('#contloaded')[0]) { angular.element(document.querySelector('#contloaded')).removeClass('hide'); }
    $http({
      method: 'POST',
      data: { 'projid': projid, 'type': type },
      url: '/api/read/' + what
    }).then(function successCallback(response) {
      $scope.contentLoaded = true;
      if (response.data != "null") { $scope.names = response.data; } else { $scope.names = {}; }
    });
  };
  $scope.exportData = function (what) { alasql('SELECT * INTO XLSX("MidleoData_' + what + '.xlsx",{sheetid:"' + what + '",headers:true}) FROM ?', [$scope.names]); };
  $scope.getAllAppserv = function (appcode) {
    if ($('#contloaded')[0]) { angular.element(document.querySelector('#contloaded')).removeClass('hide'); }
    $http({
      method: 'POST',
      data: { 'appcode': appcode },
      url: '/api/readappsrv'
    }).then(function successCallback(response) {
      $scope.contentLoaded = true;
      if (response.data != "null") { $scope.names = response.data; } else { $scope.names = {}; }
    });
  };
  $scope.getAllfw = function (appcode) {
    if ($('#contloaded')[0]) { angular.element(document.querySelector('#contloaded')).removeClass('hide'); }
    $http({
      method: 'POST',
      data: { 'appcode': appcode },
      url: '/api/firewall/all'
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
      url: '/api/readfte'
    }).then(function successCallback(response) {
      $scope.contentLoaded = true;
      if (response.data != "null") { $scope.names = response.data; } else { $scope.names = {}; }
    });
  };
  $scope.getAllusers = function (user) {
    if ($('#contloaded')[0]) { angular.element(document.querySelector('#contloaded')).removeClass('hide'); }
    $http({
      method: 'POST',
      data: { 'user': user },
      url: '/api/users'
    }).then(function successCallback(response) {
      $scope.contentLoaded = true;
      if (response.data != "null") { $scope.names = response.data; } else { $scope.names = []; }
    });
  };
  $scope.getAllgroups = function (user) {
    if ($('#contloaded')[0]) { angular.element(document.querySelector('#contloaded')).removeClass('hide'); }
    $http({
      method: 'POST',
      data: { 'user': user },
      url: '/api/groups'
    }).then(function successCallback(response) {
      $scope.contentLoaded = true;
      if (response.data != "null") { $scope.names = response.data; } else { $scope.names = []; }
    });
  };
  $scope.showgrinfo = function (thisid) {
    if ($(".grudiv" + thisid).hasClass('grudivtg')) {
      $(".grudiv" + thisid).removeClass("grudivtg");
      $(".grudiv" + thisid).slideUp();
      $("#svg" + thisid).html('<i class="mdi mdi-plus mdi-18px"></i>');
    } else {
      $(".grudiv" + thisid).addClass("grudivtg");
      $(".grudiv" + thisid).slideDown("slow");
      $("#svg" + thisid).html('<i class="mdi mdi-minus mdi-18px"></i>');
    }
  }
  $scope.delete = function (what, qid, qmid, projid, user, type) {
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
          data: { 'qid': qid, 'qmid': qmid, 'projid': projid, 'user': user, 'type': type },
          url: '/api/dell/' + what
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
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
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
          url: '/api/duplicate/' + what
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
  $scope.delappsrv = function (server, user, appcode) {
    Swal.fire({
      title: 'Delete this Server?',
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
          data: { 'server': server, 'user': user, 'appcode': appcode },
          url: '/api/dellappsrv'
        }).then(function successCallback(response) {
          notify(response.data, 'success');
          $scope.getAllAppserv(appcode);
        });
      }
    })
  };
  $scope.deldes = function (id, desid, user) {
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
          data: { 'id': id, 'user': user, 'desid': desid },
          url: '/drawapi/deldesign'
        }).then(function successCallback(response) {
          notify(response.data, 'success');
          $scope.getAllDes();
        });
      }
    })
  };
  $scope.deletefw = function (id, user, dns, projid) {
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
          data: { 'id': id, 'user': user, 'dns': dns, 'projid': projid },
          url: '/api/firewall/delete'
        }).then(function successCallback(response) {
          notify(response.data, 'success');
          $scope.getAllfw(projid);
        });
      }
    })
  };
  $scope.deletefte = function (projid, fteid, user, type, bstep) {
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
          data: { 'projid': projid, 'user': user, 'fteid': fteid, 'type': type, 'bstep': bstep },
          url: '/api/dellfte/'
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
          data: { 'projid': projid, 'user': user, 'flowid': flowid, 'type': type, 'flowname': flowname },
          url: '/api/dellflows'
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
          data: { 'projid': projid, 'user': user, 'flowid': flowid, 'file': file, 'type': type, 'flowname': flowname },
          url: '/api/dellflow'
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
      url: '/api/update/' + what
    }).then(function successCallback(response) {
      notify(response.data, 'success');
      $('#modal-obj-form').modal('hide');
      $scope.clearForm();
      $scope.getAll(what, projid, type);
    });
  };
  $scope.updappsrv = function (user, appcode) {
    if ($("#tags").val()) { $scope.serv.tags = $("#tags").val(); }
    if ($("#server").val()) { $scope.serv.serverdns = $("#server").val(); }
    if ($("#serverip").val()) { $scope.serv.serverip = $("#serverip").val(); }
    if ($("#serverid").val()) { $scope.serv.serverid = $("#serverid").val(); }
    var sslkey = $scope.serv.sslkey;
    if (sslkey) {
      $scope.serv.sslkey = btoa(sslkey).replace(/=/g, "");
    };
    $http({
      method: 'POST',
      data: { 'serv': $scope.serv, 'user': user, 'appcode': appcode },
      url: '/api/updappsrv'
    }).then(function successCallback(response) {
      notify(response.data, 'success');
      $('#modal-obj-form').modal('hide');
      $scope.clearFormServ();
      $scope.getAllAppserv(appcode);
    });
  };
  $scope.updatefw = function (projid, user) {
    if ($("#tags").val()) { $scope.fw.tags = $("#tags").val(); }
    $http({
      method: 'POST',
      data: { 'fw': $scope.fw, 'user': user, 'projid': projid },
      url: '/api/firewall/update'
    }).then(function successCallback(response) {
      notify(response.data, 'success');
      $('#modal-obj-form').modal('hide');
      $scope.clearFormfw();
      $scope.getAllfw(projid);
    });
  };
  $scope.updatefte = function (projid, user, type, bstep) {
    if ($("#tags").val()) { $scope.mqfte.tags = $("#tags").val(); }
    $scope.mqfte.mqftetype = $scope.mqfte.type;
    $http({
      method: 'POST',
      data: { 'mqfte': $scope.mqfte, 'user': user, 'projid': projid, 'type': type, 'bstep': bstep },
      url: '/api/updatefte/'
    }).then(function successCallback(response) {
      notify(response.data, 'success');
      $('#modal-fte-form').modal('hide');
      $scope.clearFormfte();
      $scope.getAllfte(projid, type);
    });
  };
  $scope.updateuser = function (sessuser) {
    $http({
      method: 'POST',
      data: { 'users': $scope.user },
      url: '/api/users/update'
    }).then(function successCallback(response) {
      notify(response.data, 'success');
      $('#modal-user-form').modal('hide');
      $scope.clearFormuser();
      $scope.getAllusers(sessuser);
    });
  };
  $scope.updategroup = function (sessuser) {
    var modules = $("#selectedmodules").val();
    $http({
      method: 'POST',
      data: { 'group': $scope.group, 'sessuser': sessuser, 'modules': modules },
      url: '/api/groups/update'
    }).then(function successCallback(response) {
      notify(response.data, 'success');
      $('#modal-user-form').modal('hide');
      $scope.clearFormgroup();
      $scope.getAllgroups();
    });
  };
  $scope.readOne = function (what, qid, qmid, projid, type) {
    $('#btn-update-obj').show();
    $('#btn-mqsc-obj').show();
    $('#btn-create-obj').hide();
    $http({
      method: 'POST',
      data: { 'qid': qid, 'qmid': qmid, 'projid': projid, 'type': type },
      url: '/api/read/' + what + '/one'
    }).then(function successCallback(response) {
      $scope.mq = response.data;
      $("#tags").tagsinput('removeAll');
      $("#tags").tagsinput('add', $scope.mq.tags);
      $('#modal-obj-form').modal('show');
    }, function errorCallback(response) {
      notify('Unable to retrieve record.', 'danger');
    });
  };
  $scope.readOneAppserv = function (server, appcode) {
    $('#btn-update-obj').show();
    $('#btn-mqsc-obj').show();
    $('#btn-create-obj').hide();
    $scope.serv = {};
    $http({
      method: 'POST',
      data: { 'server': server, 'appcode': appcode },
      url: '/api/readappsrv/one'
    }).then(function successCallback(response) {
      $scope.serv = response.data;
      var sslkey = $scope.serv.sslkey;
      if (sslkey) {
        $scope.serv.sslkey = atob($scope.serv.sslkey);
      }
      $("#tags").tagsinput('removeAll');
      $("#tags").tagsinput('add', $scope.serv.tags);
      $('#modal-obj-form').modal('show');
    }, function errorCallback(response) {
      notify('Unable to retrieve record.', 'danger');
    });
  };
  $scope.readusr = function (user, sessuser) {
    $('#btn-update-user').show();
    $('#btn-create-user').hide();
    $('#btn-create-user-ldap').hide();
    $('.ldap-group').hide();
    $http({
      method: 'POST',
      data: { 'user': user, 'sessuser': sessuser },
      url: '/api/users/readone'
    }).then(function successCallback(response) {
      $scope.user = response.data;
      $('#modal-user-form').modal('show');
    }, function errorCallback(response) {
      notify('Unable to retrieve user.', 'danger');
    });
  };
  $scope.readgroup = function (group, sessuser) {
    $('#btn-update-group').show();
    $('#btn-create-group').hide();
    $('.usersdiv').show();
    $http({
      method: 'POST',
      data: { 'group': group, 'sessuser': sessuser },
      url: '/api/groups/readone'
    }).then(function successCallback(response) {
      $scope.groupusers = [];
      $scope.group = response.data;
      $scope.selectedid = $scope.group.selectedid;
      angular.forEach($scope.group.users, function (value, key) {
        $scope.groupusers.push(value);
      });
      $('#modal-user-form').modal('show');
    }, function errorCallback(response) {
      notify('Unable to retrieve group.', 'danger');
    });
  };
  $scope.addusrgr = function (group, grid, sessuser) {
    var groupuser = $("#groupuserselected").val();
    var groupuserarray = groupuser.split('#');
    $http({
      method: 'POST',
      data: { 'group': group, 'grid': grid, 'sessuser': sessuser, 'utype': groupuserarray[0], 'uid': groupuserarray[1], 'uname': groupuserarray[2] },
      url: '/api/groups/addusr'
    }).then(function successCallback(response) {
      $scope.addexist(groupuserarray[2], $scope.groupusers);
      $("#groupuser").val("");
      notify(response.data, 'success');
    }, function errorCallback(response) {
      notify('Unable to update group.', 'danger');
    });
  };
  $scope.readOnefw = function (id) {
    $('#btn-update-obj').show();
    $('#btn-mqsc-obj').show();
    $('#btn-create-obj').hide();
    $http({
      method: 'POST',
      data: { 'id': id },
      url: '/api/firewall/one'
    }).then(function successCallback(response) {
      $scope.fw = response.data;
      $("#tags").tagsinput('removeAll');
      $("#tags").tagsinput('add', $scope.fw.tags);
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
      url: '/api/readfte/one'
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
  $scope.showCreateFormServ = function () { $("#tags").tagsinput('removeAll'); $scope.clearFormServ(); $('#btn-update-obj').hide(); $('#btn-create-obj').show(); };
  $scope.showCreateFormFw = function () { $("#tags").tagsinput('removeAll'); $scope.clearFormfw(); $('#btn-update-obj').hide(); $('#btn-create-obj').show(); };
  $scope.showCreateFormDns = function () { $("#tags").tagsinput('removeAll'); $scope.clearFormdns(); $('#btn-update-obj').hide(); $('#btn-create-obj').show(); };
  $scope.showCreateUser = function () {
    $("#tags").tagsinput('removeAll');
    $scope.clearFormuser();
    $('#btn-update-user').hide();
    $('#btn-create-user').show();
    $('#btn-create-user-ldap').show();
    $('.ldap-group').show();
  };
  $scope.showCreateGroup = function () {
    $("#tags").tagsinput('removeAll');
    $scope.clearFormgroup();
    $('#btn-update-group').hide();
    $('#btn-create-group').show();
    $('.usersdiv').hide();
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
        url: '/api/add/' + what
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
  $scope.addappsrv = function (user, appcode) {
    if ($("#tags").val()) { $scope.serv.tags = $("#tags").val(); }
    if ($("#server").val()) { $scope.serv.serverdns = $("#server").val(); }
    if ($("#serverip").val()) { $scope.serv.serverip = $("#serverip").val(); }
    if ($("#serverid").val()) { $scope.serv.serverid = $("#serverid").val(); }
    var sslkey = $scope.serv.sslkey;
    if (sslkey) {
      $scope.serv.sslkey = btoa(sslkey).replace(/=/g, "");
    };
    $http({
      method: 'POST',
      data: { 'serv': $scope.serv, 'user': user, 'appcode': appcode },
      url: '/api/addappsrv'
    }).then(function successCallback(response) {
      notify(response.data, 'success');
      $('#modal-obj-form').modal('hide');
      $scope.clearFormServ();
      $scope.getAllAppserv(appcode);
    });
  };
  $scope.createfw = function (projid, user) {
    if ($("#tags").val()) { $scope.fw.tags = $("#tags").val(); }
    $http({
      method: 'POST',
      data: { 'fw': $scope.fw, 'projid': projid, 'user': user },
      url: '/api/firewall/add'
    }).then(function successCallback(response) {
      notify(response.data, 'success');
      $('#modal-obj-form').modal('hide');
      $scope.clearFormfw();
      $scope.getAllfw(projid);
    });
  };
  $scope.createvar = function (user, projid) {
    if ($("#tags").val()) { $scope.mq.tags = $("#tags").val(); }
    $http({
      method: 'POST',
      data: { 'mq': $scope.mq, 'user': user, 'projid': projid },
      url: '/api/addvar'
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
      url: '/api/updatevar'
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
      url: '/api/delvar'
    }).then(function successCallback(response) {
      notify(response.data, 'success');
      $('#modal-obj-form').modal('hide');
      $scope.clearForm();
      $scope.getAll('vars', '', 'mqenv');
    });
  };
  $scope.createfte = function (projid, user, type, bstep) {
    if ($("#tags").val()) { $scope.mqfte.tags = $("#tags").val(); }
    $http({
      method: 'POST',
      data: { 'mqfte': $scope.mqfte, 'user': user, 'projid': projid, 'type': type, 'bstep': bstep },
      url: '/api/addfte/'
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
      url: '/api/addflow'
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
      url: '/api/mqsc/one/' + projid
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
      url: '/api/dlqh/' + projid
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
      url: '/api/fte/one/' + projid
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
      url: '/api/auth/one/' + projid
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
      url: '/api/readflows'
    }).then(function successCallback(response) {
      $scope.contentLoaded = true;
      if (response.data != "null") { $scope.names = response.data; } else { $scope.names = []; }
    });
  };
  $scope.getAllapps = function () {
    if ($('#contloaded')[0]) { angular.element(document.querySelector('#contloaded')).removeClass('hide'); }
    $http({
      method: 'POST',
      data: {},
      url: '/api/applications'
    }).then(function successCallback(response) {
      $scope.contentLoaded = true;
      if (response.data != "null") { $scope.names = response.data; } else { $scope.names = []; }
    });
  };
  $scope.deleteapp = function (id, appcode, user, redir = false) {
    Swal.fire({
      title: 'Delete this application?',
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
          data: { 'id': id, 'user': user, 'appcode': appcode },
          url: '/api/applications/delete'
        }).then(function successCallback(response) {
          notify(response.data, 'success');
          if (redir) {
            $window.location.href = "/env/apps";
          } else {
            $scope.getAllapps();
          }
        });
      }
    })
  };
  $scope.getflow = function (flowid, type) {
    if ($('#contloaded')[0]) { angular.element(document.querySelector('#contloaded')).removeClass('hide'); }
    $http({
      method: 'POST',
      data: { 'flowid': flowid, 'type': type },
      url: '/api/readflow'
    }).then(function successCallback(response) {
      $scope.contentLoaded = true;
      if (response.data != "null") { $scope.names = response.data; } else { $scope.names = []; }
    });
  };
  $scope.delusr = function (user, sessuser) {
    Swal.fire({
      title: 'Delete this user?',
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
          data: { 'user': user },
          url: '/api/users/del'
        }).then(function successCallback(response) {
          notify(response.data, 'success');
          $scope.getAllusers(sessuser);
        });
      }
    })
  };
  $scope.delgroup = function (grid, sessuser) {
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
          data: { 'groupid': grid, 'sessuser': sessuser },
          url: '/api/groups/del'
        }).then(function successCallback(response) {
          notify(response.data, 'success');
          $scope.getAllgroups();
        });
      }
    })
  };
  $scope.delusrgr = function (grid, uid, sessuser) {
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
          data: { 'groupid': grid, 'userid': uid, 'sessuser': sessuser },
          url: '/api/groups/delusr'
        }).then(function successCallback(response) {
          $(".usr_" + grid + uid).hide();
          notify(response.data, 'success');
          //        $scope.getAllgroups();
        });
      }
    })
  };
  $scope.createuser = function (user) {
    $http({
      method: 'POST',
      data: { 'users': $scope.user, 'user': user },
      url: '/api/users/add'
    }).then(function successCallback(response) {
      notify(response.data, 'success');
      $('#modal-user-form').modal('hide');
      $scope.clearFormuser();
      $scope.getAllusers(user);
    });
  };
  $scope.creategroup = function (user) {
    var modules = $("#selectedmodules").val();
    $http({
      method: 'POST',
      data: { 'group': $scope.group, 'user': user, 'modules': modules },
      url: '/api/groups/add'
    }).then(function successCallback(response) {
      notify(response.data, 'success');
      $('#modal-user-form').modal('hide');
      $scope.clearFormgroup();
      $scope.getAllgroups();
    });
  };
  $scope.createuserldap = function (user) {
    $http({
      method: 'POST',
      data: { 'users': $scope.user, 'user': user },
      url: '/api/users/addldap'
    }).then(function successCallback(response) {
      notify(response.data, 'success');
      $('#modal-user-form').modal('hide');
      $scope.clearFormuser();
      $scope.getAllusers(user);
    });
  };
  $scope.getAllpack = function (proj) {
    if ($('#contloaded')[0]) { angular.element(document.querySelector('#contloaded')).removeClass('hide'); }
    $http({
      method: 'POST',
      data: { 'proj': proj },
      url: '/api/readpack'
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
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
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
      url: '/api/readimp'
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
      url: '/api/readdepl'
    }).then(function successCallback(response) {
      $scope.contentLoaded = true;
      if (response.data != "null") { $scope.names = response.data; } else { $scope.names = []; }
    });
  };
  $scope.getAllDes = function (destype) {
    if ($('#contloaded')[0]) { angular.element(document.querySelector('#contloaded')).removeClass('hide'); }
    $http({
      method: 'POST',
      data: { 'destype': destype },
      url: '/drawapi/readdes'
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
  $scope.clearFormapp = function () { $scope.app = {}; $('.autocomplsrv').val(); $('#server').val(); $('#serverip').val(); $('#serverid').val(); };
  $scope.clearFormServ = function () { $scope.serv = {}; };
  $scope.clearFormfw = function () { $scope.fw = {}; };
  $scope.clearFormdns = function () { $scope.dns = {}; };
  $scope.clearFormpack = function () { $scope.package = {}; };
  $scope.clearFormuser = function () { $scope.user = {}; };
  $scope.clearFormgroup = function () { $scope.group = {}; };
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
          data: { 'projid': thisapp, 'user': user, 'id': thisid, 'objname': thisname },
          url: '/tibapi/deltibacl'
        }).then(function successCallback(response) {
          notify(response.data, 'success');
          $scope.getAllTibacl(thisapp);
        });
      }
    })
  };
  $scope.getAlldns = function (appcode) {
    if ($('#contloaded')[0]) { angular.element(document.querySelector('#contloaded')).removeClass('hide'); }
    $http({
      method: 'POST',
      data: { 'appcode': appcode },
      url: '/api/dns/all'
    }).then(function successCallback(response) {
      $scope.contentLoaded = true;
      if (response.data != "null") { $scope.names = response.data; } else { $scope.names = {}; }
    });
  };
  $scope.deletedns = function (id, user, dns, projid) {
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
          data: { 'id': id, 'user': user, 'dns': dns, 'projid': projid },
          url: '/api/dns/delete'
        }).then(function successCallback(response) {
          notify(response.data, 'success');
          $scope.getAlldns(projid);
        });
      }
    })
  };
  $scope.readOnedns = function (id) {
    $('#btn-update-obj').show();
    $('#btn-mqsc-obj').show();
    $('#btn-create-obj').hide();
    $http({
      method: 'POST',
      data: { 'id': id },
      url: '/api/dns/one'
    }).then(function successCallback(response) {
      $scope.dns = response.data;
      $("#tags").tagsinput('removeAll');
      $("#tags").tagsinput('add', $scope.dns.tags);
      $('#modal-obj-form').modal('show');
    }, function errorCallback(response) {
      notify('Unable to retrieve record.', 'danger');
    });
  };
  $scope.createdns = function (projid, user) {
    if ($("#tags").val()) { $scope.dns.tags = $("#tags").val(); }
    if ($("#server").val()) { $scope.dns.dnsserv = $("#server").val(); }
    if ($("#serverid").val()) { $scope.dns.dnsservid = $("#serverid").val(); }
    $http({
      method: 'POST',
      data: { 'dns': $scope.dns, 'projid': projid, 'user': user },
      url: '/api/dns/add'
    }).then(function successCallback(response) {
      notify(response.data, 'success');
      $('#modal-obj-form').modal('hide');
      $scope.clearFormdns();
      $scope.getAlldns(projid);
    });
  };
  $scope.updatedns = function (projid, user) {
    if ($("#tags").val()) { $scope.dns.tags = $("#tags").val(); }
    if ($("#server").val()) { $scope.dns.dnsserv = $("#server").val(); }
    if ($("#serverid").val()) { $scope.dns.dnsservid = $("#serverid").val(); }
    $http({
      method: 'POST',
      data: { 'dns': $scope.dns, 'user': user, 'projid': projid },
      url: '/api/dns/update'
    }).then(function successCallback(response) {
      notify(response.data, 'success');
      $('#modal-obj-form').modal('hide');
      $scope.clearFormdns();
      $scope.getAlldns(projid);
    });
  };
  $scope.getAllsrv = function (grid) {
    if ($('#contloaded')[0]) { angular.element(document.querySelector('#contloaded')).removeClass('hide'); }
    $http({
      method: 'POST',
      data: { 'grid': grid },
      url: '/api/readsrv'
    }).then(function successCallback(response) {
      $scope.contentLoaded = true;
      if (response.data != "null") { $scope.names = response.data; } else { $scope.names = {}; }
    });
  };
  $scope.readoneSrv = function (server) {
    $('#btn-update-obj').show();
    $http({
      method: 'POST',
      data: { 'server': server },
      url: '/api/readsrv/one'
    }).then(function successCallback(response) {
      $scope.serv = response.data;
      $("#tags").tagsinput('removeAll');
      $("#pluid").val('');
      $(".placeauto").val('');
      $("#tags").tagsinput('add', $scope.serv.tags);
      $('#modal-obj-form').modal('show');
    }, function errorCallback(response) {
      notify('Unable to retrieve record.', 'danger');
    });
  };
  $scope.updateserv = function (user, grid, projid) {
    if ($("#tags").val()) { $scope.serv.tags = $("#tags").val(); }
    if ($("#pluid").val()) { $scope.serv.place = $("#pluid").val(); }
    $http({
      method: 'POST',
      data: { 'serv': $scope.serv, 'user': user, 'projid': projid },
      url: '/api/updsrv'
    }).then(function successCallback(response) {
      notify(response.data, 'success');
      $('#modal-obj-form').modal('hide');
      $scope.getAllsrv(grid);
    });
  };
  $scope.duplappsrv = function (srvid, user, appid) {
    $http({
      method: 'POST',
      url: '/pubapi/listallapp'
    }).then(function successCallback(response) {
      var arrresp = response.data;
      var options = [];
      $.map(arrresp, function (o) { options[o.nameid] = o.name; });
      Swal.mixin({
        title: 'Copy this object',
        //  icon: 'question',
        input: 'select',
        inputOptions: options,
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Copy',
        customClass: {
          confirmButton: 'btn btn-primary btn-sm',
          cancelButton: 'btn btn-danger btn-sm',
        }
      }).queue([
        {
          title: 'Please select Application',
          text: 'you can copy the application server parameters to another Application'
        }
      ]).then((result) => {
        if (result.value) {
          $http({
            method: 'POST',
            data: { 'qmid': srvid, 'appid': appid, 'newappid': result.value[0], 'user': user },
            url: '/api/duplicate/appsrv'
          }).then(function successCallback(response) {
            notify(response.data, 'success');
            $scope.getAllAppserv(appid);
          });
        }
      })
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
      //   if (thistype == 'importpj') {
      //    $scope.getAllproj();
      //  }
    });
    // return false;        
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
  $scope.getAllplaces = function (proj) {
    if ($('#contloaded')[0]) { angular.element(document.querySelector('#contloaded')).removeClass('hide'); }
    $http({
      method: 'POST',
      data: { 'proj': proj },
      url: '/api/readplaces'
    }).then(function successCallback(response) {
      $scope.contentLoaded = true;
      if (response.data != "null") { $scope.names = response.data; } else { $scope.names = []; }
    });
  };
  $scope.delplace = function (id, user, thisname, projid) {
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
          data: { 'id': id, 'user': user, 'name': thisname, 'projid': projid },
          url: '/api/readplaces/delete'
        }).then(function successCallback(response) {
          notify(response.data, 'success');
          $scope.getAllplaces('');
        });
      }
    })
  };
  $scope.readappgr = function (appcode, sessuser) {
    $http({
      method: 'POST',
      data: { 'appcode': appcode, 'sessuser': sessuser },
      url: '/api/appgroups/readone'
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
  $scope.addappgr = function (appcode, sessuser) {
    if (!$("#respusersselected").val()) {
      notify("Please write a correct user", "danger");
      return;
    }
    var groupuser = $("#respusersselected").val();
    var groupuserarray = groupuser.split('#');
    $http({
      method: 'POST',
      data: { 'appcode': appcode, 'sessuser': sessuser, 'utype': groupuserarray[0], 'uid': groupuserarray[1], 'uname': groupuserarray[2], 'uemail': groupuserarray[3], 'utitle': groupuserarray[4], 'avatar': groupuserarray[5] },
      url: '/api/appgroups/addusr'
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
  $scope.delappgr = function (appcode, uid, utype, sessuser) {
    Swal.fire({
      title: 'Delete this user?',
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
          data: { 'appcode': appcode, 'userid': uid, 'utype': utype, 'sessuser': sessuser },
          url: '/api/appgroups/delusr'
        }).then(function successCallback(response) {
          $(".usr_" + uid).hide();
          notify(response.data, 'success');
        });
      }
    })
  };
  $scope.addrecent = function (thislink, thisid, thisname) {
    $http({
      method: 'POST',
      data: { 'link': thislink, 'appid': thisid, 'appname': thisname },
      url: '/api/users/recent'
    }).then(function successCallback(response) {
      $window.location.href = "/env/apps/" + thisid;
    });
  };
  $scope.getQMlist = function (thisapp, thispkg, thisid) {
    $(".mql" + thisid).html('<i class="mdi mdi-loading iconspin"></i>');
    $(".mql" + thisid).prop("disabled", true);
    $(".mql" + thisid).removeClass('btn-light').addClass('btn-secondary');
    $http({
      method: 'POST',
      data: { 'appl': thisapp, 'pkg': thispkg },
      url: '/api/applications/qmlist'
    }).then(function successCallback(response) {
      $scope.pkgid = thispkg;
      $(".mql" + thisid).html('<svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-show" xlink:href="/assets/images/icon/midleoicons.svg#i-show"/></svg>');
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
});
