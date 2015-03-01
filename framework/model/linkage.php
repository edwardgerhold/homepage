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
* Time: 20:56
* Project: Edward´s Homepage
*/

namespace Framework\Model;

use \Framework\Model as Model;

/**
 * Class Linkage
 * @package Framework\Model
 *
 * A generic class for associations between two objects.
 * The type of association is identified by the type fields,
 * coz the u and v just contain the id fields to store.
 */

class Linkage extends BaseFields {

    // should be renamed after subclassing for each kind of association
    protected $_table = "framework_linkage";


    /**
     * @readwrite
     * @column
     * @type number
     * @validate required
     */
    protected $_u;
    /**
     * @readwrite
     * @column
     * @type number
     * @validate required
     */
    protected $_v;

    /**
     * @readwrite
     * @column
     * @type text
     *
     */

} 