<!DOCTYPE html>
<html>
<head>
	<title><?php echo $SITE->CFG->site_title; ?></title>
	<script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
	<script src="https://code.jquery.com/ui/1.10.4/jquery-ui.min.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
	<div id="container">
		<div id="header-wrap">
			<div id="header">
                <h3>KET Asset Inventory</h3>
                <?php
                if($USER->logged){
                    ?>
                    <span><a href="logout.php">Logout</a></span>
                <?php } ?>
            </div>
		</div>
        <div id="left-nav-wrap">
            <?php include("left-nav.php"); ?>
        </div>
		<div id="content-wrapper">
			<div id="content">