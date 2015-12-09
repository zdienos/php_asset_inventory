<?php

// config
require_once("config.php");

if($CFG->debug){
    echo debug_dump($SITE, get_var_name($SITE));
}

require_once('header.php');

// check for login
if( !$USER->logged ){
    header('login.php');
}

require_once('footer.php');

?>