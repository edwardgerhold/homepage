/**
 *
 * Edward´s Homepage is written by Edward Gerhold
 * http://github.com/edwardgerhold/homepage
 * Edward´s Homepage is originally developed for
 * http://linux-swt.de (c) 2014 Edward Gerhold
 * This is free and open source software for you.
 *
 * The Homepage Application Framework bases on the
 * "Pro PHP MVC" Framework from the namely equal book
 * by Chris Pitt released by http://apress.com.
 *
 * The application is Edward´s Homepage.
 * Load it into a PHPStorm evaluation copy from
 * http://jetbrains.com for the ultimate experience.
 *
 * Created by PhpStorm
 * Date: 21.08.14
 * Time: 21:40
 * User: Edward Gerhold
 * Project Edward´s Homepage
 */

window.addEventListener("DOMContentLoaded", function (e) {
    "use strict";
    var form;
    for (var i = 0, j = document.forms.length; i < j; i++) {
        form = document.forms.item(i);
        if (typeof form.name == "string"
            && (/^(reply)/.test(form.name)
            || /^(comment)/.test(form.name))) {
            form.hidden = true;
        }
    }
}, false);
