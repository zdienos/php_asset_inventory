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

	if(!empty($_POST["id"]) && ($_POST["submit"] == "Update")){
	
		// update assignment
		$sql = "UPDATE asset_assignments SET assignment_type = ?, assigned_to = ?, user_descr = ?, assignment_start = ?, assignment_end = ? WHERE id = ?";
		$values = array($_POST["assignment_type"],$_POST["assigned_to"], $_POST["user_descr"], $_POST["assignment_start"], $now, $_POST["id"]);
		
	} 
	else if(!empty($_POST["id"]) && ($_POST["submit"] == "Unassign")){

		// fill value for assignment_end
		$sql = "UPDATE asset_assignments SET assignment_end = ? WHERE id = ?";
		$values = array($now,$_POST["id"]);

	} else { // new assignment
		
		// create new assignment
		$sql = "INSERT INTO asset_assignments ( asset_id, assignment_type, assigned_to, user_descr, assignment_start ) VALUES ( ?, ?, ?, ?, ?)";
		$values = array($_POST["asset_id"],$_POST["assignment_type"],$_POST["assigned_to"],$_POST["user_descr"], $_POST["assignment_start"]);
		
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

// build status options
$types = get_assignment_types();
$types_dropdown_out = "<select id='assignment_type' name='assignment_type' class='.form-control'>".PHP_EOL;
$types_dropdown_out .= "<option>Select Below</option>";
foreach($types as $type){
    $types_dropdown_out .= "<option value='".$type['id']."'";
	if($assignments[0]["assignment_type"] == $type['id']){
		$statuses_dropdown_out .= " selected";
	}
	$types_dropdown_out .= ">".$type['type']."</option>".PHP_EOL;
}
$types_dropdown_out .= "</select>".PHP_EOL;


// build assignment history
$assignments = get_assignments($asset["id"]);
$assign_history_out = "<div id='assigned-list' class='assigned-list'>\n";
$assign_history_out .= "<table class='table table-striped'>\n";
$assign_history_out .= "<thead><tr><th>Type</th><th>Assigned</th><th>Note</th><th>Start</th><th>End</th><th>&nbsp;</th></tr></thead>\n";
$assign_history_out .= "<tbody>\n";

foreach($assignments as $assignment){
	$assign_history_out .= "<tr>";
	$assign_history_out .= "<td>".$assignment["type"]."</td>\n";
	$assign_history_out .= "<td>".$assignment["assigned_to"]."</td>\n";
	$assign_history_out .= "<td>".$assignment["user_descr"]."</td>";
	$assign_history_out .= "<td>".$assignment["assignment_start"]."</td>\n";
	if(empty($assignment["assignment_end"])){
		$assign_history_out .= "<td>\n";
		$assign_history_out .= "<form method='post' action='".$SITE->CFG->url."admin/assign.php'>\n";
		$assign_history_out .= "<input type='hidden' name='id' value='".$assignment["id"]."' />\n";
		$assign_history_out .= "<input type='hidden' name='asset_id' value='".$assignment["asset_id"]."' />\n";
		$assign_history_out .= "<input type='submit' name='submit' value='Unassign' />\n";
		$assign_history_out .= "</form>";
		$assign_history_out .= "</td>\n";
	} else {
		$assign_history_out .= "<td>".$assignment["assignment_end"]."</td>";
	}
	$assign_history_out .= "<td>";
	$assign_history_out .= "<span class='glyphicon glyphicon-minus'></span>";
	$assign_history_out .= "<span class='glyphicon glyphicon-pencil'></span>";
	$assign_history_out .= "</td>";
	$assign_history_out .= "</tr>";
}
$assign_history_out .= "</tbody>";
$assign_history_out .= "</table>";
?>
<div id="right-col">
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
				<select id="assigned_to" name="assigned_to"></select>
			</p>

			<p>
				<label for="user_descr">User Description</label>
				<input type="text" name="user_descr" id="user_descr" value="" />
			</p>

			<p>
				<label for="assignment_start">Start Date</label>
				<input type="text" name="assignment_start" id="assignment_start" value="" class=".form-control" />
			</p>

			<p>
				<label for="assignment_end">End Date</label>
				<input type="text" name="assignment_end" id="assignment_end" value="" class=".form-control" />
			</p>

	<?php if($USER->is_admin){ ?>
			<p>
				<input type="submit" value="Save" />
			</p>
	<?php } ?>
		</form>
	</div>
</div>

<script src="<?php echo $SITE->CFG->js; ?>assign.js"></script>