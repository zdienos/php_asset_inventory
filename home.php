<?php

$last_login_epoch = get_user_last_login($USER->session->id);
$last_login = date('Y-m-d H:i:s',$last_login_epoch);

$new_devices = get_new_assets($USER->session->id,true);
if(!$new_devices){
	$new_devices = "0";
}
$new_dev_msg = "<p>[<strong>$new_devices</strong>] new devices since your last login at: <strong>$last_login</strong></p>";
?><div>
    
    <p>Welcome <strong><?php echo $USER->session->fullname; ?></strong></p>
    <?php echo $new_dev_msg; ?>
	
	<?php include("search.php"); ?>
    
</div>