var endpointList = [];
var sourcepointList = [];
var _loadFlowChart, elementCount = 0;
var jsPlmbinst; //the jsPlumb jsPlmbinst
var properties = []; //keeps the properties of each element
var $flowchart = $('#canvas');
var $panzoom = null;
var minScale = 0.4;
var maxScale = 2;
var incScale = 0.1;
jsPlumb.ready(function () {


    var element = "";   //the element which will be appended to the canvas
    var clicked = false;    //check whether an element from the palette was clicked

    jsPlmbinst = window.jsp = jsPlumb.getInstance({
        DragOptions: { cursor: 'pointer', zIndex: 2000 },
        ConnectionOverlays: [
            [ "Arrow", {
                location: 1,
                visible:true,
                width:11,
                length:11,
                id:"ARROW",
                events:{
                    click:function() { alert("you clicked on the arrow overlay")}
                }
            } ],
            [ "Label", {
                location: 0.1,
                id: "label",
                cssClass: "aLabel",
                visible:false
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
            stroke: "#7AB02C",
            fill: "#fff",
            radius: 5,
            strokeWidth: 1
        },
        isSource: true,
        connector: [ "Flowchart", { stub: [40, 60], gap: 10, cornerRadius: 5, alwaysRespectStubs: true } ],
        connectorStyle: connectorPaintStyle,
        hoverPaintStyle: endpointHoverStyle,
        connectorHoverStyle: connectorHoverStyle,
        dragOptions: {},
        overlays: [
            [ "Label", {
                location: [0.5, 1.5],
               // label: "Out",
                cssClass: "endpointSourceLabel",
                visible:true
            } ]
        ]
    },

    //definition of the target endpoint the connector would end
    targetEndpoint = {
        endpoint: "Dot",
        paintStyle: { fill: "#7AB02C", radius: 5 },
        hoverPaintStyle: endpointHoverStyle,
        maxConnections: -1,
        dropOptions: { hoverClass: "hover", activeClass: "active" },
        isTarget: true,
        overlays: [
            [ "Label", { location: [0.5, -0.5], /* label: "In", */cssClass: "endpointTargetLabel", visible:true } ]
        ]
    };
      
    var initConn = function (connection) { 
        connection.addOverlay(["Custom", {
            create:function(component) {
             //   return $("<input type=\"text\" value=\""+connection.sourceId.substring(5) + "-" + connection.targetId.substring(5)+"\" autofocus style=\"position:absolute;\"\/>");
             return $("<input type=\"text\" value=\"change this\" autofocus style=\"position:absolute;\"\/>");
            },
            location: 0.5,
            id: "label",
            cssClass: "aLabel"
        }]);

        connection.addOverlay(["Custom", {
            create:function(component) {
                return $("<button style='display: none' title=\"Delete the connection\"><i class=\"mdi mdi-close\"><\/i><\/button>");
            },
            location: 0.2,
            id: "close",
            cssClass: "close-mark btn btn-danger",
            events:{
                click:function(){
                    $(".start").css({'border': "1px solid green"})
                }
            }
        }]);

        $("#canvas input").css({
            'font-weight':'bold',
            'text-align':'center'
        });
    };

    jsPlmbinst.bind("connection", function (connInfo, originalEvent) {
        initConn(connInfo.connection); 
    });


    _.defer(function(){
        $panzoom = $('#dragwf').panzoom({
          minScale: minScale,
          maxScale: maxScale,
          increment: incScale,
          cursor: "",
          ignoreChildrensEvents:true,
        }).on("panzoomstart",function(e,pz,ev){
          $panzoom.css("cursor","move");
        })
        .on("panzoomend",function(e,pz){
          $panzoom.css("cursor","");
        });
        $panzoom.parent()
        .on('mousewheel.focal', function( e ) {
          if(e.ctrlKey||e.originalEvent.ctrlKey)
          {
            e.preventDefault();
            var delta = e.delta || e.originalEvent.wheelDelta;
            var zoomOut = delta ? delta < 0 : e.originalEvent.deltaY > 0;
            $panzoom.panzoom('zoom', zoomOut, {
               animate: true,
               exponential: false,
            });
          }else{
            e.preventDefault();
            var deltaY = e.deltaY || e.originalEvent.wheelDeltaY || (-e.originalEvent.deltaY);
            var deltaX = e.deltaX || e.originalEvent.wheelDeltaX || (-e.originalEvent.deltaX);
            $panzoom.panzoom("pan",deltaX/2,deltaY/2,{
              animate: true,
              relative: true,
            });
          }
        })
        .on("mousedown touchstart",function(ev){
          var matrix = $("#dragwf").panzoom("getMatrix");
          var offsetX = matrix[4];
          var offsetY = matrix[5];
          var dragstart = {x:ev.pageX,y:ev.pageY,dx:offsetX,dy:offsetY};
          $(ev.target).css("cursor","move");
          $(this).data('dragstart', dragstart);
        })
        .on("mousemove touchmove", function(ev){
          var dragstart = $(this).data('dragstart');
          if(dragstart)
          {
            var deltaX = dragstart.x-ev.pageX;
            var deltaY = dragstart.y-ev.pageY;
            var matrix = $("#dragwf").panzoom("getMatrix");
            matrix[4] = parseInt(dragstart.dx)-deltaX;
            matrix[5] = parseInt(dragstart.dy)-deltaY;
            $("#dragwf").panzoom("setMatrix",matrix);
          }
        })
        .on("mouseup touchend touchcancel", function(ev){
          $(this).data('dragstart',null);
          $(ev.target).css("cursor","");
        });
      });
    var currentScale = 1;
	function makeDraggable(id, className, text){
	    $(id).draggable({
        start: function(e){
                var pz = $("#dragwf");
                currentScale = pz.panzoom("getMatrix")[0];
                $(this).css("cursor","move");
                pz.panzoom("disable");
        },
        stop: function(e,ui){
            $(this).css("cursor","");
            $("#dragwf").panzoom("enable");
          },
		helper: function(){
		    return $("<div/>",{
			text: text,
			class:className
		    });
		},
		stack: ".custom",
		revert: false
	    });
	}

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
            var p = "<p id='p"+id+"' style='line-height: 110%; ' contenteditable='true' >" + properties[0].label + "</p>";
            strong.append(p);
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
        switch(elementType) {
            case "start": return [["BottomCenter"], []];
            case "step": return [["LeftMiddle","BottomCenter"], ["TopCenter","TopLeft","TopRight"]];
            case "decision": return [["LeftMiddle", "RightMiddle", "BottomCenter", "BottomLeft", "BottomRight"], ["TopCenter","TopLeft","TopRight"]];
            case "end": return [[], ["TopCenter","TopLeft","TopRight"]];
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


    //to make the text field resizable when typing the input text.
    $.fn.textWidth = function(text, font){
        var temp = $('<span>').hide().appendTo(document.body).text(text || this.val() || this.text()).css('font', font || this.css('font')),
            width = temp.width();
        temp.remove();
        return width;
    };

    $.fn.autoresize = function(options){
        options = $.extend({padding:10,minWidth:0,maxWidth:10000}, options||{});
        $(this).on('input', function() {
            $(this).css('width', Math.min(options.maxWidth,Math.max(options.minWidth,$(this).textWidth() + options.padding)));
        }).trigger('input');
        return this;
    }

    //resize the label text field when typing
    $('#canvas').on('keyup', '.jsplumb-overlay.aLabel', function () {
        $(this).css('font-weight', 'bold');
        $(this).css('text-align', 'center');
        $(this).autoresize({padding:20,minWidth:20,maxWidth:100});
    });

    var currentST = $("#currentST").val(); 
    function _loadFlowChart(fcJson) { 
        $('#nmassign').empty();
          for ( const [key,value] of Object.entries( fcJson.nodes ) ) {
              elementCount ++;
              var endpoints = getEndpoints(value[0].nodeType); 
              loadProperties((key==currentST?value[0].clsName+" currentST":value[0].clsName), value[0].positionX, value[0].positionY, value[0].label, endpoints[0], endpoints[1], false);
              var element = createElement(key);
              drawElement(element, '#canvas', value[0].elementName);
              makeDraggable(key, value[0].clsName, value[0].elementName);

          }  
          for ( const [key,value] of Object.entries( fcJson.connections ) ) {  
              for(var i = 0; i < value.length; i++) {
                  var connection = value[i];
                  jsPlmbinst.connect({uuids: [connection.sourceUUId, connection.targetUUId]});
                  var ell = document.getElementById(connection.labelID);
                  if(typeof ell !== "undefined" && ell != null){
                    ell.value=connection.label;
                  }
  
              }
          }
      }

    $.ajax
    ({
        type: "POST",
        dataType : 'json',
        url: '/reqworkflow/read',
        data: {
            'wid': $("#wid").val(),
            'gid': $("#gid").val()
        },
        success: function (response) { _loadFlowChart(response); },
        failure: function() {alert("Error!");}
    });

    

});