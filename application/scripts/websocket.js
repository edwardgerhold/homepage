(function () {
/**
 * Created by root on 28.08.14.
 */
var directorWebsocket = null;
var wsUri = "ws://echo.websocket.org";

function init() {
    "use strict";
    var btnSend = document.getElementById("btnSend");
    var btnClose = document.getElementById("btnClose");
    btnSend.addEventListener("click", postMessage, false);
    btnClose.addEventListener("click", closeWS, false);
    updateStatus("initializing websocket connection");
    directorWebsocket = new WebSocket(wsUri);
    directorWebsocket.onopen = onOpen;
    directorWebsocket.onclose = onClose;
    directorWebsocket.onmessage = onMessage;
    directorWebsocket.onerror = onError;
}

function onOpen(e) {
    "use strict";
    updateStatus("connection open");
}
function onMessage(e) {
    "use strict";
    updateStatus("message received: "+e.data);
    document.getElementById("message").innerHTML += "<p>" + e.data + "</p>\n";
}
function onError(e) {
    "use strict";
    updateStatus("Error: "+e.data);
}
function onClose(e) {
    "use strict";
    updateStatus("connection closed");
}
function postMessage() {
    "use strict";
    var msg = document.getElementById("msg").value;
    updateStatus("Sending message: "+msg);
    directorWebsocket.send(msg);
}
function closeWS() {
    "use strict";
    updateStatus("disconnecting");
    directorWebsocket.close();
}
function updateStatus(msg) {
    "use strict";
    document.getElementById("wsState").innerHTML = msg;
}

window.addEventListener("load", init, false);
}());