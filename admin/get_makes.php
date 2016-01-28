<?php

// grab config
include("../config.php");

//if( (isset($_GET['m'])) && (isset($_GET['v'])) && () ){
if( isset($_POST['make_id']) ){
	// sanitize make_id if needed
	if(is_numeric($_POST['make_id'])){
		$make_id = intval($_POST['make_id']);
	} else {
		// TODO remove below and uncomment echo false
		die("Unexpected value encountered.");
		
		// return false
		//echo false;
	}
	
} else {
	
	// TODO remove below and uncomment echo false
	//die(var_dump($_POST));
	echo("Expected information missing.");
	
	// return false
	//echo false;
	
}

// build sql for models
$sql = "SELECT * FROM asset_models WHERE make_id = ?";
$value = array($make_id);
//$stmt = $SITE->DB->prepare($sql);
//$result = $stmt->execute($value);
//$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $SITE->DB->prepare($sql);
$stmt->execute($value);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);



if(count($result) < 1){
	//die("<option>No models</option>");
	return json_encode(array(array('id' => '','model' => 'No models found','make_id' => $make_id)));
	
} else {
	
	$output = array(array('id' => '','model' => 'Select Below','make_id' => ''));
	
	foreach($result as $row){
		$output[] = array('id' => $row['id'],'model' => $row['model'],'make_id' => $row['make_id']);
	}
	
}

echo json_encode($output);
