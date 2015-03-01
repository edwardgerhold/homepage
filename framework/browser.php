<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 17.08.14
 * Time: 18:21
 */

namespace Framework;

class Browser extends Base {

    public static function sniff($agent) {
        $browser = "#(opera|ie|firefox|chrome|version)[\s\/:]([\w\d\.]+)?.*?(safari|version[\s\/:]([\w\d\.]+)|$)#i";
        $platform = "#(ipod|iphone|ipad|webos|android|win|mac|linux)#i";
        if (preg_match($browser, $agent, $browsers)) {
            if (preg_match($platform, $agent, $platforms)) {
                $platform = $platforms[1];
            } else {
                $platform = "other";
            }
            return array(
                "browser" => (strtolower($browsers[1]) == "version") ? strtolower($browsers[3]) : strtolower($browsers[1]),
                "version" => (float)(strtolower($browsers[1]) == "opera") ? strtolower($browsers[4]) : strtolower($browsers[2]),
                "platform" => strtolower($platform)
            );
        }
        return false;
    }

    public static function detectSupport() {
        if (isset($_SERVER["HTTP_USER_AGENT"]))
            return self::sniff($_SERVER["HTTP_USER_AGENT"]);
        else return self::sniff("");
    }
}