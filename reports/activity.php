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
$sql .= "makes.make as 'Make', ";
$sql .= "models.model as 'Model', ";
$sql .= "assets.asset_tag as 'KET Tag', ";
$sql .= "assets.serial_number as 'SN', ";
$sql .= "CONCAT(users.first_name,' ',users.last_name) as 'fullname', ";


// FROM AND JOINS
$sql .= " FROM assets ";
$sql .= " INNER JOIN asset_makes makes ON assets.make_id = asset_makes.id ";
$sql .= " INNER JOIN asset_models models ON assets.model_id = asset_models.id ";
$sql .= " LEFT JOIN asset_assignments assigns ON assigns.asset_id = assets.id ";
$sql .= " LEFT JOIN users ON assigns.assigned_to = users.id ";

// WHERE CLAUSE


// ORDER CLAUSE

$sql .= "ORDER BY assets.model_id ASC ";

$stmt = $SITE->DB->query($sql);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);




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


?>