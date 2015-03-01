<?php

use Framework\Registry as Registry;
use Framework\RequestMethods as RequestMethods;
use Homepage\Controller as Controller;
use Homepage\File as File;
use Homepage\Friend as Friend;
use Homepage\Message as Message;
use Homepage\User as User;
use Homepage\Confirmation as Confirmation;

class Users extends Controller {

    /**
     * @readwrite
     */
    protected $_loginRedirect = "/users/profile.html"; // default after login

    public function __construct($options = array()) {
        parent::__construct($options);
    }

    public function register() {

        $errors = array();
        $view = $this->getActionView();
        $error = false;
        $session = Registry::get("session");

        if (RequestMethods::post("register")) {

            //$generated_captcha_token = $session->get("generated_captcha_token");

            $name = RequestMethods::post("name");
            $password = RequestMethods::post("password");
            $password2 = RequestMethods::post("password2");

            if (empty($name)) {
                $errors += array("name", "Username not provided");
                $error = true;
            }
            if (empty($password) || empty($password2)) {
                $errors += array("password", "Password not provided");
                $error = true;
            }

            if (empty($email)) {
                $errors += array("email", "Email not provided");
                $error = true;
            }

            /*if (empty($captcha) || $captcha != $generated_captcha_token) {
                $errors += array("captcha", "Captcha not provided");
                $error = true;
            }*/

            if (!$error) {

                $pw_encrypt = Registry::get("password_encryption");
                $password = $pw_encrypt->encrypt($password);
                $password2 = $pw_encrypt->encrypt($password2);
                if (!strcmp($password, $password2)) {

                    $year = RequestMethods::post("year", "1900");
                    $month = RequestMethods::post("month", "01");
                    $day = RequestMethods::post("day", "01");
                    $birthday = "{$year}-{$month}-{$day}";

                    $user = new User(array(
                        "name" => $name,
                        "password" => $password,
                        "email" => RequestMethods::post("email"),

                        "first" => RequestMethods::post("first"),
                        "last" => RequestMethods::post("last"),

                        "website" => RequestMethods::post("website"),
                        "city" => RequestMethods::post("city"),
                        "country" => RequestMethods::post("country"),
                        "job" => RequestMethods::post("job"),
                        "company" => RequestMethods::post("company"),
                        "birthday" => $birthday,
                        "language" => RequestMethods::post("language"),
                        "motto" => RequestMethods::post("motto"),
                        "likes" => RequestMethods::post("likes"),
                        "doesntlike" => RequestMethods::post("doesntlike"),
                        "confirmed" => false
                    ));

                    if ($user->validate()) {
                        $user->save();
                        $this->_upload("photo", $user->getId());

                        /*

                        $confirmation_code = md5(rand(999,999999999));
                        $confirm = new Confirmation(array(
                            "hash" => $confirmation_code,
                            "user" => $user->id
                        ));
                        $confirm->save();

                        */

                        $view->set("success", true);
                    } else {
                        $errors += array("validation" => "Registration form did not validate");
                    }
                }
            }
        } else {
            //$generated_captcha_token = md5(rand(9999,9999999));
            //$session->set("generated_captcha_token", $generated_captcha_token);
        }

        if ($user) {
            $view->set("errors", $user->getErrors() + $errors);
        } else {
            $view->set("errors", $errors);
        }
    }

