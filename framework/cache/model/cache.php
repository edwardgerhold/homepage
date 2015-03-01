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
 * Date: 23.08.14
 * Time: 18:13
 * User: Edward Gerhold
 * Project Edward´s Homepage
 */

namespace Framework\Cache\Model;

class Cache extends \Framework\Model {
    /**
     * @readwrite
     */
    protected $_table = "framework_cache";

    /**
     * @primary
     * @column
     * @type autonumber
     */
    protected $_id;
    /**
     * @column
     * @type text
     */
    protected $_key;
    /**
     * @column
     * @type text
     * @readwrite
     */
    protected $_value;
    /**
     * @column
     * @type datetime
     * @readwrite
     */
    protected $_created;
    /**
     * @column
     * @type datetime
     * @readwrite
     */
    protected $_expires;


    public function save() {
        $primary = $this->getPrimaryColumn();
        $raw = $primary["raw"];
        if (empty($this->$raw)) {
            $this->setCreated(date("Y-m-d H:i:s"));
        }
        parent::save();
    }
}