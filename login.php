<?php

// check for authorized post
if( (isset($_POST['key'])) && (base64_decode($_POST['key']) !== session_id()) ) {

    // TODO replace this with actually error logging
    die("don't hack me bro!");
    
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

    if ($bind) {
        $filter = "(sAMAccountName=$username)";
        $result = ldap_search($LDAP,$CFG->ldap_basedn,$filter);
        ldap_sort($LDAP,$result,"sn");
        $info = ldap_get_entries($LDAP, $result);
        $firstname = $info[0]["givenname"][0];
        $lastname = $info[0]["sn"][0];
        $fullname = $firstname." ".$lastname;
        for($i=0; $i<$info[0]["memberof"]["count"]; $i++){
            $group_dn = explode(",",$info[0]["memberof"][$i]);
            $group = explode("=",$group_dn[0]);
            $groups[] = $group[1];
        }
        
        $ad_dn = getDN($LDAP, $username, $CFG->ldap_basedn);
        ldap_unbind($LDAP);

    }


    
    // check if user has logged in before
    $user_exists = get_user_info($username);
    
    
    if(!$user_exists){
        
        // write user data to db if none exists (no login)
        $creation_time = time();
        $new_user_sql = "INSERT INTO users (username, first_name, last_name, created_at, last_update) VALUES (:user,:first,:last,:time,CURRENT_TIMESTAMP)";
        //$new_user_sql = "INSERT INTO users (username, first_name, last_name, created_at, last_update) VALUES ('$username','$firstname','$lastname','$creation_time',CURRENT_TIMESTAMP)";
        
        $stmt = $SITE->DB->prepare($new_user_sql);
        
        $stmt->bindValue(':user', $username, PDO::PARAM_STR);
        $stmt->bindValue(':first', $firstname, PDO::PARAM_STR);
        $stmt->bindValue(':last', $lastname, PDO::PARAM_STR);
        $stmt->bindValue(':time', $creation_time, PDO::PARAM_INT);      
        if(!$stmt->execute()){
        	trigger_error("Couldn't write to DB.", E_USER_ERROR);
        }

        
        // get user id
        $USER->id = $SITE->DB->lastInsertId();
        
        // write to USER
        $USER->logged = true;
        $USER->firstname = $firstname;
        $USER->lastname = $lastname;
        $USER->fullname = $fullname;
        $USER->dn = $ad_dn;
        $USER->acct = $username;
        $USER->groups = $groups;

        // log the login
        log_event($USER->id,'','login');
    
    } else {

        //die("<pre>".var_dump($user_exists)."</pre>");
        // get stored user info
        
        $USER->logged = true;
        
        $USER->id = $user_exists[0]["id"];
        $USER->username = $user_exists[0]["username"];
        $USER->firstname = $user_exists[0]["first_name"];
        $USER->lastname = $user_exists[0]["last_name"];
        $USER->fullname = $USER->firstname." ".$USER->lastname;
        $USER->dn = $ad_dn;
        $USER->acct = $username;
        $USER->groups = $groups;
        
        

        //die("<p>user existed:</p><pre>".debug_dump($USER)."</pre>");
        
        //die(__FILE__." ".__LINE__."<br/><pre>".var_dump($USER)."</pre>");
        // log the login
        log_event($USER->id,'','login');
    }

    // write to session
    $_SESSION['uzr']['logged'] = $USER;

    header("Location: index.php");
    exit();
   
} else {
    //echo "<p>Invalid username or password</p>\n";
}

include('login_form.php');

// left without closing tag intentionally