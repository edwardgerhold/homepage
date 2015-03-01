<?php
namespace Framework;

class Core {
    /**
     * @readwrite
     */
    private static $_loaded = array();
    /**
     * @readwrite
     */

    private static $_paths = array(

        "/application/controllers",
        "/application/models",
        "/application",
        "/application/libraries",
        ""
    );

    public static function initialize() {
        if (!defined("APP_PATH")) {
            throw new \Exception("APP_PATH not defined");
        }
        if (get_magic_quotes_gpc()) {
            $_globals = array("_POST", "_GET", "_COOKIE", "_REQUEST", "_SESSION");
            foreach ($_globals as $global) {
                if (isset($GLOBALS[$global])) {
                    $GLOBALS[$global] = self::_clean($GLOBALS[$global]);
                }
            }
        }
        $paths = array_map(function ($item) {
            return APP_PATH . $item;
        }, self::$_paths);
        $paths[] = get_include_path();
        set_include_path(join(PATH_SEPARATOR, $paths));
        spl_autoload_register(__CLASS__ . "::autoload");
    }

    protected static function _clean($array) {
        if (is_array($array)) {
            return array_map(__CLASS__ . "::clean", $array);
        }
        return stripslashes($array);
    }

    public static function autoload($class) {
        self::_autoload($class);
    }

    protected static function _autoload($class) {
        //Events::fire

        $paths = explode(PATH_SEPARATOR, get_include_path());
        $flags = PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE;
        $file = strtolower(str_replace("\\", DIRECTORY_SEPARATOR, trim($class, "\\"))) . ".php";

        foreach ($paths as $path) {
            $combined = $path . DIRECTORY_SEPARATOR . $file;
            if (file_exists($combined)) {
                include($combined);
                return;
            }
        }
        throw new \Exception("{$class} not found");
    }

    protected static function debug() {
        /* temp method */
        var_dump(self::$_loaded);

        var_dump(self::$_paths);
    }
} 