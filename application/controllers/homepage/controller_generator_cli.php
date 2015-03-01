<?php

use Framework\Base as Base;
use Framework\Registry as Registry;
use Framework\Configuration as Configuration;

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
* Time: 20:50
* Project: Edward´s Homepage
*/

$controllerName = "";
$controllerActions = array(
    // default actions, alle true, bis eine auf false gesetzt wird.
    // ausserdem: actions für templates addierbar
    "add"=>true,
    "edit"=>true,
    "all"=>true,
    "delete"=>true,
    "undelete"=>true,
    "search"=>true
);

class ControllerGenerator extends Base {
    function __construct($options) {
    }
}
