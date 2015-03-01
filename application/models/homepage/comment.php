<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 28.07.14
 * Time: 19:30
 */

namespace Homepage;

class Comment extends \Homepage\Model {
    /**
    * @readwrite
    */
    protected $_table = "homepage_comment";
    /**
     * @column
     * @readwrite
     * @type integer
     * @index
     */
    protected $_user;
    /**
     * @column
     * @readwrite
     * @type integer
     */
    protected $_article;
    /**
     * @column
     * @type text
     * @readwrite
     * @length 255
     */
    protected $_title;
    /**
     * @column
     * @readwrite
     * @type text
     */
    protected $_content;

    /**
     * @column
     * @readwrite
     * @type integer
     */
    protected $_reply;

    /**
     * @readwrite
     */
    protected $_name;

    function __construct($options = array()) {
        parent::__construct($options);
    }

}