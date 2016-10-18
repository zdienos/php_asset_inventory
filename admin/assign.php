<?php

// get location of config
$cfg_dir = dirname(dirname(__FILE__));

// config
require_once($cfg_dir."/config.php");

// output data if debug
if($SITE->CFG->debug){
    echo debug_dump($SITE, get_var_name($SITE));
}

// check for login
if( (isset($USER->logged)) && ($USER->logged !== true) ){
    header('Location: '.$SITE->CFG->url.'index.php');
}


// check for post
if(!empty($_POST)){
	
	$now = date("Y-m-d");
	
	if(!empty($_POST["id"])){
		
		// update assignment
		$sql = "UPDATE asset_assignments SET assignment_type = ?, assigned_to = ?, assignment_end = ? WHERE id = ?";
		$values = array($_POST["assignment_type"],$_POST["assigned_to"], $now, $_POST["id"]);
		
	} else { // new assignment
		
		// create new assignment
		$sql = "INSERT INTO asset_assignments ( asset_id, assignment_type, assigned_to, assignment_start ) VALUES ( ?, ?, ?, ?)";
		$values = array($_POST["asset_id"],$_POST["user_descr"],$now);
		
	}

	// attempt the sql
	try {
		$stmt = $SITE->DB->prepare($sql);
		$stmt->execute($values);
	} catch (Exception $e) {
		trigger_error($e->getMessage());
	}
	
	// hey it worked lets redirect
	$redirect = $SITE->CFG->url."admin/edit.php?id=".$_POST["asset_id"];
	header("Location: $redirect");
	
} elseif(!$asset){
	return;
}


// build assignment history
$assignments = get_assignments($asset["id"]);


// build status options
$types = get_assignment_types();
$types_dropdown_out = "<select id='assignment_type' name='assignment_type' class='.form-control'>".PHP_EOL;
$types_dropdown_out .= "<option>Select Below</option>";
foreach($types as $type){
    $types_dropdown_out .= "<option value='".$type['asset_id']."'";
	/*
	if(){
		$statuses_dropdown_out .= " selected";
	}
	*/
	$types_dropdown_out .= ">".$type['status']."</option>".PHP_EOL;
}
$types_dropdown_out .= "</select>".PHP_EOL;

?>
<h3>Device Assignments</h3>
<div id="assign-history">
	<p>
		<?php echo $assign_history_out; ?>
	</p>
</div>
<div id="assign-form">
	<form method="post" action="assign.php">
		<input type="hidden" name="asset_id" value="<?php echo $asset["id"]; ?>" />

		<p>
			<label for="assignment_type">Assignment Type</label>
			<?php echo $types_dropdown_out; ?>
		</p>
		
		<p>
			<label for="assigned_to">Assigned</label>
			<select id="assigned_to"></select>
		</p>
		
<?php if($USER->is_admin){ ?>
		<p>
			<input type="submit" value="Save" />
		</p>
<?php } ?>
	</form>
</div>