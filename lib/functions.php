<?php

/*
 * @script	functions.php
 * @author: George Russell Pruitt <pruitt.russell@gmail.com>
 * @library BareBones
 *
 * Functions file contains site-wide functions
 *
**/



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



// closing tag left off intentionally to prevent white space