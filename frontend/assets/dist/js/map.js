var player = undefined;
function empty (mixedVar) {
  //  discuss at: http://locutus.io/php/empty/
  // original by: Philippe Baumann
  //    input by: Onno Marsman (https://twitter.com/onnomarsman)
  //    input by: LH
  //    input by: Stoyan Kyosev (http://www.svest.org/)
  // bugfixed by: Kevin van Zonneveld (http://kvz.io)
  // improved by: Onno Marsman (https://twitter.com/onnomarsman)
  // improved by: Francesco
  // improved by: Marc Jansen
  // improved by: Rafał Kukawski (http://blog.kukawski.pl)
  //   example 1: empty(null)
  //   returns 1: true
  //   example 2: empty(undefined)
  //   returns 2: true
  //   example 3: empty([])
  //   returns 3: true
  //   example 4: empty({})
  //   returns 4: true
  //   example 5: empty({'aFunc' : function () { alert('humpty'); } })
  //   returns 5: false

  var undef
  var key
  var i
  var len
  var emptyValues = [undef, null, false, 0, '', '0']

  for (i = 0, len = emptyValues.length; i < len; i++) {
    if (mixedVar === emptyValues[i]) {
      return true
    }
  }

  if (typeof mixedVar === 'object') {
    for (key in mixedVar) {
      if (mixedVar.hasOwnProperty(key)) {
        return false
      }
    }
    return true
  }

  return false
}
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

