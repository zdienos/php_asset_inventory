<?php

// get config
require_once("../config.php");

$current_table = "rooms";
$fields_list = "name";

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
		$stmt->execute(array($_POST['username'],$_POST['first_name'],$_POST['last_name'],$_POST['email']));
	} catch (Exception $e){
		trigger_error($e->getMessage());
	}
	
	header("Location: ".$SITE->CFG->url."admin\\".basename(__FILE__));
    
}

?><?php require_once("../header.php");?>
<p><?php echo $models_output; ?></p>
<div id="users-form">
	<fieldset>
		<legend>New User</legend>
		<form method="post">
			<p>
				<input type="hidden" name="valid" value="<?php echo $USER->key; ?>" />
				<p>
					<label for="rooms">Rooms</label>
					<input type="text" name="rooms" id="rooms" class=".form-control" />
				</p>
				<p>
					<input type="submit" name="submit" value="Save" />
				</p>
			</p>
		</form>
	</fieldset>
</div>
<?php require_once("../footer.php");?>