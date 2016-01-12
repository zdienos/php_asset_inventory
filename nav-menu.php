<?php
//$menu_output = "<nav class='navbar navbar-default'>".PHP_EOL;

//$menu_output = "<ul class='dropdown-menu'>".PHP_EOL;
$menu_output = "<ul>".PHP_EOL;

if($USER->logged){ 

    $menu_output .= "\t\t\t<!-- logged in -->".PHP_EOL;
    $menu_output .= "\t\t\t<li><a href='index.php'>Home</a></li>".PHP_EOL;
    
    if($USER->is_admin){
        $menu_output .= "\t\t\t<li><a href='edit.php'>New Asset</a></li>".PHP_EOL;
        $menu_output .= "\t\t\t<li><a href='browse.php'>Browse</a></li>".PHP_EOL;
    }
    
    $menu_output .= "\t\t\t<li><a href='reports.php'>Reports</a></li>".PHP_EOL;
    $menu_output .= "\t\t\t<li role='separator' class='divider'></li>".PHP_EOL;

} else { 
    $menu_output .= "\t\t\t<!-- not logged in -->".PHP_EOL;
    $menu_output .= "\t\t\t<li><a href='index.php'>Home</a></li>".PHP_EOL;
}

$menu_output .= "\t\t</ul>".PHP_EOL;

//$menu_output .= "</nav>".PHP_EOL;

echo $menu_output;