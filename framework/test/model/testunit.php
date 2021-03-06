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
* Time: 16:31
* User: Edward Gerhold
* Project Edward´s Homepage
*/


namespace Framework\Test\Model;


class TestUnit extends \Homepage\Model {
    /**
     * @column
     * @type text
     */
    protected $_set;
    /**
     * @column
     * @type text
     */
    protected $_title;

}