var nextSlide;
var prevSlide;
var setImgBorder;
(function () {
    "use strict";

    var slideUrl = "html5/history?slide=";

    var minSlide = 1;
    var maxSlide = 5;
    var currentSlide = 1;
    var currentTitle = "Slide 1";
    var borderOn = 0;
    var slideNote = "";

    var stateObj = {
        slide: currentSlide,
        border: borderOn,
        note: slideNote
    };
    history.replaceState(stateObj, currentTitle, slideUrl + currentSlide);
    setTimeout(showSlide);

    window.addEventListener("popstate", function (e) {
        document.getElementById("stateInfo").innerHTML = "location: " + document.location + "<br>state: " + JSON.stringify(event.state);
        currentSlide = event.state.slide;
        borderOn = event.state.border;
        slideNote = event.state.note;
        showSlide();
    }, false);

    nextSlide = function nextSlide() {
        if (typeof history.pushState !== "undefined") {
            if (currentSlide < maxSlide) {
                slideNote = document.getElementById("txtNote").value;
                var currentStateObj = {
                    slide: currentSlide,
                    border: borderOn,
                    note: slideNote
                };
                history.replaceState(currentStateObj, "Slide " + currentSlide + " " + slideNote, slideUrl + currentSlide);
                ++currentSlide;
                borderOn = 0;
                slideNote = "";
                var nextStateObj = {
                    slide: currentSlide,
                    border: borderOn,
                    note: slideNote
                };
                history.pushState(nextStateObj, "Slide " + currentSlide, slideUrl + currentSlide);
                showSlide();
            } else {
                alert("History API is not available in this browser.");
            }
        }
    };

    prevSlide = function prevSlide() {
        if (currentSlide > minSlide) {
            history.back();
            showSlide();
        }
    };

    function showSlide() {
        // document.getElementById("imgSlide").src = "images/slide" + currentSlide + ".jpg";

        var slideText = document.querySelector("script[type='slideshow/slide"+currentSlide+"']").textContent;
        document.getElementById("currentSlide").innerHTML = slideText;
        document.getElementById("slideInfo").innerHTML = "Slide " + currentSlide;
        document.title = "Slide " + currentSlide;
        if (borderOn == 1) {
            // document.getElementById("imgSlide").style.border = "0.3125em solid black";
            document.getElementById("currentSlide").style.border = "0.3125em solid black";
            document.getElementById("chkBorder").checked = 1;
        }
    }

    setImgBorder = function setImgBorder() {
        if (document.getElementById("chkBorder").checked === 1) {
            //document.getElementById("imgSlide").style.border = "0.3125em solid black";
            document.getElementById("currentSlide").style.border = "0.3125em solid black";
            borderOn = 1;
        } else {
            // document.getElementById("imgSlide").style.border = "";
            document.getElementById("currentSlide").style.border = "";
            borderOn = 0;
        }
    };


}());