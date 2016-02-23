<?php

// grab config
include("../config.php");

// check for login
if( !$USER->logged ){
    header('Location: '.$SITE->CFG->url.'index.php');
}

if( !$USER->is_admin ){
	header('Location: '.$SITE->CFG->url.'index.php');
}

// begin SQL
$sql = "SELECT ";

// FIELDS TO GRAB
$sql .= "CONCAT(users.first_name,' ',users.last_name) as 'fullname', ";
$sql .= "assets.asset_tag as 'KET Tag #', ";
$sql .= "FROM_UNIXTIME(log.time_updated) as 'Action Time', ";
$sql .= "log.action as 'Action' ";

// FROM AND JOINS
$sql .= "FROM log ";
$sql .= "LEFT JOIN assets ON log.asset_id = assets.id ";
$sql .= "INNER JOIN users ON log.user_id = users.id ";

// WHERE CLAUSE

// ORDER CLAUSE

$sql .= "ORDER BY log.time_updated DESC ";

$stmt = $SITE->DB->query($sql);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);


require_once("../header.php");

echo generate_html_table($results,"id",FALSE);

require_once("../footer.php");
?>