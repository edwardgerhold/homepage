<?php


namespace Framework;

class ErrorMethods {

    /**
     * How to upgrade:
     *
     * Custom Handler collects Exceptions
     * Custom Function throws an Exception with all Attached (a class Exceptions)
     *
     * Self has
     * $_pendingExceptions
     * $_lastExceptions
     *
     * Currently the lifetime and the usage is not known, just an idea, properly replaced if unusable
     *
     */

    private function __construct() {
    }

    private function __clone() {
    }

    /**
     * @readwrite
     */

    protected static $_exceptions = array(
        "500" => array(

            "Framework\\Cache\\Exception",
            "Framework\\Cache\\Exception\\Argument",
            "Framework\\Cache\\Exception\\Implementation",
            "Framework\\Cache\\Exception\\Service",

            "Framework\\Configuration\\Exception",
            "Framework\\Configuration\\Exception\\Argument",
            "Framework\\Configuration\\Exception\\Implementation",
            "Framework\\Configuration\\Exception\\Syntax",

            "Framework\\Controller\\Exception",
            "Framework\\Controller\\Exception\\Argument",
            "Framework\\Controller\\Exception\\Implementation",

            "Framework\\Core\\Exception",
            "Framework\\Core\\Exception\\Argument",
            "Framework\\Core\\Exception\\Implementation",
            "Framework\\Core\\Exception\\Property",
            "Framework\\Core\\Exception\\ReadOnly",
            "Framework\\Core\\Exception\\WriteOnly",

            "Framework\\Database\\Exception",
            "Framework\\Database\\Exception\\Argument",
            "Framework\\Database\\Exception\\Implementation",
            "Framework\\Database\\Exception\\Service",
            "Framework\\Database\\Exception\\Sql",

            "Framework\\Model\\Exception",
            "Framework\\Model\\Exception\\Argument",
            "Framework\\Model\\Exception\\Connector",
            "Framework\\Model\\Exception\\Implementation",
            "Framework\\Model\\Exception\\Primary",
            "Framework\\Model\\Exception\\Type",
            "Framework\\Model\\Exception\\Validation",

            "Framework\\Request\\Exception",
            "Framework\\Request\\Exception\\Argument",
            "Framework\\Request\\Exception\\Implementation",
            "Framework\\Request\\Exception\\Response",

            "Framework\\Router\\Exception",
            "Framework\\Router\\Exception\\Argument",
            "Framework\\Router\\Exception\\Implementation",

            "Framework\\Session\\Exception",
            "Framework\\Session\\Exception\\Argument",
            "Framework\\Session\\Exception\\Implementation",

            "Framework\\Template\\Exception",
            "Framework\\Template\\Exception\\Argument",
            "Framework\\Template\\Exception\\Implementation",
            "Framework\\Template\\Exception\\Parser",

            "Framework\\View\\Exception",
            "Framework\\View\\Exception\\Argument",
            "Framework\\View\\Exception\\Data",
            "Framework\\View\\Exception\\Implementation",
            "Framework\\View\\Exception\\Renderer",
            "Framework\\View\\Exception\\Syntax"
        ),

        "404" => array(
            "Framework\\Core\\Exception",
            "Framework\\Router\\Exception\\Action",
            "Framework\\Router\\Exception\\Controller"
        )
    );

    protected static $_pendingExceptions = array();
    protected static $_lastExceptions = array();
    protected static $_lastErrors = array();

    public static function printMessage($exception) {
        $message = $exception->getMessage();
        $previous = $exception->getPrevious();
        if ($previous) $previous = $previous->getMessage();
        echo "Little debugging help: exception.getMessage: {$message}<br>\n";
        if (!empty($previous)) echo "exception.getPrevious.getMessage: {$previous}<br>\n";
    }

    public static function redirect($exception, $newErrorCode = 500) {

        /**
         * solution: error_page flag to say, that an error is set.
         *
         * Setting error_page to true in the Registry
         *
         * is checked in:
         *
         *  \Framework\Controller->render()
         *
         * and if error_page is true,
         * it doesn´t render anymore
         *
         * maybe i should throw the exception again.
         *
         */

        // disabled. i rethrow the error in errorpage::redirect now
        // Registry::set("error_set", true);

        /*
         *
         *  "error_set" is evaluated in controller->render()
         *  before doing the rendering, but maybe just rethrowing
         * the exception stops the program again and this flag
         * isn´t needed.
         */

        $exception_class = get_class($exception);
        foreach (self::$_exceptions as $template => $classes) {
            foreach ($classes as $class) {
                if ($class == $exception_class) {
                    header("Content-type: text/html");
                    include(APP_PATH . "/application/views/errors/{$template}.php");
                    throw $exception;
                    //die();
                }
            }
        }
        header("Content-type: text/html");
        include(APP_PATH . "/application/views/errors/unknown.php");
        throw $exception;
    }

    public static function myCustomErrorHandler() {
        self::setCustomErrorHandler(function ($code, $error, $line, $column, $x) {

            self::$_lastErrors[] = $error;
            $x = print_r($x, true);
            $msg = "\n\n{$code}***{$error}***{$line}***{$column}***{$x}\n\n";
            echo $msg;
            throw new Exception($msg);
        }, null);
    }

    public static function setCustomErrorHandler($callback, $error_types = null) {
        if ($error_types == null) $error_types = E_ALL | E_STRICT;
        set_error_handler($callback, $error_types);
    }

    public static function myCustomExceptionHandler() {
        self::setCustomExceptionHandler(function ($exception) {
            echo "CUSTOM EXCEPTION HANDLING";
            self::$_lastExceptions[] = $exception;
            throw $exception;
        });
    }

    public static function setCustomExceptionHandler($callback) {
        set_exception_handler($callback);
    }

    public static function addPendingException($e) {
        self::$_pendingExceptions[] = $e;
    }

    public static function throwPendingExceptions() {
        $e = new Exceptions("Throwing a collection of exceptions, the Messages follow.");
        $e->exceptions = self::$_pendingExceptions;
        self::$_pendingExceptions = array();
        // last exceptions is added by custom handler.
        throw $e;
    }
}