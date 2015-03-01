<?php

use \Homepage\Post as Post;
use \Framework\Registry as Registry;
use \Framework\ArrayMethods as ArrayMethods;
use \Homepage\Category as Category;

class Index extends \Homepage\Controller {
    function __construct($options = array()) {
        parent::__construct($options);
    }
    function index() {
        $this->_getPosts();
        $this->_getCategories();
    }
    function old() {
        header("Location: /index2.php");
    }
}