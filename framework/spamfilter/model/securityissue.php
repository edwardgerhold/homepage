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
 * Time: 16:30
 * User: Edward Gerhold
 * Project Edward´s Homepage
 */

/**
 *
 *
 * Überdenke den Pfad:
 *
 * Framework\Model\SecurityIssue
 * Framework\Model\Cache
 * Framework\Model\Session
 *
 * anstatt
 *
 * Framework\Spamfilter\Model\SecurityIssue
 * Framework\Cache\Model\Cache
 * Framework\Session\Model\Session
 *
 *
 */

namespace Framework\Spamfilter\Model;
/**
 * Class SecurityIssue
 * @package Homepage
 * @description If a user causes problems, i can log what happened, not what the user does. Just the affair.
 */
class SecurityIssue extends Model {
    /**
     * @read
     */
    protected $_table = "framework_securityissue";

    protected $_id;
    /**
     * @column
     * @type integer
     * @readwrite
     *
     */
    protected $_user;
    /**
     * @column
     * @type text
     * @readwrite
     */
    protected $_reason;
    /**
     * @column
     * @type text
     * @readwrite
     */
    protected $_data;
    /**
     * @column
     * @readwrite
     * @index
     * @type boolean
     */
    protected $_live;
    /**
     * @column
     * @readwrite
     * @index
     * @type boolean
     */
    protected $_deleted;
    /**
     * @column
     * @readwrite
     * @type datetime
     */
    protected $_created;
    /**
     * @column
     * @readwrite
     * @type datetime
     */
    protected $_modified;

    public function save() {
        $primary = $this->getPrimaryColumn();
        $raw     = $primary["raw"];
        if (empty($this->$raw)) {
            $this->setCreated(date("Y-m-d H:i:s"));
            $this->setDeleted(false);
            $this->setLive(true);
        }
        $this->setModified(date("Y-m-d H:i:s"));
        parent::save();
    }
}