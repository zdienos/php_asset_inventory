<?php

// grab config
include("../config.php");


// connect to ldap

/////////////////////////////////////////////
// ldap conn and search

// connect to LDAP
$LDAP = ldap_connect($SITE->CFG->ldap_host) or die("Couldn't connect to LDAP server.");

// set ldap options
ldap_set_option($LDAP, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($LDAP, LDAP_OPT_REFERRALS, 0);
$ldap_rdn = 'KET' . "\\" . KET_ASSET_LDAP_USER;

if($debug){
	echo "<p>Attempt Bind</p>";
}

// do bind
try {
	$bind = ldap_bind($LDAP, $ldap_rdn, KET_ASSET_LDAP_PW);
} catch (Exception $e) {
	//return $e->getMessage();
	die($e->getMessage());
}


// grab accounts
if ($bind) {
	if($debug){
		echo "<p>Bind successful</p>";
	}
	//$filter = $filter = "(|(sn=$s*)(givenname=$s*))";
	//$justthese = array("ou", "sn", "givenname", "mail");
	// below got too many
	//$filter = $filter = "(|(cn=*)(ou=Employees))";
	$filter = $filter = "(&(objectCategory=person)(samaccountname=*))";
	$justthese = array('member','sn','givenname','mail','displayName');
	$result = ldap_search($LDAP,$CFG->ldap_basedn,$filter);
	ldap_sort($LDAP,$result,"sn");
	// grab user info
	$info = ldap_get_entries($LDAP, $result);
	
	$users = array();
	
	foreach($info as $user){
		unset($temp);
		$temp = array();
		$temp['username'] = $user['samaccountname'][0];
		$temp['email'] = $user['mail'][0];
		$temp['first'] = $user['givenname'][0];
		$temp['last'] = $user['sn'][0];
		if($CFG->debug){
			print_r($temp);	
		}
		$users[] = $temp;
	}
} else {
	die("Bind failed");
}


if(!$CFG->debug){
	// loop accounts
	foreach($users as $uzr){
		$user_exists = get_user_info($uzr['username']);
		if( ($uzr['username'] == NULL) || ( $user_exists ) ){
			continue;
		}
		$creation_time = time();
		$new_user_sql = "INSERT INTO users (username, first_name, last_name, email, created_at, last_update) VALUES (:user,:first,:last,:email,:time,CURRENT_TIMESTAMP)";
		$stmt = $SITE->DB->prepare($new_user_sql);
		$stmt->bindValue(':user', $uzr['username'], PDO::PARAM_STR);
		$stmt->bindValue(':first', $uzr['first'], PDO::PARAM_STR);
		$stmt->bindValue(':last', $uzr['last'], PDO::PARAM_STR);
		$stmt->bindValue(':email', $uzr['email'], PDO::PARAM_STR);
		$stmt->bindValue(':time', $creation_time, PDO::PARAM_INT);      
		if(!$stmt->execute()){
			trigger_error("Couldn't write to DB.", E_USER_ERROR);
		}
	}
}


// create / save users


ldap_close($LDAP);

// profit!?

echo "<p>Process completed.</p>";
echo "<p>".sizeof($users)." users were created.</p>";

?>