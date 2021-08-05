$(document).ready(function(){
  if($('#btn-create-req')[0]) {
   $("#btn-create-req").prop('disabled',true);
  }
    if($('#autocomplete')[0]) {
        $('#autocomplete').autocomplete({
          source: function( request, response ) {
            $.ajax({
              url: "/api/getallusrgr/all",
              type: 'post',
              dataType: "json",
              data: { search: request.term },
              success: function (data) {
                  response(data.map(function (value) {
                      return {
                          'label': value.name ,
                          'nameid': value.nameid, 
                          'utype': value.type,
                          'uemail': value.email,
                          'utitle': value.utitle,
                          'avatar': value.avatar
                      };  
                  }));
              }   
            }); 
           },
          minLength: 2,
          select: function( event, ui ) {
            $("#respusersselected").val(ui.item.utype+"#"+ui.item.nameid+"#"+ui.item.label+"#"+ui.item.uemail+"#"+ui.item.utitle+"#"+ui.item.avatar);
          }
      });
    }
    if($('#autogroups')[0]) {
      let grset = new Set();
      $('#autogroups').autocomplete({
        source: function( request, response ) {
          $.ajax({
            url: "/pubapi/getallgr",
            type: 'post',
            dataType: "json",
            data: { search: request.term },
            success: function (data) {
                response(data.map(function (value) {
                    return {
                        'label': value.name ,
                        'nameid': value.nameid
                    };  
                }));
            }   
          }); 
         },
        minLength: 2,
        select: function( event, ui ) {
          grset.add(ui.item.nameid); 
          $("#respgrsel").val(JSON.stringify(Array.from(grset)));
          notify("Group added successfully","success");
          $(this).val(''); return false;
        }
    });
   }
    if($('#groupuser')[0]) {
      $('#groupuser').autocomplete({
        source: function( request, response ) {
          $.ajax({
            url: "/api/getallusrgr",
            type: 'post',
            dataType: "json",
            data: { search: request.term },
            success: function (data) {
                response(data.map(function (value) {
                    return {
                        'label': value.name ,
                        'nameid': value.nameid
                    };  
                }));
            }   
          }); 
         },
        minLength: 2,
        select: function( event, ui ) {
          $("#groupuserselected").val("user#"+ui.item.nameid+"#"+ui.item.label);
          $(".addusrgr").show();
        }
    });
    }
    if($('#applauto')[0]) {
      $('#applauto').autocomplete({
        source: function( request, response ) {
          $.ajax({
            url: "/pubapi/getallapp",
            type: 'post',
            dataType: "json",
            data: { search: request.term },
            success: function (data) {
                response(data.map(function (value) {
                    return {
                        'label': value.name ,
                        'nameid': value.nameid
                    };  
                }));
            }   
          }); 
         },
        minLength: 2,
        select: function( event, ui ) {
          $("#appname").val(ui.item.nameid);
        }
    });
    }
    if($('.placeauto')[0]) {
      $('.placeauto').autocomplete({
        source: function( request, response ) {
          $.ajax({
            url: "/pubapi/getallplaces",
            type: 'post',
            dataType: "json",
            data: { search: request.term },
            success: function (data) {
                response(data.map(function (value) {
                    return {
                        'label': value.name ,
                        'nameid': value.nameid
                    };  
                }));
            }   
          }); 
         },
        minLength: 2,
        select: function( event, ui ) {
          $("#pluid").val(ui.item.nameid);
        }
    });
    }
    if($('.autocomplqm')[0]) {
      $('.autocomplqm').autocomplete({
        source: function( request, response ) {
          $.ajax({
            url: "/pubapi/getallappsrv/qm",
            type: 'post',
            dataType: "json",
            data: { search: request.term },
            success: function (data) {
                response(data.map(function (value) {
                    return {
                        'label': value.name ,
                        'nameid': value.nameid
                    };  
                }));
            }   
          }); 
         },
        minLength: 2,
        select: function( event, ui ) {
          $("#"+$(this).data("srv")).val(ui.item.nameid);
        }
    });
    }
    if($('.autocomplsrv')[0]) {
      $('.autocomplsrv').autocomplete({
        source: function( request, response ) {
          $.ajax({
            url: "/pubapi/getallsrv",
            type: 'post',
            dataType: "json",
            data: { search: request.term },
            success: function (data) {
                response(data.map(function (value) {
                    return {
                      'srv': value.name ,
                      'srvip': value.srvip ,
                      'label': value.label ,
                      'nameid': value.nameid
                    };  
                }));
            }   
          }); 
         },
        minLength: 2,
        select: function( event, ui ) {
          $("#server").val(ui.item.srv);
          $("#serverip").val(ui.item.srvip);
          $("#serverid").val(ui.item.nameid);
        }
    });
    }
    if($('.autocomplappsrv')[0]) {
      $('.autocomplappsrv').autocomplete({
        source: function( request, response ) {
          $.ajax({
            url: "/pubapi/getallappsrv",
            type: 'post',
            dataType: "json",
            data: { search: request.term },
            success: function (data) {
                response(data.map(function (value) {
                    return {
                      'qm': value.name ,
                      'srvappname': value.appsrvname ,
                      'label': value.appsrvname ,
                      'nameid': value.nameid
                    };  
                }));
            }   
          }); 
         },
        minLength: 2,
        select: function( event, ui ) {
          $("#appserver").val(ui.item.srvappname);
          $("#appserverid").val(ui.item.nameid);
        }
    });
    }
    if($('#reqauto')[0]) {
      $('#reqauto').autocomplete({
        source: function( request, response ) {
          $.ajax({
            url: "/pubapi/searchreq",
            type: 'post',
            dataType: "json",
            data: { search: request.term },
            success: function (data) {
                response(data.map(function (value) {
                    return {
                        'label': value.name ,
                        'nameid': value.nameid
                    };  
                }));
            }   
          }); 
         },
        minLength: 2,
        select: function( event, ui ) {
          $("#reqname").val(ui.item.nameid);
        }
    });
    }
    if($('#projauto')[0]) {
      $('#projauto').autocomplete({
        source: function( request, response ) {
          $.ajax({
            url: "/pubapi/searchproj",
            type: 'post',
            dataType: "json",
            data: { search: request.term },
            success: function (data) {
                response(data.map(function (value) {
                    return {
                        'label': value.name ,
                        'nameid': value.nameid,
                        'budget': value.budget,
                        'budgetspent': value.budgetspent
                    };  
                }));
            }   
          }); 
         },
        minLength: 2,
        select: function( event, ui ) {
          $("#projname").val(ui.item.nameid);
          $("#projbudget").val(ui.item.budget);
          $("#projbudgetspent").val(ui.item.budgetspent);
          let budgleft=ui.item.budget-ui.item.budgetspent;
          let wfcost=$("#wfcurcost").val(); 
          if(ui.item.nameid=="xxx"){
            $("#reqprojtype").val("run");
            $("#btn-create-req").prop('disabled',false);
            $("#projout").hide();
          } else {
            $("#projout").show();
            if(wfcost){
              if((budgleft-wfcost)<0){
                notify("Not enough budget left","danger");
                $("#btn-create-req").prop('disabled',true);
              } else {
                $("#btn-create-req").prop('disabled',false);
              }
              $("#projout").html("Budget left: <font style='font-weight:bold;'>"+budgleft+"</font>");
            }
            $("#reqprojtype").val("project");
          }
        }
    });
    }
    if($('#reqeffauto')[0]) {
      $('#reqeffauto').autocomplete({
        source: function( request, response ) {
          $.ajax({
            url: "/pubapi/searchreqeff",
            type: 'post',
            dataType: "json",
            data: { search: request.term },
            success: function (data) {
                response(data.map(function (value) {
                    return {
                        'label': value.name ,
                        'nameid': value.nameid
                    };  
                }));
            }   
          }); 
         },
        minLength: 2,
        select: function( event, ui ) {
          if(ui.item.nameid=="xxx"){
            ShowHide('showselect','showinput');
          } 
          $("#reqid").val(ui.item.nameid+"#"+ui.item.label);
        }
    });
    }
    if($('.textarea')[0]) {
        tinymce.init({
        selector: ".textarea" ,
        theme: 'silver',
        relative_urls: false,
        remove_script_host: false,
        plugins: [
          'advlist autolink lists link image charmap print preview hr anchor pagebreak',
          'searchreplace wordcount visualblocks visualchars code fullscreen',
          'insertdatetime media nonbreaking save table directionality',
          'emoticons template paste textpattern imagetools',
          'mention'
        ],
        mentions: {
          delimiter: ['@', '#'],
          source: function( query, process, delimiter ) {
            if (delimiter === '@') {
                    $.ajax({
                      url: "/pubapi/getallusrgr/all",
                      type: 'post',
                      dataType: "json",
                      data: { search: query },
                      success: function (data) {
                          process(data);
                      }   
                    }); 
            }
            if (delimiter === '#') {
              $.ajax({
                url: "/pubapi/searchtags",
                type: 'post',
                dataType: "json",
                data: { search: query },
                success: function (data) {
                    process(data);
                }   
              }); 
            }
          },
          render: function(item) {
            return '<li><a href="javascript:;"><span>' + item.name + '</span></a></li>';
          },
          insert: function(item) {
            if (item.nameid) {
              return '<span class="badge badge-info" id="midmention='+item.type+'@'+item.nameid+'">@' + item.name + '</span>';
            }
            if (item.what) {
              return '<a href="'+location.protocol + "//" + location.host+'/searchall/?sa=y&st=tag&fd='+item.name+'" target="_parent" class="badge badge-secondary">#' + item.name + '</a>';
            }
          },
          renderDropdown: function() {
            return '<ul class="rte-autocomplete dropdown-menu"></ul>';
          }
        },
        toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media | forecolor backcolor emoticons',
        image_advtab: true,
        height: 300,
        paste_data_images: true,
        forced_root_block : false,   
        force_br_newlines : true,   
        force_p_newlines : false
      });  
    }
    if($('.autocomplpack')[0]) {
      $('.autocomplpack').autocomplete({
        source: function( request, response ) {
          let thispack="";
          if($("#packprj").val()){thispack=$("#packprj").val();}
          $.ajax({
            url: "/pubapi/getallpack"+thispack,
            type: 'post',
            dataType: "json",
            data: { search: request.term },
            success: function (data) {
                response(data.map(function (value) {
                    return {
                      'packname': value.name ,
                      'nameid': value.nameid ,
                      'label': value.name ,
                      'srvid': value.srvid
                    };  
                }));
            }   
          }); 
         },
        minLength: 2,
        select: function( event, ui ) {
          $("#srvid").val(ui.item.srvid);
          $("#deplpkgid").val(ui.item.nameid);
        }
    });
    }
    if($('.autocomplservlist')[0]) {
      $('.autocomplservlist').autocomplete({
        source: function( request, response ) {
          $.ajax({
            url: "/projectapi/getallserv",
            type: 'post',
            dataType: "json",
            data: { search: request.term },
            success: function (data) {
                response(data.map(function (value) {
                    return {
                      'type': value.type ,
                      'formid': value.formid ,
                      'cost': value.cost ,
                      'curcost': value.curcost ,
                      'label': value.name ,
                      'nameid': value.nameid
                    };  
                }));
            }   
          }); 
         },
        minLength: 2,
        select: function( event, ui ) {
          $("#servicetype").val(ui.item.type);
          $("#servicecost").val(ui.item.cost);
          $("#servicecurcost").val(ui.item.curcost);
          $("#servicename").val(ui.item.label);
          $("#serviceid").val(ui.item.nameid);
          $("#serviceformid").val(ui.item.formid);
        }
    });
    }



});