<?php

namespace Homepage;

use \Homepage\Friend as Friend;
use \Homepage\File as File;

class User extends \Framework\User {
    /**
     * @readwrite
     */
    protected $_table = "homepage_user";
    /**
     * @column
     * @readwrite
     * @primary
     * @type autonumber
     */
    protected $_id;
    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     *
     * @label username
     * @validate required
     */
    protected $_name;   // user name
    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     *
     * @label first name
     */
    protected $_first;
    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     *
     * @label last name
     */
    protected $_last;
    /**
     * @column
     * @readwrite
     * @type text
     * @length 255
     *
     * @label email
     */
    protected $_email;
    /**
     * @column
     * @readwrite
     * @type text
     * @length 2048
     *
     * @label website
     */
    protected $_website;
    /**
     * @column
     * @readwrite
     * @type text
     * @label motto
     */
    protected $_motto;
    /**
     * @column
     * @readwrite
     * @type text
     * @label hobbies
     */
    protected $_hobbies;
    /**
     * @column
     * @readwrite
     * @type text
     *
     * @label likes
     */
    protected $_likes;
    /**
     * @column
     * @readwrite
     * @type text
     *
     * @label doesntlike
     */
    protected $_doesntlike;
    /**
     * @column
     * @readwrite
     * @type text
     * @length 255
     * @label city
     */
    protected $_city;
    /**
     * @column
     * @readwrite
     * @type text
     * @length 255
     * @label country
     */
    protected $_country;
    /**
     * @column
     * @readwrite
     * @type text
     * @length 255
     * @label job
     */
    protected $_job;
    /**
     * @column
     * @readwrite
     * @type text
     * @length 255
     *
     * @label company
     */
    protected $_company;
    /**
     * @column
     * @readwrite
     * @type date
     * @label birthday
     */
    protected $_birthday;
    /**
     * @column
     * @readwrite
     * @type text
     *
     * @label password
     * @validate required
     */
    protected $_password;

    /**
     * @column
     * @readwrite
     * @type text
     * @label language
     */
    protected $_language;
    /**
     * @column
     * @readwrite
     * @type text
     * @label application language and locale settings key
     */
    protected $_locale;

    /**
     * @column
     * @type boolean
     * @label confirmation
     * @readwrite
     */
    protected $_confirmed;
    /**
     * @column
     * @readwrite
     * @type boolean
     */
    protected $_admin;
    /**
     * @column
     * @readwrite
     * @type boolean
     */
    protected $_publisher;
    /**
     * @column
     * @type text
     * @label access control list
     * @readwrite
     */
    protected $_acl;

    /**
     * @readwrite
     * @column
     * @type datetime
     * @label last visit (saved at logoff with lastOnlineTime)
     */
    protected $_lastLogin;
    /**
     * @column
     * @type time
     * @readwrite
     * @label last visit´s duration
     */
    protected $_onlineTime;
    /**
     * @readwrite
     * @column
     * @label last used ip
     * @type text
     */
    protected $_lastAddress;
    /**
     * @readwrite
     * @column
     * @label number of visits
     * @type integer
     */
    protected $_visits;

    /**
     * @readwrite
     */
    protected $_ip;

    public function save() {
        $primary = $this->getPrimaryColumn();
        $raw = $primary["raw"];
        if (empty($this->$raw)) {
            $this->setCreated(date("Y-m-d H:i:s"));
            $this->setDeleted(false);
            $this->setLive(true);
        }
        $this->setModified(date("Y-m-d H:i:s"));
        parent::save();
    }

    public function getFile() {
        $file = File::first(array(
            "user=?" => $this->_id,
            "live=?" => true,
            "deleted=?" => false
        ), array("*"), "id", "DESC");
        if (isset($file)) return $file;
        return false;
    }

    public function isFriend($id) {
        $friend = Friend::first(array(
            "user=?" => $this->_id,
            "friend=?" => $id
        ));
        if ($friend) {
            return true;
        }
        return false;
    }

    public static function hasFriend($id, $friend) {
        // erzeugt ein user objekt mit der id
        $user = new self(array(
            "id" => $id
        ));
        // was nicht das aus der datenbank ist.
        return $user->isFriend($friend);
    }

    // added these two to get all friends,
    // those i added, and those, who added me

    public function getFriends() {
        return self::allFriends($this->id);
    }

    public static function allFriends($id) {
        $user = Friend::all(array(
            "user=?" => $id,
            "live=?" => true
        ));
        $friend = Friend::all(array(
            "friend=?" => $id,
            "live=?" => true
        ));
        return array("user"=>$user, "friend"=>$friend);
    }

}