<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 28.07.14
 * Time: 10:32
 */

use \Homepage\Post as Post;
use \Homepage\Category as Category;
use \Homepage\Comment as Comment;
use \Framework\RequestMethods as RequestMethods;
use \Homepage\Controller as Controller;

class Posts extends Controller {

    function __construct($options = array()) {
        parent::__construct($options);
    }



    function all() {
        $all = Post::getWithComments();
        $view = $this->getActionView();
        $view->set("posts", $all);
    }



    function post($id) {
        $view = $this->getActionView();
        $post = Post::first(array(
            "id=?" => $id,
            "live!=?" => false,
            "deleted!=?" => true
        ));
        if ($post->live) {
            $view->set("post", $post);
        } else {
            $view->set("error", "Can´t get post {$id}");
        }
    }

    /**
     * @before _secure, _publisher
     */
    function add() {
        $view = $this->getActionView();
        if (RequestMethods::post("post")) {
            $user = $this->getUser();
            $title = RequestMethods::post("title");
            $content = RequestMethods::post("content");
            $category = RequestMethods::post("category", null);
            $post = new Post(array(
                "title" => $title,
                "content" => $content,
                "user" => $user->id,
                "category" => $category
            ));
            if ($post->validate()) {
                $post->save();
                $view->set("success", true);
            }
        } else {
            $categories = Category::all();
            $view->set("categories", $categories);
        }
    }

    /**
     * @before _secure
     */
    function edit($id) {

        $user = $this->getUser();
        $view = $this->getActionView();
        $post = Post::first(array(
            "id=?" => $id
        ));

        if (!empty($post)) {
            if (($user->id == $post->user) || $user->admin) {
                if (RequestMethods::post("update")) {
                    $post->title = RequestMethods::post("title", $post->title);
                    $post->content = RequestMethods::post("content", $post->content);
                    $post->category = RequestMethods::post("category", $post->category);
                    if ($post->validate()) {
                        $post->save();
                        $view->set("success", true);
                    }
                }
                $view->set("editable", $post);
                $categories = Category::all();
                $view->set("categories", $categories);
            } else {
                $view->set("error", "You do not have the right to edit.");
            }
        } else {
            $view->set("error", "Can not find the post you supplied.");
        }
    }

    /**
     * @before _secure
     */
    function delete($id) {
        $user = $this->getUser();
        $view = $this->getActionView();
        $post = Post::first(array(
            "id=?" => $id,
            "live=?" => true,
            "deleted!=?" => true
        ));
        if (!empty($post)) {
            if (($user->id == $post->user) || $user->admin) {
                $post->setLive(false);
                $post->setDeleted(true);
                $post->delete();
                $view->set("success", true);
            }
        }
    }

    function undelete($id) {
        $user = $this->getUser();
        $view = $this->getActionView();
        $post = Post::first(array(
            "id=?" => $id,
            "live=?" => false,
            "deleted=?" => true
        ));
        if (!empty($post)) {
            if (($user->id == $post->user) || $user->admin) {
                $post->setLive(true);
                $post->setDeleted(false);
                $post->save();
                $view->set("success", true);
            }
        }
    }

    function comment($article) {

        $user = $this->getUser();
        $view = $this->getActionView();
        $errors = array();

        if (RequestMethods::post("comment")) {

            $data = array(
                "article" => $article,
                "title" => RequestMethods::post("title"),
                "content" => RequestMethods::post("content")
            );

            if ($user) {
                $data += array("user" => $user->id);
            }

            $comment = new Comment($data);

            if ($comment->validate()) {
                $comment->save();
                $view->set("success", true);
            }

            $view->set("errors", $errors);

            $redirect = RequestMethods::post("origin");
            if (!empty($redirect)) {
                self::redirect($redirect);
            }
        }
    }

    /*
     * I also need to get the URL, where the form is coming from
     * that i can switch between these controllers, views without redirect
     */

    function reply($comment) {
        $user = $this->getUser();
        $view = $this->getActionView();

        if (RequestMethods::post("reply")) {
            $title = RequestMethods::post("title");
            $content = RequestMethods::post("content");
            $article = RequestMethods::post("article");
            $name = RequestMethods::post("name");

            $data = array(
                "article" => $article,
                "reply" => $comment,
                "title" => $title,
                "content" => $content,
                "name" => $name
            );

            if ($user) {
                $data += array("user" => $user->id);
            }

            $model = new Comment($data);

            if ($model->validate()) {
                $model->save();
                $view->set("success", true);
            }

            $redirect = RequestMethods::post("origin");
            if (!empty($redirect)) {
                self::redirect($redirect);
            }
        }
    }

    public function category($id) {
        $categories = Category::all();
        $view = $this->getActionView();

        $view->set("categories", $categories);
        $view = $this->getActionView();

        // Data about the category
        $cat = Category::first(array(
            "id=?" => $id
        ));
        $view->set("category", $cat);

        // data about the posts
        $posts = Post::all(array(
            "category=?" => $cat->id
        ));

        $view->set("posts", $posts);
    }
}
