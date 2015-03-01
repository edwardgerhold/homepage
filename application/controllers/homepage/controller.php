<?php

namespace Homepage;

use Framework\Events as Events;
use Framework\Registry as Registry;
use Framework\Router as Router;
use Framework\RequestMethods as RequestMethods;
use Homepage\Category as Category;
use Homepage\Post as Post;

class Controller extends \Framework\Controller {

    /**
     * @readwrite
     */
    static public $includes;
    // include "css", "scripts_top", "scripts_bottom"


    /**
     * @readwrite
     */
    protected $_user;
    /**
     * @readwrite
     */
    protected $_forbidden_action = false;

    /**
     * @protected
     */
    public function _admin() {
        if (!$this->user->admin) {
            throw new Router\Exception\Controller("Not a valid admin user account");
        }
    }

    /**
     * @protected
     */
    protected function _setAfterLoginRedirect() {
        $login_redirect = RequestMethods::request_uri();
        if ($login_redirect) {
            Registry::get("session")->set("redirect_after_login_to", $login_redirect);
        }
    }

    /**
     * @protected
     */
    public function _secure() {
        $user = $this->getUser();
        if (!$user) {
            /*
             * This code redirects to the page from which _secure() lead away
             */
            $this->_setAfterLoginRedirect();
            /**
             *
             */
            header("Location: /login.html");
            exit();
        }
    }

    /**
     * @protected
     */
    public function _publisher() {
        $user = $this->getUser();
        if (!$user) {
            self::redirect("/users/login.html");
        }
        if (!$user->publisher) {
            include(APP_PATH . "/applicaton/views/errors/denied.php");
            throw new Router\Exception\Controller("Not a valid publisher account");
        }
        return true;
    }

    /**
     * @protected
     */
    public function _acl($role) {
        $user = $this->getUser();
        if (!$user || !strstr($user->acl, $role . ";")) {
            include(APP_PATH . "/applicaton/views/errors/denied.php");
            throw new Router\Exception\Controller("Not any valid access control entry found.");
        }
    }

    /**
     * @protected
     */
    public static function redirect($url, $useOrigin = false) {
        if ($useOrigin && RequestMethods::post("origin")) {
            $origin = RequestMethods::post("origin");
            header("Location: {$origin}");
        } else {
            header("Location: {$url}");
        }
        exit();
    }

    /**
     * @protected
     */
    public function setUser($user) {
        $session = Registry::get("session");
        if ($user) {
            $session->set("user", $user->id);
        } else {
            $session->erase("user");
        }
        $this->_user = $user;
        return $this;
    }

    public function __construct($options = array()) {
        parent::__construct($options);
        $database = \Framework\Registry::get("database");
        $database->connect();
        // schedule: load user from session
        Events::add("framework.router.beforehooks.before", function ($name, $parameters) {
            $session    = Registry::get("session");
            $controller = Registry::get("controller");
            $user       = $session->get("user");
            if ($user) {
                $controller->user = User::first(array(
                    "id=?" => $user
                ));
            }
        });
        // schedule: save user to session
        Events::add("framework.router.afterhooks.after", function ($name, $parameters) {
            $session    = Registry::get("session");
            $controller = Registry::get("controller");
            if ($controller->user) {
                $session->set("user", $controller->user->id);
            }
        });
        // schedule disconnect from database
        Events::add("framework.controller.destruct.after", function ($name) {
            $database = Registry::get("database");
            $database->disconnect();
        });
    }

    /**
     * @protected
     */
    public function render() {
        // temp location
        $this->_includeSheetsAndScripts(); // conv. nenn es include, nicht get falls es included
        // ...
        try {
            /**
             * shared\controller setzt den user automatisch
             */
            if ($this->getUser()) {
                if ($this->getActionView()) {
                    $this->getActionView()
                        ->set("user", $this->getUser());
                }
                if ($this->getLayoutView()) {
                    $this->getLayoutView()
                        ->set("user", $this->getUser());
                }
            }
            //    $actions = $this->_getPublicActions();
            //     $this->layoutView->set("actions", $actions);
            parent::render();
        } catch (\Exception $e) {
            \Framework\ErrorMethods::redirect($e);
        }
    }

    /**
     * @protected
     */
    protected function _getPublicActions() {
        // das ist von mir und holt die public actions raus
        $inspector = $this->_inspector;
        $methods   = $inspector->getClassMethods();
        $name      = $this->getName();
        $actions   = array();
        foreach ($methods as $method) {
            $meta = $inspector->getMethodMeta($method);
            if (
                (!isset($meta["@protected"]))
                && (!preg_match("#^(get)|^(set)#", $method))
                && ($method[0] != "_")
                && (!isset($meta["@before"]))
                && (!isset($meta["@hide"]))
            ) {
                $actions[] = $method;
            }
        }
        return array("name" => $name, "actions" => $actions);
    }

