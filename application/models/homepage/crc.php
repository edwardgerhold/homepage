<?php
/**
*
* Edward´s Homepage is written by Edward Gerhold
* http://github.com/edwardgerhold/homepage
* Edward´s Homepage is originally developed for
* http://linux-swt.de (c) 2014 Edward Gerhold
* This is free and open source software for you.
*
* The Homepage Application Framework bases on the
* "Pro PHP MVC" Framework from the namely equal book
* by Chris Pitt released by http://apress.com.
*
* The application is Edward´s Homepage.
* Load it into a PHPStorm evaluation copy from 
* http://jetbrains.com for the ultimate experience.
*
* Following more rules, the page is also developed with 
* the HTML5 Cookbook by T. Leadbetter and C. Hudson
* and by Responsive Webdesign (german) by C. Zillgens
*
* Created by PhpStorm.
* User: Edward Gerhold
* Date: 05.09.14
* Time: 11:44
* Project: Edward´s Homepage
*/

namespace Homepage;

class Crc extends Model {

    /**
     * @read
     */
    protected $_table = "homepage_crc";

    /**
     * @column
     * @type text
     * @readwrite
     * @validate required
     */
    protected $_class;
    /**
     * @column
     * @type text
     * @readwrite
     */
    protected $_responsibility;
    /**
     * @column
     * @type text
     * @readwrite
     */
    protected $_collaborations;
    /**
     * @column
     * @type integer
     * @readwrite
     */
    protected $_project;
    /**
     * @column
     * @type integer
     * @readwrite
     */
    protected $_user;
    /**
     * @column
     * @type boolean
     * @readwrite
     */
    protected $_private;
}