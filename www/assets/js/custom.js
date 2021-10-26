var toggleSwitch = document.querySelector('.theme-switch input[type="checkbox"]');
var currentTheme = localStorage.getItem('theme');
let logoimg = $(".ml");
let logoimgicon = $(".mli");
var notice = "";
if (currentTheme) {
  $('body').attr('data-color', currentTheme);
  if (currentTheme === 'dark') {
    if (toggleSwitch) {
      toggleSwitch.checked = true;
    }
  } 
} else {
  $('body').attr('data-color', "light");
  localStorage.setItem('theme', 'light');
}
$(function () {
  "use strict";
  var set = function () {
    var width = (window.innerWidth > 0) ? window.innerWidth : $(window).width();
    var topOffset = 70;
    if (width < 1170) {
      $("body").addClass("mini-sidebar");
      $('.navbar-brand .light-logo-icon').show();
      $('.navbar-brand .light-logo').hide();
      $(".scroll-sidebar, .slimScrollDiv").css("overflow-x", "visible").parent().css("overflow", "visible");
      $(".sidebartoggler i").addClass("ti-menu");
      $(".sidebartoggler").html('<i class="mdi mdi-chevron-triple-right mdi-18px"></i>');
    }
    else {
      $("body").removeClass("mini-sidebar");
      $('.navbar-brand .light-logo-icon').hide();
      $('.navbar-brand .light-logo').show();
      $(".sidebartoggler").html('<i class="mdi mdi-chevron-triple-left mdi-18px"></i>');
      //$(".sidebartoggler i").removeClass("ti-menu");
    }

    var height = ((window.innerHeight > 0) ? window.innerHeight : $(window).height()) - 1;
    height = height - topOffset;
    if (height < 1) height = 1;
    if (height > topOffset) {
      $(".page-wrapper").css("min-height", (height) + "px");
    }
  };
  $(window).ready(set);
  $(window).on("resize", set);
  $(".sidebartoggler").on('click', function () {
    if ($("body").hasClass("mini-sidebar")) {
      $("body").trigger("resize");
      $(".scroll-sidebar, .slimScrollDiv").css("overflow", "hidden").parent().css("overflow-y", "auto");
      $("body").removeClass("mini-sidebar");
      $('.navbar-brand .light-logo-icon').hide();
      $('.navbar-brand .light-logo').show();
      $(".sidebartoggler").html('<i class="mdi mdi-chevron-triple-left mdi-18px"></i>');

    }
    else {
      $("body").trigger("resize");
      $(".scroll-sidebar, .slimScrollDiv").css("overflow-x", "visible").parent().css("overflow", "visible");
      $("body").addClass("mini-sidebar");
      $('.navbar-brand .light-logo-icon').show();
      $('.navbar-brand .light-logo').hide();
      $(".sidebartoggler").html('<i class="mdi mdi-chevron-triple-right mdi-18px"></i>');
    }
  });
  $(".fix-header .topbar").stick_in_parent({});
  $(".nav-toggler").click(function () {
    $("body").toggleClass("show-sidebar");
    $(".nav-toggler i").toggleClass("ti-menu");
    $(".nav-toggler i").addClass("ti-close");
  });
  $(".search-box a, .search-box .app-search .srh-btn").on('click', function () {
    $(".app-search").toggle(200);
  });
  /*$(function () {
    var url = window.location;
    var element = $('ul#sidebarnav a').filter(function () {
      return this.href == url;
    }).addClass('active').parent().addClass('active');
    while (true) {
      if (element.is('li')) {
        element = element.parent().addClass('in').parent().addClass('active');
      }
      else {
        break;
      }
    }

  });*/
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  });
  //  $(function () {
  //      $('#sidebarnav').metisMenu();
  //  });
  $("body").trigger("resize");
  //  $(".list-task li label").click(function () {
  //      $(this).toggleClass("task-done");
  //  });
  $('#to-recover').on("click", function () {
    $("#loginform").hide();
    $("#recoverform").show();
  });
  $('a[data-action="collapse"]').on('click', function (e) {
    e.preventDefault();
    $(this).closest('.card').find('[data-action="collapse"] i').toggleClass('mdi-minus mdi-plus');
    $(this).closest('.card').children('.card-body').collapse('toggle');

  });
  $('a[data-action="expand"]').on('click', function (e) {
    e.preventDefault();
    $(this).closest('.card').find('[data-action="expand"] i').toggleClass('mdi-arrow-expand mdi-arrow-collapse');
    $(this).closest('.card').toggleClass('card-fullscreen');
  });
  $('a[data-action="close"]').on('click', function () {
    $(this).closest('.card').removeClass().slideUp('fast');
  });

});

