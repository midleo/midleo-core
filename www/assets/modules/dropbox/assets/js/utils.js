(function (window) {
  window.utils = {
    parseQueryString(str) {
      const ret = Object.create(null);
      if (typeof str !== 'string') {
        return ret;
      }
      str = str.trim().replace(/^(\?|#|&)/, '');
      if (!str) {
        return ret;
      }
      str.split('&').forEach((param) => {
        const parts = param.replace(/\+/g, ' ').split('=');
        let key = parts.shift();
        let val = parts.length > 0 ? parts.join('=') : undefined;
        key = decodeURIComponent(key);
        val = val === undefined ? null : decodeURIComponent(val);
        if (ret[key] === undefined) {
          ret[key] = val;
        } else if (Array.isArray(ret[key])) {
          ret[key].push(val);
        } else {
          ret[key] = [ret[key], val];
        }
      });
      return ret;
    },
  };
}(window));
//midleo config
function saveToCookie(obj) {
  var expiration = new Date();
  expiration.setTime(expiration.getTime() + 3600 * 5000);
  var data = JSON.stringify(obj);
  var cookie = "dbexpl=" + data + "; path=/; expires=" + expiration.toUTCString();
  if (document.location.protocol.toLowerCase() == "https") {
    cookie = cookie + ";secure";
  }
  document.cookie = cookie;
}
function loadFromCookie() {
  var cookies = document.cookie;
  var name = "dbexpl=";
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
function updateBreadcrumb(decodedPath,thistype) {
  var path = decodedPath || '';
  $('#od-breadcrumb').empty();
  var runningPath = '';
  var segments = path.split('/');
  for (var i = 0; i < segments.length; i++) {
    var segment = segments[i];
    if (segment) {
      runningPath = runningPath + '/' + encodeURIComponent(segment);
    } else {
      segment = 'Files';
    }
    let segmlink="<a href='#' onclick='showItems(\"" + runningPath + "\")' class='breadcrumb-item'>" + segment + "</a>";
    $( segmlink ).appendTo( $( "#od-breadcrumb" ) );
  }
}
function getItems(thisitem,thisid) {
  dbx.filesDownload({
          path: thisitem
      })
      .then(function(response) {
          var downloadUrl = URL.createObjectURL(response.result.fileBlob);
          var downloadButton = document.getElementById('downloaddb'+thisid);
          downloadButton.setAttribute('href', downloadUrl);
         // downloadButton.setAttribute('download', response.result.name);
          document.getElementById('downloaddb'+thisid).style.display = 'block';
      })
      .catch(function(error) {
          console.error(error);
      });
  dbx.sharingGetFileMetadata({
      file: thisitem
      })
      .then(function(response) {
          if(response.result.preview_url){
              $("#file_link").val(response.result.preview_url);
              $("#addtagdb"+thisid).show();
              alert("#addtagdb"+thisid);
          }
      })
      .catch(function(error) {
          console.error(error);
      });  
  return false;
}
function showPageSection(elementClass) {
  $("." + elementClass).show();
}
function showItems(thisdir) {
  $('#data-table').DataTable().clear().draw();
  updateBreadcrumb(thisdir);
  dbx.filesListFolder({
          path: thisdir
      })
      .then(function(response) {
          renderItems(response.result.entries);
      })
      .catch(function(error) {
          console.error(error);
      });
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
    url: "/dropboxapi/updtag",
    data: 'thisuser=' + thisuser + '&file_id=' + file_id + '&tags=' + tags + '&reqname=' + reqname + '&appname=' + appname + '&serverlist=' + serverlist + '&appserverlist=' + appserverlist + '&file_type=' + file_type + '&file_name=' + file_name + '&file_link=' + file_link + '&file_size=' + file_size,
    cache: false,
    success: function (html) {
      $("#addmap").modal('hide');
      notify("Mapping added", "success");
    }
  });
}