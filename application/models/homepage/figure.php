<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 19.08.14
 * Time: 11:38
 */

namespace Homepage;


class Figure extends \Homepage\Model {
    /**
     * @column
     * @type text
     * @label classname
     */
    protected $_className;

    /**
     * @column
     * @type text
     * @label element id
     */
    protected $_elementId;

    /**
     * @column
     * @type text
     * @label figure type
     */
    protected $_type;

    /**
     * @column
     * @type text
     * @label figure
     */
    protected $_figure;

    /**
     * @column
     * @type text
     * @label figcaption
     */
    protected $_figcaption;

    /**
     * @column
     * @type integer
     */
    protected $_document;
} 