function switchTheme(e) {
  if (e.target.checked) {
    $("body").data("color", "dark");
    $('body').attr('data-color', "dark");
    localStorage.setItem('theme', 'dark');
    $(".slidersw").html('<i class="mdi mdi-weather-sunny mdi-24px"></i>');
  }
  else {
    $("body").data("color", "light");
    $('body').attr('data-color', "light");
    localStorage.setItem('theme', 'light');
    $(".slidersw").html('<i class="mdi mdi-weather-night mdi-24px"></i>');
  }
}
if (toggleSwitch) {
  toggleSwitch.addEventListener('change', switchTheme, false);
}
$(document).ready(function () {
  if ($('.select2')[0]) {
    $('.select2').select2();
  }
  if ($('h2')[0]) {
    var showdiv = false;
    $('h2').each(function () {
      if ($(this).attr("id")) {
        showdiv = true;
        $(".h2-menu").append("<a class='waves-effect waves-light list-group-item list-group-item-light list-group-item-action' href='#" + $(this).attr("id") + "'>" + $(this).text() + "</a>");
      }
    });
    if (showdiv == true) {
      $(".h2menu").show();
    }
  }
  if ($('#reqidbut')[0]) {
    var reqid = $("#reqid").val();
    $('#reqidbut').click(function () {
      $("#modal-hist .modal-body").load("/wfview/" + reqid + "/?");
      $('#modal-hist').modal('show');
    });
  }
  if ($('.date-time-picker')[0]) {
    $('.date-time-picker').datetimepicker();
  }
  if ($('.date-time-picker-cal')[0]) {
    $('.date-time-picker-cal').datetimepicker({
      format: 'YYYY-MM-DD HH:mm',
      //   minDate: moment(),
      keepOpen: false
    });
  }
  if ($('.date-time-picker-depl')[0]) {
    $('.date-time-picker-depl').datetimepicker({
      format: 'YYYY-MM-DD HH:mm',
      minDate: moment(),
      disabledTimeIntervals: [[moment({ h: 8 }), moment({ h: 18 })]],
      enabledHours: [0, 1, 2, 3, 4, 5, 6, 7, 19, 20, 21, 22, 23, 24],
      stepping: 15,
      keepOpen: false
    });
  }
  if ($('.floating-labels')[0]) {
    $('.floating-labels .form-control').on('focus blur', function (e) {
      $(this).parents('.form-group').toggleClass('focused', (e.type === 'focus' || this.value.length > 0));
    }).trigger('blur');
  }
  if ($('.time-picker')[0]) {
    $('.time-picker').datetimepicker({
      format: 'HH:mm'
    });
  }
  if ($('.time-picker-cal')[0]) {
    $('.time-picker-cal').datetimepicker({
      format: 'HH:mm',
      useCurrent: false,
      viewMode: 'times',
      disabledTimeIntervals: [[moment({ h: 9 }), moment({ h: 24 })]],
      keepOpen: false
    });
  }
  if ($('.date-picker')[0]) {
    $('.date-picker').datetimepicker({
      format: 'YYYY-MM-DD',
      minDate: moment().add(7, 'days'),
      sideBySide: true,
      widgetPositioning: {
        horizontal: 'left',
        vertical: 'top'
      }
    });
  }
  if ($('.year-picker')[0]) {
    $('.year-picker').datetimepicker({
      format: 'YYYY',
      sideBySide: true,
      minDate: moment(),
      widgetPositioning: {
        horizontal: 'left',
        vertical: 'top'
      }
    });
  }
  if ($('.date-picker-unl')[0]) {
    $('.date-picker-unl').datetimepicker({
      format: 'YYYY-MM-DD',
      sideBySide: true,
      widgetPositioning: {
        horizontal: 'left',
        vertical: 'top'
      }
    });
  }
  if ($('.stickyside')[0]) {
    $(".stickyside").stick_in_parent({
      offset_top: 80
    });
    $('.stickyside a').click(function () {
      $('html, body').animate({
        scrollTop: $($(this).attr('href')).offset().top - 80
      }, 500);
      return false;
    });
    var lastId,
      topMenu = $(".stickyside"),
      topMenuHeight = topMenu.outerHeight(),
      menuItems = topMenu.find("a"),
      scrollItems = menuItems.map(function () {
        var item = $($(this).attr("href"));
        if (item.length) {
          return item;
        }
      });
    $(window).scroll(function () {
      var fromTop = $(this).scrollTop() + topMenuHeight - 250;
      var cur = scrollItems.map(function () {
        if ($(this).offset().top < fromTop)
          return this;
      });
      cur = cur[cur.length - 1];
      var id = cur && cur.length ? cur[0].id : "";
      if (lastId !== id) {
        lastId = id;
        menuItems
          .removeClass("active")
          .filter("[href='#" + id + "']").addClass("active");
      }
    });
  }
});
function notify(message, type) {
  notice = $.notify({ message: message }, {
    type: type,
    allow_dismiss: true,
    label: 'Cancel',
    className: 'btn-sm btn-light',
    newest_on_top: true,
    placement: { from: 'top', align: 'right' },
    delay: 300,
    animate: { enter: 'animated fadeIn', exit: 'animated fadeOut' }
  });
};
function nalert(message, type) {
  Swal.fire({
    title: '',
    html: message,
    icon: type,
    showConfirmButton: true,
    animation: true
  });
};
function getFile(thisdiv) { $("#" + thisdiv).click(); };
function sub(obj, thisdiv) { var file = obj.value; var ul = document.getElementById("fileList"); while (ul.hasChildNodes()) { ul.removeChild(ul.firstChild); } for (var x = 0; x < obj.files.length; x++) { var li = document.createElement('li'); li.innerHTML = obj.files[x].name; ul.appendChild(li); } var fileName = file.split("\\"); $("#" + thisdiv).html(fileName[fileName.length - 1]); $(".uplbut").show(); };
$(document).ready(function () {
  $('body').on('click', '.hc-trigger', function () {
    $(this).parent().toggleClass('toggled');
  });
  $(".main-menu li:not(.sub-menu):not(.openthepop) a,.logo a,.smm-alerts a,.llink").click(function () {
    $('.loader').show();
  });
  $('body').on('click', '.sub-menu > a', function (e) {
    e.preventDefault();
    $(this).next().slideToggle(200);
    $(this).parent().toggleClass('toggled');
  });
  $('body').on('click', '.hc-item', function () {
    var v = $(this).data('ma-header-value');

    $('.hc-item').removeClass('selected');
    $(this).addClass('selected');
    $('body').attr('data-ma-header', v);
  });
  if ($('.image-editor')[0]) {
    $('.image-editor').cropit({ exportZoom: 1.5, allowDragNDrop: false });
    $('form').submit(function () {
      var imageData = $('.image-editor').cropit('export', { type: 'image/jpeg', quality: 1, originalSize: false });
      $('.ufile').val(imageData);
      return true;
    });
    $('.prevbutton').click(function () {
      var imageData = $('.image-editor').cropit('export');
      window.open(imageData);
      return false;
    });
  }
  $('.form-control').on('change', function () {
    if ($(this).val()) {
      $(this).parent().removeClass("has-danger");
      $(this).removeClass("form-control-danger");
      $(this).parent().addClass("has-success");
      $(this).addClass("form-control-success");
    } else {
      $(this).parent().removeClass("has-success");
      $(this).removeClass("form-control-success");
      $(this).parent().addClass("has-danger");
      $(this).addClass("form-control-danger");
    }
  });
});
function delldap(srv, id) {
  $.ajax({
    type: "POST",
    url: "/api/delldap",
    data: 'ldapsrv=' + srv + '&srvid=' + id,
    cache: false,
    success: function (html) {
      $("#" + id).hide();
      notify("Ldap " + srv + " was deleted", "success");
    }
  });
}
if ($('.nestable-menu')[0]) {
  $('.nestable-menu').on('click', function (e) {
    var target = $(e.target),
      action = target.data('action');
    if (action === 'expand-all') {
      $('.dd').nestable('expandAll');
    }
    if (action === 'collapse-all') {
      $('.dd').nestable('collapseAll');
    }
  });
}
function ShowHide(hidethis, showthis) { $("." + hidethis).hide(); $("." + showthis).show(); }
if ($('.chkbb')[0]) {
  var all_checkbox_divs = $('.chkbb');
  for (var i = 0; i < all_checkbox_divs.length; i++) {
    all_checkbox_divs[i].onclick = function (e) {
      var div_id = this.id; var checkbox_id = div_id.split("_")[0]; var checkbox_element = $("#" + checkbox_id)[0];
      if (checkbox_element.checked == true) {
        checkbox_element.checked = false; $("#" + div_id).attr('class', 'chkbb btn btn-sm btn-light waves-effect');
      }
      else { checkbox_element.checked = true; $("#" + div_id).attr('class', 'chkbb btn btn-sm btn-primary waves-effect'); }
    };
  }
}
if ($('#nestable1')[0]) {
  $('#nestable1,#nestable2').nestable({ group: 1, maxDepth: 3 });
}
function delmodule(thisid) {
  $.ajax({
    type: "POST",
    url: "/api/modules/delete",
    data: 'thisid=' + thisid,
    cache: false,
    dataType: "json",
    success: function (data) {
      notify(data.text, "success");
    }
  });
}
function cpclip(str) {
  var el = document.createElement('textarea');
  el.value = str;
  el.setAttribute('readonly', '');
  el.style = { position: 'absolute', left: '-9999px' };
  document.body.appendChild(el);
  el.select();
  document.execCommand('copy');
  document.body.removeChild(el);
  notify(str + ':copied to clipboard', 'success');
}
$(function () {
  $(".loader").fadeOut();
});
function mkSrvlist() {
  let srvlistval = $("#serverlist").val();
  let srvlistnamesval = $("#serverlistnames").val();
  let newsrvlist = "";
  let newsrvlistnames = "";
  let srvlist = new Set();
  let srvlistnames = new Set();
  if (srvlistval) {
    let srvlistvalArr = srvlistval.split(',');
    srvlistvalArr.forEach(srvlist.add, srvlist)
    srvlist.add($("#serverid").val());
    let srvlistnamesvalArr = srvlistnamesval.split(',');
    srvlistnamesvalArr.forEach(srvlistnames.add, srvlistnames)
    srvlistnames.add($("#server").val());
  } else {
    srvlist.add($("#serverid").val());
    srvlistnames.add($("#server").val());
  }
  srvlist.forEach(function (elem) {
    newsrvlist += elem + ","
  });
  srvlistnames.forEach(function (elem) {
    newsrvlistnames += elem + ","
  });
  $(".autocomplsrv").val("");
  newsrvlist = newsrvlist.slice(0, -1);
  newsrvlistnames = newsrvlistnames.slice(0, -1);
  $("#serverlist").val(newsrvlist);
  $("#serverlistnames").val(newsrvlistnames);
  if (newsrvlistnames) {
    document.getElementById("srvlistnames").innerHTML = "<br>" + newsrvlistnames;
  }
};
function mkAppSrvlist() {
  let appsrvlistval = $("#appserverlist").val();
  let appsrvlistnamesval = $("#appserverlistnames").val();
  let newappsrvlist = "";
  let newappsrvlistnames = "";
  let appsrvlist = new Set();
  let appsrvlistnames = new Set();
  if (appsrvlistval) {
    let appsrvlistvalArr = appsrvlistval.split(',');
    appsrvlistvalArr.forEach(appsrvlist.add, appsrvlist)
    appsrvlist.add($("#appserverid").val());
    let appsrvlistnamesvalArr = appsrvlistnamesval.split(',');
    appsrvlistnamesvalArr.forEach(appsrvlistnames.add, appsrvlistnames)
    appsrvlistnames.add($("#appserver").val());
  } else {
    appsrvlist.add($("#appserverid").val());
    appsrvlistnames.add($("#appserver").val());
  }
  appsrvlist.forEach(function (elem) {
    newappsrvlist += elem + ","
  });
  appsrvlistnames.forEach(function (elem) {
    newappsrvlistnames += elem + ","
  });
  $(".autocomplappsrv").val("");
  newappsrvlist = newappsrvlist.slice(0, -1);
  newappsrvlistnames = newappsrvlistnames.slice(0, -1);
  $("#appserverlist").val(newappsrvlist);
  $("#appserverlistnames").val(newappsrvlistnames);
  if (newappsrvlistnames) {
    document.getElementById("appsrvlistnames").innerHTML = "<br>" + newappsrvlistnames;
  }

};
function formatBytes(bytes, decimals = 2) {
  if (bytes === 0) return '0 Bytes';
  const k = 1024;
  const dm = decimals < 0 ? 0 : decimals;
  const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}
