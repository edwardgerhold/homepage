<?php
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
 * Date: 25.08.14
 * Time: 08:54
 * User: Edward Gerhold
 * Project Edward´s Homepage
 */

namespace Framework\Mail\Driver;
use Framework\Mail as Mail;

class SMTP extends Mail\Driver {

    public function send($mail) {

        $error = false;
        $sent = false;
        $receiver = "";
        $subject = "";
        $message = "";
        $header = null;

        if (isset($mail["receiver"]))
            $receiver = $mail["receiver"];
        else $error = true;

        if (isset($mail["subject"]))
            $subject = $mail["subject"];

        if (isset($mail["message"]))
            $message = $mail["message"];

        if (isset($mail["headers"]))
            $headers = $mail["headers"];

        if ($error) {
            throw new Exception\Mail("incorrect or incomplete information provided");
        } else {
            $sent = mail($receiver, $subject, $message, $headers);
        }
        return $sent;
    }

} 