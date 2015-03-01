<?php
namespace Homepage;
use \Framework\Registry as Registry;

class Bootstrap {

    public function __construct() {
        
        try {

            /* Das ist ein gutes Aktivitätsdiagramm für den Anfang. */

            /* Später kannst du PHPStorm ja ein Aktivitätsgeneratorplugin schreiben. */
            define("DEBUG", true);
            define("APP_PATH", dirname(dirname(__FILE__)));
            define("WEB_PATH", APP_PATH . "/www");
            define("PLUGIN_PATH", APP_PATH . "/application/plugins");
            // 2. CLASS AUTOLOADER
            require_once("../framework/core.php");
            \Framework\Core::initialize();

            $session = new \Framework\Session(array(
                "type" => "server"
            ));
            Registry::set("session", $session->initialize());

            $cache = new \Framework\Cache(array("type" => "session"));
            $cache=$cache->initialize();
            Registry::set("cache", $cache);
            $cache->connect();

            if (DEBUG) {
                // SChrott von mir, nicht aus dem Buch.
                //\Framework\DebugMethods::attach();
                //\Framework\ErrorMethods::myCustomErrorHandler();
            }

            Registry::set("browser", \Framework\Browser::detectSupport());
            \Framework\Plugin::initialize();

//            \Framework\Localize::initialize("de_DE");

            $configuration = new \Framework\Configuration(array(
                "type" => "ini"
            ));
            Registry::set("configuration", $configuration->initialize());

            $database = new \Framework\Database();
            Registry::set("database", $database = $database->initialize());

            $cookie = new \Framework\Cookie(array(
                "type" => "server"
            ));
            Registry::set("cookie", $cookie->initialize());



            $pw_encrypt = new \Framework\Encrypt(array(
                "type" => "md5" // more soon, gotta read
            ));
            Registry::set("password_encryption", $pw_encrypt->initialize());
            

            $javascript = new \Framework\Javascript(array(
                "type" => "server"
            ));
            Registry::set("javascript", $javascript->initialize());


            $stylesheet = new \Framework\Stylesheet(array(
                "type" => "css" // and name are inconsistent
            ));
            Registry::set("stylesheet", $stylesheet->initialize());




            // These have to be moved out.
            // First with include failed.
            $css           = array(
                array("file" => WEB_PATH . "/styles/normalize.css"),
                array("file" => WEB_PATH . "/styles/GGS.css"),
                array("file" => WEB_PATH . "/styles/syntax.css"),
                array("file" => WEB_PATH . "/styles/tester.css"),
                array("file" => WEB_PATH . "/styles/lightbox.css"),
                array("file" => WEB_PATH . "/styles/styles.css"),
            );
            $scriptsTop    = array(
                array("file" => WEB_PATH . "/_scripts1.tmpl")
            );
            $scriptsBottom = array(
                array("file" => WEB_PATH . "/scripts/GGS.js"),
                array("file" => WEB_PATH . "/promise.js"),
                array("file" => WEB_PATH . "/crumb.js"),
                array("file" => WEB_PATH . "/lightbox.js"),
                array("file" => WEB_PATH . "/syntax.js"),
                array("file" => WEB_PATH . "/tester.js")
            );

            \Homepage\Controller::$includes = array(
                "css" => $css,
                "scripts_top" => $scriptsTop,
                "scripts_bottom" => $scriptsBottom
            );
            
            
        

            $editor = new \Framework\Editor(array(
                "type" => "tinymce"
            ));
            Registry::set("editor", $editor->initialize());



            $mail = new \Framework\Mail(array(
                "type" => "smtp"
            ));
            Registry::set("mail", $mail->initialize());



            $router = new \Framework\Router(
                array(
                    "url" => isset($_GET["url"]) ? $_GET["url"] : "index/index",
                    "extension" => isset($_GET["extension"]) ? $_GET["extension"] : "html"
                )
            );

            header("Access-Control-Allow-Origin: http://linux-swt.de https://ssl-110577.1blu.de");
            Registry::set("router", $router);
            // $router->load(WEB_PATH . "/routes.php");
            include("routes.php");
            $router->dispatch();

            unset($pw_encrypt);
            unset($javascript);
            unset($stylesheet);
            unset($router);
            unset($database);
            unset($cache);
            unset($session);
            
        } catch (\Exception $e) {
            \Framework\ErrorMethods::redirect($e);
        }
    }

    public function __destruct() {

    }

}

(new Bootstrap());
