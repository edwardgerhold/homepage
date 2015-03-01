<?php

use Homepage\Controller as Controller;
use Framework\RequestMethods as RequestMethods;
use Homepage\Message as Message;

class Messages extends Controller {

    /**
     * @before _secure
     */
    public function add() {
        $user = $this->getUser();
        if (RequestMethods::post("share")) {

            $message = new Message(array(
                "body" => RequestMethods::post("body"),
                "message" => RequestMethods::post("message", null),
                "user" => $user->id
            ));

            if ($message->validate()) {
                $message->save();

                $redirect = RequestMethods::post("origin");
                if (isset($redirect)) {
                    self::redirect($redirect);
                    exit();
                } else {
                    header("Location: /home.html");
                    exit();
                }
            }
        }
    }

    /**
     * @before _secure
     */
    public function edit($id) {
        $user = $this->getUser();

        if (RequestMethods::post("update")) {
            $message = Message::first(array(
                "id=?" => $id
            ));
            $message->body = RequestMethods::post("body", $message->body);
            $message->message = RequestMethods::post("message", $message->message);

            if ($message->validate()) {
                $message->save();

                if (RequestMethods::post("origin")) {
                    self::redirect(RequestMethods::post("origin"));
                    exit();
                } else {
                    header("Location: /home.html");
                    exit();
                }
            }
        }
    }

    /**
     * @before _secure
     */
    public function delete($id) {

    }
    public function undelete($id) {

    }
}
