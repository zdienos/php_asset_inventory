<?php

// config
require_once("config.php");

if($CFG->debug){
    echo debug_dump($SITE, get_var_name($SITE));
}

require_once('header.php');

// check for login
if( !$USER->logged ){
    header('login.php');
}

//$reports_output .= "";

$reports_output = "<div id='report_menu'>";

// generate admin report section
if($USER->is_admin){
	$reports_output .= "<fieldset>";
	$reports_output .= "<legend>Admin Reports</legend>";
	$reports_output .= "<p>Insert report link here</p>";
	$reports_output .= "<p>Insert report link here</p>";
	$reports_output .= "<p>Insert report link here</p>";
	$reports_output .= "</fieldset>";
}

// generate user reports
$reports_output .= "<fieldset>";
$reports_output .= "<legend>User Reports</legend>";
$reports_output .= "<p>Insert report link here</p>";
$reports_output .= "<p>Insert report link here</p>";
$reports_output .= "<p>Insert report link here</p>";
$reports_output .= "<p>Insert report link here</p>";
$reports_output .= "<p>Insert report link here</p>";
$reports_output .= "</fieldset>";


$reports_output .= "</div>";


echo $reports_output;

require_once('footer.php');

?>