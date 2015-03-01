<?php
/**
 *
 * Edward´s Homepage is written by Edward Gerhold
 * http://github.com/edwardgerhold/homepage
 * Edward´s Homepage is originally developed for
 * http://linux-swt.de (c) 2014 Edward Gerhold
 * This is free and open source software for you.
 *
 * The Homepage Application Framework bases on the
 * "Pro PHP MVC" Framework from the namely equal book
 * by Chris Pitt released by http://apress.com.
 *
 * The application is Edward´s Homepage.
 * Load it into a PHPStorm evaluation copy from
 * http://jetbrains.com for the ultimate experience.
 *
 * Created by PhpStorm
 * Date: 22.08.14
 * Time: 19:26
 * User: Edward Gerhold
 * Project Edward´s Homepage
 */

namespace Homepage;
use \Framework\Registry as Registry;
use \Framework\ArrayMethods as ArrayMethods;

class Post extends \Homepage\Model {

    /**
    * @readwrite
    */
    protected $_table = "homepage_post";
    
    /**
     * @column
     * @type integer
     * @index
     * @readwrite
     */
    protected $_user;
    /**
     * @column
     * @type text
     * @length 255
     * @readwrite
     */
    protected $_title;
    /**
     * @column
     * @type text
     * @readwrite
     */
    protected $_content;

    /**
     * @column
     * @type integer
     * @readwrite
     */
    protected $_category;

    /**
     * @readwrite
     *
     * This variable holds the comments of a join
     */
    protected $_comments;

    function __construct($options=array()) {
        parent::__construct($options);
    }

    static public function getWithComments() {
        /* Big Oh
         * This is a very complex function
         * getting the replies to comments is very expensive,
         * a query each comment..
         * I and the query class have to improve for this situation
         */

        $database = Registry::get("database");
        $posts = $database
            ->query()
            ->from("homepage_post", array(
                "homepage_post.title" => "title",
                "homepage_post.content" => "content",
                "homepage_post.user" => "user",
                "homepage_post.id" => "id",
                "homepage_post.created" => "created"
            ))
            ->join("homepage_user", "homepage_post.user=homepage_user.id", array(
                "first",
                "last",
                "name"
            ))
            ->order("id", "desc")
            ->all();

        foreach ($posts as $i => $post) {

            // ACHTUNG: Das ist ein ASSOZIATIVER ARRAY
            $id = $post["id"];

            // $post->id ist EMPTY!!!!

            $comments = $database->query()
                ->from("homepage_comment", array(
                    "homepage_comment.id" => "id",
                    "homepage_comment.title" => "title",
                    "homepage_comment.content" => "content",
                    "homepage_comment.article" => "article",
                    "homepage_comment.created" => "created"
                ))
                ->where(array(
                    "homepage_comment.article=?" => $id,
                    "homepage_comment.live=?" => true,
                    "homepage_comment.deleted!=?" => true
                ))
                ->join("homepage_user", "homepage_comment.user=homepage_user.id", array(
                    "homepage_user.name" => "name"
                ))

                ->order("homepage_comment.id", "asc")
                ->all();

            $posts[$i] += array("comments" => $comments);

            if (!empty($comments)) {
                foreach ($comments as $j => $comment) {

                    $_id = $comment["id"];

                    $replies = $database->query()
                        ->from("homepage_comment", array(
                            "homepage_comment.id" => "id",
                            "homepage_comment.title" => "title",
                            "homepage_comment.content" => "content",
                            "homepage_comment.article" => "article",
                            "homepage_comment.created" => "created"
                        ))
                        ->where(array(
                            "homepage_comment.reply=?" => $_id,
                            "homepage_comment.live=?" => true,
                            "homepage_comment.deleted=?" => false
                        ))
                        ->join("homepage_user", "homepage_comment.user=shared_user.id", array(
                            "shared_user.name" => "name"
                        ))
                        ->order("homepage_comment.id", "asc")
                        ->all();

                    $comments[$j] += array("replies" => $replies);
                }
            }
        }
        return ArrayMethods::toObject($posts);
    }

}