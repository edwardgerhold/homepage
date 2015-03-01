<?php
namespace Homepage;

class Friend extends Model {
    /**
    * @readwrite
    */
    protected $_table = "homepage_friend";

    /**
    * @readwrite
    * @column
    * @type integer
    * @validate required
    */
    protected $_user;
    
    /**
    * @readwrite
    * @column
    * @type integer
    * @validate required
    */
    protected $_friend;

}   
