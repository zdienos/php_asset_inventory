<?php
$output = "<nav class='navbar navbar-default'>".PHP_EOL;
$output .= "<ul class='dropdown-menu'>".PHP_EOL;

if($USER->logged){ 

    $output .= "\t<li><a href='index.php'>Home</a></li>".PHP_EOL;
    
    if($USER->is_admin){
        $output .= "\t<li><a href='edit.php?id=new'>New Asset</a></li>".PHP_EOL;
        $output .= "\t<li><a href='browse.php'>Browse</a></li>".PHP_EOL;
    }
    
    $output .= "\t<li><a href='reports.php'>Reports</a></li>".PHP_EOL;
    $output .= "<li role='separator' class='divider'></li>".PHP_EOL;

} else { 
    $output .= "\t<li><a href='index.php'>Home</a></li>".PHP_EOL;
}

$output .= "</ul>".PHP_EOL;
$output .= "</nav>".PHP_EOL;

echo $output;