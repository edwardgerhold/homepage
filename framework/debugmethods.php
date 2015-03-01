<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 23.07.14
 * Time: 18:44
 */
/***
 * This class is one of my extensions. New official copyright comments follow
 * to make sure the book gets the most of the good php code which was first
 */
namespace Framework;

class DebugMethods {

    private function __construct() {
    }

    private function __clone() {
    }

    /**
     * @readwrite
     */
    static public $events = array(
        "framework.controller.render.before",
        "framework.controller.render.after",
        "framework.view.render.before",
        "framework.view.render.after",
        "framework.configuration.initialize.before",
        "framework.configuration.initialize.after",
        "framework.controller.initialize.before",
        "framework.controller.initialize.after",
//        "framework.database.initialize.before",
//        "framework.database.initialize.after",
        "framework.cache.initialize.before",
        "framework.cache.initialize.after",
        "framework.javascript.initialize.before",
        "framework.javascript.initialize.after",
        "framework.stylesheet.initialize.before",
        "framework.stylesheet.initialize.after",
        "framework.view.initialize.before",
        "framework.view.initialize.after",
        "framework.view.construct.before",
        "framework.view.construct.after",
        "framework.afterhooks.before",
        "framework.afterhooks.after",
        "framework.beforehooks.before",
        "framework.beforehooks.after",
        "framework.controller.construct.before",
        "framework.controller.construct.after",
        "framework.controller.destruct.before",
        "framework.controller.destruct.after",
        "framework.javascript.initialize.after",
        "framework.javascript.initialize.before"
    );

    static public function br($data) {
        echo "{$data}<br>\n";
        return self;
    }

    static public function pre($data) {
        echo "<pre>{$data}</pre>\n";
        return self;
    }

    static public function print_r($data) {
        echo "<pre>";
        $str = print_r($data, true);
        echo $str;
        echo "<pre>";
        return self;
    }

    static public function attach() {
        foreach (self::$events as $event) {
            Events::add($event, self::_handler($event));
        }
        return self;
    }

    static public function remove() {
        foreach (self::$events as $event) {
            Events::remove($event, self::_handler($event));
        }
        return self;
    }

    static protected function _handler($event) {
        $names = explode(".", $event);
        $handler = "";
        foreach ($names as $i => $name) {
            if ($i > 0) {
                $name = ucfirst($name);
            }
            $handler .= $name;
        }
        if (isset(self::$handler)) {
            return self::$handler;
        } else {
            $code = <<<CODE
                \$_event = "{$event}";
                \$args = func_get_args();
                \$str = print_r(\$args, true);
                echo "{\$_event}: {\$str}<br>\n";
CODE;
            return create_function("", $code);
        }
    }

    static public function httpheaders() {
        echo http_get_request_headers();
    }
}
