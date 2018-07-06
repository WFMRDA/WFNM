!function(t){var i=t(window);t.fn.visible=function(t,e,o){if(!(this.length<1)){var r=this.length>1?this.eq(0):this,n=r.get(0),f=i.width(),h=i.height(),o=o?o:"both",l=e===!0?n.offsetWidth*n.offsetHeight:!0;if("function"==typeof n.getBoundingClientRect){var g=n.getBoundingClientRect(),u=g.top>=0&&g.top<h,s=g.bottom>0&&g.bottom<=h,c=g.left>=0&&g.left<f,a=g.right>0&&g.right<=f,v=t?u||s:u&&s,b=t?c||a:c&&a;if("both"===o)return l&&v&&b;if("vertical"===o)return l&&v;if("horizontal"===o)return l&&b}else{var d=i.scrollTop(),p=d+h,w=i.scrollLeft(),m=w+f,y=r.offset(),z=y.top,B=z+r.height(),C=y.left,R=C+r.width(),j=t===!0?B:z,q=t===!0?z:B,H=t===!0?R:C,L=t===!0?C:R;if("both"===o)return!!l&&p>=q&&j>=d&&m>=L&&H>=w;if("vertical"===o)return!!l&&p>=q&&j>=d;if("horizontal"===o)return!!l&&m>=L&&H>=w}}}}(jQuery);
require(
[
    "esri/config",
    "esri/Map",
    "esri/views/MapView",
    "esri/widgets/BasemapToggle",
    "esri/widgets/Locate",
    "esri/widgets/Track",
    "esri/geometry/Point",
    "esri/Graphic",
    "esri/symbols/SimpleMarkerSymbol",
    "esri/layers/support/Field",
    "esri/widgets/Legend",
    "esri/request",
    "esri/layers/FeatureLayer",
    "esri/layers/ImageryLayer",
    "esri/renderers/SimpleRenderer",
    "esri/renderers/UniqueValueRenderer",
    "esri/symbols/SimpleFillSymbol",
    "esri/symbols/PictureMarkerSymbol",
    "dojo/_base/array",
    "esri/widgets/Zoom",
    "dojo/dom",
    "dojo/on",
    "dojo/domReady!"
],function(esriConfig,Map,MapView,BasemapToggle,Locate,Track,Point,Graphic,SimpleMarkerSymbol,Field,Legend,esriRequest,FeatureLayer,ImageryLayer,SimpleRenderer,UniqueValueRenderer,SimpleFillSymbol,PictureMarkerSymbol,arrayUtils,Zoom,dom,on) {
    // esriConfig.request.corsEnabledServers.push("wildfire.cr.usgs.gov");
    var map;
    var view;
    var userMarker;
    var fireMarker;
    var geoMac;
    var layerInfos = [];
    var wfnmLayer;
    var legend;
    // console.log('ArcMapLoaded');
    // console.log(yiiOptions.mediaUrl);
    var fireMarkerSymbol = new PictureMarkerSymbol({
        url: yiiOptions.mediaUrl+"/active_fire.png",
        width: "50px",
        height: "50px"
    });
    var userMarkerSymbol = new PictureMarkerSymbol({
        url: yiiOptions.mediaUrl+"/user_location.png",
        width: "40px",
        height: "40px"
    });
    /**********************
    * MAP INIT
    **********************/
    map = new Map({
        basemap: "streets"
    });
    view = new MapView({
        container: "map",  // Reference to the scene div created in step 5
        map: map,  // Reference to the map object created before the scene
        zoom: 4,  // Sets the zoom level based on level of detail (LOD)
        center: [-84, 35],  // Sets the center point of view in lon/lat
        // scale: 24000,
        ui:{
            // components:  ["attribution", "zoom"],
            components:  [],
        },
        contstraints:{
            rotationEnabled:false
        }
    });
    view.on('pointer-up', function(){
        view.rotation = 0;
    });
    var zoom = new Zoom({
        view: view
    }, "zoomDiv");
    var track = new Locate({
        view: view,
        graphic: new Graphic({
            symbol: userMarkerSymbol,  // Overwrites the default symbol used for the
            // graphic placed at the location of the user when found
        })
    },"locateDiv");
/*    var track = new Track({
        view: view,
        graphic: new Graphic({
            symbol: userMarkerSymbol,  // Overwrites the default symbol used for the
            // graphic placed at the location of the user when found
        })
    },"locateDiv");*/
    // Add the locate widget to the top left corner of the view
    // view.ui.add(locateBtn,"top-left");
    // view.ui.add(track);
    view.ui.add(zoom);
    view.then(function() {
        // console.log('in view Then');
        getWfnmGeoJsonData()
        .then(createWfnmGraphics)
        .then(createWfnmLayer)
        .then(createLegend)
        .otherwise(errback);
    });
    // Set up a click event handler and retrieve the screen point
    view.on("click", function(evt) {
        screenPoint = evt.screenPoint;
        // console.log(screenPoint);
        // the hitTest() checks to see if any graphics in the view
        // intersect the given screen x, y coordinates
        view.hitTest(screenPoint)
          .then(getFeatureInfo);
      });
    /**********************
    * END MAP INIT
    **********************/

    /**************************************************
    * Define the specification for each field to create in the layer
    * Define the renderer for symbolizing fires on the landscape
    **************************************************/

    /*********************
    *   A => NEW
    *   B => EMERGING
    *   C => CONTAINED
    *   D => CONTROLLED
    *   E => ACTIVE
    *   F => OUT
        circle  sms_circle
        cross   sms_cross
        diamond sms_diamond
        square  sms_square
        x       sms_x
    *   https://developers.arcgis.com/javascript/latest/api-reference/esri-symbols-SimpleMarkerSymbol.html#style
    {
        value: "A",
        symbol: new SimpleMarkerSymbol({
            color: "red",
        }),
        label:"New"
    },
    {
        value: "B",
        symbol: new SimpleMarkerSymbol({
            color: "blue",
        }),
        label:"EMERGING"
    },
    {
        value: "C",
        symbol:new SimpleMarkerSymbol({
            color: "palegreen",
        }),
        label:"CONTAINED"
    },
    {
        value: "D",
        symbol: new SimpleMarkerSymbol({
            color: "orange",
        }),
        label:"CONTROLLED"
    },
    {
        value: "E",
        symbol:new SimpleMarkerSymbol({
            color: "purple",
        }),
        label:"ACTIVE"
    },
    {
        value: "F",
        symbol:new SimpleMarkerSymbol({
            color: "black",
        }),
        label:"OUT"
    }
    *********************/
    var fireRenderer =
    new UniqueValueRenderer({
        field: "fireType",
        nomalizationField: "fireType",
        defaultLabel: "Fire Classes",
        uniqueValueInfos: [
            {
                value: "A",
                symbol: new PictureMarkerSymbol({
                    url: yiiOptions.mediaUrl+"/map_new_fire.png",
                }),
                label:"New"
            },
            {
                value: "B",
                symbol:  new PictureMarkerSymbol({
                    url: yiiOptions.mediaUrl+"/map_emerging_fire.png",
                }),
                label:"EMERGING"
            },
            {
                value: "C",
                symbol: new PictureMarkerSymbol({
                    url: yiiOptions.mediaUrl+"/map_contained_fire.png",
                }),
                label:"CONTAINED"
            },
            {
                value: "D",
                symbol:  new PictureMarkerSymbol({
                    url: yiiOptions.mediaUrl+"/map_controlled_fire.png",
                }),
                label:"CONTROLLED"
            },
            {
                value: "E",
                symbol: new PictureMarkerSymbol({
                    url: yiiOptions.mediaUrl+"/map_active_fire.png",
                }),
                label:"ACTIVE"
            },
            /*{
                value: "F",
                symbol: new PictureMarkerSymbol({
                    url: yiiOptions.mediaUrl+"/map_out_fire.png",
                }),
                label:"OUT"
            }*/
        ],
        visualVariables: [
            {
                type: "size",
                field: "acres",
                valueUnit: "unknown",
                stops: [
                    { value: 99, size: 12 },
                    { value: 999, size: 15 },
                    { value: 9999, size: 18 },
                    { value: 99999, size: 20 },
                    { value: 100000, size: 25 }
                ]
            }
        ],
    });


    //Wildfires Near Me Layer
    function getWfnmGeoJsonData() {
        var options = {
            responseType: "json",
            method:'post'
        };
        return esriRequest(yiiOptions.homeUrl+'/map-rest/fires',options);
    }

    /**************************************************
    * Create graphics with returned geojson data
    **************************************************/
    function createWfnmGraphics(response) {
        // console.log('in graphics');
        // console.log(response);
        // raw GeoJSON data
        var geoJson = response.data.wfnm;
        var addtlLayers = response.data.addtlLayers;
        // console.log(geoJson);
        // console.log(addtlLayers);
        // Create an array of Graphics from each GeoJSON feature
        var map = arrayUtils.map(geoJson.features, function(feature, i) {
            return {
                geometry: new Point({
                    x: feature.geometry.coordinates[0],
                    y: feature.geometry.coordinates[1]
                }),
                // select only the attributes you care about
                attributes: {
                    ObjectID: feature.properties.Id,
                    wfnmId: feature.properties.Id,
                    fireType: feature.properties.fireType,
                    acres: feature.properties.acres,
                    name: feature.properties.Name,
                }
            };
        });
        // console.log(map);
        return {
            addtlLayers: addtlLayers,
            map:map
        }
    }
    /**************************************************
    * Create a FeatureLayer with the array of graphics
    **************************************************/
    function createWfnmLayer(layersObj) {
        // console.log('in create Layers');
        // console.log(graphics);
        // console.log(fireRenderer);
        var addtlLayers = layersObj.addtlLayers;
        var graphics = layersObj.map;
        var layersArray = [];
        // console.log(addtlLayers);
        if(addtlLayers !== null && addtlLayers !== undefined){
            if(jQuery.inArray( "GeoMac" , addtlLayers ) != -1 ){
                // console.log('GeoMac Clause');
                geoMac = new FeatureLayer({
                    url: "//wildfire.cr.usgs.gov/arcgis/rest/services/geomac_perims/MapServer/4",
                    outFields: ["*"],
                    popupEnabled: false,
                });
                layersArray.push(geoMac);
            }
        }
        wfnmLayer = new FeatureLayer({
            source: graphics, // autocast as an array of esri/Graphic
            // create an instance of esri/layers/support/Field for each field object
            fields: [
                {
                    name: "ObjectID",
                    alias: "ObjectID",
                    type: "oid"
                },
                {
                    name: "name",
                    alias: "Name",
                    type: "string"
                },
                {
                    name: "wfnmId",
                    alias: "wfnmId",
                    type: "string"
                },
                {
                    name: "fireType",
                    alias: "Fire Classes",
                    type: "string"
                },
                {
                    name: "acres",
                    alias: "Acres",
                    type: "integer"
                }
            ], // This is required when creating a layer from Graphics
            objectIdField: "ObjectID", // This must be defined when creating a layer from Graphics
            renderer: fireRenderer, // set the visualization on the layer
            spatialReference: {
                wkid: 4326
            },
            geometryType: "point", // Must be set when creating a layer from Graphics
            popupEnabled: false,
        });
        layersArray.push(wfnmLayer);
        map.addMany(layersArray);
        // map.addMany([geoMac,wfnmLayer]);
        // return wfnmLayer;
        // return layersArray;
    }
    /******************************************************************
    * Add layer to layerInfos in the legend
    ******************************************************************/
    function createLegend() {

        if (legend) {
            legend.layerInfos = layerInfos;
        } else {
            legend = new Legend({
                view: view,
                layerInfos : layerInfos,
                visible:yiiOptions.legendHelpToggle == 'active' ? true : false,
            },"legendDiv");
        }
        // view.ui.add(legend);
        // if(yiiOptions.legendHelpToggle == 'active'){
        //     jQuery.addLegend();
        // }else{
        //     jQuery.removeLegend();
        // }
        // jQuery.removeLegend();
    }

    function getFeatureInfo(response){
        // console.log(response);
        // var graphic = response.results[0].graphic;
        var graphics = response.results;
        graphics.forEach(function(el) {
            var graphic = el.graphic;
            var attributes = graphic.attributes;
            // console.log(attributes);
            //Check for WFNM Point
            if(attributes.wfnmId !== undefined){
                var fireId = attributes.wfnmId;
                return jQuery.getFireInfo(fireId);
            }
            //Check for GeoMac Point
            if(attributes.irwinid !== undefined){
                var fireId = attributes.irwinid;
                fireId = fireId.replace(/[{}]/g, "");
                console.log(fireId);
                // return true;
                return jQuery.getFireInfo(fireId);
            }
        });
    }

    jQuery.getFireInfo = function(fid,aid){
        // console.log(fid);
        $.get( "/map-rest/fire-info",{
            fid:fid,
            aid:aid,
        },function( data ) {
            jQuery.loadFireInfoPanel(data.html,data.coords);
            if(data.header != undefined){
                $('#top-notifications-li').replaceWith(data.header);
                $(".dropdown-toggle").dropdown();
                // console.log(data.header);
            }
        }, "json" );
    }

    /*
    *   Creates and Shows info panel depicting information about fire
    *   @param html string
    */
    jQuery.loadFireInfoPanel = function(html,coords){
        view.graphics.remove(fireMarker);
        var pt = new Point({
            longitude: coords['lon'],
            latitude: coords['lat'],
        });
        fireMarker = new Graphic({
            geometry: pt,
            symbol: fireMarkerSymbol,
        });

        view.goTo({
            target: pt,
            zoom: 10,
        },{
            duration:1000,
        })
        .then(addFireMarker);
        //Load Info and Show Display Panel;
        wfnm.loadDataPanel(html);
        FB.XFBML.parse(document.getElementById('info-panel'));
    }
    /*
    *   Creates and Shows info panel depicting information about fire
    *   @param html string
    */
    jQuery.panMapTo = function(coords){
        console.log(coords);
        view.graphics.remove(userMarker);
        var pt = new Point({
            longitude: coords['lon'],
            latitude: coords['lat'],
        });
        userMarker = new Graphic({
            geometry: pt,
            symbol: userMarkerSymbol,
        });

        view.goTo({
            target: pt,
            zoom: 10,
        },{
            duration:1000,
        })
        .then(addUserMarker);
    }

    jQuery._closePanel = function(){
        view.graphics.remove(fireMarker);

        $('#default-map-container').removeClass('panel-open');
        $('#info-panel').html('');
        console.log('off');
        view.goTo({
            zoom: 4,
        },{
            duration:1000,
        });
    }

    jQuery.removeLegend = function(){
        legend.visible = false;
        // view.ui.remove(legend);
    }

    jQuery.addLegend = function(){
        legend.visible = true;
        // view.ui.add(legend);
    }

    jQuery._toggleLegendHelp = function(){
        var $target = $('#legendDiv');
        if($target.hasClass('active')){
            // console.log('Going invisible');
            $target.removeClass('active');
            $target.addClass('in-active');
            jQuery.removeLegend();
            wfnm.saveSettings({type:'legendHelpToggle',val:'in-active'});
        }else{
            // console.log('Going visible');
            $target.addClass('active');
            $target.removeClass('in-active');
            wfnm.saveSettings({type:'legendHelpToggle',val:'active'});
            jQuery.addLegend();
        }
    }

    jQuery.reloadMap = function(){
        console.log('Map Reloaded');
        map.removeAll();
        getWfnmGeoJsonData()
        .then(createWfnmGraphics)
        .then(createWfnmLayer)
        // .then(createLegend)
        .otherwise(errback);
    }

    function addUserMarker(){
        view.graphics.add(userMarker);
    }

    function addFireMarker(){
        view.graphics.add(fireMarker);
    }
    // Executes if data retrevial was unsuccessful.
    function errback(error) {
        console.error("Chaining Error=> ", error);
    }
});