function syntaxHighlight(obj) {
  var json = JSON.stringify(obj, undefined, 2)
  json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
  return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g,
    function (match) {
      var cls = 'number';
      if (/^"/.test(match)) {
        if (/:$/.test(match)) {
          cls = 'key';
        } else {
          cls = 'string';
        }
      } else if (/true|false/.test(match)) {
        cls = 'boolean';
      } else if (/null/.test(match)) {
        cls = 'null';
      }
      return '<span class="' + cls + '">' + match + '</span>';
    });
}
//nestable output
function updateOutput() {
  if (window.JSON) {
    var thistype = $("#thistype").val();
    var ulserial = [];
    $("#listserial li").each(function () {
      let liobj = {};
      liobj["id"] = $(this).attr('id');
      liobj["name"] = $(".nml" + $(this).attr('id')).text();
      liobj["nameshort"] = $(this).attr('data-nameshort');
      if ($(this).attr('data-color')) {
        liobj["color"] = $(this).attr('data-color');
      }
      if ($(this).attr('data-icon')) {
        liobj["icon"] = $(this).attr('data-icon');
      }
      ulserial.push(liobj);
    });
    var json_text = window.JSON.stringify(ulserial);
    $.post('/api/updatesystemnest',
      {
        thistype: thistype,
        data: json_text
      });
  } else {
    alert('JSON browser support required for this.');
  }
};
$("#add-nmitem").click(function () {
  var totalCount = Math.floor((Math.random() * 10000000) + 1);
  let randstr = Math.random().toString(36).substring(7);
  var html = '<li id="' + totalCount + '" class="list-group-item border-bottom ni' + totalCount + '" data-id="' + totalCount + '"  data-name="Change this" data-nameshort="n' + randstr + '" data-color="#000" ><a class="nml' + totalCount + '">Change this</a><div class="float-end"><a class="button-edit' + totalCount + ' text-info" onclick="showthisnm(\'' + totalCount + '\',\'Change this\',\'#000\')" style="cursor:pointer;"><i class="mdi mdi-pencil mdi-18px"></i></a>&nbsp;<a class="text-danger" onclick="rmthisnm(\'ni' + totalCount + '\')" style="cursor:pointer;"><i class="mdi mdi-close"></i></a></div></li>';
  if ($("#listserial .list-group-item").length) {
    $("#listserial .list-group-item:last").after(html);
  } else {
    $("#listserial").append(html);
  }
  updateOutput();
});
$("#add-nmitemicon").click(function () {
  var totalCount = Math.floor((Math.random() * 10000000) + 1);
  let randstr = Math.random().toString(36).substring(7);
  var html = '<li id="' + totalCount + '" class="list-group-item border-bottom ni' + totalCount + '" data-id="' + totalCount + '"  data-name="Change this" data-nameshort="n' + randstr + '" data-icon="application" ><a class="nml' + totalCount + '">Change this</a><div class="float-end"><a class="button-edit' + totalCount + ' text-info" onclick="showthisnmicon(\'' + totalCount + '\',\'Change this\',\'application\')" style="cursor:pointer;"><i class="mdi mdi-pencil mdi-18px"></i></a>&nbsp;<a class="text-danger" onclick="rmthisnm(\'ni' + totalCount + '\')" style="cursor:pointer;"><i class="mdi mdi-close"></i></a></div></li>';
  if ($("#listserial .list-group-item").length) {
    $("#listserial .list-group-item:last").after(html);
  } else {
    $("#listserial").append(html);
  }
  updateOutput();
});
function rmthisnm(thisid) { $("." + thisid).remove(); updateOutput(); }
function showthisnm(thisid, thisname, color) {
  $("#nmname").val(thisname);
  $("#nmcolor").val(color);
  $("#nmid").val(thisid);
  $('#nmModal').modal('show');
}
function showthisnmicon(thisid, thisname, icon) {
  $("#nmname").val(thisname);
  $("#radioic_" + icon).prop("checked", true);
  $("#nmid").val(thisid);
  $('#nmModal').modal('show');
}
function savethisnm() {
  $(".button-edit" + $("#nmid").val()).attr({ 'onclick': "showthisnm('" + $("#nmid").val() + "','" + $("#nmname").val() + "','" + $("#nmcolor").val() + "')" });
  $(".ni" + $("#nmid").val()).attr({ 'data-name': $("#nmname").val() });
  $(".ni" + $("#nmid").val()).attr({ 'data-color': $("#nmcolor").val() });
  $(".ni" + $("#nmid").val() + " a.nml" + $("#nmid").val()).html($("#nmname").val());
  $('#nmModal').modal('hide');
  updateOutput();
}
function savethisnmicon() {
  var iconradios = document.getElementsByName('icons');
  for (var i = 0, length = iconradios.length; i < length; i++) {
    if (iconradios[i].checked) {
      $(".ni" + $("#nmid").val()).attr({ 'data-icon': iconradios[i].value });
      break;
    }
  }
  $(".button-edit" + $("#nmid").val()).attr({ 'onclick': "showthisnmicon('" + $("#nmid").val() + "','" + $("#nmname").val() + "','" + $("#nmicon").val() + "')" });
  $(".ni" + $("#nmid").val()).attr({ 'data-name': $("#nmname").val() });
  $(".ni" + $("#nmid").val() + " a.nml" + $("#nmid").val()).html($("#nmname").val());
  $('#nmModal').modal('hide');
  updateOutput();
}
function getGITHistory(thistype) {
  notify("<i class='mdi mdi-loading iconspin'></i>&nbsp;Please wait", "info");
  $.ajax({
    type: "POST",
    url: "/vcapi/history",
    data: { hsearch: thistype },
    dataType: 'json',
    cache: false,
    success: function (data) {
      if (data) {
        if (data.error) {
          nalert(data.errorlog, "danger");
        } else {
          let table = $('#data-table-hist').DataTable({ responsive: true, "autoWidth": false, destroy: true }).clear().draw();
          $.each(data, function (index, element) {
            let itemdata = "<tr><td>" + element.id + "</td><td><a href='#' onclick=\"copyToClipboard('" + element.commitid + "');\">" + element.commitid.substring(0, 8) + "</a></td><td>" + element.fileplace + "</td><td><a href='/browse/user/" + element.stepuser + "' target='_blank'>" + element.stepuser + "</td><td>" + moment(element.steptime).format("YYYY-MM-DD H:mm") + "</td></tr>";
            $('#data-table-hist').DataTable().row.add($(itemdata)[0]).draw(false);
          });
          notice.close();
          $('.dtfilter').keyup(function () { table.search($(this).val()).draw(); });
          $('#data-table-hist').css('display', 'table');
          $('#modal-hist').modal('show');
        }
      } else {
        notify("No changes yet", "warning");
      }
    }
  });
}
function copyToClipboard(thistext) {
  navigator.clipboard.writeText(thistext);
  notify("Copied to clipboard", "success");
}
function SetToString(set, delim){
  let str = '';
  let i = 0;
  let size = set.size;
  set.forEach(function(elem){
    str += elem
    if(i++ < size - 1) str += delim
  });
  return str
}