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
* Created by PhpStorm
* Date: 25.08.14
* Time: 12:04
* User: Edward Gerhold
* Project Edward´s Homepage
*/


namespace Homepage;


class Confirmation extends \Homepage\Model {
    /**
     * @readwrite
     */
    protected $_table = "homepage_confirmation";
    /**
     * @column
     * @type text
     *
     * @label the hash code for confirmation
     */
    protected $_hash;

    /**
     * @column
     * @type integer
     * @label the user number wo will be accredited
     */
    protected $_user;
    /**
     * @column
     * @type boolean
     * @label user has confirmed this boolean
     */
    protected $_confirmed;

} 