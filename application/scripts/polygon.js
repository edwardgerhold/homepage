/**
 * Created by root on 02.09.14.
 */
(function () {
    "use strict";
    var canvas, context;

    var context;
    function init() {
        var btnDrawPolygon = document.getElementById("btnDrawPolygon");
        btnDrawPolygon.addEventListener("click", drawPolygon, false);
        canvas = document.querySelector("canvas");
        context = canvas.getContext("2d");
        document.getElementById("numSides").addEventListener("change", function () {
            document.getElementById("sides").innerHTML = this.value;
            drawPolygon();
         });
        document.getElementById("numRadius").addEventListener("change", function () {
            document.getElementById("radius").innerHTML = this.value;
            drawPolygon();
        });
        document.getElementById("numColor").addEventListener("change", function () {
            document.getElementById("color").innerHTML = this.value;
            drawPolygon();
        });
    }
    function drawPolygon() {
        var numSides = document.getElementById("numSides").value;
        var radius = document.getElementById("numRadius").value;
        var numColor = document.getElementById("numColor").value;
        var xCenter = parseInt(canvas.width/2);
        var yCenter = parseInt(canvas.height/2);
        context.clearRect(0,0,canvas.width,canvas.height);
        context.beginPath();
        var xPos = xCenter + radius * Math.cos(0);
        var yPos = yCenter + radius * Math.sin(0);
        context.moveTo(xPos, yPos);
        for (var i = 1; i <= numSides; i++) {
            xPos = xCenter + radius * Math.cos(2*Math.PI * i / numSides);
            yPos = yCenter + radius * Math.sin(2*Math.PI * i / numSides);
            context.lineTo(xPos, yPos);
        }
        context.closePath(xPos, yPos);
        context.lineWidth = 30;
        context.lineJoin = "round";
        context.strokeColor = "#" + fill((+numColor).toString(16), 6);
        context.stroke();
        context.fillColor = "#" + fill((+numColor).toString(16), 6);
        context.fill();
    }

    function fill(str, n) {
        if (str.length < n) {
            for (var i = 0, j = str.length-n; i < j; i++)
                str = "0"+str;
        }
            return str;
    }
    window.addEventListener("load", init, false);


}());
