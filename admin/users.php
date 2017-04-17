<?php

// get config
require_once("../config.php");

$current_table = "users";
$fields_list = "username, first_name, last_name, email";

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
	
	$sql = "INSERT INTO $current_table ( $fields_list ) VALUES  ( ?, ?, ?, ? )";
	try {
		$stmt = $SITE->DB->prepare($sql);
		$stmt->execute(array($_POST['model'],$_POST['make_id']));
	} catch (Exception $e){
		trigger_error($e->getMessage());
	}
	
	header("Location: ".$SITE->CFG->url."admin\\".basename(__FILE__));
    
}

// build make options
$makes = get_asset_makes();
$makes_dropdown_out = "<select id='make_id' name='make_id' class='.form-control'>".PHP_EOL;
$makes_dropdown_out .= "<option>Select Below</option>";
foreach($makes as $make){
    $makes_dropdown_out .= "<option value='".$make['id']."'";
	if(((isset($asset)) && ($make['id'] === $asset['make_id'])) || ( (!empty($_POST['make_id'])) && ($_POST['make_id'] === $make['id']))){
        $makes_dropdown_out .= " selected";
    }
    $makes_dropdown_out .= ">".$make['make']."</option>".PHP_EOL;
}
$makes_dropdown_out .= "</select>".PHP_EOL;

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
<p><?php echo $models_output; ?></p>
<div id="models-form">
	<fieldset>
		<legend>New Model</legend>
		<form method="post">
			<p>
				<input type="hidden" name="valid" value="<?php echo $USER->key; ?>" />
				<p>
					<label for="first_name">First Name</label>
					<input type="text" name="first_name" id="first_name" class=".form-control" />
				</p>
				<p>
					<label for="last_name">Last Name</label>
					<input type="text" name="last_name" id="last_name" class=".form-control" />
				</p>
				<p>
					<label for="username">Username</label>
					<input type="text" name="username" id="username" class=".form-control" />
				</p>
				<p>
					<label for="email">Email</label>
					<input type="text" name="email" id="email" class=".form-control" />
				</p>
				<p>
					<input type="submit" name="submit" value="Save" />
				</p>
			</p>
		</form>
	</fieldset>
</div>
<?php require_once("../footer.php");?>