<?php

/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 25.07.14
 * Time: 23:59
 */

namespace Framework\Javascript\Driver;

use Framework\Javascript as Javascript;
use Framework\Javascript\Script as Script;

/**
 *
 * This driver should a) deliver script tags with script or attribs
 * b) consider polling the route, btw. do that with a new router.
 *
 * Class Server
 * @package Framework\Javascript\Driver
 */
class Server extends Javascript\Driver {
    /**
     * @readwrite
     */
    protected $_scripts;

    public function __construct($options = array()) {
        parent::__construct($options);
    }

    public function initialize() {
        $this->scripts = array();
        return $this;
    }

    public function add($data) {
        if (is_array($data)) {
            $this->_scripts[] = new Script($data);
        } else if ($data instanceof Script) {
            $this->_scripts[] = $data;
        } else {
            throw new Exception("invalid argument to function add");
        }
        return $this;
    }

    public function get() {
        $scripts = "";
        foreach ($this->scripts as $script) {
            $scripts .= $script->get() . "\n";
        }
        return $scripts;
    }
} 