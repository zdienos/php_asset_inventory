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

// 
if(!empty($_POST)){
    
    // validate POST
    if( (isset($_POST["valid"])) && (!empty($_POST["valid"])) ){
        if($_POST["valid"] !== $USER->key){
            // possible hack attempt
            // TODO handle scenario
            die("don't hack me bro");
        }
    } else {
        // possible hack attempt?
        // TODO handle scenario        
        die("don't hack me bro");
    }
    
    foreach($_POST as $key => $value){
        echo "<p>$key: <strong>[$value]</strong></p>".PHP_EOL;
    }
    
} else {
    die("page missing required components");
}

require_once('footer.php');

?>