<?php

// config
require_once("config.php");

if($CFG->debug){
    echo debug_dump($SITE, get_var_name($SITE));
}



// check for login
if( (isset($USER->logged)) && ($USER->logged !== true) ){
    header('Location: index.php');
}

// check for passed id
if(!isset($_REQUEST['id'])){
    $id = 'new';
} elseif ((isset($_REQUEST['id'])) && (!empty($_REQUEST['id']))) {
    $id = $_REQUEST['id'];
}

if( (isset($id)) && ($id !== "new") ){
    // go grab the record
    $asset_sql = "SELECT * FROM assets WHERE id = :id";
    $sth = $SITE->DB->prepare($asset_sql);
    $sth->bindParam(':id', $id, PDO::PARAM_INT);
    $sth->execute();
    $asset = $sth->fetchAll(PDO::FETCH_ASSOC);
    // reassign to first/only row
    $asset = $asset[0];
}


// build type options
$types = get_asset_types();
$type_options = "";
foreach($types as $type){
    $type_options .= "<option value='".$type['id']."'";
    if( (isset($asset)) && ($type['id'] === $asset['type_id']) ){
        $type_options .= " selected";
    }
    $type_options .= ">".$type['type']."</option>".PHP_EOL;
}


// build status options
$statuses = get_asset_statuses();
$status_options = "";
foreach($statuses as $status){
    $status_options .= "<option value='".$status['id']."'";
    if( (isset($asset)) && ($status['id'] === $asset['status_id']) ){
        $status_options .= " selected";
    }
    $status_options .= ">".$status['status']."</option>".PHP_EOL;
}


// build make options
$makes = get_asset_makes();
$make_options = "";
foreach($makes as $make){
    $make_options .= "<option value='".$make['id']."'";
    if( (isset($asset)) && ($make['id'] === $asset['make_id']) ){
        $make_options .= " selected";
    }
    $make_options .= ">".$make['make']."</option>".PHP_EOL;
}


// build model options
$models = get_asset_models();
$model_options = "";
foreach($models as $model){
    $model_options .= "<option value='".$model['id']."'";
    if( (isset($asset)) && ($model['id'] === $asset['make_id']) ){
        $model_options .= " selected";
    }
    $model_options .= ">".$model['model']."</option>".PHP_EOL;
}
?><?php require_once('header.php'); ?>

<input type="hidden" name="id" value="<?php echo $id ?>" />

<p>
    <label for="asset_tag">Asset Tag #</label>
    <input type="text" name="asset_tag" id="asset_tag" value="<?php echo $asset['asset_tag']; ?>" />
</p>

<p>
    <label for="serial_number">Serial #</label>
    <input type="text" name="serial_number" id="serial_number" value="<?php echo $asset['serial_number']; ?>" />
</p>

<p>
    <label for="po_number">Purchase Order</label>
    <input type="text" name="po_number" id="po_number" value="<?php echo $asset['po_number']; ?>" />
</p>

<p>
    <label for="type_id">Type</label>
    <select name="type_id" id="type_id">
        <?php echo $type_options; ?>
    </select>
</p>

<p>
    <label for="status_id">Status</label>
    <select name="status_id" id="status_id">
        <?php echo $status_options; ?>
    </select>
</p>

<p>
    <label for="">Make</label>
    <select name="make_id" id="make_id">
        <?php echo $make_options; ?>
    </select>
</p>

<p>
    <label for="">Model</label>
    <select name="model_id" id="model_id">
        <?php echo $model_options; ?>
    </select>
</p>

<p>
    <label for="">Service Tag #</label>
    <input type="text" name="service_tag" id="service_tag" value="<?php echo $asset['service_tag']; ?>" />
</p>

<p>
    <label for="">Purchase Date</label>
    <input type="text" name="purchase_date" id="purchase_date" value="<?php echo $asset['purchase_date']; ?>" />
</p>

<?php require_once('footer.php'); ?>