    /**
     * @protected
     */
    protected function _upload($name, $user) {

        if (isset($_FILES[$name])) {

            $file = $_FILES[$name];
            $path = WEB_PATH . "/uploads/";
            $time = time();
            $extension = pathinfo($file["name"], PATHINFO_EXTENSION);
            $filename = "{$user}-{$time}.{$extension}";

            $width = null;
            $height = null;

            if (move_uploaded_file($file["tmp_name"], $path . $filename)) {

                /*                switch ($type) {
                                    case "image/jpg":
                                    case "image/png":
                                    case "image/jpeg":
                */
                $meta = getimagesize($path . $filename);

                if ($meta) {
                    $width = $meta[0];
                    $height = $meta[1];

                    $file = new File(array(
                        "name" => $filename,
                        "mime" => $file["type"],
                        "size" => $file["size"],
                        "width" => $width,
                        "height" => $height,
                        "user" => $user
                    ));

                    if ($file->validate()) {
                        $file->save();
                        $view = $this->getActionView();
                        $view->set("photo", $file);
                    }
                }
                /*                      break;
                                  case "audio/mp3":
                                  case "audio/ogg":

                                      break;

                                  case "video/mpg":
                                  case "video/ogv":
                                  case "video/webm":

                                      break;
                              } */
            }
        }
    }

    /**
     * @before _secure
     */
    public function settings() {
        $errors = array();
        $view = $this->getActionView();
        $user = $this->getUser();

        if (RequestMethods::post("update")) {

            $oldpw = $user->password;
            $password = RequestMethods::post("password");
            $password2 = RequestMethods::post("password2");

            if (!empty($password) && !empty($password2)) {
                $pw_encrypt = Registry::get("password_encryption");
                $password = $pw_encrypt->encrypt($password);
                $password2 = $pw_encrypt->encrypt($password2);
            }

            if (!strcmp($password, $password2) || (empty($password) && empty($password2))) {
                $year = RequestMethods::post("year", "1900");
                $month = RequestMethods::post("month", "01");
                $day = RequestMethods::post("day", "01");
                $birthday = "{$year}-{$month}-{$day}";

                $user->name = RequestMethods::post("name", $user->name);
                $user->first = RequestMethods::post("first", $user->first);
                $user->last = RequestMethods::post("last", $user->last);
                $user->email = RequestMethods::post("email", $user->email);
                $user->website = RequestMethods::post("website", $user->website);
                $user->city = RequestMethods::post("city", $user->city);
                $user->country = RequestMethods::post("country", $user->country);
                $user->job = RequestMethods::post("job", $user->job);
                $user->company = RequestMethods::post("company", $user->company);
                $user->motto = RequestMethods::post("motto", $user->motto);
                $user->likes = RequestMethods::post("likes", $user->likes);
                $user->doesntlike = RequestMethods::post("doesntlike", $user->doesntlike);
                $user->birthday = $birthday;
                $user->language = RequestMethods::post("language", $user->language);

                if (!empty($password)) $user->password = $password;

                if ($user->validate()) {
                    $user->save();
                    $this->user = $user;
                    $this->_upload("photo", $this->user->id);
                    $view->set("success", true);
                    $view->set("user", $user);
                }
            } else {
                $user->errors += array("password" => "The passwords don´t match.");
            }

            $view->set("errors", $user->getErrors());


        }

        /* alle users/links auf der settings seite */
        $this->_getPublicActions();
    }

    /**
     * @before _secure, _admin
     */
    public function edit($id) {
        $errors = array();
        $user = User::first(array(
            "id=?" => $id
        ));
        if (RequestMethods::post("save")) {

            $year = RequestMethods::post("year", "1900");
            $month = RequestMethods::post("month", "01");
            $day = RequestMethods::post("day", "01");
            $birthday = "{$year}-{$month}-{$day}";

            $user->first = RequestMethods::post("first", $user->first);
            $user->last = RequestMethods::post("last", $user->last);
            $user->email = RequestMethods::post("email", $user->email);
            $user->website = RequestMethods::post("website", $user->website);
            $user->city = RequestMethods::post("city", $user->city);
            $user->country = RequestMethods::post("country", $user->country);
            $user->job = RequestMethods::post("job", $user->job);
            $user->company = RequestMethods::post("company", $user->company);
            $user->motto = RequestMethods::post("motto", $user->motto);
            $user->likes = RequestMethods::post("likes", $user->likes);
            $user->doesntlike = RequestMethods::post("doesntlike", $user->doesntlike);
            $user->birthday = $birthday;
            $user->language = RequestMethods::post("language", $user->language);

            //$user->password = RequestMethods::post("password", $user->password);

            $user->live = (boolean)RequestMethods::post("live", $user->live);
            $user->admin = (boolean)RequestMethods::post("admin", $user->admin);

            if ($user->validate()) {
                $user->save();
                $this->actionView->set("success", true);
            }
            $errors = $user->errors;
        }

        $this->actionView
            ->set("editable", $user)
            ->set("errors", $errors);
    }

