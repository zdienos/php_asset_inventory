<?php

require_once("config.php");

$sql = "SELECT * FROM log";

$result = $SITE->DB->query($sql);

$results = $result->fetchAll(PDO::FETCH_ASSOC);

foreach($results as $row){
    echo "<div style='border:solid 1px;'>".PHP_EOL;
    foreach($row as $key => $value){
        echo "<p>$key : [$value]</p>".PHP_EOL;
    }
    echo "</div>".PHP_EOL;
}

?>