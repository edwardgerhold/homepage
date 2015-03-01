<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 22.07.14
 * Time: 16:33
 */

namespace Homepage;


class Message extends Model {

    protected $_table = "homepage_message";
    
    /**
     * @column
     * @readwrite
     * @type text
     * @lenth 256
     *
     * @validate required
     * @label body
     */
    protected $_body;
    /**
     * @column
     * @readwrite
     * @type integer
     */
    protected $_message;
    /**
     * @column
     * @readwrite
     * @type integer
     */
    protected $_user;

    public function getReplies() {
        return self::all(array(
            "message=?" => $this->getId(),
            "live=?" => true,
            "deleted=?" => false
        ), array(
            "*",
            "(SELECT CONCAT(first, \" \", last) FROM user WHERE user.id=message.user)" => "user_name",
            "name"
        ), "created", "desc");
    }

    public static function fetchReplies($id) {
        $message = new self(array(
            "id" => $id
        ));
        return $message->getReplies();
    }

}
