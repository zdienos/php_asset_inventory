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
$sql .= "assets.asset_tag as 'KET Tag #', ";
$sql .= "asset_makes.make as 'Make', ";
$sql .= "asset_models.model as 'Model', ";
$sql .= "CASE asset_assignments.assignment_type ";
$sql .= "	WHEN 1 then users.email ";
$sql .= "	WHEN 2 then departments.name ";
$sql .= "	WHEN 3 then rooms.name ";
$sql .= "	WHEN 4 then projects.name ";
$sql .= "END as 'Assigned' ";


// FROM AND JOINS
$sql .= "FROM assets ";
$sql .= "INNER JOIN asset_makes ON assets.make_id = asset_makes.id ";
$sql .= "INNER JOIN asset_models ON assets.model_id = asset_models.id ";
$sql .= "LEFT JOIN asset_assignments ON asset_assignments.asset_id = assets.id ";
$sql .= "LEFT JOIN asset_assignment_types ON asset_assignment_types.id = asset_assignments.assignment_type ";
$sql .= "LEFT JOIN departments ON asset_assignments.assigned_to = departments.id ";
$sql .= "LEFT JOIN users ON asset_assignments.assigned_to = users.id ";
$sql .= "LEFT JOIN rooms ON asset_assignments.assigned_to = rooms.id ";
$sql .= "LEFT JOIN projects ON asset_assignments.assigned_to = projects.id ";

// WHERE CLAUSE

// ORDER CLAUSE

$sql .= "ORDER BY asset_models.model ASC ";


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