<?php

/*
 * This is the core config file for the BareBones system
 * 
 * This file sets up the inital global object that retains
 * all of the easy access calls to control includes and 
 * easy module and class inclusion
 *
 * @author: George Russell Pruitt <pruitt.russell@gmail.com>
 *
**/

// TODO REVAMP totally to pull most of this from database

define("BAREBONES_CORE", true);

// set the $CFG global object
unset($CFG);
$CFG = new stdClass(); // make into object

// setup debugging
$CFG->debug = false;
if( (isset($_GET['debug'])) || (isset($_GET['DEBUG'])) ){
    $CFG->debug = true;
}

// create session
session_start();

// determine OS type
$CFG->ostype = php_uname("s");
if( $CFG->ostype == "Linux" || $CFG->ostype == "FreeBSD" || $CFG->ostype == "Unix") {
	$CFG->sep = "/";
} else {
	$CFG->sep = "\\";
}    

// set configurations
$CFG->site_title = "KET IT Asset Inventory";
//$CFG->dbtype = "";

// need to store these in an non web accessible place
$CFG->dbname = "";
$CFG->dbhost = "";
$CFG->dbuser = "";
$CFG->dbpass = "";

// active directory stuffz
$CFG->ldap_host = "ldap://dc03.ket.local";
$CFG->ldap_basedn = "dc=ket,dc=local";

// base values to build on
$CFG->domain = $_SERVER['SERVER_ADDR'];
$CFG->cwd = basename( __DIR__ );
$CFG->dataroot = __DIR__ . "/";
$CFG->url_base = $CFG->domain."/";
$CFG->retained = true;

if($CFG->retained) {
	$CFG->url_base .= $CFG->cwd."/";
}

// style settings
$CFG->style = $CFG->url_base."style/";
$CFG->images = $CFG->url_base."images/";


// security settings
$CFG->ssl = false;

if($CFG->ssl) {
	$CFG->url = "https://".$CFG->url_base;
} else {
	$CFG->url = "http://".$CFG->url_base;
}


	
//if($CFG->debug) {
if(1==1) {
	error_reporting(E_ALL); // Report all PHP errors (see changelog)
	ini_set('error_reporting', E_ALL);
} else { 
	error_reporting(0);
}

// system core
include("core.php");

// closing tag left off intentionally to prevent white space