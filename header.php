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


    <!-- Bootstrap -->
    <link href="<?php echo $CFG->css; ?>bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo $CFG->css; ?>style.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>
	<div id="container">
		<div id="header-wrap">
			<div id="header">
                <div id="logo">
                    <h3>KET Asset Inventory</h3>
                    <?php
                    if($USER->logged){
                        ?>
                        <span><a href="logout.php">Logout</a></span>
                    <?php } ?>
                </div>
                <div id="navigation">
                    <?php
                        if($USER->logged){ ?>
                        <ul class="left-nav-menu">
                            <li>Home</li>
                            <li>Add New</li>
                            <li>Browse</li>
                            <li>Reports</li>
                        </ul>
                        <?php
                    } else { ?>
                        <ul>
                            <li>Home</li>
                        </ul>
                    <?php
                    }
                    ?>
                </div>


            </div>
		</div>

        <?php include("left-nav.php"); ?>
        
		<div id="content-wrap">
            <div id="content">