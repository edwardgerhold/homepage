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
* Time: 18:24
* User: Edward Gerhold
* Project Edward´s Homepage
*/


namespace Homepage;


class Contact extends Model {

    protected $_user; // user

    protected $_sync; // shall contact be synced with user address?

    protected $_name;

    protected $_address;

    protected $_city;

    protected $_country;

    protected $_phone;

    protected $_email;

    protected $_job;

    protected $_company;

    protected $_mission;

    protected $_providing;

    protected $_searching;

    protected $_openhours;

} 