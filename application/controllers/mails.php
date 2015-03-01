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
 * Date: 22.08.14
 * Time: 19:26
 * User: Edward Gerhold
 * Project Edward´s Homepage
 */

use Framework\Registry as Registry;
use Framework\RequestMethods as RequestMethods;

class Mails extends \Homepage\Controller {
    /**
     * @before _secure, _admin
     */
    public function send() {
        if (RequestMethods::post("send")) {

            $receiver = RequestMethods::post("receiver");
            $subject = RequestMethods::post("subject");
            $message = RequestMethods::post("message");

            $mail = Registry::get("mail");
            $sent = false;
            if ($mail) {
                if ($receiver && $subject && $message) {
                    $sent = $mail->send(array(
                        "receiver"=>$receiver,
                        "subject"=>$subject,
                        "message"=>$message
                    ));
                }
            }
            $view->set("success", $sent);
        }
    }
}




