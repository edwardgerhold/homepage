<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 30.07.14
 * Time: 17:12
 */

namespace Homepage;


class Category extends \Homepage\Model {

    /**
    * @readwrite
    */
    
    protected $_table = "homepage_category";
    
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

    function __construct($options=array()) {
        parent::__construct($options);
    }


} 