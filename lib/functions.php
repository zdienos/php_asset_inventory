<?php

/*
 * @script	functions.php
 * @author: George Russell Pruitt <pruitt.russell@gmail.com>
 * @library BareBones
 *
 * Functions file contains site-wide functions
 *
**/

function custom_error_handler($errno, $errstr, $errfile, $errline) {
    global $SITE;
    
    $e = new stdClass();
    
    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting
        return;
    }

    switch ($errno) {
        case E_USER_ERROR:
            /*
            echo "<b>My ERROR</b> [$errno] $errstr<br />\n";
            echo "  Fatal error on line $errline in file $errfile";
            echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
            echo "Aborting...<br />\n";
            exit(1);
            */
            $e->code = $errno;
            $e->message = $errstr;
            $e->file = $errfile;
            $e->line = $errline;
            $SITE->error->add($e);
            break;

        case E_USER_WARNING:
            //echo "<b>My WARNING</b> [$errno] $errstr<br />\n";
            break;

        case E_USER_NOTICE:
            //echo "<b>My NOTICE</b> [$errno] $errstr<br />\n";
            break;

        default:
            $e->code = $errno;
            $e->message = $errstr;
            $e->file = $errfile;
            $e->line = $errline;
            $SITE->error->add($e);
            break;
    }

    /* Don't execute PHP internal error handler */
    return true;
}


/*
 * get_var_name($data)
 * searches var globals for a match (only works on global scope)
**/
function get_var_name(&$var) {
	global $GLOBALS;
	$ret = '';
	$tmp = $var;
	$var = md5(uniqid(rand(), TRUE));

	$key = array_keys($GLOBALS);
	foreach ( $key as $k )
	if ( $GLOBALS[$k] === $var ) {
	$ret = $k;
	break;
	}

	$var = $tmp;
	return $ret;
}


/*
 * debug_dump($data)
 * processes a variable of any type into html for debugging
**/
function debug_dump($data,$name = false,$skip_null = false,$nested=false){
	if($nested){
		$output = "<div id='debugging' class='nested_debug'>";
	} else {
		$output = "<div id='debugging' class='debug'>";
	}
	if($name){
		$output .= "<h3>Debugging Info for: $name</h3><br/>";
	} else {
		$output .= "<h3>Debugging Info</h3><br/>";
	}
	foreach($data as $key => $value){
		switch(gettype($value)){
			case "array":
				if($nested){
					$output .= "[<strong>".$key."</strong>]: [Array] <br/>";
				} else {
					$output.= debug_dump($value, $key, false, true)."<br/>";
				}
				break;
			case "object":
				$output.= "\t\t".debug_dump($value, $key, false, true)."<br/>";
				break;
			case "string":
				$output .= "\t\t[<strong>".$key."</strong>]: [".$value."] <br/>";
				break;
			case "boolean":
				$output .= "\t\t[<strong>".$key."</strong>]: [".$value."] <br/>";
				break;
			case "integer":
				$output .= "\t\t[<strong>".$key."</strong>]: [".$value."] <br/>";
				break;
			case "double":
				$output .= "\t\t[<strong>".$key."</strong>]: [".$value."] <br/>";
				break;
			case "resource":
				$output .= "\t\t[<strong>".$key."</strong>]: [".$value."] <br/>";
				break;
			case "NULL":
				$output .= "\t\t[<strong>".$key."</strong>]: [".$value."] <br/>";
				break;
			default:
				$output.= "Variable [".$key."] is: UNKNOWN <br/>";
				break;
		}
	}
	$output .= "</div>";
	return nl2br($output);
}


/*
* This function searchs in LDAP tree ($ad -LDAP link identifier)
* entry specified by samaccountname and returns its DN or epmty
* string on failure.
*/
function getDN($ad, $samaccountname, $basedn) {
    $attributes = array('dn');
    $result = ldap_search($ad, $basedn,
        "(samaccountname={$samaccountname})", $attributes);
    if ($result === FALSE) { return ''; }
    $entries = ldap_get_entries($ad, $result);
    if ($entries['count']>0) { return $entries[0]['dn']; }
    else { return ''; };
}

/*
* This function retrieves and returns CN from given DN
*/
function getCN($dn) {
    preg_match('/[^,]*/', $dn, $matchs, PREG_OFFSET_CAPTURE, 3);
    return $matchs[0][0];
}

/*
* This function checks group membership of the user, searching only
* in specified group (not recursively).
*/
function checkGroup($ad, $userdn, $groupdn) {
    $attributes = array('members');
    $result = ldap_read($ad, $userdn, "(memberof={$groupdn})", $attributes);
    if ($result === FALSE) {
        return FALSE;
    }
    $entries = ldap_get_entries($ad, $result);
    return ($entries['count'] > 0);
}

