<?php

// get config
require_once("../config.php");

$current_table = "asset_makes";

// check for login
if( !$USER->logged ){
    header('Location: '.$SITE->CFG->url.'index.php');
}

// check for permission
if( (!$USER->is_admin) && (!$USER->can_edit) ){
	header('Location: '.$SITE->CFG->url.'index.php');
}

if( (!empty($_GET['action'])) && ( $_GET['action'] == 'del' ) && (!empty($_GET['id'])) ){
	$sql = "DELETE FROM $current_table WHERE id = ?";
	try {
		$stmt = $SITE->DB->prepare($sql);
		$stmt->execute(array($_GET['id']));
	} catch (Exception $e){
		trigger_error($e->getMessage());
	}
	header("Location: ".$SITE->CFG->url."admin\\".basename(__FILE__));
}

// check for post and validate
if(!empty($_POST)){
    
    // validate POST
    if( (isset($_POST["valid"])) && (!empty($_POST["valid"])) ){
        if($_POST["valid"] !== $USER->key){
            // possible hack attempt
            // TODO handle scenario
            die("<!-- don't hack me bro -->");
        }
    } else {
        // possible hack attempt?
        // TODO handle scenario        
        die("<!-- don't hack me bro -->");
    }
	
	// all appears clear insert value
	
	$sql = "INSERT INTO $current_table ( make ) VALUES  ( ? )";
	try {
		$stmt = $SITE->DB->prepare($sql);
		$stmt->execute(array($_POST['make']));
	} catch (Exception $e){
		trigger_error($e->getMessage());
	}
	
	header("Location: ".$SITE->CFG->url."admin\\".basename(__FILE__));
    
}


// build make options
$makes = get_asset_makes();
$makes_output = "<div id='makes-list'>\n";
$makes_output .= "<p>\n";
$makes_output .= "<table class='admin-table'>\n";
$makes_output .= "<tr><th><strong>Total models in system: ".sizeof($makes)."</strong></th></tr>\n";

// build current makes output
foreach($makes as $make){
	$makes_output .= "<tr>\n";
	$makes_output .= "<td>".$make["make"]."</td>";
	$makes_output .= "<td><a href='?action=del&id=".$make["id"]."' onclick=\"return confirm('Are you sure?')\"><span class='glyphicon glyphicon-remove'></span></a></td>\n";
	$makes_output .= "</tr>\n";
}


$makes_output .= "</table>\n";
$makes_output .= "</p>\n";
$makes_output .= "</div>\n";

?><?php require_once("../header.php");?>
<div id="makes-form">
	<fieldset>
		<legend>New Make</legend>
		<form method="post">
			<p>
				<input type="hidden" name="valid" value="<?php echo $USER->key; ?>" />
				<label for="make">New Make</label>
				<input type="text" name="make" id="make" class=".form-control" />
				<input type="submit" name="submit" value="Save" />
			</p>
		</form>
	</fieldset>
</div>
<p><?php echo $makes_output; ?></p>
<?php require_once("../footer.php");?>