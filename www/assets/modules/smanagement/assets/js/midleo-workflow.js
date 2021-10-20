var endpointList = [];
var sourcepointList = [];
var _saveFlowchart, _loadFlowChart, elementCount = 0;
var jsPlmbinst; //the jsPlumb jsPlmbinst
var properties = []; //keeps the properties of each element
var $flowchart = $('#canvas');
var $container = $flowchart.parent();
jsPlumb.ready(function () {


    var element = "";   //the element which will be appended to the canvas
    var clicked = false;    //check whether an element from the palette was clicked

    jsPlmbinst = window.jsp = jsPlumb.getInstance({
        DragOptions: { cursor: 'pointer', zIndex: 2000 },
        ConnectionOverlays: [
            ["Arrow", {
                location: 1,
                visible: true,
                width: 11,
                length: 11,
                id: "ARROW",
                events: {
                    click: function () { alert("you clicked on the arrow overlay") }
                }
            }],
            ["Label", {
                location: 0.1,
                id: "label",
                cssClass: "aLabel",
                visible: false
            }]
        ],
        Container: "canvas"
    });

    //define basic connection type
    var basicType = {
        connector: "StateMachine",
        paintStyle: { stroke: "red", strokeWidth: 4 },
        hoverPaintStyle: { stroke: "blue" },
        overlays: [
            "Arrow"
        ]
    };
    jsPlmbinst.registerConnectionType("basic", basicType);

    //style for the connector
    var connectorPaintStyle = {
        strokeWidth: 2,
        stroke: "#61B7CF",
        joinstyle: "round",
        outlineStroke: "white",
        outlineWidth: 2
    },

        //style for the connector hover
        connectorHoverStyle = {
            strokeWidth: 3,
            stroke: "#216477",
            outlineWidth: 5,
            outlineStroke: "white"
        },
        endpointHoverStyle = {
            fill: "#216477",
            stroke: "#216477",
            radius: 7,
        },

        //the source endpoint definition from which a connection can be started
        sourceEndpoint = {
            endpoint: "Dot",
            paintStyle: {
                stroke: "#636E82",
                fill: "#fff",
                radius: 5,
                strokeWidth: 1
            },
            isSource: true,
            connector: ["Flowchart", { stub: [40, 60], gap: 10, cornerRadius: 5, alwaysRespectStubs: true }],
            connectorStyle: connectorPaintStyle,
            hoverPaintStyle: endpointHoverStyle,
            connectorHoverStyle: connectorHoverStyle,
            dragOptions: {},
            overlays: [
                ["Label", {
                    location: [0.5, 1.5],
                    // label: "Out",
                    cssClass: "endpointSourceLabel",
                    visible: true
                }]
            ]
        },

        //definition of the target endpoint the connector would end
        targetEndpoint = {
            endpoint: "Dot",
            paintStyle: { fill: "#636E82", radius: 5 },
            hoverPaintStyle: endpointHoverStyle,
            maxConnections: -1,
            dropOptions: { hoverClass: "hover", activeClass: "active" },
            isTarget: true,
            overlays: [
                ["Label", { location: [0.5, -0.5], /* label: "In", */cssClass: "endpointTargetLabel", visible: true }]
            ]
        };

    var initConn = function (connection) {
        connection.addOverlay(["Custom", {
            create: function (component) {
                //   return $("<input type=\"text\" value=\""+connection.sourceId.substring(5) + "-" + connection.targetId.substring(5)+"\" autofocus style=\"position:absolute;\"\/>");
                return $("<input type=\"text\" value=\"change this\" autofocus style=\"position:absolute;\"\/>");
            },
            location: 0.5,
            id: "label",
            cssClass: "aLabel"
        }]);

        connection.addOverlay(["Custom", {
            create: function (component) {
                return $("<button style='display: none' title=\"Delete the connection\"><i class=\"mdi mdi-close\"><\/i><\/button>");
            },
            location: 0.2,
            id: "close",
            cssClass: "close-mark btn btn-danger",
            events: {
                click: function () {
                    jsPlmbinst.deleteConnection(connection);
                    $(".start").css({ 'border': "1px solid #0CC44F" })
                }
            }
        }]);

        $("#canvas input").css({
            'font-weight': 'bold',
            'text-align': 'center'
        });
    };

    jsPlmbinst.bind("connection", function (connInfo, originalEvent) {
        initConn(connInfo.connection);

        connInfo.connection.bind("click", function (conn) {
            $(".jtk-node").css({ 'outline': "none" });
            conn.showOverlay("close");
        })
    });

    function makeDraggable(id, className, text) {
        $(id).draggable({
            helper: function () {
                return $("<div/>", {
                    text: text,
                    class: className
                });
            },
            stack: ".custom",
            revert: false
        });
    }

    makeDraggable("#startEv", "window start jsplumb-connected custom", "start");
    makeDraggable("#stepEv", "window step jsplumb-connected-step custom", "step");
    makeDraggable("#endEv", "window end jsplumb-connected-end custom", "end");

    $("#descEv").draggable({
        helper: function () {
            return createElement("");
        },
        stack: ".custom",
        revert: false
    });

    //take the x, y coordinates of the current mouse position
    var x, y;
    var containerOffset = $container.offset();
    $(document).on("mousemove", function (e) {
        x = e.clientX + (document.documentElement.scrollLeft || document.body.scrollLeft);
        y = e.clientY + (document.documentElement.scrollTop || document.body.scrollTop);
        if (x < 0) { x = 0; }
        if (y < 0) { y = 0; }
        if (clicked) {
            properties[0].top = y - containerOffset.top - 300 ;
            properties[0].left = x - containerOffset.left - 50;
        }
    });

    var properties;
    var clicked = false;
    function loadProperties(clsName, left, top, label, startpoints, endpoints, contenteditable) {
        properties = [];
        properties.push({
            left: left,
            top: top,
            clsName: clsName,
            label: label,
            startpoints: startpoints,
            endpoints: endpoints,
            contenteditable: contenteditable
        });
    }

    //load properties of a start element once the start element in the palette is clicked
    $('#startEv').mousedown(function () {
        loadProperties("window start custom jtk-node jsplumb-connected", "5em", "5em", "start", ["BottomCenter"],
            [], true);
        clicked = true;
    });

    //load properties of a step element once the step element in the palette is clicked
    $('#stepEv').mousedown(function () {
        loadProperties("window step custom jtk-node jsplumb-connected-step", "5em", "5em", "step",
            ["LeftMiddle", "BottomCenter"], ["TopCenter", "TopLeft", "TopRight"], true);
        clicked = true;
    });

    //load properties of a decision element once the decision element in the palette is clicked
    $('#descEv').mousedown(function () {
        loadProperties("window decision custom jtk-node jsplumb-connected-step", "5em", "5em", "decision",
            ["LeftMiddle", "RightMiddle", "BottomCenter", "BottomLeft", "BottomRight"], ["TopCenter", "TopLeft", "TopRight"], true, 100, 100);
        clicked = true;
    });

    //load properties of a end element once the end element in the palette is clicked
    $('#endEv').mousedown(function () {
        loadProperties("window end custom jtk-node jsplumb-connected-end", "5em", "5em", "end",
            [], ["TopCenter", "TopLeft", "TopRight"], true);
        clicked = true;
    });
    //create an element to be drawn on the canvas
    function createElement(id, name) {
        var elm = $('<div>').addClass(properties[0].clsName).attr('id', id).attr('name', name);
        if (properties[0].clsName.indexOf("decision") > -1) {
            //   elm.outerWidth("100px");
            //   elm.outerHeight("100px");
        }
        elm.css({
            'top': properties[0].top,
            'left': properties[0].left
        });

        var strong = $('<strong>');
        /*    if (properties[0].clsName == "window decision custom jtk-node jsplumb-connected-step") {
                elm.append("<i style='display: none' " +
                "class=\"mdi mdi-close mdi-24px close-icon desc-text\" ><\/i>");
                var p = "<p style='line-height: 110%; ' class='desc-text' contenteditable='true' " +
                    "ondblclick='$(this).focus();'>" + properties[0].label + "</p>";
                strong.append(p);
            }
            else if (properties[0].clsName == "window parallelogram step custom jtk-node jsplumb-connected-step") {
                elm.append("<i style='display: none' class=\"mdi mdi-close mdi-24px close-icon input-text\"><\/i>");
                var p = "<p style='line-height: 110%; ' class='input-text' contenteditable='true' " +
                    "ondblclick='$(this).focus();'>" + properties[0].label
                    + "</p>";
                strong.append(p);
            }
            else
            */
        //    if (properties[0].contenteditable) {
        elm.append('<div class="edtgroup" style="display: none"><i class="mdi mdi-pencil mdi-24px edit-icon"></i>&nbsp;' +
            '<i class="mdi mdi-close mdi-24px close-icon"></i></div>');
        var p = "<p id='p" + id + "' style='line-height: 110%; ' contenteditable='true' >" +
               /* "ondblclick='$(this).focus();'>" + */properties[0].label + "</p>";
        strong.append(p);
        /*    } else {
                elm.append('<div class="edtgroup" style="display: none"><i class="mdi mdi-close mdi-24px close-icon"></i></div>');
                var p = $('<p>').text(properties[0].label);
                strong.append(p);
            } */
        elm.append(strong);
        return elm;
    }

    //add the endpoints for the elements
    var ep;
    var _addEndpoints = function (toId, sourceAnchors, targetAnchors) {
        for (var i = 0; i < sourceAnchors.length; i++) {
            var sourceUUID = toId + sourceAnchors[i];
            ep = jsPlmbinst.addEndpoint("fl" + toId, sourceEndpoint, {
                anchor: sourceAnchors[i], uuid: sourceUUID
            });
            sourcepointList.push(["fl" + toId, ep]);
            ep.canvas.setAttribute("title", "Drag a connection from here");
            ep = null;
        }
        for (var j = 0; j < targetAnchors.length; j++) {
            var targetUUID = toId + targetAnchors[j];
            ep = jsPlmbinst.addEndpoint("fl" + toId, targetEndpoint, {
                anchor: targetAnchors[j], uuid: targetUUID
            });
            endpointList.push(["fl" + toId, ep]);
            ep.canvas.setAttribute("title", "Drop a connection here");
            ep = null;
        }
    };

    function getEndpoints(elementType) {
        switch (elementType) {
            case "start": return [["BottomCenter"], []];
            case "step": return [["LeftMiddle", "BottomCenter"], ["TopCenter", "TopLeft", "TopRight"]];
            case "decision": return [["LeftMiddle", "RightMiddle", "BottomCenter", "BottomLeft", "BottomRight"], ["TopCenter", "TopLeft", "TopRight"]];
            case "end": return [[], ["TopCenter", "TopLeft", "TopRight"]];
        }
    }

    function drawElement(element, canvasId, name) {
        $(canvasId).append(element);
        _addEndpoints(name, properties[0].startpoints, properties[0].endpoints);
        // makeResizable('.custom.step');
        jsPlmbinst.draggable(jsPlmbinst.getSelector(".jtk-node"), {
            grid: [20, 20],
            filter: ".ui-resizable-handle"
        });
    }

    //make an element resizable
    /*  function makeResizable(classname) {
          $(classname).resizable({
              resize: function(event, ui) {
                  jsPlmbinst.revalidate(ui.helper);
                  var marginLeft = $(this).outerWidth() + 8;
                  $(this).find("i").css({'margin-left': marginLeft + "px"});
              },
              handles: "all"
          });
      }
      */
    //make the editor canvas droppable
    $("#canvas").droppable({
        accept: ".window",
        drop: function (event, ui) {
            if (clicked) {
                clicked = false;
                elementCount++;
                //elementCount++;
                var rndmtm = (Math.random() + 1).toString(36).substring(7);
                var name = "Win" + rndmtm;
                var id = "flWin" + rndmtm;
                element = createElement(id);
                if (elementCount == 1 && element.attr("class").indexOf("start") == -1) {
                    alert("The flowchart diagram should contain a start activity");
                    elementCount = 0;
                } else {
                    drawElement(element, "#canvas", name);
                }
                element = "";
            }
        }
    });
    //de-select all the selected elements and hide the delete buttons and highlight the selected element
    $('#canvas').on('click', function (e) {
        $(".jtk-node").css({ 'outline': "none" });
        $(".edtgroup").hide();
        if (e.target.getAttribute("class") != null && e.target.getAttribute("class").indexOf("jtk-canvas") > -1) {
            $.each(jsPlmbinst.getConnections(), function (index, connection) {
                connection.hideOverlay("close");
            });
        }

        /*   if(e.target.nodeName == "P") {
               e.target.parentElement.parentElement.style.outline = "4px solid red";
           }else if(e.target.nodeName == "STRONG"){
               e.target.parentElement.style.outline = "4px solid red";
           }else if(e.target.getAttribute("class") != null && e.target.getAttribute("class").indexOf("jtk-node") > -1){//when clicked the step, decision or i/o elements
               e.target.style.outline = "4px solid red";
           }
           */
    });

    //to make the text field resizable when typing the input text.
    $.fn.textWidth = function (text, font) {
        var temp = $('<span>').hide().appendTo(document.body).text(text || this.val() || this.text()).css('font', font || this.css('font')),
            width = temp.width();
        temp.remove();
        return width;
    };

    $.fn.autoresize = function (options) {
        options = $.extend({ padding: 10, minWidth: 0, maxWidth: 10000 }, options || {});
        $(this).on('input', function () {
            $(this).css('width', Math.min(options.maxWidth, Math.max(options.minWidth, $(this).textWidth() + options.padding)));
        }).trigger('input');
        return this;
    }

    //resize the label text field when typing
    $('#canvas').on('keyup', '.jsplumb-overlay.aLabel', function () {
        $(this).css('font-weight', 'bold');
        $(this).css('text-align', 'center');
        $(this).autoresize({ padding: 20, minWidth: 20, maxWidth: 100 });
    });

    //when an item is selected, highlight it and show the delete icon
    $(document).on("click", ".custom", function () {
        $(".close-icon").prop("title", "Delete the element");
        $(".edit-icon").prop("title", "Edit the element");
        $(this).find(".edtgroup").css({ 'background-color': '#fff', 'right': "-28px", 'top': "-28px", 'position': "absolute" }).show();
    });

    $(document).on("click", ".edit-icon", function () {
        var thisid = $(this).parent().parent().attr("id");
        $("#nmid").val(thisid);
        $("#nmnameen").val($('#p' + thisid).text());
        if (typeof $('#' + thisid).attr("data-assign") !== "undefined" && $('#' + thisid).attr("data-assign") != null && $('#' + thisid).attr("data-assign") != "") {
            //  $("#nmassign").prepend('<option value="'+$('#'+thisid).attr("data-assign")+'">' + $('#'+thisid).attr("data-assign-name") + '</option>');
            $("#nmassign").attr('selectedIndex', '-1').find("option:selected").removeAttr("selected");
            $('#nmassign option[value=' + $('#' + thisid).attr("data-assign") + "XXX" + $('#' + thisid).attr("data-assign-type") + ']').attr('selected', 'selected');
        } else {
            $("#nmassign").attr('selectedIndex', '-1').find("option:selected").removeAttr("selected");
        }
        if (typeof $('#' + thisid).attr("data-bstep") !== "undefined" && $('#' + thisid).attr("data-bstep") != null && $('#' + thisid).attr("data-bstep") != "") {
            $("#bstep").attr('selectedIndex', '-1').find("option:selected").removeAttr("selected");
            $('#bstep option[value=' + $('#' + thisid).attr("data-bstep") + ']').attr('selected', 'selected');
        } else {
            $("#bstep").attr('selectedIndex', '-1').find("option:selected").removeAttr("selected");
        }
        if (typeof $('#' + thisid).attr("data-confirm") !== "undefined" && $('#' + thisid).attr("data-confirm") != null && $('#' + thisid).attr("data-confirm") == 1) {
            $("#canconfirm").prop('checked', true);
        } else {
            $("#canconfirm").prop('checked', false);
        }
        if (typeof $('#' + thisid).attr("data-approve") !== "undefined" && $('#' + thisid).attr("data-approve") != null && $('#' + thisid).attr("data-approve") == 1) {
            $("#checkapprove").prop('checked', true);
        } else {
            $("#checkapprove").prop('checked', false);
        }
        if (typeof $('#' + thisid).attr("data-deploy") !== "undefined" && $('#' + thisid).attr("data-deploy") != null && $('#' + thisid).attr("data-deploy") == 1) {
            $("#candeploy").prop('checked', true);
        } else {
            $("#candeploy").prop('checked', false);
        }
        if (typeof $('#' + thisid).attr("data-efforts") !== "undefined" && $('#' + thisid).attr("data-efforts") != null && $('#' + thisid).attr("data-efforts") == 1) {
            $("#canefforts").prop('checked', true);
        } else {
            $("#canefforts").prop('checked', false);
        }
        if (typeof $('#' + thisid).attr("data-chtask") !== "undefined" && $('#' + thisid).attr("data-chtask") != null && $('#' + thisid).attr("data-chtask") == 1) {
            $("#canchtask").prop('checked', true);
        } else {
            $("#canchtask").prop('checked', false);
        }
        $('#nmModal').modal('show');
    });
    reloadgr = function () {
        window.location.reload();
    };
    savethisnm = function () {
        var thisid = $("#nmid").val();
        var assigndata = $("#nmassign").val();
        var assigndataarr = assigndata.split('XXX');
        $('#p' + thisid).text($("#nmnameen").val());
        $('#' + thisid).attr("data-bstep", $("#bstep").val());
        $('#' + thisid).attr("data-assign", assigndataarr[0]);
        $('#' + thisid).attr("data-assign-type", assigndataarr[1]);
        $('#' + thisid).attr("data-assign-name", $("#nmassign option:selected").html());
        $('#' + thisid).attr("data-approve", ($("#checkapprove").is(":checked") ? 1 : 0));
        $('#' + thisid).attr("data-confirm", ($("#canconfirm").is(":checked") ? 1 : 0));
        $('#' + thisid).attr("data-deploy", ($("#candeploy").is(":checked") ? 1 : 0));
        $('#' + thisid).attr("data-efforts", ($("#canefforts").is(":checked") ? 1 : 0));
        $('#' + thisid).attr("data-chtask", ($("#canchtask").is(":checked") ? 1 : 0));
        $('#nmModal').modal('hide');
    }
    //when the close-icon of an element is clicked, delete that element together with its endpoints
    $(document).on("click", ".close-icon", function () {
        jsPlmbinst.remove($(this).parent().parent().attr("id"));
        $(".start").css({ 'border-color': "#0CC44F" });

        //if there are no elements in the canvas, ids start from 1
        if ($(".jtk-node").length == 0) {
            elementCount = 0;
        }

        for (var i = 0; i < endpointList.length; i++) {
            if (endpointList[i][0] == $(this).parent().attr("id")) {
                for (var j = 0; j < endpointList[i].length; j++) {
                    jsPlmbinst.deleteEndpoint(endpointList[i][j]);
                    endpointList[i][j] = null;
                }
            }
        }

        for (var i = 0; i < sourcepointList.length; i++) {
            if (sourcepointList[i][0] == $(this).parent().attr("id")) {
                for (var j = 0; j < sourcepointList[i].length; j++) {
                    jsPlmbinst.deleteEndpoint(sourcepointList[i][j]);
                    sourcepointList[i][j] = null;
                }
            }
        }
    });
    var currentST = $("#currentST").val();
    function _loadFlowChart(fcJson) {
        $('#nmassign').empty();
        if (typeof fcJson.nodes !== "undefined" && fcJson.nodes != null) {
            for (const [key, value] of Object.entries(fcJson.nodes)) {
                elementCount++;
                var endpoints = getEndpoints(value[0].nodeType);
                loadProperties((key == currentST ? value[0].clsName + " currentST" : value[0].clsName), value[0].positionX, value[0].positionY, value[0].label, endpoints[0], endpoints[1], /*(value[0].label == "start" || value[0].label == "end")?false:*/true);
                var element = createElement(key);
                drawElement(element, '#canvas', value[0].elementName);
                makeDraggable(key, value[0].clsName, value[0].elementName);
                if (typeof value[0].elusr !== "undefined" && value[0].elusr != null && value[0].elusr != "") {
                    $("#" + key).attr("data-assign", value[0].elusr);
                    $("#" + key).attr("data-assign-name", value[0].elusrname);
                    $("#" + key).attr("data-assign-type", value[0].elusrtype);
                }
                if (typeof value[0].elusrappr !== "undefined" && value[0].elusrappr != null && value[0].elusrappr != "") {
                    $("#" + key).attr("data-approve", value[0].elusrappr);
                }
                if (typeof value[0].elusrbstep !== "undefined" && value[0].elusrbstep != null && value[0].elusrbstep != "") {
                    $("#" + key).attr("data-bstep", value[0].elusrbstep);
                }
                if (typeof value[0].elusrconf !== "undefined" && value[0].elusrconf != null && value[0].elusrconf != "") {
                    $("#" + key).attr("data-confirm", value[0].elusrconf);
                }
                if (typeof value[0].elusrdepl !== "undefined" && value[0].elusrdepl != null && value[0].elusrdepl != "") {
                    $("#" + key).attr("data-deploy", value[0].elusrdepl);
                }
                if (typeof value[0].elusreff !== "undefined" && value[0].elusreff != null && value[0].elusreff != "") {
                    $("#" + key).attr("data-efforts", value[0].elusreff);
                }
                if (typeof value[0].elusrchtask !== "undefined" && value[0].elusrchtask != null && value[0].elusrchtask != "") {
                    $("#" + key).attr("data-chtask", value[0].elusrchtask);
                }
            }
        }
        if (typeof fcJson.connections !== "undefined" && fcJson.connections != null) {
            for (const [key, value] of Object.entries(fcJson.connections)) {
                for (var i = 0; i < value.length; i++) {
                    var connection = value[i];
                    jsPlmbinst.connect({ uuids: [connection.sourceUUId, connection.targetUUId] });
                    var ell = document.getElementById(connection.labelID);
                    if (typeof ell !== "undefined" && ell != null) {
                        ell.value = connection.label;
                    }

                }
            }
        }
        if (typeof fcJson.ulist !== "undefined" && fcJson.ulist != null) {
            $("#nmassign").append('<option value="">Please select</option>');
            for (const [key, value] of Object.entries(fcJson.ulist)) {
                $("#nmassign").append('<option value="' + key + "XXX" + value.type + '">' + value.uname + '</option>');
            }
        }
    }
    _delFlowchart = function () {
        Swal.fire({
            title: 'Delete this workflow?',
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
                $.ajax({
                    url: '/reqworkflow/delete',
                    type: 'POST',
                    data: {
                        'wid': $("#wid").val()
                    },
                    success: function (response) {
                        notify(response, "success");
                        window.location.href = "/smanagement";
                    },
                    error: function () {
                        notify("Flowchart delete error.", "error");
                    }
                });
            }
        })
    };
    _saveFlowchart = function () {
        var totalCount = 0;
        if (elementCount == 0) {
            notify("You have to add steps for the workflow", "danger");
        }
        if (elementCount > 0) {
            var nodes = {};
            //check whether the diagram has a start element
            var elm = $(".start.jtk-node");
            if (elm.length == 0) {
                notify("The workflow should have a start element", "danger");
            } else {
                $(".jtk-node").each(function (index, element) {
                    totalCount++;
                    var $element = $(element);
                    var type = $element.attr('class').toString().split(" ")[1];
                    var elid = $element.attr('id');
                    if (typeof nodes[elid] == 'undefined') {
                        nodes[elid] = [];
                    }
                    //  if (type == "step" || type == "decision" || type == "parallelogram") {
                    var elusr = $element.attr("data-assign");
                    var elusrname = $element.attr("data-assign-name");
                    var elusrbstep = $element.attr("data-bstep");
                    var elusrtype = $element.attr("data-assign-type");
                    var elusrappr = $element.attr("data-approve");
                    var elusrconf = $element.attr("data-confirm");
                    var elusrdepl = $element.attr("data-deploy");
                    var elusreff = $element.attr("data-efforts");
                    var elusrchtask = $element.attr("data-chtask");
                    nodes[elid].push({
                        //      elementId: $element.attr('id'),
                        elementName: $element.attr('id').replace("fl", ""),
                        nodeType: type,
                        positionX: parseInt($element.css("left"), 10),
                        positionY: parseInt($element.css("top"), 10),
                        clsName: $element.attr('class').toString().replace(" currentST", ""),
                        label: $element.text().replace(/\s+/g, " ").trim(),
                        elusr: typeof elusr == 'undefined' ? '' : elusr,
                        elusrname: typeof elusrname == 'undefined' ? '' : elusrname,
                        elusrbstep: typeof elusrbstep == 'undefined' ? '' : elusrbstep,
                        elusrtype: typeof elusrtype == 'undefined' ? '' : elusrtype,
                        elusrappr: typeof elusrappr == 'undefined' ? '' : elusrappr,
                        elusrconf: typeof elusrconf == 'undefined' ? '' : elusrconf,
                        elusrdepl: typeof elusrdepl == 'undefined' ? '' : elusrdepl,
                        elusreff: typeof elusreff == 'undefined' ? '' : elusreff,
                        elusrchtask: typeof elusrchtask == 'undefined' ? '' : elusrchtask,
                        width: $element.outerWidth(),
                        height: $element.outerHeight()
                    });
                    //  } else {
                    //      nodes[elid].push({
                    //        elementId: $element.attr('id'),
                    //          elementName:  $element.attr('id').replace("fl",""),
                    //         nodeType: $element.attr('class').toString().split(" ")[1],
                    //          positionX: parseInt($element.css("left"), 10),
                    //          positionY: parseInt($element.css("top"), 10),
                    //          elusr: typeof elusr == 'undefined'?'':elusr,
                    //          clsName: $element.attr('class').toString(),
                    //          label: $element.text().replace(/\s+/g, " ").trim()
                    //      });
                    //  }
                });
                var connections = {};
                $.each(jsPlmbinst.getConnections(), function (index, connection) {
                    var connid = connection.sourceId;
                    if (typeof connections[connid] == 'undefined') {
                        connections[connid] = [];
                    }
                    connections[connid].push({
                        connectionId: connection.id,
                        sourceUUId: connection.endpoints[0].getUuid(),
                        targetUUId: connection.endpoints[1].getUuid(),
                        sourceId: connection.sourceId,
                        targetId: connection.targetId,
                        label: connection.getOverlay("label").getElement().value,
                        labelID: connection.getOverlay("label").getElement().id,
                        //   labelWidth: connection.getOverlay("label").getElement().style.width,
                        /*    anchors: $.map(connection.endpoints, function(endpoint) {
    
                                return [[endpoint.anchor.x, 
                                endpoint.anchor.y, 
                                endpoint.anchor.orientation[0], 
                                endpoint.anchor.orientation[1],
                                endpoint.anchor.offsets[0],
                                endpoint.anchor.offsets[1]]];
                        
                              }) */
                    });
                });

                var flowchart = {};
                flowchart.nodes = nodes;
                flowchart.connections = connections;
                flowchart.numberOfElements = totalCount;
                $.ajax({
                    url: '/reqworkflow/save',
                    type: 'POST',
                    data: {
                        'wid': $("#wid").val(),
                        'gid': $("#gid").val(),
                        'winfo': $("#winfo").val(),
                        'wname': $("#wname").val(),
                        'wfcost': $("#wfcost").val(),
                        'wfcurcost': $("#wfcurcost").val(),
                        'wtype':$("#wtype").val(),
                        'formid': $("#formid").val(),
                        'haveconf': ($("#haveconf").is(":checked") ? 1 : 0),
                        'haveappr': ($("#haveappr").is(":checked") ? 1 : 0),
                        'data': JSON.stringify(flowchart)
                    },
                    success: function (response) {
                        notify(response, "success");
                        window.location.reload();
                    },
                    error: function () {
                        notify("Flowchart saving error.", "error");
                    }
                });
            }
        }
    };

    $.ajax
        ({
            type: "POST",
            dataType: 'json',
            url: '/reqworkflow/read',
            data: {
                'wid': $("#wid").val(),
                'gid': $("#gid").val()
            },
            success: function (response) { _loadFlowChart(response); },
            failure: function () { alert("Error!"); }
        });

});