<?php

// get config
require_once("../config.php");

$current_table = "asset_models";

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
	
	$sql = "INSERT INTO $current_table ( model ) VALUES  ( ? )";
	try {
		$stmt = $SITE->DB->prepare($sql);
		$stmt->execute(array($_POST['model']));
	} catch (Exception $e){
		trigger_error($e->getMessage());
	}
	
	header("Location: ".$SITE->CFG->url."admin\\".basename(__FILE__));
    
}


// build model options
$models = get_asset_models();
$models_output = "<div id='models-list'>\n";
$models_output .= "<p>\n";
$models_output .= "<table class='admin-table'>\n";
$models_output .= "<tr><th><strong>Total models in system: ".sizeof($models)."</strong></th></tr>\n";

// build current models output
foreach($models as $model){
	$models_output .= "<tr>\n";
	$models_output .= "<td>".$model["model"]."</td>";
	$models_output .= "<td><a href='?action=del&id=".$model["id"]."' onclick=\"return confirm('Are you sure?')\"><span class='glyphicon glyphicon-remove'></span></a></td>\n";
	$models_output .= "</tr>\n";
}


$models_output .= "</table>\n";
$models_output .= "</p>\n";
$models_output .= "</div>\n";

?><?php require_once("../header.php");?>

<?php echo $models_output; ?>
<div id="models-form">
	<form method="post">
		<p>
			<input type="hidden" name="valid" value="<?php echo $USER->key; ?>" />
			<label for="model">New Model</label>
			<input type="text" name="model" id="model" class=".form-control" />
			<input type="submit" name="submit" value="Save" />
		</p>
	</form>
</div>
<?php require_once("../footer.php");?>