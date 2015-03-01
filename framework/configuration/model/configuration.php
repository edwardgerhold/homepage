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
* Date: 24.08.14
* Time: 11:39
* User: Edward Gerhold
* Project Edward´s Homepage
*/


namespace Framework\Configuration\Model;


class Configuration extends \Framework\Model {
    /**
     * @readwrite
     */
    protected $_table = "framework_configuration";
    /**
     * @column
     * @primary
     * @type autonumber
     * @readwrite
     * @label unique id
     */
    protected $_id;
    /**
     * @column
     * @type text
     * @readwrite
     * @label configuration field key
     */
    protected $_key;
    /**
     * @column
     * @type text
     * @readwrite
     * @label configuration field value
     */
    protected $_value;
    /**
     * @column
     * @readwrite
     * @type text
     * @label configuration path
     */
    protected $_path;
    /**
     * @column
     * @readwrite
     * @type datetime
     * @label created time this config entry
     */
    public function save() {
        $primary = $this->getPrimaryColumn();
        $raw = $primary["raw"];
        if (empty($this->$raw)) {
            $this->setCreated(date("Y-m-d H:i:s"));
        }
        parent::save();
    }
}