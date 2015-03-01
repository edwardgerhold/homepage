<?php

\Framework\Router::addSimpleRoutes($router, array(

    
    array(
        "pattern" => "user/:name",
        "controller" => "users",
        "action" => ""
    ),
    
    array(
        "pattern" => "index/index",
        "controller" => "index",
        "action" => "index"
    ),

    array(
        "pattern" => "old",
        "controller" => "index",
        "action" => "old"
    ),

    array(
        "pattern" => "html5/websocket",
        "controller" => "html5",
        "action" => "websocket"
    ),

    array(
        "pattern" => "html5/notepad",
        "controller" => "html5",
        "action" => "notepad"
    ),
    array(
        "pattern" => "html5/history",
        "controller" => "html5",
        "action" => "history"
    ),
    array(
        "pattern" => "html5/canvas",
        "controller" => "html5",
        "action" => "canvas"
    ),
    /**
     * Users
     */

    array(
        "pattern" => "users/all",
        "controller" => "users",
        "action" => "all"
    ),
    array(
        "pattern" => "users/view",
        "controller" => "users",
        "action" => "view"
    ),
    array(
        "pattern" => "users/edit/:id",
        "controller" => "users",
        "action" => "edit"
    ),
    array(
        "pattern" => "register",
        "controller" => "users",
        "action" => "register"
    ),
    array(
        "pattern" => "users/register",
        "controller" => "users",
        "action" => "register"
    ),
    array(
        "pattern" => "home",
        "controller" => "users",
        "action" => "home"
    ),
    array(
        "pattern" => "users/home",
        "controller" => "users",
        "action" => "home"
    ),
    array(
        "pattern" => "login",
        "controller" => "users",
        "action" => "login"
    ),
    array(
        "pattern" => "users/login",
        "controller" => "users",
        "action" => "login"
    ),
    array(
        "pattern" => "users/location",
        "controller" => "users",
        "action" => "location"
    ),
    array(
        "pattern" => "logout",
        "controller" => "users",
        "action" => "logout"
    ),
    array(
        "pattern" => "users/logout",
        "controller" => "users",
        "action" => "logout"
    ),
    array(
        "pattern" => "search",
        "controller" => "users",
        "action" => "search"
    ),
    array(
        "pattern" => "users/search",
        "controller" => "users",
        "action" => "search"
    ),
    array(
        "pattern" => "users/profile",
        "controller" => "users",
        "action" => "profile"
    ),
    array(
        "pattern" => "profile",
        "controller" => "users",
        "action" => "profile"
    ),
    array(
        "pattern" => "settings",
        "controller" => "users",
        "action" => "settings"
    ),
    array(
        "pattern" => "users/settings",
        "controller" => "users",
        "action" => "settings"
    ),
    array(
        "pattern" => "users/unfriend/:id",
        "controller" => "users",
        "action" => "unfriend"
    ),
    array(
        "pattern" => "users/friend/:id",
        "controller" => "users",
        "action" => "friend"
    ),
    array(
        "pattern" => "unfriend/:id",
        "controller" => "users",
        "action" => "unfriend"
    ),
    array(
        "pattern" => "friend/:id",
        "controller" => "users",
        "action" => "friend"
    ),
    /**
     *
     * Files
     */
    array(
        "pattern" => "files/view",
        "controller" => "files",
        "action" => "view"
    ),
    array(
        "pattern" => "files/delete",
        "controller" => "files",
        "action" => "delete"
    ),
    array(
        "pattern" => "files/undelete",
        "controller" => "files",
        "action" => "undelete"
    ),
    array(
        "pattern" => "thumbnail/:id",
        "controller" => "files",
        "action" => "thumbnails"
    ),
    array(
        "pattern" => "fonts/:name",
        "controller" => "files",
        "action" => "fonts"
    ),

    /**
     *
     * Posts
     */
    array(
        "pattern" => "posts/all",
        "controller" => "posts",
        "action" => "all"
    ),

    array(
        "pattern" => "posts/add",
        "controller" => "posts",
        "action" => "add"
    ),

    array(
        "pattern" => "posts/:id",
        "controller" => "posts",
        "action" => "post"
    ),
    array(
        "pattern" => "posts/post/:id",
        "controller" => "posts",
        "action" => "post"
    ),

    array(
        "pattern" => "posts/category/:id",
        "controller" => "posts",
        "action" => "category"
    ),

    array(
        "pattern" => "posts/edit/:id",
        "controller" => "posts",
        "action" => "edit"
    ),
    array(
        "pattern" => "posts/delete/:id",
        "controller" => "posts",
        "action" => "delete"
    ),
    array(
        "pattern" => "posts/undelete/:id",
        "controller" => "posts",
        "action" => "undelete"
    ),

    /**
     *
     * Messages
     */
    array(
        "pattern" => "messages/add",
        "controller" => "messages",
        "action" => "add"
    ),

    array(
        "pattern" => "messages/edit/:id",
        "controller" => "messages",
        "action" => "edit"
    ),

    array(
        "pattern" => "messages/delete/:id",
        "controller" => "messages",
        "action" => "delete"
    ),

    array(
        "pattern" => "messages/undelete/:id",
        "controller" => "messages",
        "action" => "undelete"
    ),


    /**
     * Categories
     */

    array(
        "pattern" => "categories/add",
        "controller" => "categories",
        "action" => "add"
    ),

    array(
        "pattern" => "categories/edit/:id",
        "controller" => "categories",
        "action" => "edit"
    ),
    array(
        "pattern" => "categories/delete/:id",
        "controller" => "categories",
        "action" => "delete"
    ),
    array(
        "pattern" => "categories/delete/:id",
        "controller" => "categories",
        "action" => "delete"
    ),

    /**
     * topics
     */

    array(
        "pattern" => "topics/add",
        "controller" => "topics",
        "action" => "add"
    ),

    array(
        "pattern" => "topics/edit/:id",
        "controller" => "topics",
        "action" => "edit"
    ),
    array(
        "pattern" => "topics/delete/:id",
        "controller" => "topics",
        "action" => "delete"
    ),
    array(
        "pattern" => "topics/delete/:id",
        "controller" => "topics",
        "action" => "delete"
    ),
    /*
    Games
    */
    array(
        "pattern" => "games/hanoi",
        "controller" => "games",
        "action" => "hanoi"
    ),

    /*
     Mail
    */
    array(
        "pattern" => "mail/send",
        "controller" => "mail",
        "action" => "send"
    ),

/*
Video
*/

    array(
        "pattern" => "videos/play/:id",
        "controller" => "videos",
        "action" => "play"
    ),
    array(
        "pattern" => "videos/edit/:id",
        "controller" => "videos",
        "action" => "edit"
    ),
    array(
        "pattern" => "videos/delete/:id",
        "controller" => "videos",
        "action" => "delete"
    ),    
    array(
        "pattern" => "videos/undelete/:id",
        "controller" => "videos",
        "action" => "undelete"
    ),
    array(
        "pattern" => "videos/add",
        "controller" => "videos",
        "action" => "add"
    ),
    array(
        "pattern" => "videos/all",
        "controller" => "videos",
        "action" => "all"
    ),
/*
Audio
*/

    array(
        "pattern" => "audios/play/:id",
        "controller" => "audios",
        "action" => "play"
    ),
    array(
        "pattern" => "audios/edit/:id",
        "controller" => "audios",
        "action" => "edit"
    ),
    array(
        "pattern" => "audios/delete/:id",
        "controller" => "audios",
        "action" => "delete"
    ),    
    array(
        "pattern" => "audios/undelete/:id",
        "controller" => "audios",
        "action" => "undelete"
    ),
    array(
        "pattern" => "audios/add",
        "controller" => "audios",
        "action" => "add"
    ),
    array(
        "pattern" => "sources/addvideo/:id",
        "controller" => "sources",
        "action" => "addvideo"
    ),
    array(
        "pattern" => "sources/addaudio/:id",
        "controller" => "sources",
        "action" => "addaudio"
    ),
    array(
        "pattern" => "sources/add/:type/:id",
        "controller" => "sources",
        "action" => "add"
    ),



    /*
     * Debug
     */
    array(
        "pattern" => "debug/ajax",
        "controller" => "debug",
        "action" => "ajax"
    ),
    array(
        "pattern" => "debug/framework",
        "controller" => "debug",
        "action" => "framework"
    ),
    

    array(
        "pattern" => "webgl/triangle/:id",
        "controller" => "webgl",
        "action" => "triangle"
    ),
    
    array(
        "pattern" => "webgl/three/:id",
        "controller" => "webgl",
        "action" => "three"
    )
    
    
    



));
