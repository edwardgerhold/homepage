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
 * Date: 22.08.14
 * Time: 19:26
 * User: Edward Gerhold
 * Project Edward´s Homepage
 */

namespace Framework;

use Framework\Configuration as Configuration;
use Framework\Localize\Exception as Exception;
use Framework\Localize as Localize;

// We parse the language file with the configuration driver
// For that i added the options["assoc"] = true to parse into an associative array instead of a stdClass

class Localize {

    /**
     * @readwrite
     */
    protected static $_defaultPath = "application/languages";
    /**
     * @readwrite
     */
    protected static $_defaultName = "language";
    /**
     * @readwrite
     */
    protected static $_defaultLanguage = "de_DE";
    /**
     * @readwrite
     */
    protected static $_detectedLanguage;
    /**
     * @readwrite
     */
    protected static $_defaultType = "json";
    /**
     * @readwrite
     *
     * I am giving a hard coded example how the structure is looking like to make it easier to get it.
     */
    protected static $_texts = array(
        "de_DE" => array("errors" => "Ein Fehler ist aufgetreten"),
        "en_US" => array("errors" => "An error occured.")
    );
    /**
     * @readwrite
     */
    protected static $_languages = array(
        "de_DE" => "application/languages/language.de_DE.json",
        "en_US" => "application/languages/language.en_US.json"
    );

    /**
     * @readwrite
     */
    protected static $_locales = array(
        "de_DE" => array(LC_ALL, "Deutschland"),
        "en_US" => array(LC_ALL, "United States")
    );

    private function __construct() {
    }

    public static function initialize($language = null) {
        self::$_detectedLanguage = self::detectLanguage();
        self::add($language);
        self::add(self::$_detectedLanguage);
        self::$_defaultLanguage = $language;
        return self;
    }

    public static function add($language, $filename = null, $type = null) {

        if ($type == null)
            $type = strtolower(self::$_defaultType);

        if ($filename == null) {
            $path = self::$_defaultPath;
            $filename = self::$_defaultName;
            $language = self::_normalize($language);
            $format = $type;
            $language_file = "/{$path}/{$filename}.{$language}.{$format}";
        } else {
            $language_file = $filename;
        }

        if (!file_exists($language_file)) {
            return;
            // detection may find a language i don´t have. let´s run a default instead. dont throw.
            // throw new Exception\Argument("language file for {$language} not found");
        }

        self::$_languages[$language] = $language_file;

        /*
         * Thinking about it, this is worse than
         * using a Driver Class (Factory Method)
         * How we gonna add new language file formats
         * like XML.
         * Adding the one line of code or some logic here?
         *
         * AAAh, i have an idea,
         * we use a new Framework\Configuration(array("type"=>self::$_type, "assoc"=>"true"))->initialize()->parse($language_file);
         */

        /*
        if (strcmp($type, "json")) {
            self::$_texts[$language] = json_decode(file_get_contents($language_file), true);
        } else if (strcmp($type, "ini")) {
            self::$_texts[$language] = parse_ini_string(file_get_contents($language_file));
        }

        for it i extra added the "assoc" => "true" option.
        */

        /* I USE THE CONFIGURATION PARSE TO PARSE THE LANGUAGE TEXT FILE */
        self::$_texts[$language] = (new Configuration(array(
            "type" => $type,

            "options" => array(
                "assoc" => true
            )

        )))
            ->initialize()
            ->parse($language_file);
        /**
         * woa, eh? the book has so nice classes with this config, the registry, the session, the router, ...
         */
        return self;
    }

    protected static function _normalize($name) { // de_de to de_DE and DE_de to de_DE
        if (self::validate($name)) {
            $name = str_replace($name, "-", "_");
            $parts = explode("_", $name);
            return implode("_", array(strtolower($parts[0]), isset($parts[1]) ? strtoupper($parts[1]) : strtoupper($parts[0])));
        }
        return null;
        /*else {
            throw new Exception\Argument("invalid language argument");
        }*/
    }

    public static function validate($language) {
        /* bcp47 knowledge is missing */
        return preg_match("#^([a-zA-Z]+((-|_)[A-Za-z]+)?)$#", $language);
    }

    public static function get($key, $lang = null) {
        $x = array();

        // 1. versuche detected language
        if ($lang == null) $lang = self::$_detectedLanguage;
        // 2. versuche fallback language
        if (isset(self::$_texts[$lang])) $x = self::$_texts[$lang];
        else {
            $lang = self::$_defaultLanguage;
            if (isset(self::$_texts[$lang])) $x = self::$_texts[$lang];
        }
        // 3. return or else return key, don´t throw
        // else throw new Exception\Language("language {$lang} does not exist");
        return isset($x[$key]) ? $x[$key] : $key;
    }

    public static function set($lang = null) {
        if (!isset($lang)) $lang = self::$_detectedLanugage;
        else $lang = self::_normalize($lang);
        $locale = self::$_locales[$lang];
        call_func_array("setlocale", $locale);
    }

    /* not mature features, more localization and language detection */

    public static function detectLanguage() {
        global $_SERVER;
        $accept = "";
        $language = self::$_defaultLanguage;
        $short = "";
        $q = "";

        if (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"])) {
            $accept = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
        }
        if (isset($accept)) {

            // en-US, en; q=0.5

            $parts = split(",", $accept);
            if (isset($parts[0])) {
                // en-US
                $language = $parts[0];
                /*
                // ,en;
                $parts = split(";", $parts);
                if (isset($parts[0])) {
                    $short = $parts[0];
                }
                // q=0.5
                if (isset($parts[1])) {
                    $q = $parts[1];
                }
                */
            }
            // got to get information what´s real a return value here
        }

        return self::_normalize($language);
    }

    private function __clone() {
    }

    public static function getAvailableLanguages() {
        $available = array();
        // this is checking if a text is existing for, too.
        foreach (self::$_languages as $language) {
            if (isset(self::$_texts[$language])) {
                $available[] = $language;
            }
        }
        return $available;
    }

    public static function setLanguage($language) {
        if (isset(self::$_texts[$language])) {
            self::$_detectedLanguage = $language;
            return true;
        }
        return false;
    }
}