<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 30.07.14
 * Time: 17:12
 */

namespace Homepage;

use \Homepage\Model as Model;

class Topic extends Model {

    /**
     * @readwrite
     */

    protected $_table = "homepage_topic";

    /**
     * @readwrite
     * @column
     * @type integer
     * @length 255
     */
    protected $_parent;

    /**
     * @readwrite
     * @column
     * @type text
     * @length 255
     *
     */
    protected $_name;

    /**
     * @column
     * @readwrite
     * @type integer
     *
     */
    protected $_category;

    /**
     * @column
     * @readwrite
     * @type integer
     */
    protected $_supervisor; // the user who is responsible for

    function __construct($options = array()) {
        parent::__construct($options);
    }
}