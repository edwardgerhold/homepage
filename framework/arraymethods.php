<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 17.07.14
 * Time: 11:52
 */

namespace Framework;

class ArrayMethods {
    protected function __construct() {
    }

    protected function __clone() {
    }

    static public function clean($array) {
        return array_filter($array, function ($item) {
            return !empty($item);
        });
    }

    static public function first($a) {
        if (sizeof($a) == 0) {
            return null;
        }
        $keys = array_keys($a);
        return $a[$keys[0]];
    }

    static public function last($a) {
        if (sizeof($a) == 0) {
            return null;
        }
        $keys = array_keys($a);
        return $a[sizeof($keys) - 1];
    }

    static public function trim($array) {
        return array_map(function ($item) {
            return trim($item);
        }, $array);
    }

    static public function flatten($array, $return = array()) {
        foreach ($array as $key => $value) {
            if (is_array($value) || is_object($value)) {
                $return = self::flatten($value, $return);
            } else {
                $return[] = $value;
            }
        }
        return $return;
    }

    static public function toObject($array) {
        $result = new \stdClass();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result->{$key} = self::toObject($value);
            } else {
                $result->{$key} = $value;
            }
        }
        return $result;
    }

    static public function toQueryString($array) {
        return http_build_query(self::clean($array));
    }
}
