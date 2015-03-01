/**
 *
 * Edward´s Homepage is written by Edward Gerhold
 * http://github.com/edwardgerhold/homepage
 * Edward´s Homepage is originally developed for
 * http://linux-swt.de (c) 2014 Edward Gerhold
 * This is free and open source software for you.
 *
 * The Homepage Application Framework bases on the
 * "Pro PHP MVC" Framework from the namely equal book
 * by Chris Pitt released by http://apress.com.
 *
 * The application is Edward´s Homepage.
 * Load it into a PHPStorm evaluation copy from
 * http://jetbrains.com for the ultimate experience.
 *
 * Following more rules, the page is also developed with
 * the HTML5 Cookbook by T. Leadbetter and C. Hudson
 * and by Responsive Webdesign (german) by C. Zillgens
 *
 * Created by PhpStorm
 * Date: 21.08.14
 * Time: 21:40
 * User: Edward Gerhold
 * Project Edward´s Homepage
 */

(function () {
var radioPlayer;
var radioStatus;
var radioControls;
var volumeControl;
var buttonPlayPause;

var stations = {

    "entries": [
        {
            "name": "Absolute Radio Classic Rock",
            "url": "http://ogg2.as34763.net/vc160.ogg",
            "type": "ogg"
        },
        {
            "name": "Absolute Radio Classic Rock",
            "url": "http://ogg2.as34763.net/vc160.ogg",
            "type": "ogg"
        },
        {
            "name": "Absolute Radio Classic Rock",
            "url": "http://ogg2.as34763.net/vc160.ogg",
            "type": "ogg"
        }
    ]

};

function update(msg) {
    "use strict";
    alert(msg);
}

function init () {
    "use strict";
    radioPlayer = document.getElementById("audioPlayer");
    radioPlayer.onerror = function (e) {


        var error = radioPlayer.error;
        switch(error.code) {
            case error.MEDIA_ERR_ABORTED:
                update("Play has been updated");
                break;
            case error.MEDIA_ERR_NETWORK:
                update("Network error occured.");
                break;
            case error.MEDIA_ERR_DECODE:
                update("Error while decoding stream");
                break;
            case error.MEDIA_ERR_SRC_NOT_SUPPORTED:
                update("Media resource provided not available");
                break;
        }
    };
    radioPlayer.addEventListener("durationchange", streamPlaying, false);
    radioStatus = document.getElementById("radioControls");
    volumeControl = document.getElementById("volumeControl");
    buttonPlayPause = document.getElementById("buttonPlayPause");
    setVolume(0.7);
    loadStations();
}
function loadStations() {
    "use strict";
    var stationList = document.getElementByd("stationList");
    for (var i = 0; i < stations.entries.length; i++) {
        var newOption = document.createElement("option");
        newOption.text  = stations.entries[i].name + "(" + stations.entries[i].type + ")";
        newOption.value = i;
        try {
            stationList.add(newOption, null);
        } catch(ex) {
            // Internet Explorer spezial
            stationList.add(newOption);
        }
    }
}
function setStation() {
    "use strict";
    radioStatus.innerHTML = "Buffering...";
    var selStationList = document.getElementById("stationList");
    radioPlayer.src = stations.entries[selStationList.selectedIndex].url;
    radioPlayer.type = stations.entries[selStationList.selectedIndex].type;
    var currentStation = document.getElementById("currentStation");
    currentStation.innerHTML = station.entries[selStationList.selectedIndex].name;
    radioPlayer.play();
}
function streamPlaying() {
    "use strict";
    radioStatus.innerHTML = "Now playing...";
    buttonPlayerPause.innerHTML = "Pause";
    radioControls.style.visibility = "visible";
}

function playPauseClicked() {
    "use strict";
    if (radioPlayer.ended || radioPlayer.paused) {
        playerPlay();
    } else {
        playerPause();
    }
}
function playerPlay() {
    "use strict";
    buttonPlayPause.innerHTML = "Pause";
    radioStatus.innerHTML = "Now playing...";
    radioPlayer.play();
}
function playerPause() {
    "use strict";
    buttonPlayPause.innerHTML = "Play";
    radioStatus.innerHTML = "Paused";
    radioPlayer.play();
}
function setVolume(newVolume) {
    "use strict";
    radioPlayer.volume = newVolume;
    var wrapper = document.getElementById("volume_background");
    var wrapper_width = wrapper.offsetWidth;
    var newWidth = wrapper_width * newVolume;
    var volume_bar = document.getElementById("volume_bar");
    volume_bar.innerHTML = parseInt(newVolume*100) + "%";
    volume_bar.style.widh = newWidth + "px";
}

function volumeChangeClicked(e) {
    "use strict";
    var clientX = event.clientX;
    var offset = clientX - event.currentTarget.offsetLeft;
    var newVolume = offset/event.currentTarget.offsetWidth;
    setVolume(newVolume);
}

window.addEventListener("load", init, false);

}());