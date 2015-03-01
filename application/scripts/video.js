(function () {

    var video = null;
    var seekbar = null;
    var playBtn = null;
    var muteBtn = null;
    var rewindBtn = null;
    var ffBtn = null;
    var fullscreenBtn = null;
    var volume = null;
    var time, duration, rate;
    var filename;
    var volumeValue;

    function init() {
        "use strict";
        filename = document.getElementById("filename");
        video = document.getElementsByTagName("video")[0];
        seekbar = document.getElementById("seekbar");
        volume = document.getElementById("volume");
        volumeValue = document.getElementById("volumeValue");
        time = document.getElementById("time");
        duration = document.getElementById("duration");
        rate = document.getElementById("rate");
        playBtn = document.getElementById("play");
        muteBtn = document.getElementById("mute");
        rewindBtn = document.getElementById("rewind");
        ffBtn = document.getElementById("ff");
        fullscreenBtn = document.getElementById("fullscreen");

        seekbar.addEventListener("change", seek, false);
        volume.addEventListener("change", changeVolume, false);
        playBtn.addEventListener("click", playPause, false);
        muteBtn.addEventListener("click", mute, false);
        rewindBtn.addEventListener("click", rewind, false);
        ffBtn.addEventListener("click", fastforward, false);
        fullscreenBtn.addEventListener("click", fullscreen, false);
        video.removeAttribute("controls");

        if (video.readyState > 0) {
            var durationText = document.getElementById("duration");
            var durationRounded = Math.round(video.duration);
            seekbar.setAttribute("max", durationRounded);
            durationText.innerHTML = formatTime(video.duration);
            playBtn.disabled = false;
            seekbar.value = 0;
        }

        video.addEventListener("ratechange", function (e) {
            rate.innerHTML = "rate: " + video.playbackRate;
        }, false);

        video.addEventListener("timeupdate", function (e) {
            time.innerHTML = formatTime(video.currentTime);
        }, false);


        video.addEventListener("play", function (e) {
            filename.innerHTML = "Now playing "+video.currentSrc;
            playBtn.innerHTML = "Pause";
        });

        video.addEventListener("pause", function (e) {
            playBtn.innerHTML = "Play";
        });
        video.addEventListener("ended", function (e) {
            playBtn.innerHTML = "Play again";
        });


    }

    function playPause() {
        if (ifPlaying()) {
            video.pause();
            playBtn.innerHTML = "Play";
        } else {
            video.play();
            playBtn.innerHTML = "Pause";
        }
    }

    function mute() {
        "use strict";
        if (!video.muted) {
            video.muted = true;
            muteBtn.innerHTML = "Unmute";
        } else {
            video.muted = false;
            muteBtn.innerHTML = "Mute";
        }
    }

    function changeVolume() {
        "use strict";
        video.volume = volume.value;
        volumeValue.innerHTML = +video.volume * 10;
    }

    function seek() {
        "use strict";
        video.currentTime = seekbar.value;
        time.innerHTML = formatTime(video.currentTime);

    }

    function fastforward() {
        "use strict";
        video.playbackRate = video.playbackRate + 2;
    }

    function rewind() {
        "use strict";
        video.playbackRate = video.playbackRate - 2;
    }

    function fullscreen() {
        "use strict";
        if (video.requestFullscreen) {
            video.requestFullscreen();
        } else if (video.webkitEnterFullscreen) {
            video.webkitEnterFullscreen();
        }
    }

    function ifPlaying() {
        "use strict";
        return  !(video.paused || video.ended);
    }

    function formatTime(seconds) {
        "use strict";
        seconds = Math.round(seconds);
        var minutes = Math.floor(seconds / 60);
        minutes = (minutes >= 10) ? minutes : "0" + minutes;
        seconds = (seconds >= 10) ? seconds : "0" + seconds;
        return minutes + ":" + seconds;
    }

    window.addEventListener("load", init, false);

})();