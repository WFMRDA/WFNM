$( document ).ready(function() {
    // //console.log( "ready!" );
    $(document).on('click','.follow-fire',function(e){
        e.preventDefault();
        //console.log('clicked Follow');
        var fid = $(this).data('id');
        wfnm.followFire(fid);
    });
    $(document).on('click','.unfollow-fire',function(e){
        e.preventDefault();
        //console.log('clicked UnFollow');
        var fid = $(this).data('id');
        wfnm.unFollowFire(fid);
    });
    $(document).on('click','.myLocationsEdit,.myLocationsCancelEdit',function(e){
        e.preventDefault();
        $('#myLocationsTable-Container').toggleClass('editable')
        //console.log('clicked Edit Table');
    });

});

window.wfnm = (function ($) {
    var panelSelector = '#info-panel';
    var mapContainer = '#default-map-container';
    var coords;
    var pub = {
        panelSelector,
        mapContainer,
        coords,
        loadDataPanel : function(html){
            // //console.log(this.panelSelector);
            this.panelSelector.html(html);
            this.mapContainer.addClass('panel-open');
            //console.log('on');
        },
        loadLegendDataPanel: function(href){
            $.get(href,function( data ) {
                pub.loadDataPanel(data.html);
            }, "json" );
        },
        loadMyFiresDataPanel : function(href,coords){
            var location = (coords == undefined)? pub.coords : coords;
            var payload;
            if(location){
                var payload = {coords:location};
            }
            $.post(href, payload, function( data ) {
                //console.log( data ); // John
                pub.loadDataPanel(data.html);
                //Set Map Location
                //console.log(data);
                // //console.log(data.lng);v
                if(data.coords != undefined){
                    jQuery.panMapTo(data.coords);
                }
            }, "json");
        },
        saveSettings: function(data,cb){
            // //console.log(data);
            $.post( "/system-rest/store-settings", data,function(data){
                if(data.success && cb != undefined && (typeof cb === "function")){
                    // //console.log('success');
                    cb();
                }
            }, "json");
        },
        followFire: function(fid){
            $.post( "/map-rest/follow-fire", {fid:fid}, function( data ) {
                //console.log( data ); // John
                $('#btn-311').html(data.html);
            }, "json");
        },
        unFollowFire: function(fid){
            $.post( "/map-rest/unfollow-fire", {fid:fid}, function( data ) {
                //console.log( data ); // John
                $('#btn-311').html(data.html);
            }, "json");
        },
        goToUserMyLocation: function(model){
            var address = model.data("address");
            var place_id = model.data("place_id");
            var latitude = model.data("latitude");
            var longitude = model.data("longitude");
            //console.log(address);
            //console.log(place_id);
            //console.log(latitude);
            //console.log(longitude);
            if($('#myLocationsTable-Container').hasClass('editable')){
                $('<div></div>').dialog({
                    modal: true,
                    title: "Confirm",
                    open: function () {
                       var markup = 'This is not reversable. If you delete this location you will receive no alerts about fires in the vicinity of this location';
                       $(this).html(markup);
                    },
                    close: function () {
                       $(this).remove();
                    },
                    buttons: {
                        Cancel: function () {
                            $(this).dialog("close");
                        },
                        Delete: function() {
                            $.post( "/map-rest/unfollow-location", {pid:place_id}, function( data ) {
                                //console.log( data.params );
                                $('#myLocationsTable-container').html(data.html);
                            }, "json");
                            $( this ).dialog( "close" );
                            // location.reload();
                        }
                    }
                }); //end confirm dialog
                //Delete location
                //console.log('deleting => ' + 'place_id');
            }else{
                //Go to Location
                //console.log('going to => ' + 'place_id');
                //console.log(address);
                //console.log(place_id);
                //console.log(latitude);
                //console.log(longitude);
                href = $('#firesnearme').attr('href');
                var coords = {
                    address:address,
                    lat:latitude,
                    lng:longitude,
                };
                pub.loadMyFiresDataPanel(href,coords);
            }

        },
        initModule: function (module) {
            if (module.isActive !== undefined && !module.isActive) {
                return;
            }
            if ($.isFunction(module.init)) {
                module.init();
            }
            $.each(module, function () {
                if ($.isPlainObject(this)) {
                    pub.initModule(this);
                }
            });
        },
        init: function () {
            // Call any Init functions here.
            setSelectors();
        },
    },
    setLocation = function(){
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
                            var coords = {
                                address:address,
                                lat:pos.coords.latitude,
                                lng:pos.coords.longitude
                            };
                            setUserLocation(coords);
                        }else{
                            var coords = {
                                lat:pos.coords.latitude,
                                lng:pos.coords.longitude
                            };
                            setUserLocation(coords);
                        }
                    });

                },
                function(err) {
                    var coords  = false;
                    setUserLocation(coords);
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
            var coords = false;
            setUserLocation(coords);
        }
    },
    setUserLocation = function(coords){
        pub.coords = coords;
        // //console.log(pub.coords);
    },
    setSelectors = function(){
        pub.panelSelector = $(panelSelector);
        pub.mapContainer = $(mapContainer);
        setLocation();
    }
    return pub;
})(window.jQuery);
window.jQuery(function () {
    window.wfnm.initModule(window.wfnm);
});
