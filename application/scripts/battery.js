/**
 * Created by root on 02.09.14.
 */
window.addEventListener("batterystatus", function (e) {
    "use strict";
    var div = document.createElement("div");
    div.className = "batterystatus";
    div.innerHTML = "<span>Battery Status is at level: "+e.level+"</span>";
    if (e.isPlugged) {
      div.innerHTML += "<span>User is now plugged</span>";
    }
    document.body.insertBefore(div, document.body.firstChild);
    setTimeout(function () {
        div.parentNode.removeChild(div);
    }, 3000);
});
