<?php

/*
 * @author: George Russell Pruitt <pruitt.russell@gmail.com>
 */

defined("KET_ASSET") || die("External linking to the file is restricted");

switch($CFG->dbtype){
    case "mysql":
        $dsn = "mysql:host=$CFG->dbhost;dbname=$CFG->dbname";
        break;
/*    case "mssql":
 *      $dsn = "";
 *      break;
*/
}

$DB = new PDO($dsn, $CFG->dbuser, $CFG->dbpass);
$DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// closing tag left off intentionally to prevent white space