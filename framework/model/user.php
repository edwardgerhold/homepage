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
* Date: 01.10.14
* Time: 20:53
* Project: Edward´s Homepage
*/

namespace Framework\Model;

class User extends BaseFields {

    // standard profile (define two or three for the framework base to choose from)

    protected $_nick;           // user name
    protected $_email;          // alias
    protected $_title;          // mr. mrs. prof.dr.ing.habil
    protected $_first;          // eddie
    protected $_middle;         // null
    protected $_last;           // gerhold
    protected $_gender;         // m
    protected $_age;            // 37
    protected $_profession;     // ruined by mother has no profession due to the years through her
    protected $_interests;
    protected $_city;
    protected $_country;
    protected $_holidays;
    protected $_food;
    protected $_drinks;
    protected $_movies;
    protected $_music;
    protected $_clubs;
    protected $_sports;
    protected $_nogos;
    protected $_motto;
    protected $_welfare;

    // linkage to application tables
    protected $_questions;

    protected $_session; // remember me? serialized session might be stored here.
}