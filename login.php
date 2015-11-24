<?php

// check for authorized post
if( (isset($_POST['key'])) && (base64_decode($_POST['key']) !== session_id()) ) {

    var_dump(session_id());
    
    var_dump(base64_decode($_POST['key']));
    
    // replace this with actually error logging
    die("don't hack me foo!");
    
} elseif( (isset($_POST['key'])) && (base64_decode($_POST['key']) === session_id()) ) {

    // possible TODO handle this better
    $username = html_entity_decode($_POST['username']);
    $password = html_entity_decode($_POST['password']);
    
    // connect to LDAP
    $LDAP = ldap_connect($CFG->ldap_host);

    // set ldap options
    ldap_set_option($LDAP, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($LDAP, LDAP_OPT_REFERRALS, 0);

    $ldap_rdn = 'KET' . "\\" . $username;
    
    // try user credential
    
    $login_start = microtime(true);
    
    $bind = @ldap_bind($LDAP, $ldap_rdn, $password);

    $login_end = microtime(true);

    
    if ($bind) {
        
        $filter = "(sAMAccountName=$username)";
        $result = ldap_search($LDAP,$CFG->ldap_basedn,$filter);
        ldap_sort($LDAP,$result,"sn");
        $info = ldap_get_entries($LDAP, $result);
        $fullname = $info[0]["givenname"][0]." ".$info[0]["sn"][0];
        
        for($i=0; $i<$info[0]["memberof"]["count"]; $i++){
            $group_dn = explode(",",$info[0]["memberof"][$i]);
            $group = explode("=",$group_dn[0]);
            $groups[] = $group[1];
        }

        
        // write to USER
        $USER->logged = true;
        $USER->fullname = $fullname;
        $USER->dn = getDN($LDAP, $username, $CFG->ldap_basedn);
        $USER->acct = $username;
        $USER->groups = $groups;
                
        // write to session
        $_SESSION['uzr']['logged'] = $USER;
        
        ldap_unbind($LDAP);
        header("Location: index.php");
        exit();
   
    } else {
        
        echo "<p>Invalid username or password</p>\n";
        
    }

}

include('login_form.php');

// left without closing tag intentionally