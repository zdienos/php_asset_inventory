<?php

// grab config
include("../config.php");

if( isset($_GET['id']) ){
	
	// sanitize id if needed
	if(is_numeric($_GET['id'])){
		$type_id = intval($_GET['id']);
	} else {
		echo false;
	}
	
} else {
	echo false;
}

// build sql for models

switch($type_id){
	case 1:
		$sql = "SELECT id, CONCAT(first_name,' ',last_name) as name FROM users ORDER BY last_name ASC";
		break;
	case 2:
		$sql = "SELECT id, name FROM departments";
		break;
	case 3:
		$sql = "SELECT id, name FROM rooms";
		break;
	case 4:
		$sql = "SELECT id, name FROM projects";
		break;
}


// execute sql
$stmt = $SITE->DB->prepare($sql);
$stmt->execute($value);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);


// check for results
if(count($result) < 1){

	echo false;
	
} else {
	
	//var_dump($result);
	$output = array(array('id' => '','name' => 'Select Below'));
	foreach($result as $row){
		$output[] = array('id' => $row['id'],'name' => $row['name']);
	}

}

echo json_encode($output);
