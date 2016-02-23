<?php

// get config
require_once("../config.php");

$current_table = "asset_types";

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
	
	$sql = "INSERT INTO $current_table ( type ) VALUES  ( ? )";
	try {
		$stmt = $SITE->DB->prepare($sql);
		$stmt->execute(array($_POST['type']));
	} catch (Exception $e){
		trigger_error($e->getMessage());
	}
	
	header("Location: ".$SITE->CFG->url."admin\\".basename(__FILE__));
    
}


// build type options
$types = get_asset_types();
$types_output = "<div id='types-list'>\n";
$types_output .= "<p>\n";
$types_output .= "<table class='admin-table'>\n";
$types_output .= "<tr><th><strong>Total models in system: ".sizeof($types)."</strong></th></tr>\n";

// build current types output
foreach($types as $type){
	$types_output .= "<tr>\n";
	$types_output .= "<td>".$type["type"]."</td>";
	$types_output .= "<td><a href='?action=del&id=".$type["id"]."' onclick=\"return confirm('Are you sure?')\"><span class='glyphicon glyphicon-remove'></span></a></td>\n";
	$types_output .= "</tr>\n";
}


$types_output .= "</table>\n";
$types_output .= "</p>\n";
$types_output .= "</div>\n";

?><?php require_once("../header.php");?>

<?php echo $types_output; ?>
<div id="types-form">
	<form method="post">
		<p>
			<input type="hidden" name="valid" value="<?php echo $USER->key; ?>" />
			<label for="type">New Type</label>
			<input type="text" name="type" id="type" class=".form-control" />
			<input type="submit" name="submit" value="Save" />
		</p>
	</form>
</div>
<?php require_once("../footer.php");?>