<?php

// TODO REVAMP totally to pull most of this from database

include_once("secure.php");

// set the $CFG global object
unset($CFG);
$CFG = new stdClass(); // make into object

// setup debugging
// turned on by default for dev
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

// need to store these in an non web accessible place

$CFG->dbtype = KET_ASSET_DBTYPE;
$CFG->dbhost = KET_ASSET_DBHOST;
$CFG->dbname = KET_ASSET_DBNAME;
$CFG->dbuser = KET_ASSET_DBUSER;
$CFG->dbpass = KET_ASSET_DBPASS;

// active directory stuffz
$CFG->ldap_host = KET_ASSET_LDAP_HOST;
$CFG->ldap_basedn = KET_ASSET_LDAP_BASEDN;


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
$CFG->css = $CFG->url_base."css/";
$CFG->images = $CFG->url_base."images/";
$CFG->js = $CFG->url_base."js/";


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


// closing tag left off intentionally to avoid white space