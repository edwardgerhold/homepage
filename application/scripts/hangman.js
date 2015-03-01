/**
 * Created by root on 07.09.14.
 * From Jeanine Meyer - The essential Guide to HTML5
 */

var ctx;
var thingelem;
var alphabet = "abcdefghijklmnopqrstuvwxyz";
var alphabety = 300;
var alphabetx = 20;
var alphabetwidth=25;
var secret;
var lettersguessed = 0;
var secretx = 160;
var secrety = 50;
var secretwidth = 50;
var gallowscolor = "brown";
var facecolor = "tan";
var bodycolor = "tan";
var noosecolor = "#f60";
var bodycenterx = 70;
var steps = [
    drawgallows,
    drawhead,
    drawbody,
    drawrightarm,
    drawleftarm,
    drawrightleg,
    drawleftleg,
    drawnoose
];
var cur = 0;
function drawgallows() {
    "use strict";
    ctx.lineWidth = 8;
    ctx.strokeStyle = gallowscolor;
    ctx.beginPath();
    ctx.moveTo(2,180);
    ctx.lineTo(40,180);
    ctx.moveTo(2,40);
    ctx.lineTo(80,40);
    ctx.stroke();
    ctx.closePath();
}
function drawhead() {
    "use strict";
    ctx.lineWidth = 3;
    ctx.strokeStyle = facecolor;
    ctx.save();
    ctx.scale(.6,1);
    ctx.beginPath();
    ctx.arc(bodycenterx /.6,80,10,0,Math.PI*2,false);
    ctx.stroke();
    ctx.closePath();
    ctx.restore();
}
function drawbody() {
    "use strict";
    ctx.strokeSTyle = bodycolor;
    ctx.beginPath();
    ctx.moveTo(bodycenterx, 90);
    ctx.lineTo(bodycenterx, 125);
    ctx.stroke();
    ctx.closePath();
}
function drawrightarm() {
    "use strict";
    ctx.beginPath();
    ctx.moveTo(bodycenterx, 100);
    ctx.lineTo(bodycenterx+20,110);
    ctx.stroke();
    ctx.closePath();
}
function drawleftarm() {
    "use strict";
    ctx.beginPath();
    ctx.moveTo(bodycenterx, 100);
    ctx.lineTo(bodycenterx-20,110);
    ctx.stroke();
    ctx.closePath();
}
function drawrightleg() {
    "use strict";
    ctx.beginPath();
    ctx.moveTo(bodycenterx, 125);
    ctx.lineTo(bodycenterx, 155);
    ctx.stroke();
    ctx.closePath();
}
function drawleftleg() {
    "use strict";
    ctx.beginPath();
    ctx.moveTo(bodycenterx, 125);
    ctx.lineTo(bodycenterx-10, 155);
    ctx.stroke();
    ctx.closePath();
}
function drawnoose() {
    "use strict";
    ctx.strokeStyle = noosecolor;
    ctx.beginPath();
    ctx.moveTo(bodycenterx-10,40);
    ctx.lineTo(bodycenterx-5,95);
    ctx.stroke();
    ctx.closePath();
    ctx.save();
    ctx.scale(1,.3);
    ctx.beginPath();
    ctx.arc(bodycenterx,95 /.3,8,0,Math.PI*2,false);
    ctx.stroke();
    ctx.closePath();
    ctx.restore();
    drawneck();
}
function drawneck() {
    "use strict";
    ctx.strokeStyle=bodycolor;
    ctx.beginPath();
    ctx.moveTo(bodycenterx,90);
    ctx.lineTo(bodycenterx, 95);
    ctx.stroke();
    ctx.closePath();
}
function init() {
    "use strict";
    ctx = document.getElementById("canvas").getContext("2d");
    setupgame();
    ctx.font="bold 20pt Ariel";
}
function setupgame() {
    "use strict";
    var i;
    var x;
    var y;
    var d;
    var uniqueid;
    var an = alphabet.length;
    for (i=0;i<an;i++) {
        uniqueid = "a"+String(i);
        d = document.createElement("alphabet");
        d.innerHTML = (
            "<div class='letters' id='"+uniqueid+"'>"+alphabet[i]+"</div>"
            );
        document.body.appendChild(d);
        var thingelem = document.getElementById(uniqueid);
        x = alphabetx + alphabetwidth * i;
        y = alphabety;
        thingelem.style.top = String(y)+"px";
        thingelem.style.left = String(x)+"px";
        thingelem.addEventListener("click", pickelement, false);
    }
   var ch = Math.floor(Math.random()*words.length);
    secret = words[ch];
    for (i=0;i<secret.length;i++) {
        uniqueid = "s"+String(i);
        d = document.createElement("secret");
        d.innerHTML = ("<div class='blanks' id='"+uniqueid+"'> __ </div>");
        document.body.appendChild(d);
        thingelem = document.getElementById(uniqueid);
        x = secretx + secretwidth * i;
        y = secrety;
        thingelem.style.top = String(y)+"px";
        thingelem.style.left = String(x)+"px";
    }
    steps[cur]();
    cur++;
    return false;
}
function pickelement(ev) {
    "use strict";
    var not = true;
    var picked = this.textContent;
    var i;
    var j;
    var uniqueid;
    var thingelem;
    var out;
    for (i=0;i<secret.length;i++) {
        if(picked==secret[i]) {
            uniqueid = "s"+String(i);
            document.getElementById(uniqueid).textContent = picked;
            not = false;
            lettersguessed++;
            if (lettersguessed==secret.length) {
                ctx.fillStyle = gallowscolor;
                out = "You won!";
                ctx.fillText(out, 200, 80);
                ctx.fillText("Re-load the page to try again.", 200, 120);
                for (j=0;j<alphabet.length;j++) {
                    uniqueid = "a"+String(j);
                    thingelem = document.getElementById(uniqueid);
                    thingelem.removeEventListener("click", pickelement, false);
                }

            }
        }
    }
    if (not) {
        steps[cur]();
        cur++;
        if (cur>=steps.length) {
            for (i=0;i<secret.length;i++) {
                id="s"+String(i);
                document.getElementById(id).textContent = secret[i];
            }
            ctx.fillStyle = gallowscolor;
            out = "You lost!";
            ctx.fillText(out,200,80);
            ctx.fillText("Re-load the page to try again.", 200, 120);
            for (j=0;j<alphabet.length;j++) {
                uniqueid = "a"+String(j);
                thingelem = document.getElementById(uniqueid);
                thingelem.removeEventListener("click", pickelement, false);
            }
        }
    }
    var id = this.id;
    document.getElementById(id).style.display = "none";
}
window.addEventListener("load", init, false);