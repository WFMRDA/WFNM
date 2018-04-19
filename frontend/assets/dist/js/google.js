$( document ).ready(function() {
    // console.log( "googlejs Loaded!" );
    $(document).on('click','.add-location',function(e){
        e.preventDefault();
        var loc = $('#autocomplete')
        console.log('clicked add-location');
        fillInAddress();
    });

});
// This example displays an address form, using the autocomplete feature
// of the Google Places API to help users fill in the information.

// This example requires the Places library. Include the libraries=places
// parameter when you first load the API. For example:
// <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

var placeSearch, autocomplete;
var componentForm = {
    street_number: 'short_name',
    route: 'long_name',
    locality: 'long_name',
    administrative_area_level_1: 'short_name',
    country: 'long_name',
    postal_code: 'short_name'
};

function initAutocomplete() {
    console.log('called');
    // Create the autocomplete object, restricting the search to geographical
    // location types.
    autocomplete = new google.maps.places.Autocomplete(
    /** @type {!HTMLInputElement} */(document.getElementById('addressInput')),
    {
        // types: ['geocode'],
        componentRestrictions: { country: "us" }
    });

    // When the user selects an address from the dropdown, populate the address
    // fields in the form.
    // autocomplete.addListener('place_changed', fillInAddress);
}

function fillInAddress() {
    // Get the place details from the autocomplete object.
    geocoder = new google.maps.Geocoder();
    var place = autocomplete.getPlace();
    var address = (place != undefined && place.formatted_address != undefined) ? place.formatted_address:$('#addressInput').val();
    // console.log(place);
    // console.log(address);
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
                console.log( data.params );
                $('#myLocationsTable-container').html(data.html);
                initAutocomplete();
            }, "json");
        }
    });

}

// [START region_geolocation]
// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
function geolocate() {
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
            autocomplete.setBounds(circle.getBounds());
        });
    }
}
// [END region_geolocation]
