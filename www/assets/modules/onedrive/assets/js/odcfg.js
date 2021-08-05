function signInToOneDrive() {
  $.getJSON("/pubapi/getodcfg", function (result) {
    if (result.error) {
      notify(result.errorlog, "danger");
    } else {
      var appInfo = {
        "clientId": result.appid,
        "redirectUri": result.redirecturi,
        "scopes": "user.read files.read files.read.all",
        "authServiceUri": result.endpoint
      };
      provideAppInfo(appInfo);
      var baseUrl = getQueryVariable("baseUrl")
      msGraphApiRoot = (baseUrl) ? baseUrl : "https://graph.microsoft.com/v1.0/me";
      challengeForAuth();
      saveToCookie({ "apiRoot": msGraphApiRoot, "signedin": true });
      return false;
    }
  });
}

function showCustomLoginButton(show) {
  $(".od-login").css("display", show ? "inline-table" : "none");
  $(".od-logoff").css("display", show ? "none" : "inline-table");
  $(".od-logoff-flex").css("display", show ? "none" : "flex");
  $(".od-login-flex").css("display", show ? "flex" : "none");
}
function getUrlParts(url) {
  var a = document.createElement("a");
  a.href = url;
  return { "hostname": a.hostname, "path": a.pathname }
}
function setOneDriveTitle(title) {
  var element = document.getElementById("od-site");
  element.innerText = title;
}
function saveToCookie(obj) {
  var expiration = new Date();
  expiration.setTime(expiration.getTime() + 3600 * 1000);
  var data = JSON.stringify(obj);
  var cookie = "odexplorer=" + data + "; path=/; expires=" + expiration.toUTCString();

  if (document.location.protocol.toLowerCase() == "https") {
    cookie = cookie + ";secure";
  }
  document.cookie = cookie;
}
function loadFromCookie() {
  var cookies = document.cookie;
  var name = "odexplorer=";
  var start = cookies.indexOf(name);
  if (start >= 0) {
    start += name.length;
    var end = cookies.indexOf(';', start);
    if (end < 0) {
      end = cookies.length;
    }
    else {
      postCookie = cookies.substring(end);
    }
    var value = cookies.substring(start, end);
    return JSON.parse(value);
  }
  return "";
}
function signOut() {
  logoutOfAuth();
  saveToCookie({ "apiRoot": msGraphApiRoot, "signedin": false });
  $('#od-breadcrumb').empty();
  $('#od-items').empty();
  $('#od-json').empty();
  location.reload();
}
function getQueryVariable(variable) {
  var query = window.location.search.substring(1);
  var vars = query.split("&");
  for (var i = 0; i < vars.length; i++) {
    var pair = vars[i].split("=");
    if (pair[0] == variable) { return pair[1]; }
  }
  return (false);
}
function updateBreadcrumb(decodedPath) {
  var path = decodedPath || '';
  $('#od-breadcrumb').empty();
  var runningPath = '';
  var segments = path.split('/');
  for (var i = 0; i < segments.length; i++) {
    //  if (i > 0) {
    //   $('<span>').text(' > ').appendTo("#od-breadcrumb");
    //  }

    var segment = segments[i];
    if (segment) {
      runningPath = runningPath + '/' + encodeURIComponent(segment);
    } else {
      segment = 'Files';
    }

    $('<a>').
      addClass("breadcrumb-item").
      attr("href", "#" + runningPath).
      click(function () {
        loadedForHash = $(this).attr('href');
        window.location = loadedForHash;
        odauth(true);
      }).
      text(segment).
      appendTo("#od-breadcrumb");
  }
}
function updtag(thisuser) {
  let file_id = $("#file_id").val();
  let tags = $("#tags").val();
  let reqname = $("#reqname").val();
  let appname = $("#appname").val();
  let serverlist = $("#serverlist").val();
  let appserverlist = $("#appserverlist").val();
  let file_type = $("#file_type").val();
  let file_name = $("#file_name").val();
  let file_link = $("#file_link").val();
  let file_size = $("#file_size").val();
  $.ajax({
    type: "POST",
    url: "/odapi/updtag",
    data: 'thisuser=' + thisuser + '&file_id=' + file_id + '&tags=' + tags + '&reqname=' + reqname + '&appname=' + appname + '&serverlist=' + serverlist + '&appserverlist=' + appserverlist + '&file_type=' + file_type + '&file_name=' + file_name + '&file_link=' + file_link + '&file_size=' + file_size,
    cache: false,
    success: function (html) {
      $("#addmap").modal('hide');
      notify("Mapping added", "success");
    }
  });
}