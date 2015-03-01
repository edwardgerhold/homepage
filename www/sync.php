
<?php
/*

    i use this file to create and remove databases on the fly

    when doing initial development
    
    just uncomment the pieces where new tables are created with $db->sync(new Model());
    
    sync is from the book, it has a superb db driver
    

    i didnt develop long, just a few hours, days later until i got 
    interrupted by life

*/
namespace Homepage;

class Bootstrap {

    static public function load () {
        try {
    	    define("APP_PATH", dirname(dirname(__FILE__)));
            define("WEB_PATH", APP_PATH + "/www");
            require_once("../framework/core.php");
            \Framework\Core::initialize();
            $configuration = new \Framework\Configuration(array(
                "type"=>"ini"
            ));
            \Framework\Registry::set("configuration", $configuration->initialize());
            $database = new \Framework\Database();
            \Framework\Registry::set("database", $database = $database->initialize());

    	    echo "connecting";
            $database->connect();

    	    //echo $encrypt->encrypt("abcdef");

    	    echo "sync";
    	    

	    
    	// uncomment to create new tables    

/*            $database->sync(new \Homepage\Video());
            $database->sync(new \Homepage\Audio());
            $database->sync(new \Homepage\Source());*/
            /*
            $database->sync(new \Homepage\Crc());
    	    $database->sync(new \Homepage\Project());
    	    $database->sync(new \Homepage\UserGroup());
            */

            //$database->sync(new \Homepage\GroupRelation());

            echo "disconnect<br>";
            $database->disconnect();
            unset($database);

//            $debugger->remove();
//            unset($debugger);

        } catch(\Exception $e) {
	        throw $e;
        }
    }
}

Bootstrap::load();

