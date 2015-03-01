<?php

namespace Framework\Cache\Driver;

use \Framework\Cache as Cache;
use \Framework\Cache\Exception as Exception;

class Session extends Cache\Driver {

    /**
     * @readwrite
     */
    protected $_service;

    /**
     * @readwrite
     */
    protected $_isConnected = false;

    /**
     * @readwrite
     */
    protected $_prefix = "app_cache_";

    protected function _isValidService() {
        return $this->_isConnected;
    }
    public function connect() {
        global $_SESSION;
        try {
            if (isset($_SESSION)) {
                $this->_service = &$_SESSION;
                $this->isConnected = true;
            }
        } catch (\Exception $e) {
            throw new Exception\Service("unable to connect to service");
        }
        return $this;
    }

    public function disconnect() {
        if ($this->_isValidService()) {
            $this->isConnected = false;
        }
        return $this;
    }

    public function get($key, $default = null) {
        global $_SESSION;
        if (!$this->_isValidService()) {
            throw new Exception\Service("Not connected to a valid service");
        }
        $value = $default;
        if (isset($_SESSION[$this->_prefix.$key])) {
            $value = unserialize($_SESSION[$this->_prefix.$key]);
        }
        return $value;
    }
    
    public function has($key) {
        global $_SESSION;
        return isset($_SESSION[$this->_prefix.$key]);
    }

    public function set($key, $value, $duration = 120) {
        global $_SESSION;
        if (!$this->_isValidService()) {
            throw new Exception\Service("Not connected to a valid service");
        }
        $_SESSION[$this->_prefix.$key] = serialize($value);
        return $this;
    }

    public function erase($key) {
        global $_SESSION;
        if (!$this->_isValidService()) {
            throw new Exception\Service("Not connected to a valid service");
        }
        unset($_SESSION[$this->_prefix.$key]);
        return $this;
    }
}
