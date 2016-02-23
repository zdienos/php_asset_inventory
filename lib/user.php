<?php

/*
 * George Russell Pruitt <pruitt.russell@gmail.com>
 */

defined("KET_ASSET") || die("External linking to the file is restricted");

// set the $SITE global object
unset($USER);
global $USER; // make globally accessible
$USER = new stdClass(); // make into object
$is_admin = false;
$can_edit = false;
$session_id = session_id();

if( (isset($_SESSION['uzr']['logged'])) && (!empty($_SESSION['uzr']['logged'])) ){
    // session established
    $USER->logged = true;
    $USER->key = base64_encode($session_id);
    // grab values from session
    $USER->session = $_SESSION['uzr']['logged'];
} else {
    // no session create defaults
    $USER->logged = false;
    $USER->key = base64_encode($session_id);
}

if(@$USER->session->groups){

	// check for admin group
	if(in_array( $SITE->CFG->admin_group, $USER->session->groups )){
		$USER->is_admin = TRUE;
	}

	// check for edit priv
	if(in_array( $SITE->CFG->edit_group, $USER->session->groups )){
		$USER->can_edit = TRUE;
	}
	
}

// closing tag left off intentionally to prevent white space