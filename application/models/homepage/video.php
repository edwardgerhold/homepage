<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 19.08.14
 * Time: 09:03
 */
namespace Homepage;

use \Framework\Registry as Registry;

class Video extends Model {
    /**
     * @readwrite
     */
    protected $_table = "homepage_video";
    /**
     * @column
     * @type text
     * @readwrite
     */
    protected $_title;
    /**
     * @column
     * @type text
     * @readwrite
     */
    protected $_author;
    /**
     * @column
     * @type integer
     * @readwrite
     */
    protected $_user;
    /**
     * @column
     * @type text
     * @label autobuffer
     * @readwrite
     */
    protected $_autobuffer;
    /**
     * @column
     * @type boolean
     * @label autoplay
     * @readwrite
     */
    protected $_autoplay;
    /**
     * @column
     * @type boolean
     * @label controls
     * @readwrite
     */
    protected $_controls;
    /**
     * @column
     * @type integer
     * @label height
     * @readwrite
     */
    protected $_height;
    /**
     * @column
     * @type boolean
     * @label loop
     * @readwrite
     */
    protected $_loops; // loop
    /**
     * @column
     * @type text
     * @label poster
     * @readwrite
     */
    protected $_poster;
    /**
     * @column
     * @type text
     * @label src
     * @readwrite
     */
    protected $_src;
    /**
     * @column
     * @type integer
     * @label width
     * @readwrite
     */
    protected $_width;
    /**
     * @column
     * @type time
     * @label duration
     * @readwrite
     */
    protected $_duration;
    /**
     * @readwrite
     *
     * Will be added to the model, the source elements for media
     */
    protected $_sources = array();

    static public function getAllWithSources() {
        $videos = self::all();
        foreach ($videos as $video) {
            $id      = $video->id;
            $sources = Source::all(array(
                "video=?" => $id
            ));
            if (!$sources) $sources = new \stdClass();
            $video->sources = $sources;
        }
        return $videos;
    }

    static public function getWithSources($id) {
        $video = self::first(array(
            "id=?" => $id,
            "live=?" => true,
            "deleted=?" => false
        ));
        if ($video) {
            $sources = Source::all(array(
                "video=?" => $video->id,
                "live=?" => true,
                "deleted=?" => false
            ));
            if (!$sources) $sources = new \stdClass();
            $video->sources = $sources;
        }
        return $video;
    }
}