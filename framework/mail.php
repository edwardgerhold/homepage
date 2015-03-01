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
 * Time: 08:48
 * User: Edward Gerhold
 * Project Edward´s Homepage
 */

namespace Framework;

use Framework\Mail\Exception as Exception;

class Mail extends Base {

    /**
     * @readwrite
     */
    protected $_type;
    /**
     * @readwrite
     */
    protected $_options;

    protected function _getExceptionForImplementation($method) {
        return new Exception\Implementation("{$method} not implemented");
    }

    public function __construct($options = array()) {
        parent::__construct($options);
    }

    public function initialize() {
        Events::fire("framework.mail.initialize.before", array($this->type, $this->options));
        if (!$this->_type) {
            throw new Exception("mailer class type not provided");
        }
        Events::fire("framework.mail.initialize.after", array($this->type, $this->options));
        switch ($this->_type) {
            case "smtp":
                return new Mail\Driver\SMTP($this->options);
            default:
                throw new Exception\Argument("invalid configuration type");
        }
    }
}
