<?php

// get config
if(!isset($SITE)){
	require_once("../config.php");
}

// check for post
if(!empty($_POST)){
	
	$now = date("Y-m-d");
	
	if(!empty($_POST["id"])){
		
		
		
		// update sql
		$sql = "UPDATE asset_assignments SET assignment_end = ? WHERE id = ?";
		$values = array($now, $_POST["id"]);
		
	} else {

		// insert sql
		$sql = "INSERT INTO asset_assignments ( asset_id, user_descr, assignment_start ) VALUES ( ?, ?, ? )";
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




// gather assignment history
$sql = "SELECT * FROM asset_assignments WHERE asset_id = ?";
try {
	$stmt = $SITE->DB->prepare($sql);
	$stmt->execute(array($asset['id']));
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e){
	trigger_error($e->getMessage());
}


if(sizeof($result) < 1){
	$assign_history_out = "<p>No assignment history found.</p>";
} else {
	$assign_history_out = "<div id='assigned-list'>\n";
	$assign_history_out .= "<table border='1'>\n";
	$assign_history_out .= "<thead><tr><th>Assigned To</th><th>Start</th><th>End</th></tr></thead>\n";
	$assign_history_out .= "<tbody>\n";
	foreach($result as $row){
		$assign_history_out .= "<tr>\n";
		$assign_history_out .= "<td>".$row["user_descr"]."</td><td>".$row["assignment_start"]."</td>";
		if(empty($row["assignment_end"])){
			$assign_history_out .= "<td>\n";
			$assign_history_out .= "<form method='post' action='".$SITE->CFG->url."admin/assign.php'>\n";
			$assign_history_out .= "<input type='hidden' name='id' value='".$row["id"]."' />\n";
			$assign_history_out .= "<input type='hidden' name='asset_id' value='".$row["asset_id"]."' />\n";
			$assign_history_out .= "<input type='submit' name='submit' value='Unassign' />\n";
			$assign_history_out .= "</form>";
			$assign_history_out .= "</td>\n";
		} else {
			$assign_history_out .= "<td>".$row["assignment_end"]."</td>";
		}
		$assign_history_out .= "</tr>\n";
	}
	$assign_history_out .= "</tbody>\n";
	$assign_history_out .= "</table>\n";
	$assign_history_out .= "</div>\n";
}


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
			<label for="user_descr">User Description</label>
			<input type="text" name="user_descr" id="user_descr" value="" />
		</p>
		<p>
			<input type="submit" value="Save" />
		</p>
	</form>
</div>