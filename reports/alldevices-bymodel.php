<?php

// grab config
include("../config.php");

// check for login
if( !$USER->logged ){
    header('Location: '.$SITE->CFG->url.'index.php');
}

if( !$USER->is_admin ){
	header('Location: '.$SITE->CFG->url.'index.php');
}

if( isset($_GET['csv']) && !empty($_GET['csv'])){
	$csv_report = false;
} else {
	$csv_report = true;
}

// begin SQL
$sql = "SELECT ";

// FIELDS TO GRAB
$sql .= "assets.id as 'asset_id', ";
$sql .= "CONCAT(users.first_name,' ',users.last_name) as 'fullname', ";
$sql .= "assets.asset_tag as 'KET Tag #', ";
$sql .= "FROM_UNIXTIME(log.time_updated) as 'Action Time', ";
$sql .= "log.action as 'Action' ";

// FROM AND JOINS
$sql .= "FROM log ";
$sql .= "LEFT JOIN assets ON log.asset_id = assets.id ";
$sql .= "INNER JOIN users ON log.user_id = users.id ";

// WHERE CLAUSE

// ORDER CLAUSE

$sql .= "ORDER BY log.time_updated DESC ";

$stmt = $SITE->DB->query($sql);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);


require_once("../header.php");

if($csv_report = true){
	header("Content-type: text/csv");
	header("Content-Disposition: attachment; filename=report.csv");
	header("Pragma: no-cache");
	header("Expires: 0");
	//echo generate_csv($user_data,"id",false);
	echo generate_csv($results,"asset_id");
	echo "Total Records,".sizeof($results).PHP_EOL;
	
} else {
	require_once("../header.php");
	echo generate_html_table($results,"asset_id");
	require_once("../footer.php");
}

require_once("../footer.php");
?>