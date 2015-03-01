/**
 * Created by root on 02.09.14.
 */
function createNotification(text, ms) {
    "use strict";
    ms = ms === undefined ? 3000 : ms;
    var div = document.createElement("div");
    div.className = "notification wrapper";
    var textDiv = document.createElement("div")
    textDiv.innerHTML = text;
    div.appendChild(textDiv);
    document.body.appendChild(div);
    setTimeout(function () {
        div.parentNode.removeChild(div);
    }, ms);
}

