<?php
//$menu_output = "<nav class='navbar navbar-default'>".PHP_EOL;

//$menu_output = "<ul class='dropdown-menu'>".PHP_EOL;
$menu_output = "<ul>".PHP_EOL;

if($USER->logged){ 

    if($SITE->CFG->debug){
        $menu_output .= "\t\t\t<!-- logged in -->".PHP_EOL;
    }
    $menu_output .= "\t\t\t<li><a href='".$SITE->CFG->url."index.php'>Home</a></li>".PHP_EOL;
    if($USER->is_admin){
        $menu_output .= "\t\t\t<li><a href='".$SITE->CFG->url."admin/edit.php'>New Asset</a></li>".PHP_EOL;
        $menu_output .= "\t\t\t<li><a href='".$SITE->CFG->url."browse.php'>Browse</a></li>".PHP_EOL;
		$menu_output .= "\t\t\t<li><a href='".$SITE->CFG->url."admin/index.php'>Admin</a></li>".PHP_EOL;
    }
    $menu_output .= "\t\t\t<li><a href='".$SITE->CFG->url."reports.php'>Reports</a></li>".PHP_EOL;
	$menu_output .= "\t\t\t<li><a href='".$SITE->CFG->url."reports/activity.php'>Activity Log</a></li>".PHP_EOL;
	$menu_output .= "\t\t\t<li><a href='".$SITE->CFG->url."reports/activity.php'>All Devices</a></li>".PHP_EOL;
	
} else {

	if($SITE->CFG->debug){
        $menu_output .= "\t\t\t<!-- not logged in -->".PHP_EOL;
    }
    $menu_output .= "\t\t\t<li><a href='".$SITE->CFG->url."index.php'>Home</a></li>".PHP_EOL;
}

$menu_output .= "\t\t\t<li role='separator' class='divider'></li>".PHP_EOL;
if($USER->logged){ 
	$menu_output .= "\t\t\t<li><a href='".$SITE->CFG->url."logout.php'>Logout</a></li>".PHP_EOL;
}
$menu_output .= "\t\t</ul>".PHP_EOL;

//$menu_output .= "</nav>".PHP_EOL;

echo $menu_output;