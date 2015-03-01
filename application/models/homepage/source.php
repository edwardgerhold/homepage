<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 13.08.14
 * Time: 17:38
 */

namespace Homepage;

class Source extends Model {
    /**
     * @readwrite
     */
    protected $_table = "homepage_source";
    /**
     * @column
     * @type text
     * @label media
     * @readwrite
     */
    protected $_media;
    /**
     * @column
     * @type text
     * @label src
     * @readwrite
     */
    protected $_src;
    /**
     * @column
     * @type text
     * @label type
     * @readwrite
     */
    protected $_type;

    /**
     * @column
     * @type integer
     * @label audio key
     * @index
     * @readwrite
     */
    protected $_audio;
    /**
     * @column
     * @type integer
     * @label video key
     * @index
     * @readwrite
     */
    protected $_video;
    /**
     * @column
     * @type integer
     * @label user id
     * @readwrite
     */
    protected $_user;


}