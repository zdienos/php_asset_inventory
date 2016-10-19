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
		$sql = "SELECT id, email as name FROM users";
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
	//die("<option>No models</option>");
	return json_encode(array(array('error' => 'no records')));
	
} else {
	
	//var_dump($result);
	$output = array(array('id' => '','assigned_to' => 'Select Below'));
	foreach($result as $row){
		$output[] = array('id' => $row['id'],'name' => $row['name']);
	}

}

echo json_encode($output);
