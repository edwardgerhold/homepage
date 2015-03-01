<?php
/**
 * Copyright (c) 2014 by Edward Gerhold
 *
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 11.08.14
 * Time: 19:22
 *
 * The used Framework is originally invented by
 * Chris Pitt for his book entitled Pro PHP MVC.
 */

namespace Homepage;
use \Homepage\Model as Model;

class Tag extends Model {
    public function __construct($options=array()) {
        parent::__construct($options);
    }
    /**
     * @readwrite
     * @column
     * @type text
     * @validate required
     * @label name of the tag
     */
    protected $_name;
    /**
     * @readwrite
     * @column
     * @type integer
     * @label id of a cloud for the tag
     */
    protected $_cloud;


}