var vueModel = new Vue({
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
        sitReportTable:undefined,
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
        alerts:[],
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
        fid:undefined,
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
        incidentsLayer:{
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
            /*{
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
            },*/
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
    this.plLevel = yiiOptions.plLevel;
    this.dataSet = yiiOptions.wfnm;
    this.activeIncidentLayers = yiiOptions.layers.incidentLayers;
    this.myFires = yiiOptions.myFires;
    this.alerts = yiiOptions.alerts;
    this.myLocations = yiiOptions.myLocations;
    this.sitReport = yiiOptions.sitReport;
    this.fireDb = yiiOptions.fireDb;
    this.fid =  yiiOptions.fid;
    console.log(yiiOptions.homeUrl);
    },
    mounted() {
        var vm = this;
        this.initMap();
        var mapLayers = (yiiOptions.layers.mapLayers == undefined)?[]:yiiOptions.layers.mapLayers;
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
        this.initLayers();
        this.loading = false;
        $('[data-toggle="tooltip"]').tooltip();
        this.$nextTick(function() {
            vm.initAutocomplete();
            vm.sitReportTable = $('#sitReportTable').DataTable({
                order: [[ 3, "desc" ]],
                deferRender:true,
                dom: '<"pull-left" l>rtip',
                columnDefs: [
                    {
                        "targets": [0 ],
                        "visible": false,
                        "searchable": true
                    },
                ]
            });
            if($('#terms-of-service').length){
                $('#terms-of-service').modal('show');
                $('#terms-of-service').on('hidden.bs.modal', function (e) {
                    vm.saveDisclaimerSeen();
                });
            }
            let initMap = (vm.fid.length == 0 || vm.fid == undefined)?false:true;
            if(initMap){
                vm.getFireInfo(vm.fid,'WF');
                history.pushState(null,null, yiiOptions.homeUrl);
            }

        });
    },





    computed:{
        myAlerts(){
            return this.alerts.dataSet;
        },
        series(){
            return this.fireDb;
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
            // console.log(this.fireInfo);
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
            var commentsUrl = 'https://www.wildfiresnearme.wfmrda.com/' + this.fireInfo.uniqueFireIdentifier,
            commentsUrl = commentsUrl.replace(/-/g, "");
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
                commentsUrl: commentsUrl,
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
            // console.log('browserLoc',this.browserLocation);
            var locationList = this.prepend(this.browserLocation,this.clone(this.myLocations));
            return locationList;
        }
    },








    watch:{
        sitReportFilter(updated,old){
            this.searchSitRepTable();
            // if(updated != old && !empty(old) ){
            //     // console.log(updated);
            //     this.searchSitRepTable();
            // }
        },
        sitReportType(updated,old){
            if(updated != old && !empty(old) ){
                // this.sitReportTable.order( [[ 3, 'desc' ]] ).draw( );
                this.$nextTick(function() {
                    // this.sitReportTable.destroy();
                    this.sitReportTable = $('#sitReportTable').DataTable({
                        order: [[ 3, "desc" ]],
                        destroy: true,
                        deferRender:true,
                        dom: '<"pull-left" l>rtip',
                        columnDefs: [
                            {
                                "targets": [0 ],
                                "visible": false,
                                "searchable": true
                            },
                        ]
                    });
                });
            }
        },
        incidentLayers(updated,old){
            // console.log(updated,old);
            if(updated != old && !empty(old) ){
                // console.log(updated,old);
                this.storeMapList();
                this.setIncidentLayer();
            }
        },
        wfnmFilter(updated,old){
            this.searchWFNMTable();
        },
        userLocation(updated,old){
            // console.log('userLoc',updated,old);
            //     console.log('updated',updated);
            //         console.log('old',old,empty(old));
            // if(updated != old && Object.getOwnPropertyNames(old).length ){
            var isFirst = empty(old);
            if(updated != old && !isFirst ){
                this.wfnmTab ='index';
                self = this;
                var obj = updated.split('*|*');
                var coords = {
                    address: obj[0],
                    lat:obj[1],
                    lng:obj[2]
                }
                this.goToLocation(coords);
            }else if(!isFirst){
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
        saveDisclaimerSeen(){
            $.post( yiiOptions.homeUrl+"/system-rest/store-disclaimer" ,function( data ) {
                console.log(data);
            });
        },
        markAllNotificationSeen(){
            var vm = this;
            $.post( yiiOptions.homeUrl+"/map-rest/mark-all-notification-seen" ,function( data ) {
                console.log(data);
                vm.alerts = data;
            });
        },
        gotoAlert(alert){
            var vm = this;
            $.post(yiiOptions.homeUrl+ "/map-rest/get-alert" ,{id:alert.id},function( data ) {
                console.log(data);
                // var fire = data.fireInfo;
                vm.getFireInfo(data.fireInfo,'WF')

                vm.alerts = data.alerts;
            });

        },
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
            var options = (this.wfnmFilter.length) ? this.wfnmFilter.join('|'):'XXXXX';
            // console.log(options);
            this.firesNearMeTable.columns(3).search(options,true).draw();
        },
        searchSitRepTable(){
            var options = (this.sitReportFilter.length) ? this.sitReportFilter.join('|'):'XXXXX';
            console.log(this.sitReportFilter.length,options);
            this.sitReportTable.columns(0).search(options,true).draw();
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
        goToLocation(coords,panMap,initMap){
            if(panMap == undefined){
                panMap = true;
            }
            // console.log(coords,panMap);
            this.loading = true;
            self = this;
            var lat = coords.lat;
            var lng = coords.lng;
            // console.log(lat,lng);
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
            if(initMap !== true){
                this.panMapToCenter('loc',panMap);
            }
            $.post( "/map-rest/fires-near-me",coords, function( data ) {
                // console.log(data);
                if(self.firesNearMeTable !== undefined){
                    self.firesNearMeTable.destroy();
                }
                self.firesNearMe = data.fireInfo;
                self.localGaccPlLevel = {
                    name: self.getGaccName(data.gacc.gacc),
                    class: self.getPLCSS(data.localGaccPlLevel),
                    gaccUrl: self.getGaccUrl(data.gacc.gacc)
                };
                // console.log(self.firesNearMe);

                self.$nextTick(function(){
                    self.firesNearMeTable = $('#firesnearmeTable').DataTable({
                        "order": [[ 1, "asc" ]],
                        "deferRender":true,
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
                if(panMap){
                    self.activePane = 'wfnm';
                }
                self.loading = false;
            }, "json");
            // self.activePane = 'wfnm';
        },
        getUserLocation(initMap){
            var vm = this;
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
                                    vm.browserLocation = {
                                        address: address,
                                        latitude:pos.coords.latitude,
                                        longitude:pos.coords.longitude,
                                        default:true,
                                    };


                                    vm.userLocation =  vm.formatLocation(vm.browserLocation);

                                    var coords = {
                                        address: address,
                                        lat:pos.coords.latitude,
                                        lng:pos.coords.longitude,
                                    }
                                    vm.goToLocation(coords,false,initMap);
                                }else{
                                    var address = pos.coords.latitude + ', ' +pos.coords.longitude;
                                    vm.browserLocation = {
                                        address: address,
                                        latitude:pos.coords.latitude,
                                        longitude:pos.coords.longitude,
                                        default:true,
                                    };
                                    var coords = {
                                        address: address,
                                        lat:pos.coords.latitude,
                                        lng:pos.coords.longitude,
                                    }
                                    vm.goToLocation(coords,false,initMap);
                                    vm.userLocation =  vm.formatLocation(vm.browserLocation);
                                }
                            });

                        },
                        function(err) {
                            // vm.setUserLocation(coords);
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
                var coords = {
                    address: defaultLoc.address,
                    lat: defaultLoc.latitude,
                    lng: defaultLoc.longitude,
                }
                this.goToLocation(coords,false,initMap);
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
        activatePane(str,forceOpen){
            if(forceOpen){
                this.activePane = str;
                if(str == 'myLocations'){
                    this.initAutocomplete();
                }
                if(str == 'alerts'){
                    this.seenAlerts();
                }
            }else{
                if(this.activePane != str){
                    this.loading = true;
                    this.activePane = str;
                    if(str == 'myLocations'){
                        this.initAutocomplete();
                    }
                    if(str == 'alerts'){
                        this.seenAlerts();
                    }
                }else{
                    this.activePane = '';
                }
            }
        },
        clearLoading(){
            console.log('clear loading');
            this.loading = false;
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
                date = date * 1000;
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
                case 'Incidents':
                    this.incidentsLayer = layer;
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
            // console.log('incident layer');
            var vm = this;
            var layer = vm.layers[vm.incidentLayerId];
            if(vm.map.hasLayer(layer.instance) ){
                vm.map.removeLayer(layer.instance);
            }
            layer.instance = L.geoJSON(vm.dataSet,{
                filter: function(feature, layer) {
                    return ((feature.properties.fireType in vm.incidentLayers) && (feature.properties.fireClass in vm.incidentLayers));
                },
                pointToLayer: function (feature, latlng) {
                    var iconUrl = vm.getFireIcon(feature.properties.fireType);
                    if(iconUrl){
                        var sizeClass = (feature.properties.fireType == 'CX')? '5':feature.properties.fireClass;
                        var fireIcon = L.icon({
                            iconUrl: iconUrl,
                            className: 'sizeClass-'+sizeClass,
                        });
                        return L.marker(latlng,{
                            icon : fireIcon
                        }).on('click',vm.getInfo);
                    }
                }
            });
            layer.instance.addTo(vm.map);
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
        panMapToCenter(marker,panMap){
            if(marker == 'loc'){
                marker = this.locMarker;
            }else{
                marker = this.fireMarker;
            }
            if(panMap == undefined){
                panMap = true;
            }
            // console.log(marker,panMap);
            self = this;
            if(panMap){
                var offset = this.getMarkerOffset();
                var center = this.map.project(marker.getLatLng(),this.defaultZoomIn);
                center = new L.point(center.x + offset,center.y);
                var target = this.map.unproject(center,this.defaultZoomIn);
            }else{
                var target = marker.getLatLng();
            }
            this.map.setView(target, this.defaultZoomIn,{animate:true});
        },
        clearFireMarker(){
            console.log('close');
            this.map.removeLayer(this.fireMarker);
            this.fireInfo = {};
            this.loading =false;
        },
        getFireInfo(obj,type){
            // console.log(obj);
            // var marker = (type == 'CX')?'map_complex':'active_fire';
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
            var vm = this;
            console.log(id);
            $.post(yiiOptions.homeUrl+'/map-rest/fire-info',{
                fid:id,
            },function( data ) {
                console.log(data);
                vm.fireInfo = data.fireInfo;
                vm.fireInfo.localPrepLevel = '';
                vm.fireInfo.localPrepLevel = data.localGaccPlLevel;
                vm.isFollowing = data.isFollowing;
                if(vm.showFireInfo){
                    vm.panMapToCenter();
                }else{
                    vm.showFireInfo = true;
                    vm.activePane = '';
                }
                vm.loading = false;

                vm.$nextTick(function() {
                    if (typeof FB !== 'undefined') {
                        FB.XFBML.parse(document.getElementById('comment-tab'));
                    }
                });
            }, "json" );
        },
        unFollowFire(fid){
            console.log('unfollow',fid);
            self = this;
            self.loading = true;
            $.post( yiiOptions.homeUrl+"/map-rest/unfollow-fire", {fid:fid}, function( data ) {
                self.myFires = data.data;
                self.loading = false;
            }, "json");
        },
        toggleFireFollow(fid){
            this.loading = true;
            console.log('toggle',fid);
            self = this;
            if(this.isFollowing){
                $.post( yiiOptions.homeUrl+"/map-rest/unfollow-fire", {fid:fid}, function( data ) {
                    self.myFires = data.data;
                    self.isFollowing = data.status;
                    console.log(data.status);
                    self.loading = false;
                }, "json");
            }else if(!this.isFollowing){
                $.post(yiiOptions.homeUrl+ "/map-rest/follow-fire", {fid:fid}, function( data ) {
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
        seenAlerts(){
            var vm = this;
            console.log('Seen Alerts');
            $.post(yiiOptions.homeUrl+'/map-rest/check-alerts',function( data ) {
                // console.log('my-fires',data);
                // self.myFires = data;
                vm.alerts = data
            }, "json" );
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

            let initMap = (this.fid.length == 0 || this.fid == undefined)?false:true;
            this.getUserLocation(initMap);

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
        removeURLParameter(url, parameter) {
            //prefer to use l.search if you have a location/link object
            var urlparts= url.split('?');
            if (urlparts.length>=2) {

                var prefix= encodeURIComponent(parameter)+'=';
                var pars= urlparts[1].split(/[&;]/g);

                //reverse iteration as may be destructive
                for (var i= pars.length; i-- > 0;) {
                    //idiom for string.startsWith
                    if (pars[i].lastIndexOf(prefix, 0) !== -1) {
                        pars.splice(i, 1);
                    }
                }

                url= urlparts[0] + (pars.length > 0 ? '?' + pars.join('&') : "");
                return url;
            } else {
                return url;
            }
        },
        GetURLParameter(sParam){
            var sPageURL = window.location.search.substring(1);
            var sURLVariables = sPageURL.split('&');
            for (var i = 0; i < sURLVariables.length; i++)
            {
                var sParameterName = sURLVariables[i].split('=');
                if (sParameterName[0] == sParam)
                {
                    return sParameterName[1];
                }
            }
        },
    },
});
var vueHeader = new Vue({
    el:'#top-notifications-li',
    computed:{
        badge(){
            // console.log('badge',vueModel.alerts.badge);
            return vueModel.alerts.badge;
        },
        unreadTotal(){
            // console.log('unreadTotal',vueModel.alerts.unreadTotal);
            return vueModel.alerts.unreadTotal;
        }
    },
});
