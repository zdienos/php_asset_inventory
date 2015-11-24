<?php

/*
 * @script	site.php
 * @author: George Russell Pruitt <pruitt.russell@gmail.com>
 * @library BareBones
 *
 * Core system file that creates the highly used $SITE object.
 * $SITE is added to in core.php and accessed throughout the site.
 *
**/

//defined("BAREBONES_CORE") || die("External linking to the file is restricted");

// set the $SITE global object
unset($SITE);
$SITE = new stdClass(); // make into object

$SITE->CFG = $CFG;

// useful shortcuts
$SITE->lib = $SITE->CFG->dataroot."lib".$SITE->CFG->sep;
$SITE->mod = $SITE->lib."mod".$CFG->sep;
$SITE->view = $SITE->CFG->dataroot."view".$SITE->CFG->sep;
$SITE->templates = $CFG->dataroot."templates".$CFG->sep;

// exception handler
include($SITE->lib."ExceptionHandler.class.php");

// database
include($SITE->lib."db.php");

$SITE->DB = $DB;

$SITE->error = new ExceptionHandler();


// closing tag left off intentionally to prevent white space