$(window).on('load', function(){
    // console.log('window loaded');
    //Check to see if toggle is offscreen or Mobile Device
    var width = window.innerWidth  || document.documentElement.clientWidth  || document.body.clientWidth;
    console.log(width);
        // $("#menu-toggle").removeAttr("style");
    if( !$("#menu-toggle").visible() || width <= 768){
        console.log('Toggle offscreen');
        $("#menu-toggle").removeAttr("style");
    }

    if(width > 768){
        $( "#menu-toggle" ).draggable({
            stop: function() {
                var el = $(this).attr('style');
                var data = {type:'toggleBtn',val:el};
                wfnm.saveSettings(data);
                // console.log(data);
            }
        });
    }
});

//
// $(document).ready(function() {
//     // console.log(yiiOptions);
//     // console.log('map.js');
//         /*Binding Actions*/
//     $(".dropdown-toggle").dropdown();
//
//     $('body').on('beforeSubmit', 'form#mapLayerForm', function () {
//         var form = $(this);
//         // return false if form still have some validation errors
//         if (form.find('.has-error').length) {
//             return false;
//         }
//         var data = {type:'mapLayers',val:form.serializeArray()};
//         wfnm.saveSettings(data,jQuery.reloadMap);
//         return false;
//     });
//
//
//
//     $('#default-map-container').on('click','#closePanel',function(e){
//         e.preventDefault();
//         jQuery._closePanel();
//     });
//
//     $(document).on('click','.legend-btn',function(e){
//         e.preventDefault();
//         wfnm.loadLegendDataPanel($(this).attr('href'));
//     });
//
//
//     $('#default-map-container').on('click','.layers-btn, #legendClose',function(e){
//         e.preventDefault();
//         console.log('layers-btn');
//         if($('#mapLegend').hasClass('fadeIn')){
//             console.log('visible');
//             $('#mapLegend').removeClass();
//             $('#mapLegend').addClass('animated fadeOut');
//         }else{
//             console.log('not visible');
//             $('#mapLegend').removeClass();
//             $('#mapLegend').addClass('animated fadeIn');
//         }
//     });
//     $('#default-map-container').on('click','#legendToogle',function(e){
//         e.preventDefault();
//         jQuery._toggleLegendHelp();
//     });
//
//
//
//     $('#default-map-container').on('click','.wfnm-btn',function(e){
//         e.preventDefault();
//         console.log('wfnm-btn');
//         var href = $(this).attr('href');
//         wfnm.loadMyFiresDataPanel(href);
//     });
//
//     $('#info-panel').on('click','.mylocation-list-selection',function(e){
//         e.preventDefault();
//         href = $('#firesnearme').attr('href');
//         var coords = {
//             address:$(this).data("address"),
//             lat:$(this).data("latitude"),
//             lng:$(this).data("longitude"),
//         };
//         wfnm.loadMyFiresDataPanel(href,coords);
//     });
//
//     $('#info-panel').on('keyup keypress keydown', function(e) {
//         var keyCode = e.keyCode || e.which;
//         if (keyCode === 13) {
//             e.preventDefault();
//             return false;
//         }
//     });
//
//     $(document).on('click','#checkboxOneInput', function () {
//         var valueEpilogi_1 = $(this).prop('checked');
//         if (valueEpilogi_1 == true || valueEpilogi_1 == "true" ) {
//             $('#map-btn-container').removeClass();
//             $('#map-btn-container').addClass('animated fadeIn');
//             $('#legend-bar-container').addClass('active');
//             console.log('on');
//         }else{
//             console.log('off');
//             $('#map-btn-container').removeClass();
//             $('#map-btn-container').addClass('animated fadeOut');
//             $('#legend-bar-container').removeClass('active');
//         }
//     });
//
//     $('#map-btn-container').on('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
//         console.log('done');
//         if($('#map-btn-container').hasClass('fadeOut')){
//             $('#map-btn-container').removeClass();
//         }
//     });
//     $('#mapLegend').on('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
//         console.log('done');
//         if($('#mapLegend').hasClass('fadeOut')){
//             $('#mapLegend').removeClass();
//         }
//     });
//     //Eliminate User location Dropdown conflict on Vulcan Maps Page
//     $("#info-panel").on("click", "[data-stopPropagation]", function(e) {
//         console.log('click');
//         e.stopPropagation();
//     });
//
//     $(document).on('click','.notifications',function(e){
//         var fid = $(this).data('key');
//         var aid = $(this).attr('notif');
//         jQuery.getFireInfo(fid,aid);
//     });
//     /*End Binding Actions*/
// });

// var app = new Vue({
//   el: '#app',
//   data: {
//     message: 'Hello Vue!'
//   }
// })
