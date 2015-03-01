<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 20.07.14
 * Time: 14:05
 */

namespace Homepage;

class File extends Model {
    /**
    * @readwrite
    */
    protected $_table = "homepage_file";

    /**
     * @column
     * @readwrite
     * @type text
     * @length 255
     *
     */
    protected $_name;
    /**
     * @column
     * @readwrite
     * @type text
     * @length 32
     */
    protected $_mime;

    /**
     * @column
     * @readwrite
     * @type integer
     */
    protected $_size;
    /**
     * @column
     * @readwrite
     * @type integer
     */
    protected $_width;
    /**
     * @column
     * @readwrite
     * @type integer
     */
    protected $_height;
    /**
     * @column
     * @readwrite
     * @type integer
     */
    protected $_user;

} 