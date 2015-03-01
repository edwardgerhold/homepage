<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 18.07.14
 * Time: 12:01
 */

namespace Framework;

use Framework\Controller\Exception as Exception;

class Controller extends Base {
    /**
     * @read
     */
    protected $_name;
    /**
     * @readwrite
     */
    protected $_parameters;
    /**
     * @readwrite
     */
    protected $_layoutView;
    /**
     * @readwrite
     */
    protected $_actionView;
    /**
     * @readwrite
     */
    protected $_willRenderLayoutView = true;
    /**
     * @readwrite
     */
    protected $_willRenderActionView = true;
    /**
     * @readwrite
     */
    protected $_defaultPath = "application/views";
    /**
     * @readwrite
     */
    protected $_defaultLayout = "layouts/standard";
    /**
     * @readwrite
     */
    protected $_defaultExtension = "html";
    /**
     * @readwrite
     */
    protected $_defaultContentType = "text/html";

    /**
     * @readwrite
     */
    protected $_errors = array();

    /**
     * @readwrite
     */
    protected $_successes = array();

    public function _getExceptionForImplementation($method) {
        return new Exception\Implementation("{$method} is not implemented");
    }

    public function _getExceptionForArgument($argument) {
        return new Exception\Argument("invalid argument");
    }

    public function __construct($options = array()) {

        parent::__construct($options);

        if (RequestMethods::isAjax()) {
            $this->_willRenderLayoutView = false;
        }

        Events::fire("framework.controller.construct.before", array($this->name));

        $defaultPath = $this->getDefaultPath();
        $defaultLayout = $this->getDefaultLayout();
        $defaultExtension = $this->getDefaultExtension();

        if ($this->getWillRenderLayoutView()) {
            $file = APP_PATH . "/{$defaultPath}/{$defaultLayout}.{$defaultExtension}";
        } else {
            $file = null;
        }
        $view = new View(array(
            "file" => $file
        ));

        $this->setLayoutView($view);

        if ($this->getWillRenderActionView()) {

            $router = Registry::get("router");
            $controller = $router->getController();
            $action = $router->getAction();
            $view = new View(array(
                "file" => APP_PATH . "/{$defaultPath}/{$controller}/{$action}.{$defaultExtension}"
            ));
            $this->setActionView($view);
        }

        Events::fire("framework.controller.construct.after", array($this->name));
    }

    public function __destruct() {
        Events::fire("framework.controller.destruct.before", array($this->name));
        $this->render();
        Events::fire("framework.controller.destruct.after", array($this->name));
    }

    /**
     * @protected
     */
    public function getName($asDir=false) {
        if (empty($this->_name)) {
            $repl = ($asDir) ? "/" : "_";
            $name = get_class($this);
            $name = str_replace("\\", $repl, $name);
            $this->_name = strtolower($name);
        }
        return $this->_name;
    }

    public function render() {

        Events::fire("framework.controller.render.before", array($this->name));
        $defaultContentType = $this->getDefaultContentType();
        $results = null;

        $doAction = $this->getWillRenderActionView() && $this->getActionView();
        $doLayout = $this->getWillRenderLayoutView() && $this->getLayoutView();

        try {

            if ($doAction) {
                $view = $this->getActionView();
                $results = $view->render();
                $this->actionView->template->implementation->set("action", $results);
            }

            if ($doLayout) {
                $view = $this->getLayoutView();
                $results = $view->render();
            }

            if ($doLayout) {
                header("Content-Type: {$defaultContentType}");
                echo $results;
            } else if ($doAction) {
                header("Content-Type: {$defaultContentType}");
                echo $results;
            }
            $this->setWillRenderLayoutView(false);
            $this->setWillRenderActionView(false);

        } catch (\Exception $e) {
            if (DEBUG) {
                echo $e->getMessage();
                print_r($e);
            }
            throw new Exception\Template("invalid layout or template syntax");
        }
        Events::fire("framework.controller.render.after", array($this->name));
    }

    protected function _intoCache($key,$value) {
        Registry::get("cache")->set($key, $value);
    }

    protected function _fromCache($key, $default=null) {
        return null;

        //return Registry::get("cache")->get($key, $default);
    }
}