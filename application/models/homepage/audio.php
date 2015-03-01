<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 12.08.14
 * Time: 13:29
 */

namespace Homepage;


class Audio extends \Homepage\Model {
    /**
     * @readwrite
     */
    protected $_table = "homepage_audio";

    /**
     * @column
     * @type text
     * @label song title
     */
    protected $_title;
    /**
     * @column
     * @type text
     * @label song author
     */
    protected $_author;
    /**
     * @column
     * @type integer
     * @label user with
     */
    protected $_user;

    /**
     * @column
     * @type text
     * @label autobuffer
     */
    protected $_autobuffer;
    /**
     * @column
     * @type boolean
     * @label autoplay
     */
    protected $_autoplay;
    /**
     * @column
     * @type boolean
     * @label controls
     */
    protected $_controls;
    /**
     * @column
     * @type boolean
     * @label loop
     */
    protected $__loop;
    /**
     * @column
     * @type text
     * @label src
     */
    protected $_src;
    /**
     * @column
     * @type time
     * @label duration
     */
    protected $_duration;
}