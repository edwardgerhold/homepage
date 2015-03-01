var interval;
var pingpong = {};
pingpong.pressedKeys = [];
pingpong.ball = {
    speed: 5,
    x: 150,
    y: 100,
    directionX: 1,
    directionY: 1
};

function gameloop() {
    "use strict";
    moveBall();
    movePaddles();
}
function moveBall() {
    "use strict";
    // reference useful variables
    var playgroundHeight = parseInt($("#playground").height());
    var playgroundWidth = parseInt($("#playground").width());
    var ball = pingpong.ball;
// check playground boundary
// check bottom edge
    if (ball.y + ball.speed * ball.directionY > playgroundHeight) {
        ball.directionY = -1;
    }
// check top edge
    if (ball.y + ball.speed * ball.directionY < 0) {
        ball.directionY = 1;
    }
// check right edge
    if (ball.x + ball.speed * ball.directionX > playgroundWidth) {
// player B lost.
// reset the ball;
        ball.x = 250;
        ball.y = 100;
        $("#ball").css({
            "left": ball.x,
            "top": ball.y
        });
        ball.directionX = -1;
    }
// check left edge
    if (ball.x + ball.speed * ball.directionX < 0) {
// player A lost.
// reset the ball;
        ball.x = 150;
        ball.y = 100;
        $("#ball").css({
            "left": ball.x,
            "top": ball.y
        });
        ball.directionX = 1;
    }
// check moving paddle here, later.
    // check left paddle
    var paddleAX = parseInt($("#paddleA").css("left")) + parseInt($("#pa
    ddleA
    ").css("
    width
    "));
    var paddleAYBottom = parseInt($("#paddleA").css("top")) + parseInt($
    ("#paddleA").css("height"));
    var paddleAYTop = parseInt($("#paddleA").css("top"));
    if (ball.x + ball.speed * ball.directionX < paddleAX) {
        if (ball.y + ball.speed * ball.directionY <= paddleAYBottom &&
            ball.y + ball.speed * ball.directionY >= paddleAYTop) {
            ball.directionX = 1;
        }
    }
// check right paddle
    var paddleBX = parseInt($("#paddleB").css("left"));
    var paddleBYBottom = parseInt($("#paddleB").css("top")) + parseInt($("#paddleB").css("height"));
    var paddleBYTop = parseInt($("#paddleB").css("top"));
    if (ball.x + ball.speed * ball.directionX >= paddleBX) {
        if (ball.y + ball.speed * ball.directionY <= paddleBYBottom &&
            ball.y + ball.speed * ball.directionY >= paddleBYTop) {
            ball.directionX = -1;
        }
    }
// actually move the ball with speed and direction
    $("#ball").css({
        "left": ball.x,
        "top": ball.y
    });
}
function movePaddles() {
    "use strict";

}

window.addEventListener("load", function () {
    "use strict";
    interval = setInterval(gameloop, 30);
})