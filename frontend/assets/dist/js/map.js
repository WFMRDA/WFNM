var player = undefined;
if (!Array.isArray) {
    Array.isArray = function(arg) {
        return Object.prototype.toString.call(arg) === '[object Array]';
    };
}
function isObject(arg){
	return Object.prototype.toString.call(arg) === '[object Object]';
}
String.prototype.replaceAll = function(strReplace, strWith) {
    // See http://stackoverflow.com/a/3561711/556609
    var esc = strReplace.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
    var reg = new RegExp(esc, 'ig');
    return this.replace(reg, strWith);
};
function strrpos (haystack, needle, offset) {
	// github: https://github.com/kvz/locutus/blob/master/src/php/strings/strrpos.js
	//  discuss at: http://locutus.io/php/strrpos/
	// original by: Kevin van Zonneveld (http://kvz.io)
	// bugfixed by: Onno Marsman (https://twitter.com/onnomarsman)
	// bugfixed by: Brett Zamir (http://brett-zamir.me)
	//    input by: saulius
	//   example 1: strrpos('Kevin van Zonneveld', 'e')
	//   returns 1: 16
	//   example 2: strrpos('somepage.com', '.', false)
	//   returns 2: 8
	//   example 3: strrpos('baa', 'a', 3)
	//   returns 3: false
	//   example 4: strrpos('baa', 'a', 2)
	//   returns 4: 2

	var i = -1
	if (offset) {
		i = (haystack + '')
		  	.slice(offset)
		  	.lastIndexOf(needle) // strrpos' offset indicates starting point of range till end,
			// while lastIndexOf's optional 2nd argument indicates ending point of range from the beginning
		if (i !== -1) {
		  	i += offset
		}
	} else {
		i = (haystack + '')
		  	.lastIndexOf(needle)
	}
	return i >= 0 ? i : false
}
function getValue(array, key, dVal)
{
    if (Array.isArray(key)) {
        var lastKey = key.pop();
        var keyLength = key.length;
        for (var i = 0; i < keyLength; i++) {
            var keyPart = key[i];
            var array = getValue(array,keyPart);
        };
        var key = lastKey;
    }
    var defaultValue = (dVal == undefined)? null:dVal;
    var pos = strrpos(key, '.');
    if (pos !== false) {
        var newKey = key.substr(0, pos);
        array = getValue(array, newKey, defaultValue);
        var key = key.substr(pos + 1);
    }
    return ((Array.isArray(array) || isObject(array)) && (array[key] !== undefined && array[key] !== null)) ? array[key] : defaultValue;
}
new Vue({
    el: '#app',
    data: {
        loading:true,
        map: null,
        // showLayers: false,
        showIncidentLayers: false,
        dataSet:{},
        activePane: '',
        incidentLayerId:undefined,
        firesNearMeTable:undefined,
        activeIncidentLayers: [],
        wfnmFilter:['A','B','C','D','E','CX'],
        sitReportFilter:['A','B','C','D','E','CX'],
        showFireInfo: false,
        fireInfo:{},
        fireMarker:undefined,
        locMarker:undefined,
        isFollowing:undefined,
        activeFireInfoTab:'index',
        myFires:[],
        myLocations:[],
        sitReport: [],
        fireDb:[],
        plLevel: '',
        autocomplete:undefined,
        currentLocation:undefined,
        location:'',
        wfnmTab:'index',
        userLocation: '',
        browserLocation:'',
        sitReportType:'dailyAcres',
        localGaccPlLevel:{
            name:'',
            class: '',
            gaccUrl: ''
        },
        firesNearMe:[],
        ercLayer:{
            active:false,
        },
        biLayer:{
            active:false,
        },
        sfwpLayer:{
            active:false,
        },
        weatherLayer:{
            active:false,
        },
        weatherPlayer:undefined,
        radarTime:undefined,
        ticker:[
            // 'Message 1',
            // 'Message 2',
            // 'Message 3',
        ],
        defaultZoom:6,
        defaultZoomIn:10,
        sitReportTypeOptions:[
            {
                val:'dailyAcres',
                label: 'Acres Burned'
            },
            {
                val:'totalIncidentPersonnel',
                label: 'Total Incident Personnel'
            },
            {
                val:'percentContained',
                label: 'Percent Contained'
            },
            {
                val:'estimatedCostToDate',
                label: 'Cost To Date'
            }
        ],
        layers: [
            {
                id: '',
                name: 'Weather',
                active: false,
                type: 'nonTiledLayer',
                src: 'https://new.nowcoast.noaa.gov/arcgis/services/nowcoast/radar_meteo_imagery_nexrad_time/MapServer/WMSServer',
                attributes: {
                    layers: '1',
                    format: 'image/png',
                    transparent: true,
                    opacity: 0.8,
                },
                instance:undefined,
            },
            {
                id: '',
                name: 'VIIRS I-Band 375m',
                active: false,
                type: 'wms',
                src: [
                    'https://fsapps.nwcg.gov/afm/cgi-bin/mapserv.exe?map=conus_viirs_iband.map&',
                    'https://fsapps.nwcg.gov/afm/cgi-bin/mapserv.exe?map=alaska_viirs_iband.map&'
                ],
                attributes: {
                    layers: 'Last 24 hour fire detections',
                    format: 'image/png',
                    transparent: true,
                    // attribution: "Weather data © 2012 IEM Nexrad"
                },
                instance:undefined,
            },
            {
                id: '',
                name: 'VIIRS-AF 750m',
                active: false,
                type: 'wms',
                src: [
                    'https://fsapps.nwcg.gov/afm/cgi-bin/mapserv.exe?map=conus_viirs-af.map&',
                    'https://fsapps.nwcg.gov/afm/cgi-bin/mapserv.exe?map=alaska_viirs-af.map&'
                ],
                attributes: {
                    layers: 'Last 24 hour fire detections',
                    format: 'image/png',
                    transparent: true,
                    // attribution: "Weather data © 2012 IEM Nexrad"
                },
                instance:undefined,
            },
            {
                id: '',
                name: 'MODIS 1km',
                active: false,
                type: 'wms',
                src: [
                    'https://fsapps.nwcg.gov/afm/cgi-bin/mapserv.exe?map=conus.map&',
                    'https://fsapps.nwcg.gov/afm/cgi-bin/mapserv.exe?map=alaska.map&'
                ],
                attributes: {
                    layers: 'Last 24 hour fire detections',
                    format: 'image/png',
                    transparent: true,
                    // attribution: "Weather data © 2012 IEM Nexrad"
                },
                instance:undefined,
            },
            {
                id: '',
                name: 'Fire Perimeters',
                active: false,
                type: 'wms',
                src: [
                    'https://wildfire.cr.usgs.gov/ArcGIS/services/geomac_dyn/MapServer/WMSServer?',
                    'https://wildfire.cr.usgs.gov/ArcGIS/services/geomacAK_dyn/MapServer/WMSServer?'
                ],
                attributes: {
                    layers: 'Current Fire Perimeters',
                    format: 'image/png',
                    transparent: true,
                    // attribution: "Weather data © 2012 IEM Nexrad"
                },
                instance:undefined,
            },
            {
                id: '',
                name: 'ERC',
                active: false,
                type: 'wms',
                src: 'https://www.wfas.net/cgi-bin/mapserv?map=/data/maps/wfas_time_index_dyn.map',
                attributes: {
                    layers: 'ercpercnew',
                    format: 'image/png',
                    transparent: true,
                    crs: L.CRS.EPSG4326,
                    attribution: 'WFAS'
                },
                instance:undefined,
            },
            {
                id: '',
                name: 'Burn Index',
                active: true,
                type: 'wms',
                src: 'https://www.wfas.net/cgi-bin/mapserv?map=/data/maps/wfas_time_index_dyn.map',
                attributes: {
                    layers: 'biperc',
                    format: 'image/png',
                    transparent: true,
                    crs: L.CRS.EPSG4326,
                    attribution: 'WFAS'
                },
                instance:undefined,
            },
            {
                id: '',
                name: 'Severe Fire Weather Potential',
                active: true,
                type: 'wms',
                src: 'https://www.wfas.net/cgi-bin/mapserv?map=/data/maps/wfas_time_index_dyn.map',
                attributes: {
                    layers: 'fbx',
                    format: 'image/png',
                    transparent: true,
                    crs: L.CRS.EPSG4326,
                    attribution: 'WFAS'
                },
                instance:undefined,
            },
            {
                id:'',
                name: 'Incidents',
                active: false,
                type: 'incidents',
                src: yiiOptions.homeUrl+'/map-rest/fires',
                instance:undefined,
            },

        ],
    },
    created(){
        $.post(yiiOptions.homeUrl+'/map-rest/my-fires',function( data ) {
            // console.log('my-fires',data);
            self.myFires = data;
        }, "json" );
        $.post(yiiOptions.homeUrl+'/map-rest/alerts',function( data ) {
            // console.log('alerts',data);
        }, "json" );
        $.post(yiiOptions.homeUrl+'/map-rest/my-locations',function( data ) {
            // console.log('my-locations',data);
            self.myLocations = data;
        }, "json" );
        $.post(yiiOptions.homeUrl+'/map-rest/sit-rep',function( data ) {
            // console.log('sit-report',data);
            self.sitReport = data.sitreport;
            self.fireDb = data.fireDb;
        }, "json" );
    },
    mounted() {
        var vm = this;
        this.initMap();
        this.plLevel = yiiOptions.plLevel;
        $.post(yiiOptions.homeUrl+'/map-rest/fires',function( data ) {
            // console.log(this.layers,vm.layers);
            // console.log(data.layers);
            vm.dataSet = data.wfnm;
            // console.log(vm.dataSet);
            vm.activeIncidentLayers = data.layers.incidentLayers;
            var mapLayers = (data.layers.mapLayers == undefined)?[]:data.layers.mapLayers;
            var obj = {};
            var length = mapLayers.length;
            for (var i = 0; i < length; i++) {
                obj[mapLayers[i]] = 1;
            }
            var length = vm.layers.length;
            for (var i = 0; i < length; i++) {
                var layer = vm.layers[i];
                layer.active = (layer.name in obj);
            }
            vm.initLayers();
            vm.loading = false;
        }, "json" );
        $('[data-toggle="tooltip"]').tooltip();
        this.$nextTick(function() {
            this.initAutocomplete();
        });
    },





    computed:{
        series(){
            return this.fireDb;
       },
        //sitReportType
        sitReportData(){
            this.loading = true;
            var vm = this;
            // var newDb = this.clone(this.fireDb);
            var newDb = [];
            var dbL = this.fireDb.length;
            for (var i = 0; i < dbL; i++) {
                var fire = this.fireDb[i];
                if(this.sitReportFilter.indexOf(fire.fireClassId) != -1){
                    newDb.push(fire);
                }

            }
            var sorted =  newDb.sort(function(a, b){
                return b[vm.sitReportType] - a[vm.sitReportType];
            });
            // console.log(this.fireDb);
            // console.log(sorted);
            this.loading = false;
            return sorted;
        },
        fireHasInfo(){
            return this.fireInfo.projectedIncidentActivity72Plus == null &&
            this.fireInfo.projectedIncidentActivity72 == null &&
            this.fireInfo.projectedIncidentActivity48 == null &&
            this.fireInfo.projectedIncidentActivity24 == null &&
            this.fireInfo.plannedActions == null &&
            this.fireInfo.weatherConcerns == null &&
            this.fireInfo.significantEvents == null &&
            this.fireInfo.ics209Remarks == null &&
            this.fireInfo.incidentShortDescription == null &&
            this.fireInfo.fireBehavior == null &&
            this.fireInfo.summaryFuelModel == null &&
            this.fireInfo.fireBehaviorDescription == null &&
            this.incidentInfo.fuelModel == '' &&
            this.incidentInfo.fireBehavior == '';
        },
        incidentInfo(){
            var gaccUrl = this.getGaccUrl(this.fireInfo.gacc);
            var pl = this.getPLCSS(this.fireInfo.localPrepLevel);
            var landOwnershipArray = [];
            var fireBehavior = [];
            var fuelModel = [];
            var pooLandownerKind = getValue(this.fireInfo,'pooLandownerKind',null);
            var pooLandownerCategory = getValue(this.fireInfo,'pooLandownerCategory',null);
            var pooJurisdictionalAgency = getValue(this.fireInfo,'pooJurisdictionalAgency',null);

            if(pooLandownerKind !== null){
                landOwnershipArray.push(pooLandownerKind);
            }
            if(pooLandownerCategory !== null){
                landOwnershipArray.push(pooLandownerCategory);
            }else if (pooJurisdictionalAgency !== null) {
                landOwnershipArray.push(pooJurisdictionalAgency);
            }

            if(this.fireInfo.fireBehaviorGeneral1 !== null){
                fireBehavior.push(this.fireInfo.fireBehaviorGeneral1);
            }
            if(this.fireInfo.fireBehaviorGeneral2 !== null){
                fireBehavior.push(this.fireInfo.fireBehaviorGeneral2);
            }
            if(this.fireInfo.fireBehaviorGeneral3 !== null){
                fireBehavior.push(this.fireInfo.fireBehaviorGeneral3);
            }

            if(this.fireInfo.primaryFuelModel !== null){
                fuelModel.push(this.fireInfo.primaryFuelModel);
            }
            if(this.fireInfo.secondaryFuelModel !== null){
                fuelModel.push(this.fireInfo.secondaryFuelModel);
            }
            if(this.fireInfo.additionalFuelModel !== null){
                fuelModel.push(this.fireInfo.additionalFuelModel);
            }
            var acres = this.formatAcres(getValue(this.fireInfo,'dailyAcres','Unknown'));


            // console.log(landOwnershipArray);
            var fireObj = {
                complexity: getValue(this.fireInfo,'fireMgmtComplexity','Not Specified'),
                fireCause: getValue(this.fireInfo,'fireCause','Unknown'),
                acres: acres, //.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                fireStatusUrl: this.getFireIcon(this.fireInfo.fireClassId),
                gaccUrl: gaccUrl,
                localGaccPlLevel:{},
                toggleFollowFireClass: {
                    "btn-danger": this.isFollowing,
                    "btn-success": !this.isFollowing
                },
                location: this.precisionRound(this.fireInfo.pooLatitude,2)+' , '+this.precisionRound(this.fireInfo.pooLongitude,2),
                gaccName: this.getGaccName(this.fireInfo.gacc),
                commentsUrl: 'https://www.wildfiresnearme.wfmrda.com/wfnm/index#' + this.fireInfo.uniqueFireIdentifier,
                countyState: getValue(this.fireInfo,'pooCity','') + ' ' + getValue(this.fireInfo,'pooCounty','')+' '+getValue(this.fireInfo,'pooState',''),
                landOwnership: landOwnershipArray.join(' '),
                modifiedBySystem: getValue(this.fireInfo,'modifiedBySystem','').toUpperCase(),
                fireBehavior: fireBehavior.join(', '),
                fuelModel: fuelModel.join(', '),
                complexIncidentName:getValue(this.fireInfo,'complex.incidentName','').toString().replaceAll("Complex", ""),

            }
            fireObj.localGaccPlLevel[pl] = true;
            return fireObj;
        },
        paneActive(){
            return (this.showFireInfo || this.infoPaneActive);
        },
        infoPaneActive(){
            return (this.activePane != 'layers' && this.activePane != '');
        },
        incidentLayers(){
            var obj = {};
            var length = this.activeIncidentLayers.length;
            for (var i = 0; i < length; i++) {
                obj[this.activeIncidentLayers[i]] = 1;
            }
            return obj;
        },
        mapLayers(){
            var mapLayers = [];
            var length = this.layers.length;
            for (var i = 0; i < length; i++) {
                var layer = this.layers[i];
                if(layer.active){
                    mapLayers.push(layer.name);
                }
            }
            return mapLayers;
        },
        myLocationList(){
            var locationList = this.prepend(this.browserLocation,this.clone(this.myLocations));
            return locationList;
        }
    },








    watch:{
        incidentLayers(updated,old){
            // console.log(updated,old);
            if(updated != old && Object.getOwnPropertyNames(old).length ){
                // console.log(updated,old);
                this.storeMapList();
                this.setIncidentLayer();
            }
        },
        wfnmFilter(updated,old){
            if(updated != old && Object.getOwnPropertyNames(old).length ){
                this.searchWFNMTable();
            }
        },
        userLocation(updated,old){
            if(updated != old && Object.getOwnPropertyNames(old).length ){
                this.wfnmTab ='index';
                self = this;
                var obj = updated.split('*|*');
                var coords = {
                    address: obj[0],
                    lat:obj[1],
                    lng:obj[2]
                }
                this.goToLocation(coords);
            }else{
                this.wfnmTab ='index';
                this.activePane = 'wfnm';
            }
        }
        /*activePane(updated,old){
            console.log('updated=>'+updated,'old=>'+old);
            if(updated == 'myFires'){

            }
            // if(updated == 'myFires')
        }*/
    },











    methods: {
        prepend(value, array) {
            var newArray = array.slice();
            newArray.unshift(value);
            return newArray;
        },
        clone(obj){
            return JSON.parse(JSON.stringify(obj));
        },
        formatSitReportInfo(val){
            if(this.sitReportType == 'dailyAcres' || this.sitReportType == 'totalIncidentPersonnel' ){
                val = this.formatAcres(val);
            }
            if (this.sitReportType == 'estimatedCostToDate'){
                if(val == undefined || val == null){
                    val = 'Unknown';
                }else{
                    val = parseFloat(val).toFixed(2);
                    val = '$'+ this.formatAcres(val);
                }
            }
            return val;
        },
        getGaccUrl(key){
            // console.log(key);
            return (key == null)? '': 'https://gacc.nifc.gov/'+ key.toLowerCase();
        },
        getPLCSS(level){
            return 'pl-sprite pl' + level;
        },
        searchWFNMTable(){
            var options = this.wfnmFilter.join('|');
            // console.log(options);
            this.firesNearMeTable.columns(3).search(options,true).draw();
        },
        initAutocomplete(){
            // console.log('called Auto Complete');
            this.autocomplete = new google.maps.places.Autocomplete((document.getElementById('addressInput')),
            {
                // types: ['geocode'],
                componentRestrictions: { country: "us" }
            });
        },
        // [START region_geolocation]
        // Bias the autocomplete object to the user's geographical location,
        // as supplied by the browser's 'navigator.geolocation' object.
        geolocate() {
            self = this;
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var geolocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    var circle = new google.maps.Circle({
                        center: geolocation,
                        radius: position.coords.accuracy
                    });
                    self.autocomplete.setBounds(circle.getBounds());
                });
            }
        },
        formatLocation(loc){
            return loc.address+'*|*'+loc.latitude+'*|*'+loc.longitude;
        },
        setLoc(loc){
            var obj = this.formatLocation(loc);
            if(obj == this.userLocation){
                this.wfnmTab = 'index';
                this.activePane = 'wfnm';
            }else{
                this.userLocation = obj;
            }
        },
        goToLocation(coords){
            this.loading = true;
            self = this;
            var lat = coords.lat;
            var lng = coords.lng;
            // console.log(lat,lng,address);
            if(this.map.hasLayer(this.locMarker) ){
                this.map.removeLayer(this.locMarker);
            }
            var size = 30;
            var locIcon = L.icon({
                iconUrl: yiiOptions.mediaUrl+"/user_location.png",
                iconSize: [size, size],
            });
            this.locMarker = L.marker([lat,lng], {icon: locIcon});
            this.locMarker.addTo(this.map);
            this.panMapToCenter('loc');
            $.post( "map-rest/fires-near-me",coords, function( data ) {
                console.log(data);
                if(self.firesNearMeTable !== undefined){
                    self.firesNearMeTable.destroy();
                }
                self.firesNearMe = data.fireInfo;
                self.localGaccPlLevel = {
                    name: self.getGaccName(data.gacc.gacc),
                    class: self.getPLCSS(data.localGaccPlLevel),
                    gaccUrl: self.getGaccUrl(data.gacc.gacc)
                };
                // console.log(self.localGaccPlLevel);

                self.$nextTick(function(){
                    self.firesNearMeTable = $('#firesnearmeTable').DataTable({
                        "order": [[ 1, "asc" ]],
                        "columnDefs": [
                            {
                                "targets": [ 3 ],
                                "visible": false,
                                "searchable": true
                            },
                        ]
                    });
                    self.searchWFNMTable();
                });
                self.activePane = 'wfnm';
                self.loading = false;
            }, "json");
            // self.activePane = 'wfnm';
        },
        getUserLocation(){
            self = this;
            var defaultLoc = yiiOptions.defaultLocation;
            // console.log(defaultLoc);
            if(defaultLoc == undefined){
                if (navigator.geolocation) {
                    //Navigation Enabled. Do Something
                    navigator.geolocation.getCurrentPosition(
                        function(pos) {
                            var geocoder = new google.maps.Geocoder();
                            var addressGPS = pos.coords.latitude+','+pos.coords.longitude;
                            geocoder.geocode( { 'address':addressGPS}, function(results, status) {
                                if (status == google.maps.GeocoderStatus.OK) {
                                    str = results[0].geometry.location.toString();
                                    str = str.replace(/\(|\)/g,'');
                                    gpscoords = str.split(",");
                                    address = results[0].formatted_address;
                                    address = address.replace(', USA','');
                                    //console.log(address);
                                    self.browserLocation = {
                                        address: address,
                                        latitude:pos.coords.latitude,
                                        longitude:pos.coords.longitude,
                                        default:true,
                                    };


                                    self.userLocation =  self.formatLocation(self.browserLocation);
                                }else{
                                    var address = pos.coords.latitude + ', ' +pos.coords.longitude;
                                    self.browserLocation = {
                                        address: address,
                                        latitude:pos.coords.latitude,
                                        longitude:pos.coords.longitude,
                                        default:true,
                                    };

                                    self.userLocation =  self.formatLocation(self.browserLocation);
                                }
                            });

                        },
                        function(err) {
                            // self.setUserLocation(coords);
                            console.log('User Location No Found Or Enabled');
                            //console.log(err);
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 5000,
                            maximumAge: 0
                        }
                    );
                }else{
                    //Navigation Not Enabled. Do Something Else
                    console.log('User Location No Found Or Enabled');
                }
            }else{
                this.browserLocation = {
                    address: defaultLoc.address,
                    latitude: defaultLoc.latitude,
                    longitude: defaultLoc.longitude,
                    default:true,
                };
                this.userLocation =  this.formatLocation(this.browserLocation);
            }
        },
        addLocation(){
            this.loading = true;
            self = this;
            geocoder = new google.maps.Geocoder();
            var place = this.autocomplete.getPlace();
            var address = (place != undefined && place.formatted_address != undefined) ? place.formatted_address:$('#addressInput').val();
            geocoder.geocode( { 'address':address}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    str = results[0].geometry.location.toString();
                    str = str.replace(/\(|\)/g,'');
                    gpscoords = str.split(",");
                    address = results[0].formatted_address;
                    var place = {
                        address : address.replace(', USA',''),
                        place_id : results[0].place_id,
                        latitude : gpscoords[0],
                        longitude : gpscoords[1],
                    };
                    console.log(place);
                    // save place;
                    $.post( "/map-rest/add-location", place, function( data ) {
                        self.myLocations = data;
                        $("#addressInput").val('');
                        self.initAutocomplete();
                        self.loading = false;
                    }, "json");
                }
            });
        },
        formatAcres(acres){
            if(acres == null || acres == undefined){
                acres = 'Unknown';
            }
            if(acres != ' Unknown'){
                acres = acres.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }
            return acres;
        },
        activatePane(str){
            this.activePane = (this.activePane != str)?str:'';
        },
        getGaccName(name){
            switch (name) {
                case 'NICC':
                    var desc =  'National (NICC)';
                    break;
                case 'AKCC':
                    var desc =  'Alaska (AICC)';
                    break;
                case 'EACC':
                    var desc =  'Eastern Area (EACC)';
                    break;
                case 'GBCC':
                    var desc =  'Great Basin (GBCC)';
                    break;
                case 'ONCC':
                    var desc =  'Northern California (ONCC)';
                    break;
                case 'NRCC':
                    var desc =  'Northern Rockies (NRCC)';
                    break;
                case 'NWCC':
                    var desc =  'Northwest (NWCC)';
                    break;
                case 'RMCC':
                    var desc =  'Rocky Mountain (RMCC)';
                    break;
                case 'SACC':
                    var desc =  'Southern Area (SACC)';
                    break;
                case 'OSCC':
                    var desc =  'Southern California (SWCC)';
                    break;
                case 'SWCC':
                    var desc =  'Southwest (SWCC)';
                    break;
                default:
                    var desc = '';
            }
            return desc;
        },
        formatDateTime(date,timestamp){
            if(date == undefined || date == null){
                return '';
            }
            var tz = moment.tz.guess();
            if(timestamp){
                var format = moment.tz(date,tz).format("dddd, MMMM Do YYYY, h:mm:ss a");
            }else{
                var format = moment(date, "YYYY-MM-DD HH:mm:ss").tz(tz).format("dddd, MMMM Do YYYY, h:mm:ss a");
            }
            return format;
            var tz = new Date().getTimezoneOffset();
            // console.log('tz',tz);
            return moment(date, "YYYY-MM-DD HH:mm:ss").utcOffset(tz).format("dddd, MMMM Do YYYY, h:mm:ss a");
        },
        formatDate(date){
            if(date == undefined || date == null){
                return '';
            }
            var tz = moment.tz.guess();
            return moment(date, "YYYY-MM-DD").tz(tz).format("dddd, MMMM Do YYYY");
        },
        precisionRound(number, precision) {
            var factor = Math.pow(10, precision);
            return Math.round(number * factor) / factor;
        },
        toggleLayer(id){
            var layer = this.layers[id];
            if(layer.name == 'Incidents'){
                this.showIncidentLayers = !this.showIncidentLayers;
                layer.active = this.showIncidentLayers;
                return false;
            }
            if (layer.active){
                this.map.removeLayer(layer.instance);
                layer.active = false;
                if(layer.name == 'Weather'){
                    this.weatherPlayer.stop();
                }
            }else{
                layer.instance.addTo(this.map);
                layer.active = true;
                if(layer.name == 'Weather'){
                    this.weatherPlayer.start();
                }
            }
            this.storeMapList();
        },
        initLayers() {
            var length = this.layers.length;
            for (var i = 0; i < length; i++) {
                var layer = this.layers[i];
                layer.id = i;
                this.assignLegendKey(layer);
                if(Array.isArray (layer.src)){
                    var layersArray = [];
                    var srcLength = layer.src.length;
                    for (var j = 0; j < srcLength; j++) {
                        if(layer.type == 'wms'){
                            layersArray.push(L.tileLayer[layer.type](layer.src[j],layer.attributes));
                        }
                    }
                    layer.instance = L.layerGroup(layersArray);
                    if(layer.active){
                        layer.instance.addTo(this.map);
                    }
                }else{
                    if(layer.type == 'wms'){
                        layer.instance = L.tileLayer[layer.type](layer.src,layer.attributes);
                        if(layer.active){
                            layer.instance.addTo(this.map);
                        }
                    }else if(layer.type == 'incidents'){
                        layer.active = this.showIncidentLayers;
                        this.incidentLayerId = layer.id
                        this.setIncidentLayer();
                    }else if(layer.type == 'nonTiledLayer'){
                        var nonTiledLayer = L.nonTiledLayer.wms(layer.src, layer.attributes);
                        layer.instance = L.timeDimension.layer.wms(nonTiledLayer, {
                            wmsVersion: '1.3.0',
                            updateTimeDimension: false,
                            updateTimeDimensionMode: "replace"
                        });
                        this.weatherPlayer = this.map.timeDimensionControl._player;
                        if(layer.active){
                            layer.instance.addTo(this.map);
                            this.weatherPlayer.start();
                        }
                        // player = this.map.timeDimensionControl._player;
                    }
                }
            }
        },
        assignLegendKey(layer){
            switch (layer.name) {
                case 'ERC':
                    this.ercLayer = layer;
                    break;
                case 'Burn Index':
                    this.biLayer = layer;
                    break;
                case 'Severe Fire Weather Potential':
                    this.sfwpLayer = layer;
                    break;
                case 'Weather':
                    this.weatherLayer = layer;
                    break;
            }
        },
        storeMapList(){
            console.log('storing map settings');
            wfnm.saveSettings({
                type:'mapLayers',
                val: {mapLayers:this.mapLayers,
                incidentLayers:this.activeIncidentLayers}
            });
        },
        setIncidentLayer(){
            console.log('incident layer');
            self = this;
            var layer = self.layers[self.incidentLayerId];
            if(self.map.hasLayer(layer.instance) ){
                self.map.removeLayer(layer.instance);
            }
            layer.instance = L.geoJSON(self.dataSet,{
                filter: function(feature, layer) {
                    return ((feature.properties.fireType in self.incidentLayers) && (feature.properties.fireClass in self.incidentLayers));
                },
                pointToLayer: function (feature, latlng) {
                    var iconUrl = self.getFireIcon(feature.properties.fireType);
                    if(iconUrl){
                        var sizeClass = (feature.properties.fireType == 'CX')? '5':feature.properties.fireClass;
                        var fireIcon = L.icon({
                            iconUrl: iconUrl,
                            className: 'sizeClass-'+sizeClass,
                        });
                        return L.marker(latlng,{
                            icon : fireIcon
                        }).on('click',self.getInfo);
                    }
                }
            });
            layer.instance.addTo(self.map);
        },
        getMarkerOffset(){
            var docWidth = $(document).width();
            var offset = 0;
            if(docWidth > 768){
                var panelWidth = $('#fireInfo-pane-container').width();
                var docCenter = Math.round(docWidth/2);
                var mapWidth = docWidth - panelWidth;
                var mapCenter = Math.round(mapWidth/2);
                var techOffset = docCenter - mapCenter;
                offset = (techOffset < 0)?0:techOffset;
            }
            // console.log('getMarkerOffset',panelWidth,offset);
            return offset;
        },
        panMapToCenter(marker){
            if(marker == 'loc'){
                marker = this.locMarker;
            }else{
                marker = this.fireMarker;
            }
            // console.log(marker);
            self = this;
            var offset = this.getMarkerOffset();
            var center = this.map.project(marker.getLatLng(),this.defaultZoomIn);
            center = new L.point(center.x + offset,center.y);
            var target = this.map.unproject(center,this.defaultZoomIn);
            this.map.setView(target, this.defaultZoomIn,{animate:true});
        },
        clearFireMarker(){
            console.log('close');
            this.map.removeLayer(this.fireMarker);
            this.fireInfo = {};
        },
        getFireInfo(obj,type){
            var marker = (type == 'CX')?'map_complex':'active_fire';
            this.placeFireInfo(obj.irwinID,obj.pooLatitude,obj.pooLongitude,'active_fire');
        },
        getInfo(e){
            this.placeFireInfo(e.target.feature.properties.Id,e.latlng.lat,e.latlng.lng,'active_fire');
        },
        removeLocation(loc){
            $.confirm({
                title: '<div class="text-center">Delete <br><span class="locTitle">'+loc.address+'!</span></div>',
                content: 'This is not reversable. If you delete this location you will receive no alerts about fires in the vicinity of this location',
                draggable: false,
                buttons: {
                    delete: {
                        text: '<i class="fa fa-trash" aria-hidden="true"> Delete',
                        btnClass: 'btn-danger',
                        keys: ['enter', 'shift'],
                        action: function(){
                            self.loading = true;
                            $.post( "/map-rest/unfollow-location", {pid:loc.place_id}, function( data ) {
                                self.myLocations = data;
                                self.loading = false;
                            }, "json");
                        }
                    },
                    cancel: function () {
                        //close
                    }
                }
            });
        },
        placeFireInfo(id,lat,lng,marker){
            this.activeFireInfoTab = 'index',
            this.loading = true;
            // return false;
            if(this.map.hasLayer(this.fireMarker) ){
                this.map.removeLayer(this.fireMarker);
            }
            var size = 30;
            var fireIcon = L.icon({
                iconUrl: yiiOptions.mediaUrl+"/"+marker+".png",
                iconSize: [size, size],
                iconAnchor: [10, 10], // point of the icon which will correspond to marker's location
            });

            // var latLng = L.latLng([lat,lng]);
            // var point = this.map.latLngToContainerPoint(latLng);
            // var newPoint = L.point([point.x + 7, point.y + 4]);
            // var newLatLng = this.map.containerPointToLatLng(newPoint);

            // this.fireMarker = L.marker(newLatLng, {icon: fireIcon});
            this.fireMarker = L.marker([lat,lng], {icon: fireIcon});
            this.fireMarker.addTo(this.map);
            this.loadFireInfo(id);
        },
        loadFireInfo(id){
            self = this;
            console.log(id);
            $.post(yiiOptions.homeUrl+'/map-rest/fire-info',{
                fid:id,
            },function( data ) {
                console.log(data);
                FB.XFBML.parse(document.getElementById('comment-tab'));
                self.fireInfo = data.fireInfo;
                self.fireInfo.localPrepLevel = '';
                self.fireInfo.localPrepLevel = data.localGaccPlLevel;
                self.isFollowing = data.isFollowing;
                if(self.showFireInfo){
                    self.panMapToCenter();
                }else{
                    self.showFireInfo = true;
                    self.activePane = '';
                }
                self.loading = false;
            }, "json" );
        },
        unFollowFire(fid){
            console.log('unfollow',fid);
            self = this;
            self.loading = true;
            $.post( "/map-rest/unfollow-fire", {fid:fid}, function( data ) {
                self.myFires = data.data;
                self.loading = false;
            }, "json");
        },
        toggleFireFollow(fid){
            this.loading = true;
            console.log('toggle',fid);
            self = this;
            if(this.isFollowing){
                $.post( "/map-rest/unfollow-fire", {fid:fid}, function( data ) {
                    self.myFires = data.data;
                    self.isFollowing = data.status;
                    console.log(data.status);
                    self.loading = false;
                }, "json");
            }else if(!this.isFollowing){
                $.post( "/map-rest/follow-fire", {fid:fid}, function( data ) {
                    self.myFires = data.data;
                    self.isFollowing = data.status;
                    console.log(data.status);
                    self.loading = false;
                }, "json");
            }
        },
        getFireIcon(type){
            switch (type) {
                case 'A':
                    var icon = yiiOptions.mediaUrl+"/map_new_fire.png";
                    break;
                case 'B':
                    var icon = yiiOptions.mediaUrl+"/map_emerging_fire.png";
                    break;
                case 'C':
                    var icon = yiiOptions.mediaUrl+"/map_contained_fire.png";
                    break;
                case 'D':
                    var icon = yiiOptions.mediaUrl+"/map_controlled_fire.png";
                    break;
                case 'E':
                    var icon = yiiOptions.mediaUrl+"/map_active_fire.png";
                    break;
                case 'CX':
                    var icon = yiiOptions.mediaUrl+"/map_complex.png";
                    break;
                case 'F':
                    var icon = yiiOptions.mediaUrl+"/map_out_fire.png";
                    break;
                default:
                    var icon = false;
            }
            return icon;
        },
        initMap() {
            var endDate = new Date();
            endDate.setUTCSeconds(0, 0);
            var minutes = endDate.getUTCMinutes();
            endDate.setUTCMinutes(minutes - (minutes % 5));
            this.map = L.map('map', {
                center: [34.470493, -109.053168],
                zoom: this.defaultZoom,
                attributionControl: false,
                inertia: true,
                inertiaDeceleration: 2000,
                timeDimension: true,
                timeDimensionControl: true,
                timeDimensionControlOptions: {
                    autoPlay: false,
                    playerOptions: {
                        buffer: 10,
                        transitionTime: 500,
                        loop: true,
                        startOver:true,
                    },
                    speedSlider: false
                },
                timeDimensionOptions:{
                    timeInterval: "PT1H/" + endDate.toISOString(),
                    period: "PT5M", // or  "PT10M"...
                    currentTime: endDate
                },
            });

            var baseLayer =  L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}');
            baseLayer.addTo(this.map);
            self = this;
            this.map.timeDimension.on('timeload', function(data) {
                var tz = moment.tz.guess();
                self.radarTime = moment.tz(data.time,tz).format("MM/DD/YYYY HH:mm:z");
                // console.log(data.time,self.radarTime);
            });
            // this.getUserLocation();
        },
        splitOnCapitolLetter(string){
            if(string == undefined || string == null){
                return '';
            }
            return string.split(/(?=[A-Z])/).join(" ");
        },
        capitalizeFirstLetter(string) {
            if(string == undefined || string == null){
                return '';
            }
            return string.charAt(0).toUpperCase() + string.slice(1);
        },
    },
});
