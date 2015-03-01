<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 12.08.14
 * Time: 13:27
 */
use \Homepage\Audio as Audio;
use \Homepage\Source as Source;

class Audios extends \Homepage\Controller {
    public function play($id) {
            $first = Audio::first(array(
                "id=?" => $id
            ));
            $sources = Source::all(array(
                "audio=?" => $id
            ))
            $this->actionView->set("audio", $first);
            $this->actionView->set("sources", $sources);

        $this->actionView->set("sources", array(
            array(
                "type" => "audio/mp3",
                "src" => "/audios/eddiesdemo2-track-003.mp3"
            )
        ));
    }


    public function all() {
        $all = Audio::all();
        $this->actionView->set("audios", $all);
    }

    public function stream() {
        /**
         * This is a HTML5 Developer Cookbook Application
         * to play Radio Streams, i added german radio channels
         * when typing it down.
         */
    }
}