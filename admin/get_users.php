<?php

// grab config
include("../config.php");

/////////////////////////////////////////////
// check for response

// expecting str email address
if( !empty($_GET) ){
	parse_str($_SERVER['QUERY_STRING'],$request);
}


// check for debugging
if( isset($request['debug']) ){
	$debug = true;
}

// do output
if( $debug ){
	echo "<p>Starting debug mode</p>";
	// leaving for debug
	var_dump($request);
}

// set variables
extract($request);


// build sql

$select_sql = "SELECT ";
$select_sql .= "id, ";
$select_sql .= "username, ";
$select_sql .= "CONCAT(first_name,' ',last_name) as full_name ";
$select_sql .= "FROM users ";
$select_sql .= "WHERE ";
$select_sql .= "first_name LIKE CONCAT('%',:search,'%') OR ";
$select_sql .= "last_name LIKE CONCAT('%',:search,'%') OR ";
$select_sql .= "username LIKE CONCAT('%',:search,'%') ";

// execute sql
$stmt = $SITE->DB->prepare($select_sql);
$stmt->bindValue(':search', $search, PDO::PARAM_STR);
if(!$stmt->execute()){
	trigger_error("Couldn't query DB.", E_USER_ERROR);
}

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// loop results

//build_options_html($options,$label,$id = NULL)

// profit!?


?>