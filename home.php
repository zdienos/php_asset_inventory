<?php


// build type options
$types = get_asset_types();
$types_dropdown_output = "<select id='type_id' name='type_id'>".PHP_EOL;
$types_dropdown_output .= "<option>Select Below</option>";
foreach($types as $type){
    $types_dropdown_output .= "<option value='".$type['id']."'";
    if( (isset($asset)) && ($type['id'] === $asset['type_id']) ){
        $types_dropdown_output .= " selected";
    }
    $types_dropdown_output .= ">".$type['type']."</option>".PHP_EOL;
}
$types_dropdown_output .= "</select>".PHP_EOL;


// build status options
$statuses = get_asset_statuses();
$statuses_dropdown_out = "<select id='status_id' name='status_id'>".PHP_EOL;
$statuses_dropdown_out .= "<option>Select Below</option>";
foreach($statuses as $status){
    $statuses_dropdown_out .= "<option value='".$status['id']."'";
    if( (isset($asset)) && ($status['id'] === $asset['status_id']) ){
        $statuses_dropdown_out .= " selected";
    }
    $statuses_dropdown_out .= ">".$status['status']."</option>".PHP_EOL;
}
$statuses_dropdown_out .= "</select>".PHP_EOL;


// build make options
$makes = get_asset_makes();
$makes_dropdown_out = "<select id='make_id' name='make_id'>".PHP_EOL;
$makes_dropdown_out .= "<option>Select Below</option>";
foreach($makes as $make){
    $makes_dropdown_out .= "<option value='".$make['id']."'";
    if( (isset($asset)) && ($make['id'] === $asset['make_id']) ){
        $makes_dropdown_out .= " selected";
    }
    $makes_dropdown_out .= ">".$make['make']."</option>".PHP_EOL;
}
$makes_dropdown_out .= "</select>".PHP_EOL;


// build model options
$models = get_asset_models();
$models_dropdown_output = "<select id='model_id' name='model_id'>".PHP_EOL;
//$models_dropdown_output .= "<option>Pick Make First</option>";
/*
$models_dropdown_output .= "<option>Select Below</option>";
foreach($models as $model){
    $models_dropdown_output .= "<option value='".$model['id']."'";
    if( (isset($asset)) && ($model['id'] === $asset['model_id']) ){
        $models_dropdown_output .= " selected";
    }
    $models_dropdown_output .= ">".$model['model']."</option>".PHP_EOL;
}
*/
$models_dropdown_output .= "</select>".PHP_EOL;

$last_login_epoch = get_user_last_login($USER->session->id);
$last_login = date('Y-m-d H:i:s',$last_login_epoch);

$new_devices = get_new_assets($USER->session->id,true);

?><div>
    
    <p>Welcome <strong><?php echo $USER->session->fullname; ?></strong></p>
    <p>There are <strong><?php echo $new_devices ?></strong> new devices since your last login at: <strong><?php echo $last_login; ?></strong></p>
    
    <div id="content-search-box">
        <form action="browse.php" method="post">
            
            <input type="hidden" name="valid" value="<?php echo $USER->key; ?>" />
            
            <div id="type-search">
                <label>Type</label>
                <?php echo $types_dropdown_output; ?>
            </div>
            <div id="make-search">
                <label>Make</label>
                <?php echo $makes_dropdown_out; ?>
            </div>
            <div id="model-search">
                <label>Model</label>
                <?php echo $models_dropdown_output; ?>
            </div>
           <div id="status-search">
                <label>Status</label>
                <?php echo $statuses_dropdown_out; ?>
            </div>
            <div id="asset-search">
                <label>Asset Tag</label>
                <input type="text" id="asset_tag" name="asset_tag" />
            </div>

        <input type="submit" value="Go">
        </form>
    </div>
    
	<script src="<?php echo $SITE->CFG->js; ?>home.js"></script>
    
</div>