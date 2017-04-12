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

    switch ($errno) {
        case E_USER_ERROR:
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
            //$e->code = $errno;
            $e->code = $e->getCode();
            //$e->message = $errstr;
            $e->message = $e->getMessage();
            //$e->file = $errfile;
            $e->file = $e->getFile();
            //$e->line = $errline;
            $e->line = $e->getLine();
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



////////////////////////////////////////////////////////////////
// active directory functions
////////////////////////////////////////////////////////////////
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


function get_user_last_login($user){
    global $SITE;
    $sql = "SELECT time_updated FROM log WHERE user_id = ? AND action = 'login' ORDER BY time_updated DESC LIMIT 0,2";
    //$sql = "SELECT FROM_UNIXTIME(time_updated) as 'time_updated' FROM log WHERE user_id = ? AND action = 'login' ORDER BY time_updated DESC LIMIT 0,2";
	$stmt = $SITE->DB->prepare($sql);
    $stmt->execute(array($user));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if(count($result) < 1){
        return false;
    } else {
        return $result[1]['time_updated'];
    }
}


function get_user_info($acct){
	global $SITE;
	$sql = "SELECT * FROM users WHERE username = '$acct'";
	$result = $SITE->DB->query($sql);
	$count = $result->rowCount();
    if($count > 0){
        return $result->fetchAll(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
}


function get_new_assets($user,$count = false){
	global $SITE;
	$last_login = get_user_last_login($user);
	if(!$last_login){
		return false;
	}
	//$sql = "SELECT * FROM assets WHERE creation_date >= '$last_login' ORDER BY creation_date DESC";
	$sql = "SELECT * FROM assets WHERE creation_date >= FROM_UNIXTIME($last_login) ORDER BY creation_date DESC";
	$stmt = $SITE->DB->query($sql);
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if(count($result) < 1){
		return false;
	} else {
		if($count){
			return count($result);
		} else {
			return $result;
		}
	}
}


function log_event($user, $asset, $action){
    global $SITE;
    $log_sql = "INSERT INTO log (asset_id, user_id, action, time_updated) VALUES ('$asset','$user','$action','".time()."')";
    $stmt = $SITE->DB->prepare($log_sql);
    if(!$stmt->execute()){
		return false;
	}
    return true;
}


/**
 * processes $data array into HTML table
 *
 * returns html string
**/
function generate_html_table($data,$id_fld = NULL,$print_id = false){
    global $SITE;
    $output = "<table class='tablesorter' id='goodTable'>";
    $output .= "<thead>".PHP_EOL;
    $output .= "<tr>".PHP_EOL;
    $output .= "<th>&nbsp;</th>".PHP_EOL;
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
                //$row_output .= "<td>$value</td>".PHP_EOL;
				$row_output .= "<td><a href='".$SITE->CFG->url."admin/edit.php?id=".$row[$id_fld]."'>$value</a></td>".PHP_EOL;
            } elseif($key !== $id_fld && $print_id == false){
                //$row_output .= "<td>$value</td>".PHP_EOL;
				$row_output .= "<td><a href='".$SITE->CFG->url."admin/edit.php?id=".$row[$id_fld]."'>$value</a></td>".PHP_EOL;
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
    $result = $SITE->DB->query($types_sql);
	$count = $result->rowCount();
    if($count > 0){
        return $result->fetchAll(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
}


function get_asset_statuses(){
	global $SITE;
	$status_sql = "SELECT * FROM asset_statuses ORDER BY status ASC";
	$result = $SITE->DB->query($status_sql);
	$count = $result->rowCount();
    if($count > 0){
        return $result->fetchAll(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
}


function get_asset_makes(){
	global $SITE;
	$make_sql = "SELECT * FROM asset_makes ORDER BY make ASC";
	$result = $SITE->DB->query($make_sql);
	$count = $result->rowCount();
	if($count > 0){
		return $result->fetchAll(PDO::FETCH_ASSOC);
	} else {
		return false;
	}
}


function get_asset_models(){
    global $SITE;
    $model_sql = "SELECT * FROM asset_models ORDER BY model ASC";
	$result = $SITE->DB->query($model_sql);
	$count = $result->rowCount();
	if($count > 0){
		return $result->fetchAll(PDO::FETCH_ASSOC);
	} else {
		return false;
	}
}


function get_asset($id){
	global $SITE;

	$asset_sql = "SELECT * FROM assets WHERE id = ?";
	$stmt = $SITE->DB->prepare($asset_sql);
	$stmt->execute(array($id));
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if(sizeof($results) < 1){
		return false;
	} else {
		$asset = $results[0];
	}
	return $asset;
}


function get_assignment_types(){
    global $SITE;
    $types_sql = "SELECT * FROM asset_assignment_types ORDER BY type ASC";
    $result = $SITE->DB->query($types_sql);
	$count = $result->rowCount();
    if($count > 0){
        return $result->fetchAll(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
}


function get_assignments($asset_id){
	global $SITE;
	$sql = "SELECT ";
	$sql .= "asset_assignments.id, ";
	$sql .= "asset_assignments.asset_id, ";
	$sql .= "asset_assignment_types.type, ";
	$sql .= "asset_assignments.user_descr, ";
	$sql .= "CASE asset_assignments.assignment_type ";
	$sql .= "	WHEN 1 then users.email ";
	$sql .= "	WHEN 2 then departments.name ";
	$sql .= "	WHEN 3 then rooms.name ";
	$sql .= "	WHEN 4 then projects.name ";
	$sql .= "END as assigned_to, ";
	$sql .= "assignment_start, ";
	$sql .= "assignment_end ";
	
	$sql .= "FROM asset_assignments ";
	$sql .= "LEFT JOIN asset_assignment_types ON asset_assignment_types.id = asset_assignments.assignment_type ";
	$sql .= "LEFT JOIN departments ON asset_assignments.assigned_to = departments.id ";
	$sql .= "LEFT JOIN users ON asset_assignments.assigned_to = users.id ";
	$sql .= "LEFT JOIN rooms ON asset_assignments.assigned_to = rooms.id ";
	$sql .= "LEFT JOIN projects ON asset_assignments.assigned_to = projects.id ";
	
	$sql .= "WHERE ";
	$sql .= "asset_assignments.asset_id = ?";
		
	$stmt = $SITE->DB->prepare($sql);
	$stmt->execute(array($asset_id));
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if(sizeof($results) < 1){
		return false;
	} else {
		$data = $results;
	}
	return $data;
}

/*
function get_assignments($asset_id){
    global $SITE;
	$sql = "SELECT ";
	$sql .= "assets.id, ";
	$sql .= "asset_assignments.assignment_start, ";
	$sql .= "asset_assignments.assignment_end, ";
	$sql .= "assignment_type.type, ";
	$sql .= " ";
	$sql .= "CASE asset_assignment_details.assignment_type ";
	$sql .= "	WHEN 1 then users.email ";
	$sql .= "	WHEN 2 then departments.name ";
	$sql .= "	WHEN 3 then rooms.name ";
	$sql .= "	WHEN 4 then projects.name ";
	$sql .= "END as assigned_to ";
	$sql .= " ";
	$sql .= "FROM asset_assignments ";
	$sql .= "INNER JOIN assets ON asset_assignments.asset_id = assets.id ";
	$sql .= "LEFT JOIN asset_assignment_details ON asset_assignment_details.assignment_id = asset_assignments.id ";
	$sql .= "LEFT JOIN assignment_type ON assignment_type.id = asset_assignment_details.assignment_type ";
	$sql .= "LEFT JOIN departments ON asset_assignment_details.assigned_to = departments.id ";
	$sql .= "LEFT JOIN users ON asset_assignment_details.assigned_to = users.id ";
	$sql .= "LEFT JOIN rooms ON asset_assignment_details.assigned_to = rooms.id ";
	$sql .= "LEFT JOIN projects ON asset_assignment_details.assigned_to = projects.id ";
	$sql .= "WHERE assets.id = ?";
	
	$result = $SITE->DB->query($sql);
	$count = $result->rowCount();
	if($count > 0){
		return $result->fetchAll(PDO::FETCH_ASSOC);
	} else {
		return false;
	}
}
*/

function get_department_codes(){
    global $SITE;
    $types_sql = "SELECT * FROM departments ORDER BY code ASC";
    $result = $SITE->DB->query($types_sql);
	$count = $result->rowCount();
    if($count > 0){
        return $result->fetchAll(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
}


function build_options_html($options,$label,$id = NULL){
    $output = "";
    foreach($options as $option){
        $output .= "<option value='".$option['id']."'";
        if( $option['id'] === $id ){
            $output .= " selected";
        }
        $output .= ">".$option[$label]."</option>".PHP_EOL;
    }
    return $output;
}



/**
 * process $data array into csv
 * 
 * returns csv string
**/
function generate_csv($data,$id_fld = NULL,$print_id = false){
	$csv_header = "#,";
	// build header
	foreach($data[0] as $key => $value){
		// check whether to include ID field
		if($key == $id_fld && $print_id == false){
			//$csv_header .= $key.",";
		} elseif($key == $id_fld && $print_id == true){
			$csv_header .= $key.",";
		} elseif($key !== $id_fld && $print_id == false){
			$csv_header .= $key.",";
		}
	}
	$csv_header = substr($csv_header,0,(strlen($csv_header)-1)).PHP_EOL;
	$users_count = 0;
	$row_output = "";
	// build rows
	foreach($data as $row){
		$users_count++;
		$row_output .= $users_count.",";
		foreach($row as $key => $value){
			// check whether to include ID field
			if($key == $id_fld && $print_id == false){
			//$row_output .= $value.",";
			} elseif($key == $id_fld && $print_id == true){
				$row_output .= $value.",";
			} elseif($key !== $id_fld && $print_id == false){
				$row_output .= $value.",";
			}
		}
		$row_output = substr($row_output,0,(strlen($row_output)-1)).PHP_EOL;
	}
	$output = $csv_header.$row_output;
	return $output;
}

// closing tag left off intentionally to prevent white space