<?php


namespace Framework\Cookie\Driver;

use Framework\Cookie as Cookie;

class Server extends Cookie\Driver {
    /**
     * @read
     */
    protected $_prefix = "app_";
    /**
     * @readwrite
     */
    protected $_name;
    /**
     * @readwrite
     */
    protected $_expire;
    /**
     * @readwrite
     */
    protected $_path;
    /**
     * @readwrite
     */
    protected $_domain;
    /**
     * @readwrite
     */
    protected $_secure = null;
    /**
     * @readwrite
     */
    protected $_httponly = null;

    public function __construct($options = array()) {
        parent::__construct($options);
    }

    public function get($name, $default = null) {
        if (isset($_COOKIE[$this->prefix . $name])) {
            return $_COOKIE[$this->prefix . $name];
        }
        return $default;
    }

    public function has($name) {
        return isset($_COOKIE[$this->prefix . $name]);
    }

    public function set() {
        $arguments = func_get_args();
        if (!sizeof($arguments)) {
            $prefix = $this->_prefix;
            $name = $this->_name;
            $expire = $this->_expire;
            $path = $this->_path;
            $domain = $this->_domain;
            $secure = $this->_secure;
            $httponly = $this->_httponly;
            setcookie($prefix.$name, $expire, $path, $domain, $secure, $httponly);
        } else {
            call_user_func_array("setcookie", $arguments);
        }
    }

    public function erase($name) {
        unset($_COOKIE[$this->prefix . $name]);
        return $this;
    }

    public function __destruct() {
    }
}

?>