/*
* This function checks group membership of the user, searching
* in specified group and groups which is its members (recursively).
*/
function checkGroupEx($ad, $userdn, $groupdn) {
    $attributes = array('memberof');
    $result = ldap_read($ad, $userdn, '(objectclass=*)', $attributes);
    if ($result === FALSE) { 
        return FALSE; 
    }
    $entries = ldap_get_entries($ad, $result);
    if ($entries['count'] <= 0) {
        return FALSE; 
    }
    if (empty($entries[0]['memberof'])) {
        return FALSE; 
    } else {
        for ($i = 0; $i < $entries[0]['memberof']['count']; $i++) {
            if ($entries[0]['memberof'][$i] == $groupdn) { 
                return TRUE;
            } elseif (checkGroupEx($ad, $entries[0]['memberof'][$i], $groupdn)) {
                return TRUE;
            }
        }
    }
    return FALSE;
}


function log_event($user, $asset, $action){
    global $SITE;
    $log_sql = "INSERT INTO log (asset_id, user_id, action, time_updated) VALUES ('$asset','$user','$action','".time()."')";
    $stmt = $SITE->DB->prepare($log_sql);
    try{
        $stmt->execute();
    } catch (PDOException $e) {
        $SITE->error->add($e);
        return false;
    }
    return true;
}


function get_user_info($acct){
    global $SITE;
    $sql = "SELECT * FROM users WHERE username = '$acct'";
    try{
        $result = $SITE->DB->query($sql);
        $count = $result->rowCount();
    } catch (PDOException $e) {
        $SITE->error->add($e);
        return false;
    }
    if($count > 0){
        return $result->fetchAll(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
}


/**
 * processes $data array into HTML table
 *
 * returns html string
**/
function generate_html_table($data,$id_fld = NULL,$print_id = false){
    global $CFG;
    $output = "<table class='tablesorter' id='goodTable'>";
    $output .= "<thead>".PHP_EOL;
    $output .= "<tr>".PHP_EOL;
    $output .= "<th>#</th>".PHP_EOL;
    foreach($data[0] as $key => $value){
        if($key == $id_fld && $print_id == false){
            //$row_output .= html_writer::nonempty_tag('th',$key);
        } elseif($key == $id_fld && $print_id == true){
            $output .= "<th>$key</th>".PHP_EOL;
        } elseif($key !== $id_fld && $print_id == false){
            $output .= "<th>$key</th>".PHP_EOL;
        }
    }
    $output .= "</tr>".PHP_EOL;
    $output .= "</thead>".PHP_EOL;
    $output .= "<tbody>".PHP_EOL;
    $users_count = 0;

    $row_output = "";
    // TODO convert to use array
    foreach($data as $row){
        $users_count++;
        $row_output .= "<tr>".PHP_EOL;
        $row_output .= "<th>$users_count</th>".PHP_EOL;
        foreach($row as $key => $value){
            if($key == $id_fld && $print_id == false){
                $tempid = $value;
                //$row_output .= html_writer::nonempty_tag('td',$value);
            } elseif($key == $id_fld && $print_id == true){
                $tempid = $value;
                $row_output .= "<td>$value</td>".PHP_EOL;
            } elseif($key !== $id_fld && $print_id == false){
                $row_output .= "<td>$value</td>".PHP_EOL;
            }
        }
        $row_output .= "</tr>".PHP_EOL;
    }
    $output .= $row_output;
    $output .= "</tbody>".PHP_EOL;
    $output .= "</table>".PHP_EOL;
    return $output;
}



function get_asset_types(){
    global $SITE;
    $types_sql = "SELECT * FROM asset_types ORDER BY type ASC";
    try{
        $result = $SITE->DB->query($types_sql);
        $count = $result->rowCount();
    } catch (PDOException $e) {
        $SITE->error->add($e);
        return false;
    }
    if($count > 0){
        return $result->fetchAll(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
}


function get_asset_statuses(){
    global $SITE;
    $status_sql = "SELECT * FROM asset_statuses ORDER BY status ASC";
    try{
        $result = $SITE->DB->query($status_sql);
        $count = $result->rowCount();
    } catch (PDOException $e) {
        $SITE->error->add($e);
        return false;
    }
    if($count > 0){
        return $result->fetchAll(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
}


function get_asset_makes(){
    global $SITE;
    $make_sql = "SELECT * FROM asset_makes ORDER BY make ASC";
    try{
        $result = $SITE->DB->query($make_sql);
        $count = $result->rowCount();
    } catch (PDOException $e) {
        $SITE->error->add($e);
        return false;
    }
    if($count > 0){
        return $result->fetchAll(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
}



function get_asset_models(){
    global $SITE;
    $model_sql = "SELECT * FROM asset_models ORDER BY model ASC";
    try{
        $result = $SITE->DB->query($model_sql);
        $count = $result->rowCount();
    } catch (PDOException $e) {
        $SITE->error->add($e);
        return false;
    }
    if($count > 0){
        return $result->fetchAll(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
}


// closing tag left off intentionally to prevent white space