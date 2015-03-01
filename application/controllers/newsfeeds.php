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
 * Following more rules, the page is also developed with
 * the HTML5 Cookbook by T. Leadbetter and C. Hudson
 * and by Responsive Webdesign (german) by C. Zillgens
 *
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 03.09.14
 * Time: 15:49
 * Project: Edward´s Homepage
 */
use Homepage\Post as Post;

class Newsfeeds extends Homepage\Controller {
    /**
     * @readwrite
     */
    protected $_articles;

    function rss() {
        if ($feed = $this->_fromCache("feed")) {
            echo $feed;
            exit();
        } else {
            echo self::update();
        }

    }

    protected function _feed($articles) {

        $title = "Edward´s Homepage"; // get from config and use as page title, too
        $link = "http://linux-swt.de";
        $feed = <<<XMLDOC
<?xml version='1.0'?>
<rss>
    <title>{$title}</title>
    <link>{$link}</title>
    <description></description>
    <body>
XMLDOC;
        foreach ($articles as $article) {
            $feed .= <<<XMLDOC
        <title>{$article->title}</title>
        <link></link>
        <description></description>
XMLDOC;
        }
        $feed .= <<<XMLDOC
    </body>
</rss>
XMLDOC;
            return $feed;
    }

    static public function update() {

        $this->_articles = Post::all(array(
            "live=?" => true,
            "deleted=?" => false
        ), array("*"), "id", "desc");


        $feed = $this->_feed($articles);

        $this->_intoCache("feed", $feed);

        return $feed;
    }

} 