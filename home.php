
<?php

// grab models
$types = array();
$types[] = array("1","Computer");
$types[] = array("2","Tablet");
$types[] = array("3","Other");

// build models dropdown output
$types_dropdown_output = "<select id='types-dropdown' name='types'>".PHP_EOL;
$types_dropdown_output .= "<option>Select Below</option>".PHP_EOL;
foreach($types as $type){
    $types_dropdown_output .= "<option value='$type[0]'>$type[1]</option>".PHP_EOL;
}
$types_dropdown_output .= "</select>".PHP_EOL;


// grab models
$models = array();
$models[] = array("1","Optiplex 7020");
$models[] = array("2","Optiplex 7010");
$models[] = array("3","Optiplex 780");

// build models dropdown output
$models_dropdown_output = "<select id='models-dropdown' name='models'>".PHP_EOL;
$models_dropdown_output .= "<option>Select Below</option>".PHP_EOL;
foreach($models as $model){
    $models_dropdown_output .= "<option value='$model[0]'>$model[1]</option>".PHP_EOL;
}
$models_dropdown_output .= "</select>".PHP_EOL;
?>
<div>
    
    <?php var_dump($USER); ?>

    <p>Welcome <strong><?php echo $USER->session->fullname; ?></strong></p>
    
    <div id="content-search-box">
        <form action="browse.php" method="post">
            
            <input type="hidden" name="valid" value="<?php echo $USER->key; ?>" />
            
            <div id="type-search">
                <label>Type</label>
                <?php echo $types_dropdown_output; ?>
            </div>
            <div id="model-search">
                <label>Model</label>
                <?php echo $models_dropdown_output; ?>
            </div>
            <div id="asset-search">
                <label>Asset Tag</label>
                <input type="text" id="asset-search" name="asset-search" />
            </div>
        <input type="submit" value="Go">
        </form>
    </div>
    
    <div></div>
    
    <div></div>
    
</div>