    /**
     * @before _secure, _admin
     */
    public
    function view() {
        $view = $this->getActionView();
        $count = User::count();
        $view->set("count", $count);
        $all = User::all();
        $view->set("users", $all);
    }

    /**
     * @before _secure, _admin
     */
    public function delete($id) {
        $user = User::first(array(
            "id=?" => $id
        ));
        if ($user) {
            $user->setLive(false);
            $user->setDeleted(true);
            $user->save();
        }
        self::redirect("/users/view.html");
    }

    /**
     * @before _secure, _admin
     */
    public function undelete($id) {
        $user = User::first(array(
            "id=?" => $id
        ));

        if ($user) {
            $user->live = true;
            $user->deleted = false;
            $user->save();
        }

        self::redirect("/users/view.html");
    }

    public function home() {

        $user = $this->getUser();
        $view = $this->getActionView();

        if ($user) {

            $friends = Friend::all(array(
                "user=?" => $user->id,
                "live=?" => true,
                "deleted=?" => false
            ), array("*"));

            $ids = array();
            foreach ($friends as $friend) {
                $ids[] = $friend->friend;
            }

            $messages = Message::all(array(
                "user in ?" => $ids,    // message von den usern der friend id
                "live=?" => true,
                "deleted=?" => false
            ), array("*"), "created", "asc");

            $view->set("messages", $messages);
        }
    }

    /**
     * @protected
     */
    public function getFile() {
        $user = $this->getUser();
        return File::first(array(
            "user=?" => $user->id,
            "live=?" => true
        ), array("*"), "id", "DESC");
    }

    /**
     * @before _secure
     */
    public function all() {
        $all = User::all();
        $this->actionView->set("users", $all);
    }

    public function login() {
        $errors = array();
        $view = $this->getActionView();
        $session = $this->_getSession();

        if (RequestMethods::post("login")) {
            $name = RequestMethods::post("name");
            $password = RequestMethods::post("password");

            $pw_encrypt = Registry::get("password_encryption");
            if (empty($name)) {
                $errors += array("name", "Username not provided");
            }
            if (empty($password)) {
                $errors += array("password", "Password not provided");
            }
            if (sizeof($errors) == 0) {

                $encPw = $pw_encrypt->encrypt($password);

                $user = User::first(array(
                    "name=?" => $name,
                    "password=?" => $encPw,
                    "live=?" => true,
                    "deleted=?" => false
                ));

                if (!empty($user) && $this->_isConfirmed($user)) {

                    $this->setUser($user);
                    $this->_takeLoginTime();

                    $view->set("user", $user);
                    $view->set("errors", $user->getErrors());

                    $this->_redirectAfterLogin("/users/profile.html");

                } else {
                    $errors += array("password", "Username and Password not valid");
                    $view->set("errors", $errors);
                }
            } else {
                $view->set("errors", $errors);
            }
        }
    }

    protected function _getSession() {
        return Registry::get("session", null);
    }

    protected function _isConfirmed($user) {
        /*
        if (!$user->confirmed) {
           self::redirect("/users/confirm");
           exit();
        }
        */
        return true;
    }

    protected function _takeLoginTime() {
        $session = $this->_getSession();
        $loginTime = mktime();
        $loginDate = date("Y-m-d H:i:s");
        $session->set("loginTime", $loginTime);
        $session->set("loginDate", $loginDate);
    }

