<?php
namespace Framework;

use \Framework\Router\Exception as Exception;
use \Framework\Iterator as Iterator;

class Router extends Base {
    /**
     * @readwrite
     */
    protected $_url;
    /**
     * @readwrite
     */
    protected $_extension;
    /**
     * @readwrite
     */
    protected $_controller;
    /**
     * @read
     */
    protected $_action;
    /**
     * @readwrite
     */
    protected $_routes = array();

    /**
     * $router->setRedirect($controller, $action, $params, $url);
     * can be called by the controller via $this->setRedirect (same args)
     * @readwrite
     */

    protected $_followup;

    public function __construct($options = array()) {
        parent::__construct($options);
    }

    public static function addSimpleRoutes($router, $routes) {
        foreach ($routes as $route) {
            $router->addRoute(new Router\Route\Simple($route));
        }
    }

    public static function addSimpleRoute($router, $route) {
        $router->addRoute(new Router\Route\Simple($route));
    }

    public static function addRegexRoutes($router, $routes) {
        foreach ($routes as $route) {
            $router->addRoute(new Router\Route\Regex($route));
        }
    }

    public static function addRegexRoute($router, $route) {
        $router->addRoute(new Router\Route\Regex($route));
    }

    public function _getExceptionForImplementation($method) {
        return new Exception("{$method} not implemented");
    }

    public function addRoute($route) {
        $this->_routes[] = $route;
        return $this;
    }

    public function removeRoute($route) {
        foreach ($this->_routes as $i => $stored) {
            if ($stored == $route) {
                unset($this->_routes[$i]);
            }
        }
        return $this;
    }

    public function getRoutes() {
        $list = array();
        foreach ($this->_routes as $route) {
            $list[$route->pattern = get_class($route)];
        }
        return $list;
    }

    public function dispatch() {
        $url = $this->_url;

        /*
        $query_string = RequestMethods::query_string();
        if ($query_string[0] === "#") {

        // take the http://example.org/#/dir/name way
            // without history or js or whatever reason sends this
        // move your ajax receiver/sender to this url format??

        }
        */
        $parameters = array();
        $controller = "index";
        $action = "index";
        Events::fire("framework.router.dispatch.before", array($url));

        /**
         * check for ajax and set parameter if found
         */

        foreach ($this->_routes as $route) {
            $matches = $route->matches($url);
            if ($matches) {
                $controller = $route->controller;
                $action = $route->action;
                $parameters = $route->parameters;
                Events::fire("framework.router.dispatch.after", array($url, $controller, $action, $parameters));
                $this->_pass($controller, $action, $parameters);
                return;
            }
        }

        // I think the following is
        // Default dispatcher for non matching routes to call controller/action/parameter/parameter as c->a(p,p)

        // I tried to remove it and got errors, got to redo the route class and matches for

        // as well as adding a easy one-call for defining "add,edit,del,undel,all,one" routes

        $parts = explode("/", trim($url, "/"));
        if (sizeof($parts) > 0) {
            $controller = $parts[0];
            if (sizeof($parts) >= 2) {
                $action = $parts[1];
                $parameters = array_slice($parts, 2);
            }
        }
        Events::fire("framework.router.dispatch.after", array($url, $controller, $action, $parameters));
        $this->_pass($controller, $action, $parameters);

        //    $exception = new \Framework\Router\Exception\Controller("Can not find a route.");
        //    throw $exception;
        // \Framework\Errorpage::redirect($exception);
        // redirect is done in index.php i forgot, so it would be there twice.

    }

    protected function _pass($controller, $action, $parameters = array()) {

        $name = ucfirst($controller);
        $this->_controller = $controller;
        $this->_action = $action;
        Events::fire("framework.router.controller.before", array($controller, $parameters));
        try {
            $instance = new $name(array(
                "parameters" => $parameters
            ));
            Registry::set("controller", $instance);
        } catch (Exception $e) {
            throw new Exception("controller not found");
        }
        Events::fire("framework.router.controller.after", array($controller, $parameters));
        /**
         * Support for Magic Controller
         * over a isset test for a isMagic Property
         * (first solution)
         */
        if (!isset($instance->isMagic) &&
            /**
             * Router exception if action isnt. No chance for magic controller/route
             */
            (!method_exists($instance, $action))) {
            /*
             * Wenn die Methode Action nicht existiert
             * Hier kann man was ändern, um die Exception
             * in eine Ausgabe zu verwandeln.
             */
            $instance->willRenderLayoutView = false;
            $instance->willRenderActionView = false;
            throw new Exception("action not found");

        }

        if (method_exists($instance, $action) || !$instance->isMagic) {
            $inspector = new Inspector($instance);
            $methodMeta = $inspector->getMethodMeta($action);
            if (!empty($methodMeta["@protected"]) || !empty($methodMeta["@private"])) {
                throw new Exception("action is not found");
            }

        } else {
            $methodMetha = array();
        }
        $hooks = function ($meta, $type) use ($inspector, $instance) {
            if (isset($meta[$type])) {
                $run = array();
                foreach ($meta[$type] as $method) {
                    $hookMeta = $inspector->getMethodMeta($method);
                    if (in_array($method, $run) && !empty($hookMeta["@once"])) {
                        continue;
                    }
                    $instance->$method();
                    $run[] = $method;
                }
            }
        };
        $parameters = is_array($parameters) ? $parameters : array($parameters);
        $eventArgs = array($action, $parameters);

        Events::fire("framework.router.beforehooks.before", $eventArgs);
        $hooks($methodMeta, "@before");
        Events::fire("framework.router.beforehooks.after", $eventArgs);

        Events::fire("framework.router.action.before", $eventArgs);
        call_user_func_array(array($instance, $action), $parameters);
        Events::fire("framework.router.action.after", $eventArgs);

        Events::fire("framework.router.afterhooks.before", $eventArgs);
        $hooks($methodMeta, "@after");
        Events::fire("framework.router.afterhooks.after", $eventArgs);
        Registry::erase("controller");

    }

    // bad below

    public function visitAllRoutes(\Framework\Visitor $v) {
        foreach ($this->_routes as $route) {
            $v->visit($route);
        }
    }

    public function visitRouteByController(\Framework\Visitor $v, $controller) {
        foreach ($this->_routes as $route) {
            if ($route->controller === $controller) {
                $v->visit($route);
            }
        }
    }

    public function getRouteIterator() {
        return new \Framework\Iterator($this->_routes);
    }
}
