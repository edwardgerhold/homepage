<?php
/**
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
 * Date: 21.08.14
 * Time: 20:27
 * User: Edward Gerhold
 * Project Edward´s Homepage
 */

namespace Framework;

class Markup {

    protected function __construct() {
        // do nothing
    }

    protected function __clone() {
        // do nothing
    }

    public static function errors($array, $key, $separator = "<br />", $before = "<br />", $after = "") {
        if (isset($array[$key])) {
            return $before . join($separator, $array[$key]) . $after;
        }
        return "";
    }

    /**
     *
     *
     * I added a generic function to create HTML from array information
     *
     *
     * Markup::markup(array(
     *      "tag" => "script"
     *      "attlist" => array(  "async" => null, "src" => "myfile.js" )
     *      "content" => "alert(\"Hi\");"
     *      ));
     *
     *
     * results in <script async src='myfile.js'>alert("Hi")</script>
     *
     *
     * Markup::markup(array(
     *      "tag" => "input"
     *      "attlist" => array("type"=>"text", "name"=>"test")
     *      "empty" => true
     * ));
     *
     *
     * "content" => "" instead is the empty content
     *
     *
     * And that added another one to combine array("key" => "value", "key"=>"value") to "key='value' key='value'
     *
     * Markup::attributes(array("key"=>"value", "key2"=>"value2"))
     * returns
     * "key='value' key2='value'"
     * "key" => null returns "key"
     */

    public static function attributes($array) {
        $atts = array();
        foreach ($array as $key => $value) {
            if ($value === null) {
                $atts[] = "{$key}";
            } else {
                $atts[] = "{$key}='{$value}'";
            }
        }
        return join(" ", $atts);
    }

    public static function markup($array, $xml = false) {
        // the tag
        if (!isset($array["tag"])) $tag = "div";
        else $tag = strtolower($array["tag"]);

        // closing slash <br/> with xml
        if (isset($array["xml"]) && $array["xml"] === true) {
            $emptyTagCloser = "/";
        } else {
            $emptyTagCloser = "";
        }

        // attributes
        if (isset($array["attlist"])) {
            $atts = self::attributes($array["attlist"]);
            if (sizeof($atts)) $atts = " " . $atts;
        } else {
            $atts = "";
        }

        // non empty element
        if (!isset($array["empty"]) || $array["empty"] === false) {

            $template = "<{$tag}%s>%s</{$tag}>";
            if (!isset($array["content"])) {
                $content = "";
            }
            return sprintf($template, $atts, $content);
            // empty element
        } else if ($array["empty"]) {

            $template = "<{$tag}%s{$emptyTagCloser}>";
            return sprintf($template, $atts);
        }
    }

    public static function _normalizeUrl($url) {
        if (empty($url)) return $url;
        if (!preg_match("#^(http[s]?://)#", $url)) {
            if (!preg_match("#[\w]+(://)", $url)) $url = "http://" . $url;
        }
        return $url;
    }

    public static function anchor($url, $text = "", $title = "") {
        $_url = self::_normalizeUrl($url);
        if (empty($_url)) return $_url;
        $_title = " title='{$_url}'";
        $_text = $_url;
        if ($title != "") {
            $_title = " title='{$title}'";
        }
        if ($text != "") {
            $_text = "{$text}";
        }
        $template = "<a href='%s'%s>%s</a>";
        return sprintf($template, $_url, $_title, $_text);
    }
}