    /**
     * @protected
     */
    protected function _getCategories() {
        // this function loads all categories into the layout template
        // cache is respected and tried first
        if (($categories = $this->_fromCache("categories")) == null) {
            $categories = Category::all();
            $this->_intoCache("categories", $categories);
        }
        $this->layoutView->set("categories", $categories);
    }

    /**
     * @protected
     */
    protected function _getPosts() {
        // this function loads all posts into the action template
        // if they are currently cached, the cache is taken
        if (($data = $this->_fromCache("posts", null)) != null) {

            $this->actionView->set("posts", $data);
        } else {
            $posts = Post::getWithComments();
            $this->_intoCache("posts", $posts);
            $this->actionView->set("posts", $posts);
        }
    }

    /**
     * @protected
     */
    protected function _toOrigin() {
        // This function redirects to whatever some <input type=hidden name=origin> is set to
        if (RequestMethods::post("origin")) {
            self::redirect(RequestMethods::post("origin"));
            return true;
        }
        return false;
    }








    protected function _includeSheetsAndScripts() {
        if ($data = $this->_fromCache("sheets_and_scripts")) {
            $styles         = $data["styles"];
            $scripts_top    = $data["scripts_top"];
            $scripts_bottom = $data["scripts_bottom"];
        } else {

            /*
             * Set in index.php
             */
            $css = self::$includes["css"];
            $scriptsTop = self::$includes["scripts_top"];
            $scriptsBottom = self::$includes["scripts_bottom"];
            // ---

            $stylesheet = \Framework\Registry::get("stylesheet");
            $javascript = \Framework\Registry::get("javascript");
            // STYLE: Tags in the Header
            $stylesheet = $stylesheet->initialize();
            foreach ($css as $ss) {
                $stylesheet->add($ss);
            }
            $styles = $stylesheet->get();
            // TOP: Javascript in the header
            $javascript = $javascript->initialize();
            foreach ($scriptsTop as $scr) {
                $javascript->add($scr);
            }
            $scripts_top = $javascript->get();
            // BOTTOM: Javascript at the bottom of the body
            $javascript = $javascript->initialize();
            foreach ($scriptsBottom as $scr) {
                $javascript->add($scr);
            }
            $scripts_bottom = $javascript->get();
            $this->_intoCache("sheets_and_scripts", array(
                "styles"         => $styles,
                "scripts_top"    => $scripts_top,
                "scripts_bottom" => $scripts_bottom
            ));
        }
        $this->_layoutView->set("styles", $styles);
        $this->_layoutView->set("scripts_top", $scripts_top);
        $this->_layoutView->set("scripts_bottom", $scripts_bottom);
    }

    protected function _loadViewFromSubDirectory($id, $subid="") {

        $action = Registry::get("router")->getAction();
        $file   = null;
        $path   = $this->_defaultPath;
        $name   = $this->_name;
        $ext    = $this->_defaultExtension;
        if (isset($id)) {

            // ersetzen mit $args = func_get_args();

            if ($subid != "") $subid = "/".$subid;


            $file = APP_PATH . "/{$path}/{$name}/{$action}/{$id}{$subid}.{$ext}";
            if (file_exists($file)) {
                $this->_actionView->file = $file;
                return true;
            } else {
                $file = null;
            }
        }
        if ($file === null) {
            $this->_actionView->file = APP_PATH . "/{$path}/{$name}/{$action}.{$ext}";
            $this->_actionView->set("not_found", $id);
            return false;
        }
    }

    protected function _includeFileArrayOfSubDirectory() {
        $array  = array();
        $action = Registry::get("router")->getAction();
        $path   = $this->_defaultPath;
        $name   = $this->_name;
        $dir    = APP_PATH . "/{$path}/{$name}/{$action}/";
        $iter   = new \DirectoryIterator($dir);
        foreach ($iter as $file) {
            $array[] = $file;
        }
        $this->_actionView->set("subdirfiles", $array);
        return $array;
    }

    protected function _includeLinkListOfSubDirectory() {
        $files    = $this->_includeFileArrayOfSubDirectory();
        $linklist = "";
        $action   = Registry::get("router")->getAction();
        $name     = $this->_name;
        foreach ($files as $file) {
            if (preg_match("#(\\.html)$#", $file)) {
                $url  = "/{$name}/{$action}/{$file}";
                $link = "<li><a href='{$url}'>{$url}</a></li>";
                $linklist .= $link;
            }
        }
        $this->_actionView->set("subdirlinks", "<ul>" . $linklist . "</ul>");
    }
    // subdir_files, link_list. Here is some target for global search and replace.

    protected function _includeLocalSheetsAndScripts($array) {

        // let me include for some action a certain number of scripts
        // without using script tags by hand in the template
        // let me load them conditionally with this function
    }
}