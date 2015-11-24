<?php

/*
 * @script	core.php
 * @author: George Russell Pruitt <pruitt.russell@gmail.com>
 * @library BareBones
 *
 * Core system file that includes many needed files for the system
 *
**/

defined("BAREBONES_CORE") || die("External linking to the file is restricted");

// global user object
// not used yet


// include the site global
include("site.php");

// call the functions
include($SITE->lib."functions.php");

// call user stuff
include("user.php");

/*
// call the request handler
include($SITE->lib."RequestController.class.php");
$SITE->REQUEST = $REQUEST;

// call the site / action controller
include($SITE->lib."ActionController.class.php");
$SITE->ACTIONS = $ACTIONS;
*/

// TODO call the module loading system




// closing tag left off intentionally to prevent white space