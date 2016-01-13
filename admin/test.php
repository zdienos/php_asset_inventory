<?php


// config
require_once("../config.php");

if($CFG->debug){
    echo debug_dump($SITE, get_var_name($SITE));
}

// check for login
if( (isset($USER->logged)) && ($USER->logged !== true) ){
    header('Location: index.php');
}

// check for post
if( ($_POST) ){ // check for post
    if( (!empty($_POST['v'])) && (base64_decode($_POST['v']) !== $USER->key) ){ // check for malicious activity
        die("don't hack me brah!".PHP_EOL);
    }

    echo "found post".PHP_EOL;
    
    // post exists process it

    // update / insert data

    // log it
    
} elseif ( ($_GET) && (!empty($_GET['id'])) ){ // no post check for get
    
    if(!is_int($_GET['id'])){
        die("don't hack me brah!".PHP_EOL);
    } else {
        //
        $id = intval($_GET['id']);
    }

    /*
    // id passed in GET use for selection
    if(!isset($_GET['id'])){
  
    } elseif ((isset($_GET['id'])) && (!empty($_GET['id'])) &&  ) {
        $id = intval($_GET['id']);
    }
    */
    
    echo "found get, id=[$id]".PHP_EOL;

} else {
    // set this to default value
    $id = 'new';
}


trigger_error("this is a custom error",E_USER_ERROR);

trigger_error("russell now likes errors",E_USER_ERROR);

// render page
if($SITE->error->has_errors()){
    echo $SITE->error->display();
} else {
    echo "error handling not working as hoped.";
}


?>