/**
 * Created by root on 07.09.14.
 */
var cwidth = 600;
var cheight = 400;
var ctx;
var everything = [];
var tid;
var horvelocity;
var verticalvalve1;
var verticalvalve2;
var gravity = 2;
var iballx = 20;
var ibally = 300;

var cannonx = 10;
var cannony = 280;
var cannonlength = 200;
var cannonht = 20;
var ballrad = 10;
var targetx = 500;
var targety = 50;
var targetw = 85;
var targeth = 280;
var htargetx = 450;
var htargety = 220;
var htargetw = 355;
var htargeth = 96;

function Ball(sx,sy,rad, stylestring) {
    "use strict";
    this.sx = sx;
    this.sy = sy;
    this.rad = rad;
    this.draw = drawball;
    this.moveit = moveball;
    this.fillStyle = stylestring;
}

function drawball() {
    "use strict";
    ctx.fillStyle = this.fillStyle;
    ctx.beginPath();
    ctx.arc(this.sx, this.sy, this.rad, Math.PI*2, true);
    ctx.fill();
}
function moveball(dx,dy) {
    "use strict";
    this.sx += dx;
    this.sy += dy;
}
var cball = new Ball(iballx, ibally,10,"rgb(250,0,0)");
function MyRectangle(sx,sy,swidth,sheight,stylestring) {
    "use strict";
    this.sx = sx;
    this.sy = sy;
    this.swidth = swidth;
    this.sheight = sheight;
    this.fillStyle = stylestring;
    this.draw = drawrects;
    this.moveit = moveball;
}
function drawrects() {
    "use strict";
    ctx.fillStyle = this.fillStyle;
    ctx.fillRect(this.sx,this.sy,this.swidth,this.sheight);
}
var target = new MyRectangle(30,100,80,200,"rgb(0,5,90)");
var ground = new MyRectangle(0,300,600,30,"rgb(10,250,0");
everything.push(target);
everything.push(ground);
everything.push(cball);
function init() {
    "use strict";
    ctx=document.getElementById("canvas").getContext("2d");
    cball.draw();
}
function fire() {
    "use strict";
    cball.sx = iballx;
    cball.sy = ibally;
    horvelocity = Number(document.f.hv.value);
    verticalvalve1 = Number(document.f.vv.value);
    cball.draw(); // drawall();
    tid = setInterval(change, 100);
    return false;
}
function drawall() {
    "use strict";
    ctx.clearRect(0,0,cwidth, cheight);
    var i;
    for (i=0;i<everything.length;i++) {
        everything[i].draw();
    }
}
function change() {
    "use strict";
    var dx = horvelocity;
    verticalvalve2 = verticalvalve1 + gravity;
    var dy = (verticalvalve1 + verticalvalve2) * .5;
    verticalvalve1 = verticalvalve2;
    cball.moveit(dx,dy);
    var by = cball.sy;
    if ((bx>=target.sx) && (bx<(target.sx+target.swidth))&&
    (by>=target.sy)&&(by<=(target.sy+target.sheight))) {
        clearInterval(tid);
    }
    if (by<=ground.sy) {
        clearInterval(tid);
    }
    drawall();
}