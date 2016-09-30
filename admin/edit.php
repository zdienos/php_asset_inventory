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
if( ($_POST) ){ // check for post

    if( (!empty($_POST['v'])) && ($_POST['v'] !== $USER->key) ){ // check for malicious activity
        die("don't hack me brah!".PHP_EOL);
    }
    
    if( isset($_POST['id']) && ($_POST['id'] !== '0') ){
	
        $id = intval($_POST['id']);
        $asset = get_asset($id);
        if(!$asset){
            require_once($SITE->CFG->url."header.php");
            $SITE->error->display();
            require_once($SITE->CFG->url."footer.php");
        }

    }

    // predefine post fields to process
    $edit_fields = array(
        array("name" => "id","type" => "integer"),
        array("name" => "asset_tag","type" => "string"),
        array("name" => "serial_number","type" => "string"),
        array("name" => "po_number","type" => "string"),
        array("name" => "type_id","type" => "integer"),
        array("name" => "status_id","type" => "integer"),
        array("name" => "make_id","type" => "integer"),
        array("name" => "model_id","type" => "integer"),
        array("name" => "service_tag","type" => "string"),
		array("name" => "room_number","type" => "string"),
		array("name" => "purchase_date","type" => "string"),
        array("name" => "surplus_date","type" => "string")
    );
    
    $sanitized = array();
    $update_values = array();
    
    // process data
    foreach($edit_fields as $array){
        extract($array);
        if( (isset($_POST[$name])) && (!empty($_POST[$name])) ){
            unset($temp_arr);
            $temp_arr = array();
            $temp_arr["name"] = $name;
            $temp_arr["type"] = $type;
            // have to come back to this later all post vals are string
            //if(gettype($_POST[$name]) === $type){
            if(1===1){ // place keeper to keep above code 
                switch($type){
                    /* temporarily remove this code all come thru as str
                    case "integer":
                        $temp_arr['value'] = intval($_POST[$name]);
                        break;
                    case "string":
                        $temp_arr['value'] = strval($_POST[$name]);
                        break;
                    */
                    default:
                        $temp_arr['value'] = strval($_POST[$name]);
                        break;
                }  // end switch
                $sanitized[] = $temp_arr;
            } else {
                if($SITE->CFG->debug){
                    echo "<!-- $name was not the expected type of: [$type] -->";
                }
            }
            
        } else {
            if($SITE->CFG->debug){
                echo "<!-- $name contained no value? -->";
            }
        }
    }
    
    // check if new or update
    if( isset($id) && ($id !== 0) && ($id !== '0') ){
        
        $asset = get_asset($id);
        $action = "updated id: [$id]";
        $update_sql = "UPDATE assets SET ";
        
        foreach($sanitized as $field){
            if($field["name"] !== 'id'){
                extract($field);
                $update_sql .= "$name = ?, ";
                $update_values[] = $value;
            }
        }
        
        $update_sql = substr($update_sql,0,strlen($update_sql)-2);
        
        $update_sql .= " WHERE id = ? ";
        $update_values[] = $id;
        
    } else { // its a new record
        
        $action = "inserted new asset";
        $update_sql = "INSERT INTO assets ";
		$update_flds = "";
		$update_vals = "";
        foreach($sanitized as $fld_arr){
            $update_flds .= $fld_arr['name'].", ";
            $update_vals .= "?, ";
            $update_values[] = $fld_arr['value'];
        }
        
        $update_flds = substr($update_flds,0,strlen($update_flds)-2);
        $update_vals = substr($update_vals,0,strlen($update_vals)-2);
        $update_sql .= "( $update_flds ) VALUES ( $update_vals )";
        
    }
    
    // update / insert data

	try{
    	$stmt = $SITE->DB->prepare($update_sql);
		$stmt->execute($update_values);
	} catch (Exception $e){
		die($e->getMessage());
	}
	
    /*if($stmt->execute($update_values) == false){
    	trigger_error("Unable to write to database.", E_USER_ERROR);
    }*/
    
    if( $SITE->error->has_errors() ){
        require_once($SITE->CFG->dataroot."header.php");
        $SITE->error->display();
        require_once($SITE->CFG->dataroot."footer.php");
    }

    // log it
    if($action == "inserted new asset"){
		$new_id = $SITE->DB->lastInsertId();
		log_event($USER->session->id, $new_id, $action);
	} else {
		log_event($USER->session->id, $asset['id'], $action);
	}
	
    // redirect?
    if( (!empty($_POST["submit"])) && ($_POST["submit"] !== "Save") ){
		header("Location: ".$SITE->CFG->url."admin/edit.php");
	} else {
		header("Location: ".$SITE->CFG->url."browse.php");
	}
    die("should have redirected.");
    
} elseif ( ($_GET) && (!empty($_GET['id'])) ){ // no post check for get
    
    $id = intval($_GET['id']);
    
    $asset = get_asset($id);
    if(!$asset){
        trigger_error("Asset not found.", E_USER_ERROR);
    } 
    
} else {

    // set this to default value
    $id = 0;
}


