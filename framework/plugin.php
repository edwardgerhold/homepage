<?php
namespace Framework;
class Plugin {

    private function __construct() {
    }

    private function __clone() {
    }

    public static function initialize() {
        $iterator = new \DirectoryIterator(PLUGIN_PATH);
        foreach ($iterator as $item) {
            if (!$item->isDot() && $item->isDir()) {
                include(PLUGIN_PATH . "/" . $item->getFilename() . "/initialize.php");
            }
        }
    }
}