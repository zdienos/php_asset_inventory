<?php

// get config
require_once("../config.php");

$current_table = "asset_statuses";

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
	
	$sql = "INSERT INTO $current_table ( status ) VALUES  ( ? )";
	try {
		$stmt = $SITE->DB->prepare($sql);
		$stmt->execute(array($_POST['status']));
	} catch (Exception $e){
		trigger_error($e->getMessage());
	}
	
	header("Location: ".$SITE->CFG->url."admin\\".basename(__FILE__));
    
}


// build status options
$statuses = get_asset_statuses();
$statuses_output = "<div id='statuses-list'>\n";
$statuses_output .= "<p>\n";
$statuses_output .= "<table class='admin-table'>\n";
$statuses_output .= "<tr><th><strong>Total Statuses in system: ".sizeof($statuses)."</strong></th></tr>\n";

// build current makes output
foreach($statuses as $status){
	$statuses_output .= "<tr>\n";
	$statuses_output .= "<td>".$status["status"]."</td>";
	$statuses_output .= "<td><a href='?action=del&id=".$status["id"]."' onclick=\"return confirm('Are you sure?')\"><span class='glyphicon glyphicon-remove'></span></a></td>\n";
	$statuses_output .= "</tr>\n";
}


$statuses_output .= "</table>\n";
$statuses_output .= "</p>\n";
$statuses_output .= "</div>\n";

?><?php require_once("../header.php");?>

<?php echo $statuses_output; ?>
<div id="statuses-form">
	<form method="post">
		<p>
			<input type="hidden" name="valid" value="<?php echo $USER->key; ?>" />
			<label for="status">New Status</label>
			<input type="text" name="status" id="status" class=".form-control" />
			<input type="submit" name="submit" value="Save" />
		</p>
	</form>
</div>
<?php require_once("../footer.php");?>