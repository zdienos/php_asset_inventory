<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <title><?php echo $SITE->CFG->site_title; ?></title>
    <script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.min.js"></script>
    <script src="<?php echo $SITE->CFG->js; ?>navigation.js"></script>

    <!-- Bootstrap -->
    <link href="<?php echo $SITE->CFG->css; ?>bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo $SITE->CFG->css; ?>style.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>
	<div class="container">
        <div id="header">
            <h3>KET Asset Inventory</h3>
            <button id="nav-toggle-button" type="button" class="btn btn-default" aria-label="Left Align">
                <span style="font-size:16px" class="glyphicon glyphicon-menu-hamburger"></span>
            </button>
            <?php if($USER->logged){ 
            ?>
            <button id="nav-toggle-button" type="button" class="btn btn-default" aria-label="Left Align">
                <span style="font-size:16px" class="glyphicon glyphicon-menu-hamburger"></span>
            </button>
            <?php } ?>
            <div id="nav-menu-links">
                <?php include("nav-menu.php"); ?>
            </div>
        </div>
		<div id="content-wrap">
            <div id="content">
