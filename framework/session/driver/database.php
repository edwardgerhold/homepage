<?php

namespace Framework\Session\Driver;

use Framework\Session as Session;

class Database extends Session\Driver {
    /**
     * @read
     */
    protected $_prefix = "app_";

    public function __construct($options = array()) {
        parent::__construct($options);
    }

    public function get($key, $default = null) {
        return $default;
    }

    public function set($key, $value) {
        return $this;
    }

    public function erase($key) {
        return $this;
    }

    public function __destruct() {
    }
}

?>