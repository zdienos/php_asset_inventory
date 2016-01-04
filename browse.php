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

// check for post and validate
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
    
    /*
    foreach($_POST as $key => $value){
        echo "<p>$key: <strong>[$value]</strong></p>".PHP_EOL;
    }
    */
    
} else {
    die("page missing required components");
}

// begin building SQL
$search_sql = "SELECT ";
$search_sql .= " * ";
$search_sql .= "FROM assets ";
$search_sql .= "INNER JOIN asset_type ON assets.type_id = asset_type.id ";
$search_sql .= "INNER JOIN asset_status ON assets.status_id = asset_status.id ";

// build where clause
$search_sql .= "WHERE ";
$where_values = array();

// build where array
foreach($_POST as $key => $value){
    if( ($key !== "valid") && ( !empty($value) ) ){
        $where_values[] .= " $key = '$value' ";
    }
}

foreach($where_values as $clause){
    if($clause === end($where_values)){
        $search_sql .= $clause;
    } else {
        $search_sql .= $clause." AND ";
    }
}

// TODO order sql
$search_sql .= "";

// TODO limit(pagination) sql
$search_sql .= "";

require_once('footer.php');
?>