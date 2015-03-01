/**
 * Created by root on 14.08.14.
 */
(function () {

    var audio = null;
    var seekbar = null;
    var playBtn = null;
    var muteBtn = null;
    var rewindBtn = null;
    var ffBtn = null;
    var fullscreenBtn = null;
    var volume = null;
    var time, duration, rate;

    function init() {
        "use strict";
        audio = document.getElementsByTagName("audio")[0];
        seekbar = document.getElementById("seekbar");
        volume = document.getElementById("volume");
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
        audio.removeAttribute("controls");

        if (audio.readyState > 0) {
            var durationText = document.getElementById("duration");
            var durationRounded = Math.round(audio.duration);
            seekbar.setAttribute("max", durationRounded);
            playBtn.disabled = false;
            seekbar.value = 0;
        }

        audio.addEventListener("ratechange", function (e) {
            rate.innerHTML = "rate: " + audio.playbackRate;
        }, false);

        audio.addEventListener("play", function (e) {
            playBtn.innerHTML = "Pause";
        });
        audio.addEventListener("pause", function (e) {
            playBtn.innerHTML = "Play";
        });
        audio.addEventListener("ended", function (e) {
            playBtn.innerHTML = "Play again";
        });

    }
    function playPause() {
        if (ifPlaying()) {
            audio.pause();
            playBtn.innerHTML = "Play";
        }else {
            audio.play();
            playBtn.innerHTML = "Pause";
        }
    }
    function mute() {
        "use strict";
        if (!audio.muted) {
            audio.muted = true;
            muteBtn.innerHTML = "Unmute";
        } else {
            audio.muted = false;
            muteBtn.innerHTML = "Mute";
        }
    }
    function changeVolume() {
        "use strict";
        audio.volume = volume.value;
    }
    function seek() {
        "use strict";
        audio.currentTime = seekbar.value;
    }
    function fastforward() {
        "use strict";
        audio.playbackRate = audio.playbackRate + 2;
    }
    function rewind() {
        "use strict";
        audio.playbackRate = audio.playbackRate - 2;
    }
    function fullscreen() {
        "use strict";
        if (audio.requestFullscreen) {
            audio.requestFullscreen();
        } else if (audio.webkitEnterFullscreen) {
            audio.webkitEnterFullscreen();
        }
    }
    function ifPlaying() {
        "use strict";
        return  !(audio.paused || audio.ended);
    }
    function formatTime(seconds) {
        "use strict";
        seconds = Math.round(seconds);
        var minutes = Math.floor(seconds / 60);
        minutes = (minutes >= 10) ? minutes : "0"+minutes;
        seconds = (seconds >= 10) ? seconds : "0"+seconds;
        return minutes + ":" + seconds;
    }

    window.addEventListener("load",init, false);

})();