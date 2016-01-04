<?php

// config
require_once("config.php");

if($CFG->debug){
    echo debug_dump($SITE, get_var_name($SITE));
}

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
//$search_sql .= " * ";

$search_sql .= "assets.id, ";
$search_sql .= "assets.asset_tag, ";
$search_sql .= "assets.serial_number, ";
$search_sql .= "assets.po_number, ";
$search_sql .= "assets.type_id, ";
$search_sql .= "assets.status_id, ";
$search_sql .= "assets.make, ";
$search_sql .= "assets.model, ";
$search_sql .= "assets.service_tag, ";
$search_sql .= "assets.purchase_date ";


$search_sql .= "FROM assets ";
//$search_sql .= "INNER JOIN asset_type ON assets.type_id = asset_type.id ";
//$search_sql .= "INNER JOIN asset_status ON assets.status_id = asset_status.id ";

// build where clause
$where_sql = " WHERE ";
$where_values = array();

// build where array
foreach($_POST as $key => $value){
    if( ($key !== "valid") && (!empty($value)) && ($value !== 'Select Below') ){
        $where_values[] = " $key = '$value' ";
    }
}

foreach($where_values as $clause){
    if($clause === end($where_values)){
        $where_sql .= $clause;
    } else {
        $where_sql .= $clause." AND ";
    }
}

$search_sql .= $where_sql;

// TODO order sql
$search_sql .= "";

// TODO limit(pagination) sql
$search_sql .= "";

try {
    $stmt = $SITE->DB->query($search_sql);
    //echo $stmt->rowCount();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $SITE->error->add($e);
}

require_once('header.php');

if($SITE->error->has_errors()){
    die($SITE->error->display());
} else {
    echo generate_html_table($results,"id",FALSE);
}

require_once('footer.php');
?>