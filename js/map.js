var map = new OpenLayers.Map('map');
var osm = new OpenLayers.Layer.OSM();
		map.addLayer(osm);
        map.zoomToMaxExtent();
		//map.setCenter(new OpenLayers.LonLat(73.1667, 33.6667), 6);
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else {
        x.innerHTML = "Geolocation is not supported by this browser.";
    }
}
function showPosition(position) {
    var latlon = position.coords.latitude + "," + position.coords.longitude;
//    var img_url = "http://maps.googleapis.com/maps/api/staticmap?center="
//    +latlon+"&zoom=14&size=400x300&sensor=false";
    document.getElementById("map").innerHTML = "<img src='"+map+"'>";
}
function showError(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            x.innerHTML = "User denied the request for Geolocation."
            break;
        case error.POSITION_UNAVAILABLE:
            x.innerHTML = "Location information is unavailable."
            break;
        case error.TIMEOUT:
            x.innerHTML = "The request to get user location timed out."
            break;
        case error.UNKNOWN_ERROR:
            x.innerHTML = "An unknown error occurred."
            break;
    }
}
		
      