<?php
namespace Homepage;

class Bootstrap {
    static public function load() {
        try {

            define("APP_PATH", dirname(dirname(__FILE__)));

            require_once("../framework/core.php");
            \Framework\Core::initialize();

            \Framework\DebugMethods::attach();

            $configuration = new \Framework\Configuration(array(
                "type" => "ini"
            ));
            \Framework\Registry::set("configuration", $configuration->initialize());

            $database = new \Framework\Database();
            // Da ich keine $options angab, wird die .ini versucht.
            \Framework\Registry::set("database", $database = $database->initialize());

            $database->connect();
            echo "sync user<br>";

            $database->sync(new \Homepage\User());
            echo "sync friend<br>";
            $database->sync(new \Homepage\Friend());
            echo "sync messages<br>";
            $database->sync(new \Homepage\Message());
            echo "sync file<br>";
            $database->sync(new \Homepage\File());

            echo "sync posts<br>";
            $database->sync(new \Homepage\Post());

            echo "sync article<br>";
            $database->sync(new \Homepage\Article());
            echo "sync comment<br>";
            $database->sync(new \Homepage\Comment());
            echo "sync category<br>";
            $database->sync(new \Homepage\Category());

            echo "disconnect<br>";
            $database->disconnect();
            unset($database);
            unset($session);
            unset($router);
//            $debugger->remove();
//            unset($debugger);

        } catch (\Exception $e) {
            print_r($e);
            throw $e;
        }
    }
}

Bootstrap::load();

