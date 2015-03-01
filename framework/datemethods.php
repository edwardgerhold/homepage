<?php
/**
 *
 * Edward´s Homepage is written by Edward Gerhold
 * http://github.com/edwardgerhold/homepage
 * Edward´s Homepage is originally developed for
 * http://linux-swt.de (c) 2014 Edward Gerhold
 * This is free and open source software for you.
 *
 * The Homepage Application Framework bases on the
 * "Pro PHP MVC" Framework from the namely equal book
 * by Chris Pitt released by http://apress.com.
 *
 * The application is Edward´s Homepage.
 * Load it into a PHPStorm evaluation copy from
 * http://jetbrains.com for the ultimate experience.
 *
 * Created by PhpStorm
 * Date: 24.08.14
 * Time: 14:05
 * User: Edward Gerhold
 * Project Edward´s Homepage
 */

namespace Framework;

class DateMethods {

    private function __construct() {
    }

    private function __clone() {
    }

    public static function isExpired($date) {
        if (is_int($date)) {
            // beachte als timestamp
        } else if (is_string($date)) {
            // beachte datetime
        }
    }
}