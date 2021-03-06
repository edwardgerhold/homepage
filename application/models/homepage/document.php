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
* Created by PhpStorm
* Date: 29.08.14
* Time: 18:22
* User: Edward Gerhold
* Project Edward´s Homepage
*/


namespace Homepage;


class Document extends \Homepage\Model {

    /**
     * @column
     * @type text
     * @label url for the route
     */
    protected $_url;         // the document url for the router

    /**
     * @column
     * @type text
     * @label
     */
    protected $_filename;    // if set filename replaces uri for loading

    /**
     * @column
     * @type integer
     */
    protected $_author;     // is this user id or some record? take CONTACT for such
} 