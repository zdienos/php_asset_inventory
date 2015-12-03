<?php

/*
 * @script	index.php
 * @author: George Russell Pruitt <pruitt.russell@gmail.com>
 *
 * Main index file
 *
 * All requests are handled by this script.
 *
**/
 
// config
require_once("config.php");

if($CFG->debug){
    echo debug_dump($SITE, get_var_name($SITE));
}

require_once('header.php');

// check for login
if($USER->logged){
    require_once('home.php');
} else { // not logged in
    require_once('login.php');
}

require_once('footer.php');