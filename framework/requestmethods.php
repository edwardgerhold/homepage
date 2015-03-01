<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 18.07.14
 * Time: 13:42
 */

namespace Framework;

class RequestMethods {

    private function __construct() {
    }

    private function __clone() {
    }

    protected static $_ajax;

    static function get($name, $default = "") {
        global $_GET;
        if (isset($_GET[$name])) {
            $data = $_GET[$name];
            //$data = str_replace($data, array("<script", "</script"), array("&lt;xsscript", "&lt;/xsscript"));
            return $data;
        }
        return $default;
    }

    static function post($name, $default = "") {
        global $_POST;
        if (isset($_POST[$name])) {
            $data = $_POST[$name];
            // protect yourself
           // $data = str_replace($data, array("<script", "</script"), array("&lt;xsscript", "&lt;/xsscript"));
            return $data;
        }
        return $default;
    }

    static function request($name, $default = "") {
        global $_REQUEST;
        if (isset($_REQUEST[$name])) {
            $data = $_REQUEST[$name];
           // $data = str_replace($data, array("<script", "</script"), array("&lt;xsscript", "&lt;/xsscript"));
            return $data;
        }
        return $default;
    }

    static function server($name, $default = "") {
        global $_SERVER;
        if (isset($_SERVER[$name])) {
            return $_SERVER[$name];
        }
        return $default;
    }

    // some i added when i begun the book
    // and typed down methods and classes

    static function script_name() {
        return $_SERVER["SCRIPT_NAME"];
    }

    static function request_uri() {
        return $_SERVER["REQUEST_URI"];
    }

    static function query_string() {
        return $_SERVER["QUERY_STRING"];
    }

    static function request_method() {
        return $_SERVER["REQUEST_METHOD"];
    }

    /**
     *
     * Der Request sollte auch die incoming Header des Browsers
     * lesen und anzeigen. Das Gegenstück zu header. getheaders
     */

    public static function headers() {
        //return array();
        if (function_exists("getallheaders")) {
            return getallheaders();
        } else {
            return array();
        }
    }

    public static function method() {
        return $_SERVER["REQUEST_METHOD"];
    }

    public static function host() {
        return $_SERVER["HTTP_HOST"];
    }

    public static function isAjax() {
        if (isset(self::$_ajax) && !empty(self::$_ajax)) {
            return self::$_ajax;
        }
        $headers = self::headers();
        foreach ($headers as $key => $value) {
            if ($key == "Ajax" && $value == "Yes") {
                self::$_ajax = true;
                return true;
            }
        }
        self::$_ajax = false;
        return false;
    }
}
