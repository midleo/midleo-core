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
  };
  $scope.delappsrv = function (server, user, appcode) {
    Swal.fire({
      title: 'Delete this Server?',
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
  $scope.delusr = function (user, sessuser) {
    Swal.fire({
      title: 'Delete this user?',
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
  $scope.clearFormapp = function () { $scope.app = {}; $('.autocomplsrv').val(); $('#server').val(); $('#serverip').val(); $('#serverid').val(); };
  $scope.clearFormServ = function () { $scope.serv = {}; };
  $scope.clearFormfw = function () { $scope.fw = {}; };
  $scope.clearFormdns = function () { $scope.dns = {}; };
  $scope.clearFormuser = function () { $scope.user = {}; };
  $scope.clearFormgroup = function () { $scope.group = {}; };
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
  $scope.getAllplaces = function () {
    if ($('#contloaded')[0]) { angular.element(document.querySelector('#contloaded')).removeClass('hide'); }
    $http({
      method: 'POST',
      data: { },
      url: '/api/readplaces'
    }).then(function successCallback(response) {
      $scope.contentLoaded = true;
      if (response.data != "null") { $scope.names = response.data; } else { $scope.names = []; }
    });
  };
  $scope.getAllreleses = function () {
    if ($('#contloaded')[0]) { angular.element(document.querySelector('#contloaded')).removeClass('hide'); }
    $http({
      method: 'POST',
      data: { },
      url: '/api/readreleases'
    }).then(function successCallback(response) {
      $scope.contentLoaded = true;
      if (response.data != "null") { $scope.names = response.data; } else { $scope.names = []; }
    });
  };
  $scope.delplace = function (id, user, thisname) {
    Swal.fire({
      title: 'Delete this object?',
      icon: 'error',
      showCancelButton: true,
      confirmButtonText: 'Delete',
      customClass: {
        confirmButton: 'btn btn-danger btn-sm',
        cancelButton: 'btn btn-light btn-sm',
      }
    }).then((result) => {
      if (result.value) {
        $http({
          method: 'POST',
          data: { 'id': id, 'user': user, 'name': thisname },
          url: '/api/readplaces/delete'
        }).then(function successCallback(response) {
          notify(response.data, 'success');
          $scope.getAllplaces('');
        });
      }
    })
  };
  $scope.delrelease = function (id, user, thisname) {
    Swal.fire({
      title: 'Delete this object?',
      icon: 'error',
      showCancelButton: true,
      confirmButtonText: 'Delete',
      customClass: {
        confirmButton: 'btn btn-danger btn-sm',
        cancelButton: 'btn btn-light btn-sm',
      }
    }).then((result) => {
      if (result.value) {
        $http({
          method: 'POST',
          data: { 'id': id, 'user': user, 'name': thisname },
          url: '/api/readreleases/delete'
        }).then(function successCallback(response) {
          notify(response.data, 'success');
          $scope.getAllreleses('');
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
  };
});
