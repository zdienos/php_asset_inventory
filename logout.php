<?php
include("config.php");
log_event($USER->session->id,'0','logout');
session_destroy(); //destroy the session
header("Location: index.php"); //to redirect back to "index.php" after logging out
exit();
?>