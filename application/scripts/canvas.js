/**
 * Created by root on 01.09.14.
 */
(function () {

    var stopBtn = document.getElementById("stopBtn");

    var canvas = document.getElementById("canvas");
    var context = canvas.getContext("2d");
    var width, height;

    var interval = 250;
    var timer;

    var pause = false;
    var part = 2;
    var parts;

    var radius;
    var percent = 0;

    function drawPart(part) {
        "use strict";
        context.clearRect(0, 0, width*2, height*2);
        context.save();
        context.beginPath();
        context.arc.apply(context, parts[part]);
        context.strokeColor = "#000";
        // context.strokeStyle =
        context.lineWidth = 4;
        context.shadowOffsetX = 2;
        context.shadowOffsetY = 3;
        context.shadowColor = "green";
        context.shadowBlur = 0.3;
        context.stroke();
        context.lineWidth = 5;
        context.shadowOffsetX = 6;
        context.shadowOffsetY = 1;
        context.shadowColor = "red";
        context.shadowBlur = 0.6;
        context.stroke();
        context.closePath();
        context.restore();
    }

    function drawText(text, percent) {
        "use strict";
        context.save();
        context.moveTo(1, 10);
        context.beginPath();
        context.strokeColor = "yellow";
        context.shadowOffsetX = 1;
        context.shadowOffsetY = 1;
        context.shadowColor = "brown";
        context.lineWidth = 1;
        context.fillColor = "gold";
        context.fillText(text, 1, 10);
        context.fill();
        context.stroke();
        context.closePath();
        context.restore();
        context.save();
        var textWidth = context.measureText(text);
        context.beginPath();
        context.strokeText(percent + "%", textWidth.width + 6, 10);
        context.lineWidth = 4;
        context.shadowColor = "blue";
        context.shadowOffsetX = 3;
        context.shadowOffsetY = 3;
        context.shadowBlur = 0.4;
        context.stroke();
        context.closePath();
        context.restore();
    }

    function draw() {
        "use strict";
        part += 1;
        ++percent;
        if (percent == 101) percent = 0;
        if (part == 3) part = 0;
        if (!pause) {
            drawPart(part);
            drawText("Loading....", percent);
        }

    }

    function stop() {
        "use strict";
        pause = !pause;
        if (pause)
            stopBtn.innerHTML = "Start";
        else
            stopBtn.innerHTML = "Stop";
    }

    function init() {
        "use strict";
        width = canvas.width;
        height = canvas.height;

        canvas.width = Math.max(canvas.width, canvas.height);
        canvas.height = Math.max(canvas.width, canvas.height);

        radius = Math.min(Math.floor(height/2), Math.floor(width / 2)) - 1;


        parts = [
            [width/2, height, radius, Math.PI / 180 * 0, Math.PI / 180 * 120],
            [width/2, height, radius, Math.PI / 180 * 121, Math.PI / 180 * 240],
            [width/2, height, radius, Math.PI / 180 * 241, Math.PI / 180 * 360]
        ];

        timer = setInterval(draw, interval);
    }

    window.addEventListener("load", init, false);
    stopBtn.addEventListener("click", stop, false);
}());