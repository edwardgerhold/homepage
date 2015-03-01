<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 28.07.14
 * Time: 19:07
 */

namespace Homepage;

class Article extends \Homepage\Model {
    /**
    * @readwrite
    */
    protected $_table = "homepage_article";

    /**
     * @column
     * @type integer
     * @index
     */
    protected $_user;
    /**
     * @column
     * @type text
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
     * @type text
     */
    protected $_code;
    /**
     * @column
     * @type datetime
     * @readwrite
     */

    /**
     * add to ?
     * @readwrite
     */
    protected $_style;

    function __construct($options = array()) {
        parent::__construct($options);
    }

} 