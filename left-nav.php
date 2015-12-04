<?php

$output = "<ul>".PHP_EOL;

if($USER->logged){ 

    $is_admin = FALSE;
    
    // check for admin group
    if(in_array( "KET Administrators", $USER->session->groups )){
        $is_admin = TRUE;
    }
    
    $output .= "\t<li><a href='index.php'>Home</a></li>".PHP_EOL;
    
    if($is_admin){
        $output .= "\t<li><a href='edit.php?id=new'>New Asset</a></li>".PHP_EOL;
        $output .= "\t<li><a href='browse.php'>Browse</a></li>".PHP_EOL;
    }
    
    $output .= "\t<li><a href='reports.php'>Reports</a></li>".PHP_EOL;
    

} else { 
    $output .= "\t<li><a href='index.php'>Home</a></li>".PHP_EOL;
}

$output .= "</ul>".PHP_EOL;

echo $output;