<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 17.08.14
 * Time: 19:17
 */


use Homepage\Friend as Friend;
use Homepage\Message as Message;

class Friends extends \Homepage\Controller {

    /**
     * @before _secure
     */

    public function all() {
        $user = $this->getUser();
        $view = $this->getActionView();
        if ($user) {
            $allFriends = $user->getFriends();
            $view->set("byUser", $allFriends["user"]);
            $view->set("byFriend", $allFriends["friend"]);
        }
    }

    public function add($id) {
        if (!isset($id)) {

        }
    }

    public function edit($id) {

    }

    public function delete($id) {

    }

    public function undelete($id) {

    }

    public function mail() {
        /**
         *
         * send mail to your friends
         *
         */
    }
} 