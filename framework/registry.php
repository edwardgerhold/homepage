<?php
namespace Framework;
/**
 * @description This class allows access to it´s registered objects from everywhere.
 *
 * Class Registry
 * @package Framework\Registry
 */
class Registry {
    /**
     * @readwrite
     */
    protected static $_reg = array();

    protected function __construct() {
    }

    protected function __clone() {
    }

    public static function get($n, $default = null) {
        if (isset(self::$_reg[$n])) {
            return self::$_reg[$n];
        }
        return $default;
    }

    public static function set($n, $v = null) {
        if (!isset(self::$_reg[$n])) {
            self::$_reg[$n] = $v;
        }
    }

    public static function erase($n) {
        if (isset(self::$_reg[$n])) {
            unset(self::$_reg[$n]);
        }
    }
}

?>