    protected function _saveLoginTime() {
        $session = $this->_getSession();
        $user = $this->getUser();
        if ($session) {
            $loginDate = $session->get("loginDate");
            $loginTime = $session->get("loginTime");
            $onlineTime = date("H:i:s", time() - $loginTime);

            $user->lastLogin = $loginDate;
            $user->onlineTime = $onlineTime;
            if ($user->validate()) {
                $user->save();
            }
        }
    }

    /**
     *
     */
    protected function _redirectAfterLogin($ifNoRedirect) {
        $session = $this->_getSession();
        $redirect_after_login_to = $session->get("redirect_after_login_to");
        if ($redirect_after_login_to) {
            $session->erase("redirect_after_login_to");
            self::redirect($redirect_after_login_to);
        } else {
            self::redirect($ifNoRedirect);
        }
    }

    /**
     * @before _secure
     */
    public function profile() {
        $session = Registry::get("session");
        $user = $this->getUser();

        if (empty($user)) {
            exit();
        }
        $this->getActionView()
            ->set("user", $user)
            ->set("photo", $user->getFile());
    }

    public function search() {

        $view = $this->getActionView();

        $search_users = RequestMethods::post("search_users");
        $search_posts = RequestMethods::post("search_posts");
        $query = RequestMethods::post("query");
        $order = RequestMethods::post("order", "modified");
        $direction = RequestMethods::post("direction", "desc");
        $page = RequestMethods::post("page", 1);
        $limit = RequestMethods::post("limit", 10);
        $count = 0;
        $users = false;
        $posts = false;

        if (RequestMethods::post("search")) {

            if ($search_users) {
                $where = array(
                    "SOUNDEX(first) = SOUNDEX(?)" => $query,
                    "live=?" => true,
                    "deleted=?" => false
                );

                $fields = array(
                    "id", "first", "last", "city", "company", "job", "name"
                );
                $count = User::count($where);
                $users = User::all($where, $fields, $order, $direction, $limit, $page);
            }

            if ($search_posts) {
                $where = array(
                    "WITHIN(?, content)" => $query,
                    "WITHIN(?, title)" => $query,
                    "live=?" => true,
                    "deleted=?" => false
                );
                $fields = array("title", "content");
            }


        } else {

            $count = User::count();
            $view->set("count", $count);
            $view->set("search_users", true);

        }

        $view->set("query", $query)
            ->set("order", $order)
            ->set("direction", $direction)
            ->set("page", $page)
            ->set("limit", $limit)
            ->set("count", $count)
            ->set("users", $users)
            ->set("posts", $posts)
        ->set("search_users", $search_users)
        ->set("search_posts", $search_posts)
            ;
    }

    public function logout() {
        $user = $this->getUser();
        // $this->_saveLoginTime();
        $this->setUser(false);
        self::redirect("/users/login.html");
    }

    /**
     * @before _secure
     */
    public function friend($id) {
        $user = $this->getUser();

        $friend = new Friend(array(
            "user" => $user->id,
            "friend" => $id
        ));
        $friend->save();
        header("Location: /users/search.html");
        exit();
    }

    /**
     * @before _secure
     */
    public function unfriend($id) {
        $user = $this->getUser();

        $friend = Friend::first(array(
            "user=?" => $user->id,
            "friend=?" => $id
        ));

        if ($friend) {
            $friend->delete();
        }

        header("Location: /users/search.html");
        exit();
    }

    /**
     * @hide
     */
    public function confirm() {
        $view = $this->getActionView();
        if (RequestMethods::post("confirm")) {
            $hash = RequestMethods::post("hash");

            if (!$hash) {
                $view->set("success", false);
            }

            $entry = Confirmation::all(array(
                "hash=?" => $hash
            ));

            if ($entry) {
                $user = User::first(array(
                    "id=?" => $entry->user
                ));
                if ($user) {
                    $user->confirmed = true;
                    $user->save();
                } else {
                    // cant find user from table ???
                }
            } else {
                // cant find hash
            }
        }
    }

    public function location() {
        $user = $this->getUser();
        $view = $this->getActionView();
    }


}