// build type options
$types = get_asset_types();
$type_options = "<option>Select Below</option>";
foreach($types as $type){
    $type_options .= "<option value='".$type['id']."'";
    if( (isset($asset)) && ($type['id'] === $asset['type_id']) ){
        $type_options .= " selected";
    }
    $type_options .= ">".$type['type']."</option>".PHP_EOL;
}


// build status options
$statuses = get_asset_statuses();
$status_options = "<option>Select Below</option>";
foreach($statuses as $status){
    $status_options .= "<option value='".$status['id']."'";
    if( (isset($asset)) && ($status['id'] === $asset['status_id']) ){
        $status_options .= " selected";
    }
    $status_options .= ">".$status['status']."</option>".PHP_EOL;
}


// build make options
$makes = get_asset_makes();
$make_options = "<option>Select Below</option>";
foreach($makes as $make){
    $make_options .= "<option value='".$make['id']."'";
    if( (isset($asset)) && ($make['id'] === $asset['make_id']) ){
        $make_options .= " selected";
    }
    $make_options .= ">".$make['make']."</option>".PHP_EOL;
}


// build model options
$models = get_asset_models();
$model_options = "<option>Select Below</option>";
foreach($models as $model){
    $model_options .= "<option value='".$model['id']."'";
    if( (isset($asset)) && ($model['id'] === $asset['model_id']) ){
        $model_options .= " selected";
    }
    $model_options .= ">".$model['model']."</option>".PHP_EOL;
}

echo "<!-- ";
var_dump($USER);
echo " -->";


require_once('../header.php');

// render page
if($SITE->error->has_errors()){
    echo $SITE->error->display();
} else {
    ?>
    <form id="assetForm" name="assetForm" role="form" method="post" action="<?php echo $SITE->CFG->url; ?>admin/edit.php">
		<div class="form-group">
			<?php
			if($id){
				?>
				<input type="hidden" name="id" value="<?php echo $id ?>" />
				<?php
			}
			?>
			<input type="hidden" name="v" value="<?php echo $USER->key; ?>" />

			<p>
				<label for="asset_tag">Asset Tag #</label>
				<input type="text" name="asset_tag" id="asset_tag" value="<?php echo @$asset['asset_tag']; ?>" class=".form-control" />
			</p>
			
			<p>
				<label for="serial_number">Serial #</label>
				<input type="text" name="serial_number" id="serial_number" value="<?php echo @$asset['serial_number']; ?>" class=".form-control" />
			</p>

			<p>
				<label for="po_number">Purchase Order</label>
				<input type="text" name="po_number" id="po_number" value="<?php echo @$asset['po_number']; ?>" class=".form-control" />
			</p>

			<p>
				<label for="type_id">Type</label>
				<select name="type_id" id="type_id" class=".form-control">
					<?php echo $type_options; ?>
				</select>
			</p>

			<p>
				<label for="status_id">Status</label>
				<select name="status_id" id="status_id" class=".form-control">
					<?php echo $status_options; ?>
				</select>
			</p>

			<p>
				<label for="make_id">Make</label>
				<select name="make_id" id="make_id" class=".form-control">
					<?php echo $make_options; ?>
				</select>
			</p>

			<p>
				<label for="model_id">Model</label>
				<select name="model_id" id="model_id" class=".form-control">
					<?php echo $model_options; ?>
				</select>
			</p>

			<p>
				<label for="service_tag">Service Tag #</label>
				<input type="text" name="service_tag" id="service_tag" value="<?php echo @$asset['service_tag']; ?>" class=".form-control" />
			</p>
			
			<p>
				<label for="room_number">Room #</label>
				<input type="text" name="room_number" id="room_number" value="<?php echo @$asset['room_number']; ?>" class=".form-control" />
			</p>

			<p>
				<label for="purchase_date">Purchase Date</label>
				<input type="text" name="purchase_date" id="purchase_date" value="<?php echo @$asset['purchase_date']; ?>" class=".form-control" />
			</p>

			<p>
				<label for="surplus_date">Surplus Date</label>
				<input type="text" name="surplus_date" id="surplus_date" value="<?php echo @$asset['surplus_date']; ?>" class=".form-control" />
			</p>



<?php if($USER->is_admin){ 
	echo '<p>';
	echo '<input type="submit" name="submit" value="Save" />';
	echo '<input type="submit" name="submit" value="Save & Add New" />';
	echo '</p>';
}
?>
			
		</div>
    </form>

<?php require_once('assign-old.php'); ?>

<script src="<?php echo $SITE->CFG->js; ?>jquery.validate.min.js"></script>
<script src="<?php echo $SITE->CFG->js; ?>jquery.metadata.js"></script>
<script src="<?php echo $SITE->CFG->js; ?>additional-methods.min.js"></script>
<script src="<?php echo $SITE->CFG->js; ?>edit.js"></script>
<?php    
}

require_once('../footer.php');

?>