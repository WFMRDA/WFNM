!function(t){var i=t(window);t.fn.visible=function(t,e,o){if(!(this.length<1)){var r=this.length>1?this.eq(0):this,n=r.get(0),f=i.width(),h=i.height(),o=o?o:"both",l=e===!0?n.offsetWidth*n.offsetHeight:!0;if("function"==typeof n.getBoundingClientRect){var g=n.getBoundingClientRect(),u=g.top>=0&&g.top<h,s=g.bottom>0&&g.bottom<=h,c=g.left>=0&&g.left<f,a=g.right>0&&g.right<=f,v=t?u||s:u&&s,b=t?c||a:c&&a;if("both"===o)return l&&v&&b;if("vertical"===o)return l&&v;if("horizontal"===o)return l&&b}else{var d=i.scrollTop(),p=d+h,w=i.scrollLeft(),m=w+f,y=r.offset(),z=y.top,B=z+r.height(),C=y.left,R=C+r.width(),j=t===!0?B:z,q=t===!0?z:B,H=t===!0?R:C,L=t===!0?C:R;if("both"===o)return!!l&&p>=q&&j>=d&&m>=L&&H>=w;if("vertical"===o)return!!l&&p>=q&&j>=d;if("horizontal"===o)return!!l&&m>=L&&H>=w}}}}(jQuery);
require(
[
    "vue",
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
],function(Vue,esriConfig,Map,MapView,BasemapToggle,Locate,Track,Point,Graphic,SimpleMarkerSymbol,Field,Legend,esriRequest,FeatureLayer,ImageryLayer,SimpleRenderer,UniqueValueRenderer,SimpleFillSymbol,PictureMarkerSymbol,arrayUtils,Zoom,dom,on) {
    // esriConfig.request.corsEnabledServers.push("wildfire.cr.usgs.gov");
    var map;
    var view;
    var userMarker;
    var fireMarker;
    var geoMac;
    var layerInfos = [];
    var wfnmLayer;
    var legend;
    /**********************
    * MAP INIT
    **********************/
    map = new Map({
        basemap: "streets",
        ground: "world-elevation"
    });
    view = new MapView({
        container: "mapDiv",  // Reference to the scene div created in step 5
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

    Vue.component('button-counter', {
        data: function () {
            return {
                count: 0
            }
        },
        template: '<button class="btn btn-success" @click="count++">You clicked me {{ count }} times.</button>'
    });

    view.when(function(){
        var button = new Vue({
            el: '#app',
            data: {

            }
        });
        view.ui.add(button.$el,'top-right');
    })

});
