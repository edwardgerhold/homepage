<?php

class Js extends Homepage\Controller {

    public function libs($id) {
        // javascript libraries on a single page in a subdirectory....
        // so i can work on my pages and articles from the dbase both
        $this->_loadViewFromSubDirectory($id);
    }

    public function syntax($id) {
        /**
         * My new syntax.js Homepage goes here into. Yes.
         * If the subdirectory view js/syntax/id.html isnt found the user or
         * guest or admin is getting the main directories file /js/syntax.html
         */
        $this->_loadViewFromSubDirectory($id);
    }

    public function tutorials($id) {
        if (!$this->_loadViewFromSubDirectory($id)) {
            $this->_includeLinkListOfSubDirectory();
        }
    }
}