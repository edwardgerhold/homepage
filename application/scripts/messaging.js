/**
 * Created by root on 01.09.14.
 */

(function () {

var to = document.getElementById("to");
var message = document.getElementById("message")

function normalize(number, message) {
    if (number[0]!="+") number = "+"+number;
    return "sms:"+encodeURI(number)+"?body="+encodeURIComponent(message);
}

window.addEventListener(document.getElementById("sendBtn"), function (e) {

    if (navigator.device && navigator.device.sendMessage) {
        navigator.device.sendMessage(normalize(to, message));
    }

}, false);

}());