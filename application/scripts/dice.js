var cwidth = 400;
var cheight = 300;
var dicex = 50;
var dicey = 50;
var dicewidth = 100;
var diceheight = 100;
var dotrad = 6;
var ctx;
var dx;
var dy;
var dices = [];
var output = document.getElementById("results");

// gets two random numbers between 1 and 6 and draws each dice.
function throwDice(e) {

    "use strict";
    var ch = 1 + Math.floor(Math.random() * 6);
    dx = dicex;
    dy = dicey;
    drawface(ch);
    dices[0] = ch;
    dx = dicex + 150;
    ch = 1 + Math.floor(Math.random() * 6);
    drawface(ch);
    dices[1] = ch;
    showResults();
    e.preventDefault();
}

function showResults() {
    "use strict";
    output.innerHTML = "You´ve thrown "+dices[0]+" and "+dices[1] + "<br>";
    if (dices[0] == dices[1]) {
        output.innerHTML += "Congratulations, you´ve thrown a pair!"
    }
}
function drawface(n, diceNum) {
    "use strict";
    ctx = ctx || document.getElementById("canvas").getContext("2d");
    ctx.lineWidth = 5;
    ctx.clearRect(dx, dy, dicewidth, diceheight);
    ctx.strokeRect(dx, dy, dicewidth, diceheight);
    var dotx;
    var doty;
    ctx.fillStyle = "#096";
    switch (n) {
        case 1:
            draw1();
            break;
        case 2:
            draw2();
            break;
        case 3:
            draw2();
            draw1();
            break;
        case 4:
            draw4();
            break;
        case 5:
            draw4();
            draw1();
            break;
        case 6:
            draw4();
            draw2mid();
            break;
    }
}

function draw1() {
    "use strict";
    var dotx, doty;
    ctx.beginPath();
    dotx = dx + .5 * dicewidth;
    doty = dy + .5 * diceheight;
    ctx.arc(dotx, doty, dotrad, 0, Math.PI * 2, true );
    ctx.closePath();
    ctx.fill();
}
function draw2() {
    "use strict";
    var dotx, doty;
    ctx.beginPath();
    dotx = dx + 3 * dotrad;
    doty = dy + 3 * dotrad;
    ctx.arc(dotx, doty, dotrad, 0, Math.PI * 2, true);
    dotx = dx + dicewidth - 3 * dotrad;
    doty = dy + diceheight - 3 * dotrad;
    ctx.arc(dotx, doty, dotrad, 0, Math.PI * 2, true);
    ctx.closePath();
    ctx.fill();
}
function draw4() {
    "use strict";
    var dotx, doty;
    ctx.beginPath();
    dotx = dx + 3 * dotrad;
    doty = dy + 3 * dotrad;
    ctx.arc(dotx, doty, dotrad, 0, Math.PI * 2, true);
    dotx = dx + dicewidth - 3 * dotrad;
    doty = dy + diceheight - 3 * dotrad;
    ctx.arc(dotx, doty, dotrad, 0, Math.PI * 2, true);
    ctx.closePath();
    ctx.fill();
    ctx.beginPath();
    dotx = dx + 3 * dotrad;
    doty = dy + diceheight - 3 * dotrad;
    ctx.arc(dotx, doty, dotrad, 0, Math.PI * 2, true);
    ctx.closePath();
    ctx.fill();
    ctx.beginPath();
    dotx = dx + dicewidth - 3 * dotrad;
    doty = dy + 3 * dotrad;
    ctx.arc(dotx, doty, dotrad, 0, Math.PI * 2, true);
    ctx.closePath();
    ctx.fill();
}
function draw2mid() {
    "use strict";
    var dotx, doty;
    ctx.beginPath();
    dotx = dx + 3 * dotrad;
    doty = dy + 0.5 * diceheight;
    ctx.arc(dotx, doty, dotrad, 0, Math.PI * 2, true);
    dotx = dx + dicewidth - 3 * dotrad;
    doty = dy + .5 * diceheight;
    ctx.arc(dotx, doty, dotrad, 0, Math.PI * 2, true);
    ctx.closePath();
    ctx.fill();
}