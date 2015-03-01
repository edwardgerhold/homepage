// From the HTML5 Developers Cookbook

function init() {
    "use strict";
    var btnMapMe = document.getElementById("mapMe");
    btnMapMe.addEventListener("click", mapMe, false)
}

function geoSuccess(position)  {
    "use strict";
    var myLocationDiv = document.getElementById("myLocation");
    var posLat = position.coords.latitude;
    var posLng = position.coords.longitude;
    var posAccuracy = position.coords.accuracy;
    myLocationDiv.innerHTML = "Lat: "+posLat+", Lng: "+posLng+"<br>Accuracy: "+posAccuracy;
    var myLatLng = new google.maps.LatLng(posLat, posLng);
    var myOptions = {
        zoom: 14,
        center: myLatLng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var map = new google.maps.Map(document.getElementById("mapCanvas"), myOptions);
    var marker = new google.maps.Marker({
        position: myLatLng,
        map: map
    });
    var infoText;
    infoText = posLat + ", " +posLng + "<br>Accuracy: "+ posAccuracy;
    if (position.address) {
        infoText += "<br> "+position.address.city + ", " + position.address.region;
    }
    var infoWindow = new google.maps.InfoWindow();
    infoWindow.setContent(infoText);
    infoWindow.open(map, marker);
}

function geoErrorHandler(error) {
    "use strict";
    var errMessage = "ERROR: ";
    switch(error.code) {
        case error.PERMISSION_DENIED:
            errMessage += "User did not share geolocation data.";
            break;
        case error.POSITION_UNAVAILABLE:
            errMessage += "Could not detect current position";
            break;
        case error.TIMEOUT:
            errMessage += "Retrieving position timed out.";
            break;
        default:
            errMessage += "Unknown error.";
            break;
    }
    document.getElementById("myLocation").innerHTML = errMessage;
}

function mapMe() {
    "use strict";
    var myLocationDiv = document.getElementById("myLocation");
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(geoSuccess, geoErrorHandler);
        myLocationDiv.innerHTML = "Retrieving your location...";
    } else {
        myLocationDiv.innerHTML = "Geolocation API not supported";
    }
}

window.addEventListener("load", init, false);


